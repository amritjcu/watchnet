<?php
session_start();
include "includes/config.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Watchent</title>
    <!-- Bootstrap CSS -->
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <div class="container mt-5">
        <h2>Profile Settings</h2>

        <!-- Display Success Message -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Profile Update Form -->
        <form action="update_profile.php" method="post">
            <div class="form-group">
                <label>Current Email:</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label>New Email (Optional):</label>
                <input type="email" name="new_email" class="form-control">
            </div>
            <div class="form-group">
                <label>Current Password:</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>New Password (Optional):</label>
                <input type="password" name="new_password" class="form-control">
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
        </form>

        <hr>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
</body>
</html>
