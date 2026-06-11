<?php
include '../db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admin only.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = '';
    
    if ($_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = time() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }
    
    mysqli_query($conn, "INSERT INTO products (name, description, price, image) VALUES ('$name','$desc','$price','$image')");
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Product</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-5">
<h2>Add New Product</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" class="form-control mb-2" placeholder="Product Name" required>
    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
    <input type="number" step="0.01" name="price" class="form-control mb-2" placeholder="Price (KES)" required>
    <input type="file" name="image" class="form-control mb-2">
    <button type="submit" class="btn btn-primary">Add Product</button>
    <a href="products.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>