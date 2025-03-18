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
        $role       = 'guest';
        $first_name = '';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchent - Home</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Link to your external CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- For Carousel -->
    <style>
        /* Container for the header content */
.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}

/* Logo styles */
.logo a {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}

/* User-specific options styles */
.user-options {
    display: flex;
    align-items: center;
}

/* Admin Panel link */
.admin-panel {
    background-color: #4CAF50;
    padding: 10px 15px;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

/* User info styles */
.user-info {
    display: flex;
    align-items: center;
}

.user-info .user-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.user-info span {
    font-size: 16px;
    font-weight: normal;
}

/* Login/Signup links */
.login, .signup {
    padding: 10px 15px;
    background-color: #008CBA;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-left: 10px;
}

.login:hover, .signup:hover {
    background-color: #005f75;
}

        </style>
</head>
<body>

<!-- Header with Logo, Login, Signup, and Cart Icons -->
<header>
    <div class="logo">
        <img src="logo.png" alt="Watchent Logo" height="50">
    </div>
     <!-- User-specific options -->
     <div class="user-options">
                <?php if ($role == 'admin'): ?>
                    <!-- Admin Panel link for admin users -->
                    <a href="admin_panel.php" class="admin-panel">Admin Panel</a>
                <?php elseif ($role == 'user'): ?>
                    <!-- User's first name and "Logged in" message for normal users -->
                    <div class="user-info">
                        <img src="assets/images/user-icon.png" alt="User Icon" class="user-icon">
                        <span>Welcome,                                       <?php echo $first_name; ?> (Logged In)</span>
                    </div>
                    <!-- Logout link for users who are logged in -->
                    <a href="logout.php" class="logout">Logout</a>
                <?php else: ?>
                    <!-- Login and Signup links for guests -->
                    <a href="login.php" class="login">Login</a>
                    <a href="signup.php" class="signup">Sign Up</a>
                <?php endif; ?>
            </div>
    <div class="top-right">
        <a href="cart.php"><img src="cart-icon.png" alt="Cart" height="30"></a>
    </div>
</header>

<!-- Navbar with Home, About Us, Products, etc., and Search Bar -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="new-arrivals.php">New Arrivals</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <li>
            <input type="text" placeholder="Search...">
        </li>
    </ul>
</nav>

<!-- Ads Banner (Image Carousel) -->
<section class="ads-banner">
    <div class="carousel">
        <div class="carousel-images">
            <?php
                $query  = "SELECT * FROM ads LIMIT 5"; // Retrieve ads from database
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<img src='uploads/ads/" . $row['image'] . "' alt='Ad'>";
                }
            ?>
        </div>
    </div>
</section>

<!-- Product Listings Section -->
<section class="products">
    <h2>Featured Products</h2>
    <div class="product-list">
        <?php
            $query  = "SELECT * FROM products LIMIT 3"; // Get 3 featured products
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<img src='uploads/" . $row['image'] . "' alt='" . $row['name'] . "'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>$" . $row['price'] . "</p>";
                echo "<p>Rating: " . number_format($row['average_rating'], 1) . " ⭐</p>";
                echo "<a href='product.php?id=" . $row['id'] . "'>View Details</a>";
                echo "</div>";
            }
        ?>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="new-arrivals">
    <h2>New Arrivals</h2>
    <div class="new-arrivals-list">
        <?php
            $query  = "SELECT * FROM products ORDER BY created_at DESC LIMIT 2"; // Get 2 new arrivals
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<img src='uploads/" . $row['image'] . "' alt='" . $row['name'] . "'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>$" . $row['price'] . "</p>";
                echo "<p>Rating: " . number_format($row['average_rating'], 1) . " ⭐</p>";
                echo "<a href='product.php?id=" . $row['id'] . "'>View Details</a>";
                echo "</div>";
            }
        ?>
    </div>
</section>

<!-- Footer Section with Google Map, Quick Navigation, and Copyright -->
<footer>
    <div class="footer-map">
        <h3>Find Us</h3>
        <iframe src="https://www.google.com/maps/embed?pb=..." width="300" height="200" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    <div class="footer-nav">
        <ul>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="products.php">Products</a></li>
        </ul>
    </div>
    <div class="footer-address">
        <p>123 Watchent Street, City, Country</p>
    </div>
    <div class="footer-copyright">
        <p>&copy; 2025 Watchent. All rights reserved.</p>
    </div>
</footer>

<script>
    $(document).ready(function() {
        // Auto-scrolling carousel (ads banner)
        let currentIndex = 0;
        let images = $(".carousel-images img");
        let totalImages = images.length;

        setInterval(function() {
            // Move to the next image
            currentIndex = (currentIndex + 1) % totalImages;

            // Slide the carousel
            $(".carousel").css("transform", "translateX(-" + (currentIndex * 100) + "%)");
        }, 3000); // Change image every 3 seconds
    });
</script>

</body>
</html>
