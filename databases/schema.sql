CREATE DATABASE IF NOT EXISTS watchent_db;
USE watchent_db;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table (Watches)
CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `color` varchar(50) NOT NULL,
  `average_rating` decimal(3,2) DEFAULT '0.00',
  `image` varchar(255) NOT NULL,
  `stock` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_banner` enum('yes','no') NOT NULL DEFAULT 'no',
  `is_new_arrival` enum('yes','no') NOT NULL DEFAULT 'no',
  `is_featured` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CREATE TABLE products (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     brand VARCHAR(100) NOT NULL,  -- Added brand filtering
--     description TEXT NOT NULL,
--     price DECIMAL(10,2) NOT NULL,
--     gender ENUM('Men', 'Women', 'Unisex') NOT NULL,  -- Added gender filtering
--     color VARCHAR(50) NOT NULL,  -- Added color filtering
--     average_rating DECIMAL(3,2) DEFAULT 0.0,  -- Average rating (updated dynamically)
--     image VARCHAR(255) NOT NULL,
--     stock INT NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'shipped', 'delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Cart Table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Ratings Table (Users rate watches after buying)
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),  -- Ratings from 1 to 5
    review TEXT DEFAULT NULL,  -- Optional review
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE (user_id, product_id)  -- Each user can rate a watch only once
);

-- Automatically update the product's average rating when a new rating is added
DELIMITER //
CREATE TRIGGER update_avg_rating AFTER INSERT ON ratings
FOR EACH ROW
BEGIN
    UPDATE products
    SET average_rating = (
        SELECT AVG(rating) FROM ratings WHERE product_id = NEW.product_id
    )
    WHERE id = NEW.product_id;
END;

