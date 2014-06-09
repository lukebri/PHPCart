<?php

class Products {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  public function getprod($id) {
    $query = $this->db->prepare("SELECT * FROM `products` WHERE `id` = ? LIMIT 1");
    $query->bindValue(1, $id, PDO::PARAM_INT);

    try{
      $query->execute();
      $prod = $query->fetchAll(PDO::FETCH_ASSOC);
      return $prod;

    } catch(PDOException $e){
      die($e->getMessage());

    }
  }

  public function getprods($lim) {
    $query = $this->db->prepare("SELECT * FROM `products` LIMIT ?");
    $query->bindValue(1, $lim, PDO::PARAM_INT);

    try{
      $query->execute();
      $prod = $query->fetchAll(PDO::FETCH_ASSOC);
      return $prod;

    } catch(PDOException $e){
      die($e->getMessage());

    }
  }

  public function get_products_count() {
    $total = $this->db->query('SELECT COUNT(*) FROM products')->fetchColumn();
    return intval($total);
  }

  public function get_products_subset($start,$end) {
    $query = $this->db->prepare("SELECT * FROM `products` LIMIT ?, ?");
    $query->bindValue(1, $start, PDO::PARAM_INT);
    $query->bindValue(2, $end, PDO::PARAM_INT);

    try{
      $query->execute();
      $products = $query->fetchAll(PDO::FETCH_ASSOC);
      return $products;

    } catch(PDOException $e){
      return $error[] = "Couldn't get any products M'lord";
      die($e->getMessage());

    }
  }

}