<?php
// Start session to check user login status
session_start();

// Include database connection
include "includes/config.php";

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Sanitize the product ID to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);

    // Query to fetch the product details from the database
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $conn->query($query);

    // Check if product is found
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc(); // Fetch product details
    } else {
        // If no product found, redirect to products page or show an error message
        header("Location: product.php");
        exit;
    }
} else {
    // If no product ID is provided, redirect to products page
    header("Location: product.php");
    exit;
}

// Fetch featured products where is_featured = 'yes'
$featureProductQuery  = "SELECT id, name, description, price, image FROM products WHERE is_banner = 'no' LIMIT 3";

$featureProductResult = $conn->query($featureProductQuery);
?>

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
    <style>
    .icon-box {
        display: flex;
    }

    .icon-box > div {
    padding: 14px;
    border: 1px solid #eee;
    border-radius: 6px;
    margin-top: 10px;
    margin-right: 14px;
    }
</style>
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
        <div class="row">
            <div class="col-md-4">
            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-4">
          <!-- Dynamic Product Image -->
        <div class="col-md-8">
          <h2><?php echo htmlspecialchars($product['name']); ?></h2>
          <p class="text-muted">Price: <strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
          <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                <!-- Add to Cart Button -->
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="1">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control"
                            style="width: 100px;">
                    </div>
                    <!-- <button type="submit" class="btn btn-success">Add to Cart</button> -->
                </form>

                <a href="product.php" class="btn btn-secondary mt-3">Back to Products</a>
                <div class="icon-box">
                    <div class="left">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="right">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="mt-5">
            <h3 class="mb-4">You may also like</h3>
            <div class="row">
            <?php while ($product = $featureProductResult->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $product['description']), 0, 15))) . (str_word_count($product['description']) > 15 ? '...' : ''); ?></p>
                        <p class="card-text"><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                            <a href="cart.php" class="btn btn-success">Add to Cart</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
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