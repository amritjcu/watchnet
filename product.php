<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
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
$featureProductQuery = "SELECT id, name, description, price, image FROM products WHERE is_banner = 'no'";


$featureProductResult = $conn->query($featureProductQuery);



// Initialize query variables
$min_price = isset($_GET['min_price']) && !empty($_GET['min_price']) ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && !empty($_GET['max_price']) ? $_GET['max_price'] : PHP_INT_MAX;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc'; // Default sort by name ascending
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page or default to 1
$limit = 6; // Number of products per page
$offset = ($page - 1) * $limit; // Calculate offset for pagination

// Build the base query
$query = "SELECT * FROM products WHERE 1=1";

// Add price filter conditions
$params = [];
$type = ''; // This will hold the parameter types for bind_param


if ($min_price !== null) {
    $min_price = filter_var($min_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $query .= " AND price >= ?";
    $params[] = $min_price;
    $type .= 'd'; // 'd' for double (float)
}

if ($max_price !== null) {
    $max_price = filter_var($max_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $query .= " AND price <= ?";
    $params[] = $max_price;
    $type .= 'd'; // 'd' for double (float)
}

// Add sorting condition
switch ($sort) {
    case 'name_asc':
        $query .= " ORDER BY name ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY name DESC";
        break;
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    default:
        $query .= " ORDER BY name ASC"; // Default to sorting by name ascending
}

// Add LIMIT and OFFSET directly to the query
$query .= " LIMIT $limit OFFSET $offset";

// // Debugging output the query to check
// echo "Query: " . $query . "<br>";


// Prepare the statement
$stmt = $conn->prepare($query);

// If there are parameters to bind, bind them
if (!empty($params)) {
    $stmt->bind_param($type, ...$params); // Use variable-length argument list to bind parameters
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Check if products are found
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
    echo "No products found matching the filters.<br>";  // Debugging message
}

// Fetch total products for pagination
$totalQuery = "SELECT COUNT(*) as total FROM products WHERE 1=1";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$total_products = $totalRow['total'];
$total_pages = ceil($total_products / $limit);
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
        <h2 class="text-center mb-4">All Products</h2>

        <!-- Filter and Sort Form -->
        <form method="GET" class="mb-4">
      <div class="row">
      <div class="col-md-3">
    <input type="number" name="min_price" class="form-control" placeholder="Min Price" 
           value="<?php echo isset($min_price) && $min_price > 0 ? htmlspecialchars($min_price) : ''; ?>">
</div>
<div class="col-md-3">
    <input type="number" name="max_price" class="form-control" placeholder="Max Price" 
           value="<?php echo isset($max_price) && $max_price < PHP_INT_MAX ? htmlspecialchars($max_price) : ''; ?>">
</div>
        <div class="col-md-3">
          <select name="sort" class="form-select">
            <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
            <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
            <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price (Low to High)</option>
            <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price (High to Low)</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100">Apply</button>
        </div>
      </div>
    </form>

        <div class="row">
            <!-- Repeat this block for each product -->
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $product['description']), 0, 15))) . (str_word_count($product['description']) > 15 ? '...' : ''); ?></p>
                        <p class="card-text"><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        <form action="cart.php" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="1">
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>
                    </div>
                </div>
               
            </div>
            <?php endforeach; ?>
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