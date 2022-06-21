<?php
  $pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_list_app", "siddiq", "test1234");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $title = $description = $price = null;
  $errors = [];

  echo $_SERVER["REQUEST_METHOD"];

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description']; 


    if(!$title) $errors[] = 'product title is required';
    if(!$price) $errors[] = 'product price is required';

    if(empty($erors)){
      $statement = $pdo->prepare(
        "INSERT INTO products (title, price, description, image) VALUES(:title, :price, :description, :image)"
      );
      $statement->bindValue(':title', $title);
      $statement->bindValue(':price', $price);
      $statement->bindValue(':description', $description);
      $statement->bindValue(':image', '');
      $statement->execute();
      header("Location: create.php");
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
  <title>Product List App | Create Product</title>
</head>
<body>
<header>
    <div class="container">
      <h1>Create New Product</h1>
      <p>
        <a href="./index.php" class="btn btn-success">See All Products</a>
      </p>
    </div>
  </header>
  <main>
    <div class="container">
      <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
          <?php foreach($errors as $error): ?>
            <div><?= $error ?></div>
          <?php endforeach ?>
        </div>
      <?php endif ?>
      <form action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="POST">
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
    </div>
  </main>
</body>
</html>