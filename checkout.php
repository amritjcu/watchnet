<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchent</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <!-- Display Success Message -->
  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['success_message']; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <!-- Header Section -->
  <header class="bg-dark text-white py-3">
    <div class="container">
      <div class="row align-items-center">
        <!-- Logo -->
        <div class="col-6 col-md-4">
          <a href="index.php" class="text-white">
            <img src="./assets/images/LogoWhite.png" alt="Logo" class="img-fluid" style="max-height: 70px">
          </a>
        </div>

        <!-- Navigation -->
        <div class="col-6 col-md-8">
          <nav class="navbar navbar-expand-lg navbar-light float-right">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-white" id="navbarNav">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                  <a class="nav-link text-white" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="product.php">Products</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="about_us.php">About Us</a>
                </li>

                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superuser')): ?>
                  <li class="nav-item">
                    <a href="admin_panel.php" class="nav-link text-white">Admin Panel</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-white" href="logout.php">Logout</a>
                  </li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                  <li class="nav-item">
                    <a class="nav-link text-white" href="profile.php">Profile</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-white" href="logout.php">Logout</a>
                  </li>
                <?php else: ?>
                  <li class="nav-item">
                    <a href="login.php" class="nav-link text-white">Login</a>
                  </li>
                  <li class="nav-item">
                    <a href="signup.php" class="nav-link text-white">Sign Up</a>
                  </li>
                  <li class="nav-item">
                    <a href="cart.php" class="btn btn-outline-primary">Cart (0)</a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </header>

    <div class="container py-5">
        <h2>Checkout</h2>
    
        <p>Total Amount: <strong>$100.20</strong></p>
    
        <form method="POST" action="order-success.html">
            <!-- Shipping Form -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" required></textarea>
            </div>
    
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" name="state" id="state" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
    
            <button type="submit" class="btn btn-success">Confirm Order</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-5">
        <div class="container">
            &copy; 2025 MyShop. All Rights Reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/assets/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   
</body>

</html>