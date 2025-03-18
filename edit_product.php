<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include "includes/config.php";

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get product ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = $_GET['id'];

// Fetch product details from database
$query = "SELECT * FROM products WHERE id = '$product_id'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $color = $_POST['color'];
    $average_rating = $_POST['average_rating'];
    $stock = $_POST['stock'];

    // Validate Gender Selection
    $allowed_genders = ['Male', 'Female', 'Unisex'];
    $gender = in_array($_POST['gender'], $allowed_genders) ? $_POST['gender'] : 'Unisex'; // Default to 'Unisex'

    $update_image_sql = "";

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $update_image_sql = ", image='$image_name'";
        } else {
            echo "<p>Error uploading the image.</p>";
        }
    }

    $update_query = "UPDATE products SET 
        name='$name', 
        brand='$brand', 
        description='$description', 
        price='$price', 
        gender='$gender', 
        color='$color', 
        average_rating='$average_rating', 
        stock='$stock'
        $update_image_sql 
        WHERE id='$product_id'";

    if ($conn->query($update_query)) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "<p>Error updating product: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .current-image {
            text-align: center;
            margin: 10px 0;
        }

        .current-image img {
            width: 150px;
            border-radius: 5px;
        }

        .button {
            display: block;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .button:hover {
            background-color: #45a049;
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
<nav class="breadcrumb">
    <a href="admin_panel.php">Admin Dashboard</a> &raquo;
    <a href="manage_products.php">Manage Products</a> &raquo;
    <span>Edit Product</span>
</nav>
    <h2>Edit Product</h2>

    <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="brand">Brand</label>
            <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php echo ($product['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($product['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Unisex" <?php echo ($product['gender'] == 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
            </select>
        </div>

        <div class="form-group">
            <label for="color">Color</label>
            <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($product['color']); ?>" required>
        </div>

        <div class="form-group">
            <label for="average_rating">Average Rating</label>
            <input type="number" id="average_rating" name="average_rating" step="0.1" max="5" value="<?php echo htmlspecialchars($product['average_rating']); ?>" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image">
        </div>

        <?php if (!empty($product['image'])): ?>
        <div class="current-image">
            <p>Current Image:</p>
            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
        </div>
        <?php endif; ?>

        <div class="form-group">
            <button type="submit" class="button">Update Product</button>
        </div>
    </form>
</div>

</body>
</html>
