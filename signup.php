<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "includes/config.php";

$username = $email = "";
$errors = [];
$successMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    $role = 'user';

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        // Format checks
        if (!preg_match('/^[A-Za-z0-9]{3,20}$/', $username)) {
            $errors[] = "Username should be 3-20 characters and contain only letters and numbers.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Password should be at least 6 characters.";
        }

        // Check if username already exists using prepared statement to avoid SQL injection
        $checkUser = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $checkUser->bind_param("s", $username);
        $checkUser->execute();
        $result = $checkUser->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Username already exists. Please choose another.";
        }

        // Check if email already exists using prepared statement to avoid SQL injection
        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $resultEmail = $checkEmail->get_result();
        if ($resultEmail->num_rows > 0) {
            $errors[] = "Email is already registered. Please use another one.";
        }
    }

    // If no errors, insert into DB using prepared statements to prevent SQL injection
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $username, $email, $hashedPassword, $role);
        if ($query->execute()) {
            $successMessage = "Registration successful! You can now <a href='login.php'>login</a>.";
            // Clear form
            $username = $email = "";
        } else {
            $errors[] = "Database error: " . $conn->error;
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .signup-box {
            padding: 30px;
            text-align: center;
        }
        .signup-box h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
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
        .error {
            color: red;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: left;
        }
        .success {
            color: green;
            font-size: 16px;
            margin-bottom: 15px;
            text-align: center;
        }
        .breadcrumb {
            background-color: #f9f9f9;
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
        }
        .breadcrumb a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .breadcrumb a:hover {
            color: #45a049;
            text-decoration: underline;
        }
        .breadcrumb span {
            color: #aaa;
            margin-left: 8px;
        }
        .breadcrumb::before {
            content: '»';
            margin-right: 8px;
            color: #4CAF50;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="index.php">Home</a><span>Signup</span>
    </div>
    <div class="signup-box">
        <h2>Create an Account</h2>

        <?php
        // Display errors
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='error'>• $error</p>";
            }
        }

        // Display success message
        if (!empty($successMessage)) {
            echo "<p class='success'>$successMessage</p>";
        }
        ?>

        <form action="signup.php" method="POST">
            <div class="textbox">
                <input type="text" name="username" placeholder="Username"
                       required pattern="[A-Za-z0-9]{3,20}"
                       title="Username should be 3-20 characters, only letters and numbers."
                       value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="textbox">
                <input type="email" name="email" placeholder="Email"
                       required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="textbox">
                <input type="password" name="password" placeholder="Password"
                       required minlength="6" title="Password should be at least 6 characters.">
            </div>
            <button type="submit" class="btn">Signup</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</div>
</body>
</html>
