<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Watchnet</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General Reset and Layout */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    box-sizing: border-box;
    display: flex;
    min-height: 100vh;
}

.admin-panel-wrapper {
    display: flex;
    width: 100%;
    min-height: 100vh;
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    background-color: #2c3e50;
    color: #fff;
    padding: 30px 20px;
    position: fixed;
    height: 100%;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    justify-content: flex-start; /* Align items from the top */
}

/* Sidebar Header */
.sidebar .sidebar-header {
    display: flex;
    justify-content: center; /* Horizontally center the header text */
    align-items: center; /* Vertically center the header text */
    margin-bottom: 40px;
}

.sidebar .sidebar-header h2 {
    font-size: 22px;
    margin: 0;
}

/* Sidebar Navigation */
.sidebar-nav {
    display: flex;
    flex-direction: column; /* Arrange nav items vertically */
    align-items: flex-start; /* Align items to the left */
    padding: 0; /* Remove padding */
    margin: 0; /* Remove margin */
    flex-grow: 1; /* Take up remaining space */
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%; /* Make the list take up full width */
}

.sidebar-nav ul li {
    margin: 20px 0;
}

.sidebar-nav ul li a {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 18px;
    display: block;
    padding: 12px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.sidebar-nav ul li a:hover {
    background-color: #34495e;
}

.sidebar-nav ul li a.logout {
    background-color: #e74c3c;
}

.sidebar-nav ul li a.logout:hover {
    background-color: #c0392b;
}


/* Main Content */
.main-content {
    margin-left: 250px;
    padding: 40px 20px;
    width: 100%;
    background-color: #ecf0f1;
    box-sizing: border-box;
    transition: margin-left 0.3s;
}

/* Admin Header */
.admin-header {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.admin-header .header-content {
    font-size: 20px;
    color: #2c3e50;
}

/* Dashboard Section */
.dashboard {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.dashboard h2 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 30px;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.card {
    background-color: #fff;
    padding: 20px;
    text-align: center;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
}

.card h3 {
    font-size: 20px;
    color: #7f8c8d;
}

.card p {
    font-size: 36px;
    font-weight: bold;
    color: #2ecc71;
}

/* Footer */
footer {
    background-color: #2c3e50;
    color: #ecf0
}

        </style>
</head>
<body>

<!-- Admin Panel Wrapper -->
<div class="admin-panel-wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>&nbsp;&nbsp;Admin Panel</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin_panel.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <!-- <li><a href="manage_orders.php">Manage Orders</a></li> -->
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="admin-header">
            <div class="header-content">
                <span>&nbsp;&nbsp;&nbsp; Welcome, Admin</span>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard">
            <h2>&nbsp;&nbsp; Admin Dashboard</h2>
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total Products</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM products";
                        $result = $conn->query($query);
                        $row = $result->fetch_row();
                        echo $row[0];
                        ?>
                    </p>
                </div>
                <div class="card">
                    <h3>Total Users</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM users";
                        $result = $conn->query($query);
                        $row = $result->fetch_row();
                        echo $row[0];
                        ?>
                    </p>
                </div>
                <div class="card">
                    <h3>Total Orders</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM orders";
                        $result = $conn->query($query);
                        $row = $result->fetch_row();
                        echo $row[0];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </main>

</div>

<footer>
    <p>&copy; 2025 Watchnet. All rights reserved.</p>
</footer>

</body>
</html>
