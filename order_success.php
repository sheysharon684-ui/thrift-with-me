<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['id'] ?? 0;
$result = mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id AND user_id={$_SESSION['user_id']}");
if (mysqli_num_rows($result) == 0) {
    die("Order not found.");
}
$order = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Thrift With Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Thrift With Me</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 text-center">
    <div class="alert alert-success">
        <h2>✅ Order Placed Successfully!</h2>
        <p>Thank you for shopping with Thrift With Me!</p>
        <p>Your order ID is: <strong>#<?= $order_id ?></strong></p>
        <p>Total amount: <strong>KES <?= number_format($order['total'], 2) ?></strong></p>
        <p>We'll ship it to: <?= htmlspecialchars($order['shipping_address']) ?></p>
        <hr>
        <a href="dashboard.php" class="btn btn-dark">View My Orders</a>
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © 2026</p>
</footer>
</body>
</html>