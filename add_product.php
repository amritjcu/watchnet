<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Start session to check if user is logged in and is an admin
session_start();

// Include the config file for database connection
include "includes/config.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // Redirect to the login page if the user is not an admin
    header("Location: login.php");
    exit();
}

// Process the form submission when it is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $gender = $_POST['gender'];
    $color = $_POST['color'];
    $description = $_POST['description'];
    $average_rating = $_POST['average_rating'];
    $stock = $_POST['stock'];

    // Image upload handling
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_error = $image['error'];
        
        // Check for image errors
        if ($image_error === 0) {
            // Get image extension and validate the type
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array(strtolower($image_ext), $allowed_ext)) {
                // Generate a unique name for the image to avoid conflicts
                $image_new_name = uniqid('', true) . "." . $image_ext;
                $image_destination = 'uploads/' . $image_new_name;
                
                // Move image to the target directory
                if (move_uploaded_file($image_tmp_name, $image_destination)) {
                    // Insert the product into the database
                    $query = "INSERT INTO products (name, brand, description, price, gender, color, average_rating, image, stock, created_at) 
                              VALUES ('$name', '$brand', '$description', '$price', '$gender', '$color', '$average_rating', '$image_new_name', '$stock', NOW())";
                    if ($conn->query($query)) {
                        // Redirect back to the product management page
                        header("Location: manage_products.php");
                        exit();
                    } else {
                        echo "<p>Error adding product. Please try again later.</p>";
                    }
                } else {
                    echo "<p>Error uploading the image.</p>";
                }
            } else {
                echo "<p>Invalid image type. Only JPG, JPEG, PNG, GIF files are allowed.</p>";
            }
        } else {
            echo "<p>There was an error uploading the image.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Container for Admin Panel */
        .container {
            width: 80%;
            margin: 0 auto;
        }

        /* Header */
        header {
            background-color: #333;
            padding: 15px 0;
            color: #fff;
            text-align: center;
        }

        header .logo a {
            text-decoration: none;
            color: #fff;
            font-size: 2em;
            font-weight: bold;
        }

        header .user-options {
            text-align: right;
            margin-top: 10px;
        }

        header .user-options span {
            margin-right: 15px;
        }

        header .user-options .logout {
            color: #fff;
            text-decoration: none;
            background-color: #f44336;
            padding: 8px 15px;
            border-radius: 5px;
        }

        /* Form Styling */
        .product-form {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        .product-form .form-group {
            margin-bottom: 20px;
        }

        .product-form .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .product-form .form-group input,
        .product-form .form-group textarea,
        .product-form .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }

        .product-form .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .product-form .button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .product-form .button:hover {
            background-color: #45a049;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
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

<!-- Admin Panel Header -->
<header>
    <div class="container">
        <div class="logo">
            <a href="index.php">Watchnet</a>
        </div>
        <div class="user-options">
            <span>Welcome, Admin</span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>
</header>

<!-- Add Product Section -->
<main>
    <div class="container">
        <h2>Add New Product</h2>

      <!-- Breadcrumb Navigation -->
<nav class="breadcrumb">
    <a href="admin_panel.php">Admin Dashboard</a> &raquo;
    <a href="manage_products.php">Manage Products</a> &raquo;
    <span>Add Product</span>
</nav>

        <!-- Form to Add Product -->
        <form action="add_product.php" method="POST" enctype="multipart/form-data" class="product-form">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" placeholder="Enter product name" required>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" placeholder="Enter product brand" required>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Unisex">Unisex</option>
                </select>
            </div>

            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" placeholder="Enter product color" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter product description" required></textarea>
            </div>

            <div class="form-group">
                <label for="average_rating">Average Rating</label>
                <input type="number" id="average_rating" name="average_rating" step="0.1" min="1" max="5" placeholder="Enter rating (1-5)" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" placeholder="Enter stock quantity" required>
            </div>

            <div class="form-group">
                <button type="submit" class="button">Add Product</button>
            </div>
        </form>
    </div>
</main>

<!-- <footer>
    <p>&copy; 2025 Watchnet. All rights reserved.</p>
</footer> -->

</body>
</html>
