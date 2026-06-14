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

// Check if product exists
$check = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
if (mysqli_num_rows($check) == 0) {
    header("Location: read.php");
    exit();
}

// Delete the product
mysqli_query($conn, "DELETE FROM products WHERE id=$id");

// Redirect back to products page with success message
header("Location: read.php?deleted=1");
exit();
?>