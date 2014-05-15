<?php
require_once 'password.php';
class Admin {
  private $db;

  public function __construct($database) {
    $this->db = $database;
}

public function editProduct() {

    if (isset($_POST) && !empty($_POST) && $_POST['type'] == "editproduct") {

        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $price = $_POST['price'];
        $img = $_POST['img'];
        $sku = $_POST['sku'];
        $description = $_POST['description'];

        try {
            if ($_FILES['imgupload']['size'] == 0) {
                $filename = $_POST['curimg'];
                $_SESSION['success'] = 'Product edited successfully.';
                $allUploaded = true;
            } else {
                $types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
                $allUploaded = true;
                foreach($_FILES as $file) {
                    if(in_array($file['type'], $types)) {
                        if($file['size'] <= 350000) {
                            $filename = $file['name'];
                            if(!file_exists($filename)) {
                                if(move_uploaded_file($file['tmp_name'], 'img/upload/' . $filename)) {
                                    $_SESSION['success'] = 'Product edited successfully.';
                                } else {
                                    $allUploaded = false;
                                    $_SESSION['errors'] = "Error uploading {$filename}";
                                    header('Location: admin.php?page=edit');
                                }
                            } else {
                                $_SESSION['errors'] = "Error: {$filename} already exists.";
                                header('Location: admin.php?page=edit');
                            }
                        } else {
                            $_SESSION['errors'] = "Error: {$filename}  exceeds the limit of 350kb.";
                            header('Location: admin.php?page=edit');
                        }
                    } else {
                        $_SESSION['errors'] = "Error: {$filename} is of an invalid file type.";
                        header('Location: admin.php?page=edit');
                    }
                }
            }
            if ($allUploaded) {

                $stmt = $this->db->prepare('UPDATE `products`
                   SET `name` = :name,
                   `price` = :price,
                   `sku` = :sku,
                   `description` = :description,
                   `img` = :img
                   WHERE `id` = :id');
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':price', $price, PDO::PARAM_INT);
                $stmt->bindValue(':sku', $sku, PDO::PARAM_INT);
                $stmt->bindValue(':description', $description);
                $stmt->bindValue(':img', $filename);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->execute();
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

public function editUser() {
    $data = $_POST;
    $username = htmlentities($data['username']);
    $firstname = htmlentities($data['firstname']);
    $surname = htmlentities($data['surname']);
    $email = htmlentities($data['email']);
    $currentpass = htmlentities($data['currentpass']);
    $newpass = htmlentities($data['newpass']);
    $id = $data['id'];

    if (!empty($_POST['newpass'])) {
        if ($users->checkpassword($id, $currentpass) ) {
            $insertpassword = password_hash($newpass, PASSWORD_DEFAULT);
            $users->updatePassword($insertpassword, $id);
            $_SESSION['success'] = 'Password updated successfully.';

        } else {
            $_SESSION['errors'] = 'The current password doesn\'t match.';
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
            if ($_FILES['imgupload']['size'] == 0) {
                $filename = "default.jpg";
                $_SESSION['success'] = 'Product added successfully.';
                $allUploaded = true;
                unset($_SESSION['token']);
                header('Location: admin.php?page=addproduct');
            } else {
                $types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
                $allUploaded = true;
                foreach($_FILES as $file) {
                    if(in_array($file['type'], $types)) {
                        if($file['size'] <= 350000) {
                            $filename = $file['name'];
                            if(!file_exists($filename)) {
                                if(move_uploaded_file($file['tmp_name'], 'img/upload/' . $filename)) {
                                    $_SESSION['success'] = 'Product added successfully.';
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