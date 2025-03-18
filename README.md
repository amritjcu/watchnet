# Watchnet - E-Commerce Website

## Overview

Watchnet is a modern and fully functional e-commerce platform designed for selling watches online. It features a user-friendly interface, an admin panel for managing products, users, and orders, and a secure authentication system.

## Features

### User Features:

Browse and search for watches

View detailed product descriptions and images

Add products to the shopping cart

Secure user authentication (login & signup)

Order history and account management

### Admin Features:

Manage Products: Add, edit, delete, and view products

Manage Users: View, edit, and delete users

Manage Orders: Process and update order status

Secure Authentication: Admin-only access to the dashboard

### Technologies Used

Frontend: HTML, CSS, JavaScript

Backend: PHP, MySQL

Database: MySQL

Server: Apache2 (Ubuntu)

### Installation

#### Prerequisites

Ensure you have the following installed on your system:

PHP

MySQL

Apache2

Git (for version control)

### Setup Steps

#### Clone the Repository:

git clone https://github.com/your-username/watchnet.git
cd watchnet

### Setup Database:

Import the provided database.sql file into MySQL

Update includes/config.php with your database credentials

### Run the Project:

Move the project to /var/www/html/ (if using Apache)

Start Apache and MySQL services

sudo service apache2 start
sudo service mysql start

Access the website at http://localhost/watchnet

### File Structure

##### watchnet/
##### │-- assets/               # CSS, JavaScript, images
##### │-- includes/             # Configuration and helper files
##### │-- uploads/              # Product images
##### │-- index.php             # Homepage
##### │-- admin_panel.php       # Admin Dashboard
##### │-- manage_products.php   # Product management page
##### │-- add_product.php       # Add new product
##### │-- edit_product.php      # Edit existing product
##### │-- view_product.php      # Product details
##### │-- manage_users.php      # User management
##### │-- login.php             # User authentication
##### │-- signup.php            # User registration
##### │-- logout.php            # Logout functionality
##### │-- README.md             # Project documentation

### Future Enhancements

Implement a payment gateway

Add user reviews and ratings for products

Improve UI/UX with modern design frameworks

Implement REST API for better scalability

# Contributors

Amrit Thapa

# License

Copyright @Amrit

