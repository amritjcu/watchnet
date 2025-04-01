<?php
session_start();
include "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $new_email = !empty($_POST['new_email']) ? filter_var($_POST['new_email'], FILTER_SANITIZE_EMAIL) : null;
    $current_password = $_POST['current_password'];
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;

    // Fetch current password hash
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error_message'] = "Incorrect current password.";
        header("Location: profile.php");
        exit();
    }

    // Update email if provided
    if ($new_email) {
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format.";
            header("Location: profile.php");
            exit();
        }
        $query = "UPDATE users SET email = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_email, $user_id);
        $stmt->execute();
    }

    // Update password if provided
    if ($new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
    }

    // Store success message and redirect to home page
    $_SESSION['success_message'] = "Profile updated successfully!";
    header("Location: index.php");
    exit();
}
?>
