<?php
// Include the config file to connect to the database
include "includes/config.php";

// Start session for tracking login state
session_start();

// Handle the login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if fields are not empty
    if (empty($email) || empty($password)) {
        echo "<p class='error'>Please fill in all fields!</p>";
    } else {
        // Check if the user exists in the database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables for user login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect user to the home page
                header("Location: index.php");
                exit();
            } else {
                echo "<p class='error'>Invalid password!</p>";
            }
        } else {
            echo "<p class='error'>No user found with this email!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Watchnet</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Resetting some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Main container */
.container {
    width: 100%;
    max-width: 500px;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

/* Login box */
.login-box {
    padding: 30px;
    text-align: center;
}

.login-box h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

/* Form input fields */
.textbox {
    margin-bottom: 15px;
}

.textbox input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

.textbox input:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Submit button */
.btn {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #45a049;
}

/* Link to signup */
p {
    margin-top: 20px;
    font-size: 14px;
}

p a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
}

p a:hover {
    text-decoration: underline;
}

/* Error and success messages */
.error {
    color: red;
    font-size: 16px;
    margin-bottom: 15px;
}

.success {
    color: green;
    font-size: 16px;
    margin-bottom: 15px;
}

        </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Login to Your Account</h2>
            <form action="login.php" method="POST">
                <div class="textbox">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="textbox">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
