<?php
  $search_string = $_GET["search"] ?? null;

  require_once('config/db.php');

  $statement = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");

  if($search_string){
    $statement = $pdo->prepare("SELECT * FROM products WHERE title LIKE :search");
    $statement->bindValue(":search", "%$search_string%"); 
    // it's a wildcard search for the search string in the title column of the products table and returns all rows that match the search string in the title column of the products table. T
  }
  $statement->execute();
  $products = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once('partials/header.php') ?>
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
        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="GET">
          <div class="input-group mb-3">
            <input type="text" name="search" value="<?= $search_string ?>" class="form-control" placeholder="Search for products..." aria-label="search for products...">
            <button class="input-group-text">Search</button>
          </div>
        </form>
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
<?php include_once('partials/footer.php') ?>
