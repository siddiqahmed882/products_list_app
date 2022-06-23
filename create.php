<?php
  require_once('config/db.php');

  $title = $description = $price = null;
  $errors = [];

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    // checking if the directory exists for image upload otherwise creating it.
    if(!is_dir('uploads')) mkdir('uploads');
    if(!is_dir('uploads/products')) mkdir('uploads/products');

    // grabbing image
    $image = $_FILES['image'] ?? null;

    // grabbing data from form to upload
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description']; 

    // having some validations for required fields
    if(!$title) $errors[] = 'product title is required';
    if(!$price) $errors[] = 'product price is required';

    // if no errors then upload the data to database
    if(empty($errors)){
      $statement = $pdo->prepare(
        "INSERT INTO products (title, price, description, image) VALUES(:title, :price, :description, :image)"
      );
      $statement->bindValue(':title', $title);
      $statement->bindValue(':price', $price);
      $statement->bindValue(':description', $description);
      $statement->bindValue(':image', '');
      $statement->execute();

      // get last insert id if image is also uploaded
      if(!empty($image['full_path'])){
        $image_tmp_name = $image['tmp_name'];
        $image_ext = strtolower(end(explode('.', $image['name'])));
        $product_id = $pdo->lastInsertId();
        $image_path = "uploads/products/product_$product_id.$image_ext";
        move_uploaded_file($image_tmp_name, $image_path);
        $statement = $pdo->prepare(
          "UPDATE products SET image = :image WHERE id = :id"
        );
        $statement->bindValue(':image', $image_path);
        $statement->bindValue(':id', $product_id);
        $statement->execute();
      }
      header("Location: index.php");
    }
  }
?>

<?php include_once('partials/header.php') ?>
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
<?php include_once('partials/footer.php') ?>