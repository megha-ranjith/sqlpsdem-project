-- Create Database
CREATE DATABASE IF NOT EXISTS sqlpsdem_test;
USE sqlpsdem_test;

-- Create Users Table (VULNERABLE - NO SANITIZATION)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    user_role VARCHAR(50),
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Users
INSERT INTO users (username, password, email, user_role) VALUES
('admin', 'admin123', 'admin@example.com', 'admin'),
('john', 'john123', 'john@example.com', 'user'),
('alice', 'alice123', 'alice@example.com', 'user'),
('bob', 'bob123', 'bob@example.com', 'user');

-- Create Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100),
    price DECIMAL(10,2),
    stock INT,
    description TEXT
);

-- Insert Sample Products
INSERT INTO products (product_name, price, stock, description) VALUES
('Laptop', 999.99, 5, 'High performance laptop'),
('Mouse', 29.99, 50, 'Wireless mouse'),
('Keyboard', 79.99, 30, 'Mechanical keyboard');

-- Create Comments Table (for second-order injection demo)
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    comment_text TEXT,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
