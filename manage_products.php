<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    // Start session to check if user is logged in and is an admin
    session_start();

    // Include the config file for database connection
    include "includes/config.php";

    // Check if the user is logged in and is an admin
    if (! isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        // Redirect to the login page if the user is not an admin
        header("Location: login.php");
        exit();
    }

    if (isset($_GET['message'])) {
        // Display success message
        echo "<div class='success-message'>" . htmlspecialchars($_GET['message']) . "</div>";
    }

    // Fetch all products from the database
    $query  = "SELECT * FROM products";
    $result = $conn->query($query);

    // Set the number of products per page
    $products_per_page = 10;

    // Get the current page from the URL (default to page 1)
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $products_per_page;

    // Fetch the total number of products
    $total_query = "SELECT COUNT(*) AS total FROM products";
    $total_result = $conn->query($total_query);
    $total_row = $total_result->fetch_assoc();
    $total_products = $total_row['total'];

    // Calculate the total number of pages
    $total_pages = ceil($total_products / $products_per_page);

    // Fetch the products for the current page
    $query = "SELECT * FROM products LIMIT $products_per_page OFFSET $offset";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Panel</title>
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

        /* Product Table Styles */
        .product-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .product-table th, .product-table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .product-table th {
            background-color: #4CAF50;
            color: white;
        }

        .product-table tr:hover {
            background-color: #f1f1f1;
        }

        .product-table .action-btns a {
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }

        .product-table .edit-btn {
            background-color: #4CAF50;
        }

        .product-table .delete-btn {
            background-color: #f44336;
        }

        .product-table .view-btn {
            background-color: #2196F3;
        }

        /* Add Product Button */
        .add-product-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .add-product-btn:hover {
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
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
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

<!-- Manage Products Section -->
<main>
    <div class="container">
        <h2>Manage Products</h2>
        <?php
        if (isset($_GET['message'])) {
            // Display success message with a close button
            echo "<div class='success-message'>
                    <span>" . htmlspecialchars($_GET['message']) . "</span>
                    <button class='close-btn' onclick='closeMessage()'>×</button>
                  </div>";
        }
    ?>

        <!-- Breadcrumb Navigation -->
<nav class="breadcrumb">
    <a href="admin_panel.php">Admin Dashboard</a> &raquo;
    <span>Manage Products</span>
</nav>

        <a href="add_product.php" class="add-product-btn">Add New Product</a>

        <!-- Products Table -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['brand']; ?></td>
                            <td>$<?php echo $row['price']; ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td class="action-btns">
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                <a href="view_product.php?id=<?php echo $row['id']; ?>" class="view-btn">View</a>
                            </td>
                        </tr>
                <?php }
                } else {?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No products available</td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
        <!-- Pagination Controls -->
    <div class="pagination">
            <!-- Previous Page -->
            <a href="?page=<?php echo ($current_page > 1) ? $current_page - 1 : 1; ?>" class="<?php echo ($current_page == 1) ? 'disabled' : ''; ?>">Previous</a>

            <!-- Page Number Links -->
            <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
                <a href="?page=<?php echo $page; ?>" class="<?php echo ($current_page == $page) ? 'active' : ''; ?>"><?php echo $page; ?></a>
            <?php } ?>

            <!-- Next Page -->
            <a href="?page=<?php echo ($current_page < $total_pages) ? $current_page + 1 : $total_pages; ?>" class="<?php echo ($current_page == $total_pages) ? 'disabled' : ''; ?>">Next</a>
        </div>

    </div>
                </div>
                </main>
<footer>
    <p>&copy; 2025 Watchnet. All rights reserved.</p>
</footer>
<script>
// JavaScript function to close the message when the close button is clicked
function closeMessage() {
    const messageBox = document.querySelector('.success-message');
    messageBox.classList.add('hide-message');
}
</script>
</body>
</html>
