<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    // Start session to check if user is logged in and is an admin
    session_start();

    // Include the config file for database connection
    include "includes/config.php";

    if (! $conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

    // Process the form submission when it is posted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        // Sanitize & validate inputs
        $name           = trim($_POST["name"]);
        $brand          = trim($_POST["brand"]);
        $price          = $_POST["price"];
        $stock          = $_POST["stock"];
        $rating         = $_POST["average_rating"];
        $color          = trim($_POST["color"]);
        $gender         = $_POST["gender"];
        $description    = trim($_POST["description"]);
        $is_banner      = strtolower($_POST['is_banner']);
        $is_new_arrival = strtolower($_POST['is_new_arrival']);
        $is_featured    = strtolower($_POST['is_featured']);
        // Ensure price is a valid decimal (check with regex)
        if (! is_numeric($price) || $price < 0 || $price > 100000) {
            $errors[] = "Price must be a valid decimal between 0 and 100,000.";
        } else {
                                                                // Ensure price has 2 decimal places
            $price = number_format((float) $price, 2, '.', ''); // Format the price to two decimals
        }
        // Image upload handling
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image          = $_FILES['image'];
            $image_name     = $image['name'];
            $image_tmp_name = $image['tmp_name'];
            $image_size     = $image['size'];
            $image_error    = $image['error'];

            // Get image extension and validate the type
            $image_ext   = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($image_ext), $allowed_ext)) {
                // Generate a unique name for the image to avoid conflicts
                $image_new_name    = uniqid('', true) . "." . $image_ext;
                $image_destination = 'uploads/' . $image_new_name;

                // Move image to the target directory
                if (move_uploaded_file($image_tmp_name, $image_destination)) {
                    // Successfully uploaded image
                    $image = $image_new_name;
                } else {
                    $errors[] = "Error uploading image.";
                }
            } else {
                $errors[] = "Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        } else {
            $errors[] = "Product image is required.";
        }

        // Validate product name (allows letters, numbers, and spaces)
        if (! preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
            $errors[] = "Product Name can only contain letters, numbers, and spaces.";
        }

        // Validate brand (only letters and spaces allowed)
        if (! preg_match("/^[a-zA-Z\s]+$/", $brand)) {
            $errors[] = "Brand should contain only letters and spaces.";
        }

        // Validate numeric values
        if (! is_numeric($price) || $price <= 0) {
            $errors[] = "Price must be a valid positive number.";
        }
        if (! is_numeric($stock) || $stock < 0) {
            $errors[] = "Stock must be a valid number (zero or more).";
        }

        // Validate rating
        if ($rating < 1 || $rating > 5) {
            $errors[] = "Average Rating must be between 1 and 5.";
        }

        // Validate color
        if (empty($color)) {
            $errors[] = "Please enter the product color.";
        }

        // Validate gender
        if ($gender !== "Male" && $gender !== "Female") {
            $errors[] = "Invalid gender selection.";
        }

        // Validate description
        if (strlen($description) < 10) {
            $errors[] = "Description must be at least 10 characters long.";
        }

        // Display errors or process data
        if (! empty($errors)) {
            echo "<div style='color: red;'><ul><li>" . implode("</li><li>", $errors) . "</li></ul></div>";
            include "product_form.html"; // Reload the form with errors
        } else {
            // Process the form data (insert into the database)
            $stmt = $conn->prepare("INSERT INTO products
            (name, brand, description, image, price, gender, color, average_rating, stock, is_banner, is_new_arrival, is_featured)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Bind parameters to prevent SQL injection
            $stmt->bind_param(
                "ssssdssdisss",
                $name,
                $brand,
                $description,
                $image,
                $price,
                $gender,
                $color,
                $rating,
                $stock,
                $is_banner,
                $is_new_arrival,
                $is_featured
            );

            // Execute query
            if ($stmt->execute()) {
                // Redirect to manage_products.php with a success message
                header("Location: manage_products.php?message=Product added successfully!");
            } else {
                echo "<div style='color: red;'>Error adding product: " . $stmt->error . "</div>";
            }

            // Close the statement and the database connection
            $stmt->close();
            $conn->close();
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
        <div id="error-messages" style="display: none; color: red; font-weight: bold;"></div>

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
        <input type="number" id="price" name="price" step="0.01" min="0" placeholder="Enter price" required>
    </div>

    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" placeholder="Enter stock quantity" required>
    </div>

    <div class="form-group">
        <label for="average_rating">Average Rating</label>
        <input type="number" id="average_rating" name="average_rating" step="0.1" min="1" max="5" placeholder="Enter rating (1-5)" required>
    </div>

    <div class="form-group">
        <label for="color">Color</label>
        <input type="text" id="color" name="color" placeholder="Enter product color" required>
    </div>

    <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>

    <div class="form-group">
        <label for="image">Product Image</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>

    <div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" placeholder="Enter product description" required></textarea>
</div>

    <div class="form-group">
        <label for="is_banner">Is Banner</label>
        <select id="is_banner" name="is_banner" required>
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
    </div>

    <div class="form-group">
        <label for="is_new_arrival">Is New Arrival</label>
        <select id="is_new_arrival" name="is_new_arrival" required>
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
    </div>

    <div class="form-group">
        <label for="is_featured">Is Featured</label>
        <select id="is_featured" name="is_featured" required>
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".product-form");

    form.addEventListener("submit", function (event) {
        let errors = [];

        // Get form values
        let name = document.getElementById("name").value.trim();
        let brand = document.getElementById("brand").value.trim();
        let price = document.getElementById("price").value;
        let stock = document.getElementById("stock").value;
        let rating = document.getElementById("average_rating").value;
        let color = document.getElementById("color").value.trim();
        let gender = document.getElementById("gender").value;
        let description = document.getElementById("description").value.trim();

        function validateForm() {
    const price = document.getElementById('price').value;
    const priceRegex = /^[0-9]+(\.[0-9]{1,2})?$/;  // Regex for valid decimal numbers

    if (!priceRegex.test(price) || parseFloat(price) < 0 || parseFloat(price) > 100000) {
        alert("Please enter a valid price within the allowed range (0 - 100000). Decimal values are allowed.");
        return false;
    }
    return true;
}

        // Define Regex
        let alphanumericRegex = /^[a-zA-Z0-9\s]+$/;
        let alphaRegex = /^[a-zA-Z\s]+$/; // Added this line

        // Product Name: Letters, numbers, spaces only
        if (!alphanumericRegex.test(name)) {
            errors.push("Product Name can only contain letters, numbers, and spaces.");
        }

        // Brand: Only letters and spaces
        if (!alphaRegex.test(brand)) {
            errors.push("Brand should contain only letters and spaces.");
        }

        // Price & Stock: Must be numeric
        if (isNaN(price) || price <= 0) {
            errors.push("Price must be a valid positive number.");
        }
        if (isNaN(stock) || stock < 0) {
            errors.push("Stock must be a valid number (zero or more).");
        }

        // Rating: Should be between 1 and 5
        if (isNaN(rating) || rating < 1 || rating > 5) {
            errors.push("Average Rating must be between 1 and 5.");
        }

        // Color: Required
        if (color === "") {
            errors.push("Please enter the product color.");
        }

        // Gender: Required
        if (gender === "") {
            errors.push("Please select a gender.");
        }

        // Description: Must be at least 10 characters
        if (description.length < 10) {
            errors.push("Description must be at least 10 characters long.");
        }

        // Show errors
        let errorContainer = document.getElementById("error-messages");
        if (errors.length > 0) {
            event.preventDefault(); // Prevent form submission
            errorContainer.innerHTML = "<ul><li>" + errors.join("</li><li>") + "</li></ul>";
            errorContainer.style.display = "block";
        } else {
            errorContainer.style.display = "none"; // Hide errors if no issues
        }
    });
});
</script>

</body>
</html>
