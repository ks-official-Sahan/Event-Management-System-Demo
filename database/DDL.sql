-- Create the database
CREATE DATABASE IF NOT EXISTS event_management;

-- Use the database
USE event_management;

-- Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile_no VARCHAR(20),
    is_admin BOOLEAN DEFAULT FALSE
);

-- Create the 'admins' table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile_no VARCHAR(20),
    username VARCHAR(50) NOT NULL UNIQUE
);

-- Create the 'event_categories' table
CREATE TABLE IF NOT EXISTS event_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Create the 'event_types' table
CREATE TABLE IF NOT EXISTS event_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Insert initial event types
INSERT IGNORE INTO event_types (name) VALUES ('Birthday'), ('Wedding'), ('Anniversary'), ('Gathering'), ('Other');

-- Create the 'events' table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(255),
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    location VARCHAR(255),
    event_type INT,
    event_date DATE,
    event_time TIME,
    num_guests INT,
    addons VARCHAR(255),
    contact_name VARCHAR(100),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    special_requests TEXT,
    status VARCHAR(20) DEFAULT 'pending', 
    FOREIGN KEY (category_id) REFERENCES event_categories(id),
    FOREIGN KEY (event_type) REFERENCES event_types(id)
);

-- Create the 'bookings' table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

-- Create the 'user_profiles' table (if needed, adjust based on your requirements)
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    full_name VARCHAR(100),
    bio TEXT,
    profile_picture VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);