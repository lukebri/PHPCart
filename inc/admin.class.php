<?php
require_once 'password.php';

function sanitize($input) {
    return htmlspecialchars(trim($input));
}


class Admin {
  private $db;
  public function __construct($database) {
    $this->db = $database;
}

public function showOrders($id) {
  $orders = $this->db->prepare("SELECT * FROM orders WHERE user_id = :id");
  $orders->bindValue(':id', $id, PDO::PARAM_INT);
  $orders->execute();
  return $orders;
}

public function editProduct() {

    if (isset($_POST) && !empty($_POST) && $_POST['type'] == "editproduct") {

        $id = intval($_POST['id']);
        $name = sanitize($_POST['name']);
        $price = sanitize($_POST['price']);
        $img = sanitize($_POST['img']);
        $sku = sanitize($_POST['sku']);
        $stock = sanitize($_POST['stock']);
        $description = sanitize($_POST['description']);
        // Check if SKU submitted from the form is same for this product
        $cur_sku = $this->db->prepare("SELECT `sku` FROM `products` WHERE `id`= ?");
        $cur_sku->bindValue(1, $id);
        $cur_sku->execute();
        $skucheck = $cur_sku->fetch();
        $skucheck = $skucheck['sku'];
        // If the SKU is different, we can then check if it already exists, as the SKU must be unique.
        if ($skucheck !== $sku) {
            $new_sku = $this->db->prepare("SELECT `sku` FROM `products` WHERE `sku`= ?");
            $new_sku->bindValue(1, $sku);
            $new_sku->execute();
            $new_sku = $new_sku->fetch();
            $new_sku = $new_sku['sku'];
            if($new_sku == $sku){
                    $_SESSION['errors'] = "That SKU is already in use.";
                    unset($_POST);
                    header('Location: admin.php?page=edit');
                    exit;
            }
}

        try {
            $allUploaded = false;
            $types = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png');
            if ($_FILES['imgupload']['size'] == 0 || !in_array($_FILES['imgupload']['type'], $types) ) {
                $filename = $_POST['curimg'];
                if ($_FILES['imgupload']['size'] > 0 && !in_array($_FILES['imgupload']['type'], $types)) {
                    $_SESSION['errors'] = "That image file type is not accepted. Try JPG, GIF or PNG.";
                    header('Location: admin.php?page=edit');
                    exit;
                }
                $allUploaded = true;
            } else {
                foreach($_FILES as $file) {
                    if(in_array($file['type'], $types)) {
                        if($file['size'] <= 350000) {
                            $filename = $file['name'];
                            if(!file_exists($filename)) {
                                if(move_uploaded_file($file['tmp_name'], 'img/upload/' . $filename)) {
                                    $allUploaded = true;
                                } else {
                                    $allUploaded = false;
                                    $_SESSION['errors'] = "Error uploading {$filename}";
                                    header('Location: admin.php?page=edit');
                                    exit;
                                }
                            } else {
                                $_SESSION['errors'] = "Error: {$filename} already exists.";
                                $allUploaded = false;
                                header('Location: admin.php?page=edit');
                                exit;
                            }
                        } else {
                            $_SESSION['errors'] = "Error: {$filename}  exceeds the limit of 350kb.";
                            $allUploaded = false;
                            header('Location: admin.php?page=edit');
                            exit;
                        }
                    } else {
                        $_SESSION['errors'] = "Error: {$filename} is of an invalid file type.";
                        $allUploaded = false;
                        header('Location: admin.php?page=edit');
                        exit;
                    }
                }
            }
            if ($allUploaded) {
                $_SESSION['success'] = 'Product edited successfully.';
                $stmt = $this->db->prepare('UPDATE `products`
                 SET `name` = :name,
                 `price` = :price,
                 `sku` = :sku,
                 `description` = :description,
                 `img` = :img,
                 `stock` = :stock
                 WHERE `id` = :id');
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':price', $price, PDO::PARAM_INT);
                $stmt->bindValue(':sku', $sku, PDO::PARAM_INT);
                $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
                $stmt->bindValue(':description', $description);
                $stmt->bindValue(':img', $filename);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            unset($_SESSION['token']);
            header('Location: admin.php?page=edit');
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

}

public function deleteProduct() {
    $stmt = $this->db->prepare("DELETE FROM products WHERE id=:id");
    $id = $_GET['delete'];
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header('location: admin.php');
}

private function updatePassword($password, $id) {
    $query = $this->db->prepare("UPDATE users SET password= ? WHERE id = ?");
    $query->bindValue(1, $password);
    $query->bindValue(2, $id);
    try{
      $query->execute();
  }catch(PDOException $e){
      die($e->getMessage());
  }
}

public function checkpassword($id, $password) {
    $password = htmlentities($_POST['currentpass']);
    $id = htmlentities($_POST['id']);

    $query = $this->db->prepare("SELECT `password`, `id` FROM `users` WHERE `id` = ?");
    $query->bindValue(1, $id);

    try{
      $query->execute();
      $data        = $query->fetch();
      $stored  = $data['password'];
      $id         = $data['id'];
    /* Hash the supplied password and compare
       it with the stored hashed password
       return the $id if verified for updatepassword() db query
    */
       if(password_verify($password, $stored)){
        return $id;
    }else{
        return false;
    }
}catch(PDOException $e){
  die($e->getMessage());
}
}

public function editUser() {
    $data = $_POST;
    $username = sanitize($data['username']);
    $firstname = sanitize($data['firstname']);
    $surname = sanitize($data['surname']);
    $email = sanitize($data['email']);
    $currentpass = sanitize($data['currentpass']);
    $newpass = sanitize($data['newpass']);
    $id = $data['id'];
    // If user enters a new password, validate the old one
    if (!empty($_POST['currentpass']) && empty($_POST['newpass'])) {
        $_SESSION['errors'] = "You didn't enter a new password";
        header('Location: admin.php?page=edituser');
        exit;
    }
    if (!empty($_POST['newpass'])) {
        if (Admin::checkpassword($id, $currentpass) ) {
            $insertpassword = password_hash($newpass, PASSWORD_DEFAULT);
            Admin::updatePassword($insertpassword, $id);
            $_SESSION['success'] = 'Password updated successfully.';

        } else {
            $_SESSION['errors'] = "The current password doesn't match.";
            header('Location: admin.php?page=edituser');
            exit;
        }

    }

    try {
        $stmt = $this->db->prepare('UPDATE users SET username = :username, firstname = :firstname, lastname = :lastname, email = :email WHERE id = :id');
        $stmt->execute(array(
            ':id'   => $id,
            ':username' => $username,
            ':firstname'   => $firstname,
            ':lastname'   => $surname,
            ':email'   => $email
            ));
        $_SESSION['success'] = 'Account updated successfully.';
        header('Location: admin.php?page=edituser');

    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

}

public function addProduct() {
    if (isset($_POST) && !empty($_POST) && $_POST['type'] == "addproduct") {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $sku = $_POST['sku'];
        $description = $_POST['description'];

        $query = $this->db->prepare("SELECT COUNT(`sku`) FROM `products` WHERE `sku`= ?");
        $query->bindValue(1, $sku);
        try{
            $query->execute();
            $rows = $query->fetchColumn();
            if($rows == 1){
                $_SESSION['errors'] = "That product SKU already exists. Delete old product first.";
                unset($_POST);
                header('Location: admin.php?page=addproduct');
                exit;
            }
        } catch(PDOException $e){
            die($e->getMessage());
        }

        try {
            $types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
            if ($_FILES['imgupload']['size'] == 0 || !(in_array($file['type'], $types))) {
                $filename = "default.jpg";
                $_SESSION['errors'] = "Error: That image is not of valid type (GIF, JPG, PNG).";
                $allUploaded = true;
            } else {
                $allUploaded = true;
                $filename = "default.jpg";
                foreach($_FILES as $file) {
                    if(in_array($file['type'], $types)) {
                        if($file['size'] <= 350000) {
                            $filename = $file['name'];
                            if(!file_exists($filename)) {
                                if(move_uploaded_file($file['tmp_name'], 'img/upload/' . $filename)) {
                                } else {
                                    $allUploaded = false;
                                    $_SESSION['errors'] = "Error uploading {$filename}";
                                    header('Location: admin.php?page=addproduct');
                                }
                            } else {
                                $_SESSION['errors'] = "Error: {$filename} already exists.";
                                header('Location: admin.php?page=addproduct');
                            }
                        } else {
                            $_SESSION['errors'] = "Error: {$filename}  exceeds the limit of 350kb.";
                            header('Location: admin.php?page=addproduct');
                        }
                    } else {
                        $_SESSION['errors'] = "Error: {$filename} is of an invalid file type.";
                        header('Location: admin.php?page=addproduct');
                    }
                }
            }
            if ($allUploaded) {
                $_SESSION['success'] = 'Product added successfully.';
                unset($_SESSION['token']);
                $stmt = $this->db->prepare('INSERT INTO products (name, price, description, img, sku)
                    VALUES(:name, :price, :description, :img, :sku)');
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':price', $price);
                $stmt->bindValue(':sku', $sku);
                $stmt->bindValue(':description', $description);
                $stmt->bindValue(':img', $filename);
            }
            $stmt->execute();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

}

} ?>