<?php
include 'db.php';
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_GET['add'])) {
    $id = $_GET['add'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit();
}

// Remove item
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit();
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Thrift With Me</title>
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

<div class="container mt-5">
    <h2>Your Shopping Cart</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info">Your cart is empty. <a href="index.php">Continue shopping</a></div>
    <?php else: ?>
        <form method="POST">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $qty):
                    $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
                    if (!$result || mysqli_num_rows($result) == 0) {
                        unset($_SESSION['cart'][$id]);
                        continue;
                    }
                    $product = mysqli_fetch_assoc($result);
                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>KES <?= number_format($product['price'], 2) ?></td>
                        <td><input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1" style="width:80px" class="form-control"></td>
                        <td>KES <?= number_format($subtotal, 2) ?></td>
                        <td><a href="cart.php?remove=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove item?')">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="table-active">
                    <td colspan="3" align="right"><strong>Total:</strong></td>
                    <td colspan="2"><strong>KES <?= number_format($total, 2) ?></strong></td>
                </tr>
                </tbody>
            </table>
            <button type="submit" name="update" class="btn btn-secondary">Update Cart</button>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-warning">Login to Checkout</a>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © 2026</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>