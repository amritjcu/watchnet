<?php
// Start session and include database connection
session_start();
include "includes/config.php";

// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the product ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']);

// Fetch the product image name
$query = "SELECT image FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$image_path = "uploads/" . $product['image'];

// Delete the product from the database
$delete_query = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    // Remove the product image from the uploads folder
    if (file_exists($image_path)) {
        unlink($image_path);
    }
    
    // Redirect back to manage_products.php
    header("Location: manage_products.php?message=Product deleted successfully");
    exit();
} else {
    echo "Error deleting product.";
}
?>
