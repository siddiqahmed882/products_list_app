<?php

  $id = $_GET["id"] ?? null;
  if(!$id) header("Location: index.php");

  $pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_list_app", "siddiq", "test1234");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $title = $description = $price = null;
  $errors = [];
  
  $statement = $pdo->prepare("SELECT * FROM products WHERE id = :id");
  $statement->bindValue(":id", $id);
  $statement->execute();
  $product = $statement->fetchAll(PDO::FETCH_ASSOC)[0] ?? null;
  if($product){
    $title = $product["title"];
    $price = $product["price"];
    $description = $product["description"];
    $image = $product["image"];
  }

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    // grabbing data from form to upload
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description']; 

    // grabbing image
    $updated_image = $_FILES['image'] ?? null;

    // having some validations for required fields
    if(!$title) $errors[] = 'product title is required';
    if(!$price) $errors[] = 'product price is required';

    // if no errors then update the data to database
    if(empty($errors)){
      $image_path = $image;
      // checking if image is also uploaded...
      if(!empty($updated_image['full_path'])){
        unlink($image_path);
        $image_tmp_name = $updated_image['tmp_name'];
        $image_ext = pathinfo($updated_image['name'], PATHINFO_EXTENSION);
        $image_path = "uploads/products/product_$id.$image_ext";
        move_uploaded_file($image_tmp_name, $image_path);
      }
      $statement = $pdo->prepare("UPDATE products SET title = :title, price = :price, description = :description, image = :image WHERE id = :id");
      $statement->bindValue(":title", $title);
      $statement->bindValue(":price", $price);
      $statement->bindValue(":description", $description);
      $statement->bindValue(":image", $image_path);
      $statement->bindValue(":id", $id);
      $statement->execute();
      header("Location: edit.php?id=$id");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./style.css">
  <title>Product List App | Update Product</title>
</head>
<body>
<header>
    <div class="container">
      <h3>Update Product</h3>
      <p>
        <a href="./index.php" class="btn btn-success">Go back to home page...</a>
      </p>
    </div>
  </header>
  <main>
    <div class="container">
      <?php if(!$product): ?>
        <p>No such product exists....</p>
      <?php else: ?>
        <?php if(!empty($errors)): ?>
          <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
              <div><?= $error ?></div>
            <?php endforeach ?>
          </div>
        <?php endif ?>
        <img src="<?= $image ?>" alt="<?= $title ?>" class="product-image" />
        <form action="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>" enctype="multipart/form-data" method="POST">
          <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" accept="image/*" name="image" class="form-control" id="image" />
          </div>
          <div class="mb-3">
            <label for="title" class="form-label">Product Title:</label>
            <input type="text" name="title" class="form-control" id="title" value="<?= $title ?>" />
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">Product Price:</label>
            <input type="number" min="0" name="price" class="form-control" id="price" value="<?= $price ?>"/>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Product Description:</label>
            <textarea name="description" id="description" class="form-control"><?= $description ?></textarea>
          </div>
          <input type="submit" name="submit" value="submit">
        </form>
      <?php endif ?>
    </div>
  </main>
</body>
</html>


        
        
