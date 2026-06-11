<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Thrift With Me</title>
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
                <li class="nav-item"><a class="nav-link" href="add_product.php">Add Product</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>📦 Product Management</h2>
    
    <?php if(isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Product deleted successfully!</div>
    <?php endif; ?>
    
    <a href="add_product.php" class="btn btn-primary mb-3">➕ Add New Product</a>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price (KES)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($products) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= number_format($row['price'], 2) ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($row['name']) ?>?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No products found. <a href="add_product.php">Add your first product</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © 2026</p>
</footer>

</body>
</html>