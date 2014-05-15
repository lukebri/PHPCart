<?php class General {
  private $db;
  public function __construct($database) {
    $this->db = $database;
  }

  public function getPrice($id) {
    $query = $this->db->prepare("SELECT `price` FROM `products` WHERE `id` = ?");
    $query->bindValue(1, $id, PDO::PARAM_INT);
    try{
     $query->execute();
     $price = $query->fetchAll(PDO::FETCH_COLUMN);
       return $price;
   } catch(PDOException $e){
    die($e->getMessage());
  }
}

public function is_admin() {
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['admin'] === "t") {
      return true;
    } else {
      return false;
    }
  }
}

public function is_logged_in() {
  if (isset($_SESSION['id'])) {
    return true;
  } else {
    return false;
  }
}


}?>