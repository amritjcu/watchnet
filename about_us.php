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

  <!-- Main Content -->
  <div class="container py-5">
    <h2 class="text-center mb-4">About Us</h2>
    <div class="row">
      <div class="col-md-6">
        <img src="assets/images/about-us.jpg" class="img-fluid rounded" alt="About Us">
      </div>
      <div class="col-md-6">
        <h3>Welcome to My Shop!</h3>
        <p>
        At My Shop, we believe that shopping should be more than just a transaction — it should be an enjoyable and fulfilling experience. As your ultimate one-stop destination for the latest and greatest products, we strive to offer something for everyone. we are passionate about delivering high-quality products at competitive prices.
                </p>
                <p>
Our journey began with a simple yet powerful mission: to make shopping easy, convenient, and accessible to all. Over the years, we've built a reputation for carefully curating each item in our collection, ensuring that it meets our strict standards of quality, functionality, and design. We understand that every customer is unique, which is why we work tirelessly to offer products that cater to a variety of needs, preferences, and lifestyles.
</p>
<p>
Our team is committed to providing exceptional customer service at every step of the journey. we aim to create a seamless shopping experience. We continuously improve our platform, making it user-friendly, fast, and secure, so you can shop with confidence. We value each customer and treat every interaction with the utmost care and attention.
</p>
<p>
At My Shop, we don't just sell products — we create connections. We believe that great service and quality products can transform the way people shop. Your satisfaction is our top priority, and we are here to ensure that every order is a positive and memorable one.
</p>
<p>
Thank you for choosing My Shop as your go-to destination for all your shopping needs. We hope you enjoy exploring. Happy shopping!
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
