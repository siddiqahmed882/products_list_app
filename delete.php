<?php
  $product_id = $_POST["id"] ?? null;
  if(!$product_id){
    header("Location: index.php");
  }
  $pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_list_app", "siddiq", "test1234");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $statement = $pdo->prepare("SELECT image FROM products WHERE id = :id");
  $statement->bindValue(":id", $product_id);
  $statement->execute();
  $product = $statement->fetchAll(PDO::FETCH_ASSOC)[0] ?? null;
  if($product){
    $image_path = $product["image"];
    unlink($image_path);
  }

  $statement = $pdo->prepare("DELETE FROM products WHERE id = :id");
  $statement->bindValue(":id", $product_id);
  $statement->execute();
  header("Location: index.php");
?>