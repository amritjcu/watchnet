<?php
// Start session
session_start();

// Include database config
include "includes/config.php";

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is missing.");
}

$product_id = intval($_GET['id']);

// Fetch product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product - Watchnet</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-img {
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            text-align: left;
            margin-top: 20px;
        }

        h2 {
            color: #333;
        }

        .product-detail {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .back-button:hover {
            background-color: #555;
        }
        .breadcrumb {
    font-size: 16px;
    margin-bottom: 20px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.breadcrumb a {
    text-decoration: none;
    color: #3498db;
    font-weight: bold;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb span {
    color: #666;
}


    </style>
</head>
<body>

<div class="container">
    <!-- Breadcrumb Navigation -->
<nav class="breadcrumb">
    <a href="admin_panel.php">Admin Dashboard</a> &raquo;
    <a href="manage_products.php">Manage Products</a> &raquo;
    <span>View Product</span>
</nav>
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>

    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">

    <div class="product-info">
        <p class="product-detail"><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></p>
        <p class="product-detail"><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <p class="product-detail"><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
        <p class="product-detail"><strong>Gender:</strong> <?php echo htmlspecialchars($product['gender']); ?></p>
        <p class="product-detail"><strong>Color:</strong> <?php echo htmlspecialchars($product['color']); ?></p>
        <p class="product-detail"><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?> Available</p>
        <p class="product-detail"><strong>Average Rating:</strong> <?php echo htmlspecialchars($product['average_rating']); ?> ⭐</p>
    </div>

    <a href="manage_products.php" class="back-button">Back to Products</a>
</div>

</body>
</html>
