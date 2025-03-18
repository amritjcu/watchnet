<?php
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

    // Include the config file to connect to the database
    include "includes/config.php";
    
    // Handle the signup form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize user input to avoid any harmful characters
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'];
    
        // Check if any field is empty
        if (empty($username) || empty($email) || empty($password) || empty($role)) {
            echo "<p class='error'>All fields are required!</p>";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Insert user into the database
            $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', '$role')";
            
            if ($conn->query($query) === TRUE) {
                echo "<p class='success'>Registration successful! You can now <a href='login.php'>login</a>.</p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
    }
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Signup - Watchnet</title>
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

/* Signup box */
.signup-box {
    padding: 30px;
    text-align: center;
}

.signup-box h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

/* Form input fields */
.textbox {
    margin-bottom: 15px;
}

.textbox input,
.textbox select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

.textbox input:focus,
.textbox select:focus {
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

/* Link to login */
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
            <div class="signup-box">
                <h2>Create an Account</h2>
                <form action="signup.php" method="POST">
                    <div class="textbox">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="textbox">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="textbox">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="textbox">
                        <select name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Signup</button>
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </form>
            </div>
        </div>
    </body>
    </html>
    

