
<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    include "includes/config.php";

    // Check admin access
    if (! isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        header("Location: login.php");
        exit();
    }

    // Success message (optional)
    if (isset($_GET['message'])) {
        echo "<div class='success-message'>" . htmlspecialchars($_GET['message']) . "</div>";
    }

    // Fetch users
    $query  = "SELECT * FROM users ORDER BY created_at DESC";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .main-wrapper {
        flex: 1;
    }


        .container {
            width: 80%;
            margin: 0 auto;
        }

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

        .user-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .user-table th, .user-table td {
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .user-table th {
            background-color: #4CAF50;
            color: white;
        }

        .user-table tr:hover {
            background-color: #f1f1f1;
        }

        .action-btns a {
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .delete-btn {
            background-color: #f44336;
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

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="main-wrapper">

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

<main>
    <div class="container">
        <h2>Manage Users</h2>

        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="admin_panel.php">Admin Dashboard</a> &raquo;
            <span>Manage Users</span>
        </nav>

        <!-- User Table -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo ucfirst($row['role']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td class="action-btns">
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
<?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
                </div>
<footer>
    <p>&copy; 2025 Watchnet. All rights reserved.</p>
</footer>

</body>
</html>
