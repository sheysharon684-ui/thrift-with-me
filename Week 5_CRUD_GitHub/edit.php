<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get product ID from URL
$id = $_GET['id'] ?? 0;

// Fetch product details
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);

// If product not found, go back to products page
if (!$product) {
    header("Location: read.php");
    exit();
}

// Update product when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    
    $update_query = "UPDATE products SET 
                     name='$name', 
                     description='$description', 
                     price='$price' 
                     WHERE id=$id";
    
    if (mysqli_query($conn, $update_query)) {
        $success = "Product updated successfully!";
        // Refresh product data
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
        $product = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating product: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Thrift With Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Thrift With Me</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="read.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">✏️ Edit Product</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?> <a href="read.php">View all products</a></div>
                    <?php endif; ?>
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (KES)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="read.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © 2026</p>
</footer>

</body>
</html>