<?php
// Start session to check user login status
session_start();

include "includes/config.php";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id  = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $role     = $_SESSION['role'];

    // Get the first name from the database for the logged-in user
    $query      = "SELECT * FROM users WHERE id = '$user_id'";
    $result     = $conn->query($query);
    $user       = $result->fetch_assoc();
    $first_name = explode(" ", $user['username'])[0]; // Get first name (assuming username is "First Last")
} else {
    $role       = 'user';
    $first_name = '';
}
// Fetch featured products where is_featured = 'yes'
$featureProductQuery  = "SELECT id, name, description, price, image FROM products ";
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
  </head>

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

    <div class="container py-5">
        <h2 class="text-center mb-4">All Products</h2>

        <!-- Filter and Sort Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="number" name="min_price" class="form-control" placeholder="Min Price">
                </div>
                <div class="col-md-3">
                    <input type="number" name="max_price" class="form-control" placeholder="Max Price">
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="name_asc">Name (A-Z)</option>
                        <option value="name_desc">Name (Z-A)</option>
                        <option value="price_asc">Price (Low to High)</option>
                        <option value="price_desc">Price (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </div>
        </form>

        <div class="row">
            <!-- Repeat this block for each product -->
            <?php while ($product = $featureProductResult->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text"><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        <form action="cart.html" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="1">
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>
                    </div>
                </div>
               
            </div>
            <?php endwhile; ?>
            <!-- End product block -->
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
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