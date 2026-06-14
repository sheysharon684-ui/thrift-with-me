<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id=$user_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Thrift With Me</title>
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

<div class="container mt-5">
    <div class="alert alert-info">
        <h3>Welcome back, <?= htmlspecialchars($_SESSION['fullname']) ?>! 👋</h3>
    </div>
    
    <h4>📦 My Order History</h4>
    <?php if(mysqli_num_rows($orders) == 0): ?>
        <div class="alert alert-secondary">You haven't placed any orders yet. <a href="index.php">Start shopping</a></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total (KES)</th>
                        <th>Status</th>
                        <th>Shipping Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($order = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                        <td><?= number_format($order['total'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= $order['status'] == 'pending' ? 'warning' : ($order['status'] == 'completed' ? 'success' : 'danger') ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                        <td><a href="order_success.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">View</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <hr>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © 2026</p>
</footer>
</body>
</html>