<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - Thrift With Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Same navbar as index.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Thrift With Me</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Shop</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4">All Thrift Items</h2>
    <div class="row">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($result)):
        ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <?php if($row['image'] && file_exists("uploads/".$row['image'])): ?>
                    <img src="uploads/<?= $row['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab" class="card-img-top" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h6><?= htmlspecialchars($row['name']) ?></h6>
                    <p class="text-primary fw-bold">KES <?= number_format($row['price'], 2) ?></p>
                    <a href="cart.php?add=<?= $row['id'] ?>" class="btn btn-dark btn-sm w-100">Add to Cart</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © <?= date('Y') ?></p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>