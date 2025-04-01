<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
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
  </head>s

<body>
    <!-- Navbar -->
    <!-- Display Success Message -->
    <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['success_message']); // Clear message after displaying ?>
<?php endif; ?>
    <!-- Header Section -->
    <header class="bg-light py-3 bg-dark text-white">
      <div class="container">
        <div class="row align-items-center">
          <!-- Logo on the left -->
          <div class="col-6 col-md-4">
            <a href="./index.html" class="text-white">
              <img
                src="./assets/images/LogoWhite.png"
                alt="Logo"
                class="img-fluid"
                style="max-height: 70px"
              />
            </a>
          </div>
          <!-- Navigation Menu on the right -->
          <div class="col-6 col-md-8">
    <nav class="navbar navbar-expand-lg navbar-light float-right">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superuser')): ?>
                    <li class="nav-item">
                    <a href="admin_panel.php" class="admin-panel nav-link text-white">Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Logout</a>
                    </li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
                    <li class="nav-item">
                    <a class="nav-link text-white" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                    <a href="login.php" class="login nav-link text-white">Login</a>
                    </li>
                    <li class="nav-item">
                    <a href="signup.php" class="signup nav-link text-white">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a href="cart.html" class="btn btn-outline-primary">Cart (0)</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    </div>

        </div>
      </div>
    </header>

    <!-- main -->
    <div class="container py-5">
        <h2 class="text-center mb-4">About Us</h2>
        <div class="row">
            <div class="col-md-6">
                <img src="assets/images/about-us.jpg" class="img-fluid rounded" alt="About Us">
            </div>
            <div class="col-md-6">
                <h3>Welcome to My Shop!</h3>
                <p>
                    My Shop is your one-stop destination for the latest and greatest products. We are passionate about delivering high-quality products at competitive prices. Our journey started with a simple mission — to make shopping easy and convenient for everyone.
                </p>
                <p>
                    Our team carefully curates each product, ensuring that it meets our high standards of quality and design. We are committed to providing exceptional customer service and a seamless shopping experience.
                </p>
                <p>
                    Thank you for choosing My Shop. We hope you enjoy shopping with us!
                </p>
            </div>
        </div>
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