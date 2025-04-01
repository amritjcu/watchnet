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

    // Fetch carousel images where is_banner = 'yes'
    $carouselQuery  = "SELECT * FROM products WHERE is_banner = 'yes' LIMIT 3";
    $carouselResult = $conn->query($carouselQuery);

    // Store the images in an array
    $carouselImages = [];
    while ($carousel = $carouselResult->fetch_assoc()) {
        $carouselImages[] = 'uploads/' . $carousel['image'];
    }

    // Fetch featured products where is_featured = 'yes'
    $featureProductQuery  = "SELECT id, name, description, price, image FROM products WHERE is_featured = 'yes' LIMIT 3";
    $featureProductResult = $conn->query($featureProductQuery);

    // Fetch new arrivals products where is_new_arrival = 'yes'
    $newProductQuery  = "SELECT id, name, description, price, image FROM products WHERE is_new_arrival = 'yes' LIMIT 2";
    $newProductResult = $conn->query($newProductQuery);

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

    <!-- Hero Section with Slider -->
    <section class="hero-section container mt-5">
    <div
        id="heroCarousel"
        class="carousel slide"
        data-ride="carousel"
        data-interval="3000"
    >
        <!-- Carousel with autoplay every 3 seconds -->
        <div class="carousel-inner">
            <?php
                // Check if there are any images in the array to display
                $isFirst = true;
                foreach ($carouselImages as $image):
            ?>
                <!-- Slide -->
                <div class="carousel-item                                          <?php echo $isFirst ? 'active' : ''; ?>">
                    <img
                        src="<?php echo $image; ?>"
                        class="d-block w-100 hero-banner"
                        alt="Hero Banner"
                    />
                </div>
                <?php
                    // After first slide, set isFirst to false
                    $isFirst = false;
                    endforeach;
                ?>
        </div>

        <!-- Carousel Controls (optional) -->
        <a
            class="carousel-control-prev"
            href="#heroCarousel"
            role="button"
            data-slide="prev"
        >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a
            class="carousel-control-next"
            href="#heroCarousel"
            role="button"
            data-slide="next"
        >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>

    <!-- Product Section -->
    <section class="products-section py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Our Featured Products</h2>
        <div class="row">
            <?php while ($product = $featureProductResult->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <div class="card-body">
                            <h5 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

    <!-- New Arrivals Section -->
    <section class="new-arrivals py-5">
    <div class="container">
        <h2 class="text-center mb-5">New Arrivals</h2>
        <div class="row">
            <?php while ($product = $newProductResult->fetch_assoc()): ?>
                <div class="col-md-6 mb-4">
                    <div class="card watch-card">
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="watch-image" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <div class="card-body text-center">
                            <h5 class="watch-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="watch-price">$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

    <!-- Why Shop With Us Section -->
    <section class="why-shop-section">
      <div class="container">
        <h2 class="why-shop-title">Why Shop With Us</h2>
        <div class="row text-center">
          <!-- Free Delivery -->
          <div class="col-md-4 mb-4">
            <div class="card feature-card">
              <i class="fas fa-truck feature-icon"></i>
              <h5 class="feature-title">Free Delivery</h5>
              <p class="feature-description">
                Enjoy free delivery on all orders with no minimum purchase
                required.
              </p>
            </div>
          </div>

          <!-- 1000+ Products -->
          <div class="col-md-4 mb-4">
            <div class="card feature-card">
              <i class="fas fa-cogs feature-icon"></i>
              <h5 class="feature-title">1000+ Products</h5>
              <p class="feature-description">
                Choose from over 1000 high-quality products across various
                categories.
              </p>
            </div>
          </div>

          <!-- Easy Return -->
          <div class="col-md-4 mb-4">
            <div class="card feature-card">
              <i class="fas fa-undo feature-icon"></i>
              <h5 class="feature-title">Easy Return</h5>
              <p class="feature-description">
                We offer a hassle-free return policy for all our products within
                30 days.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- What Our Customers Say -->
    <section class="why-shop-section">
      <div class="container bg-dark text-white">
        <h2 class="why-shop-title pt-5 pb-2">What Our Customers Say</h2>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="review-section">
              <div class="">"Amazing quality and fast delivery"</div>
              <div class="mt-3">"Jhon Doe"</div>
            </div>
          </div>

          <div class="col-md-4 mb-4">
            <div class="review-section">
              <div class="">"Love the design and build quality"</div>
              <div class="mt-3">"Sarah Smith"</div>
            </div>
          </div>

          <div class="col-md-4 mb-4">
            <div class="review-section">
              <div class="">"Best watch collection I've ever seen!"</div>
              <div class="mt-3">"Jhon Doe"</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-dark text-white py-4">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6">
            <h5>Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="./index.html" class="text-white">Home</a></li>
              <li>
                <a href="./productList.html" class="text-white">Products</a>
              </li>
              <li><a href="#" class="text-white">About Us</a></li>
              <li><a href="#" class="text-white">Login</a></li>
            </ul>
          </div>
          <div class="col-12 col-md-6">
            <h5>About Us</h5>
            <p>
              Your site description goes here. Add some text about your site.
            </p>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12 text-center">
            <p>&copy; 2025 Your Site Name. All rights reserved.</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Login Modal (Popup) -->
    <section>
      <div
        class="modal fade"
        id="loginModal"
        tabindex="-1"
        aria-labelledby="loginModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="loginModalLabel">Login</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <!-- Login Form -->
              <form>
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    placeholder="Enter your username"
                  />
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    placeholder="Enter your password"
                  />
                </div>
                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="rememberMe"
                  />
                  <label class="form-check-label" for="rememberMe"
                    >Remember me</label
                  >
                </div>
                <button type="submit" class="btn btn-primary w-100">
                  Login
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bootstrap and FontAwesome JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
