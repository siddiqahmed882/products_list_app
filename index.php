<?php
  $pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_list_app", "siddiq", "test1234");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $statement = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
  $statement->execute();
  $products = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./style.css">
  <title>Product List App</title>
</head>

<body>
  <header>
    <div class="container">
      <h1>Products List</h1>
      <p>
        <a href="./create.php" class="btn btn-success">Create Product</a>
      </p>
    </div>
  </header>
  <main>
    <div class="container">
      <?php if(empty($products)): ?>
        <p>No products to show. Please Add some products...</p>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Price</th>
            <th scope="col">Created At</th>
            <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($products as $index => $product): ?>
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="product__thumbnail"></td>
                <td><?= $product['title'] ?></td>
                <td>Rs. <?= $product['price'] ?></td>
                <td><?= $product['created_at'] ?></td>
                <td>
                  <a href="edit.php?id=<?= $product["id"] ?>" class="btn btn-sm btn-outline-primary">Edit</a>  
                  <form action="delete.php" method="POST" class="action-form">
                    <input type="hidden" name="id" value="<?= $product["id"] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>  
                  </form>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      <?php endif ?>
    </div>
  </main>
</body>

</html>