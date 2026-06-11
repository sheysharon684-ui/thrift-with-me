<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($conn, "SELECT price FROM products WHERE id=$id");
    if ($res && mysqli_num_rows($res) > 0) {
        $price = mysqli_fetch_assoc($res)['price'];
        $total += $price * $qty;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $user_id = $_SESSION['user_id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    try {
        // Insert order
        $order_query = "INSERT INTO orders (user_id, total, shipping_address, status) VALUES ('$user_id', '$total', '$address', 'pending')";
        mysqli_query($conn, $order_query);
        $order_id = mysqli_insert_id($conn);
        
        // Insert order items
        foreach ($_SESSION['cart'] as $id => $qty) {
            $res = mysqli_query($conn, "SELECT price FROM products WHERE id=$id");
            $price = mysqli_fetch_assoc($res)['price'];
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$id', '$qty', '$price')";
            mysqli_query($conn, $item_query);
        }
        
        mysqli_commit($conn);
        unset($_SESSION['cart']); // Clear cart
        header("Location: order_success.php?id=$order_id");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Order failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Thrift With Me</title>
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
    <h2>Checkout</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Shipping Information</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" class="form-control" value="<?= $_SESSION['fullname'] ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="0712345678" required>
                        </div>
                        <div class="mb-3">
                            <label>Shipping Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Street, building, city" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Place Order</button>
                        <a href="cart.php" class="btn btn-secondary w-100 mt-2">Back to Cart</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Order Summary</h5>
                    <table class="table">
                        <?php foreach ($_SESSION['cart'] as $id => $qty):
                            $res = mysqli_query($conn, "SELECT name, price FROM products WHERE id=$id");
                            $product = mysqli_fetch_assoc($res);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?> x<?= $qty ?></td>
                            <td>KES <?= number_format($product['price'] * $qty, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-active">
                            <td><strong>Total</strong></td>
                            <td><strong>KES <?= number_format($total, 2) ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>Thrift With Me © 2026</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>