-- =========================================================
-- Student Information System Database
-- Secure, normalized, and production-ready
-- =========================================================

-- 1. Create database
CREATE DATABASE IF NOT EXISTS student_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE student_db;

-- =========================================================
-- 2. Roles table (scalable replacement for ENUM)
-- =========================================================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE
);

-- Insert default roles
INSERT INTO roles (name) VALUES
('admin'),
('user');

-- =========================================================
-- 3. Users table (authentication)
-- =========================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- STORE HASHED PASSWORDS
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_roles
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
        ON DELETE RESTRICT
);

-- =========================================================
-- 4. Students table (student information)
-- =========================================================
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    student_number VARCHAR(20) NOT NULL UNIQUE,
    lastname VARCHAR(50) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_students_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);

-- Index for performance
CREATE INDEX idx_students_user_id ON students(user_id);

-- =========================================================
-- 5. Insert sample users
-- NOTE: Passwords are SHA-256 hashed for demo purposes.
-- Hash passwords in your application layer in real systems.
-- =========================================================

-- Default admin account
-- Plain password: admin123
INSERT INTO users (username, email, password, role_id)
VALUES (
    'admin',
    'admin@student.com',
    SHA2('admin123', 256),
    1
);

-- Sample normal user
-- Plain password: user123
INSERT INTO users (username, email, password, role_id)
VALUES (
    'johndoe',
    'john@student.com',
    SHA2('user123', 256),
    2
);

-- =========================================================
-- 6. Insert sample students
-- =========================================================
INSERT INTO students (user_id, student_number, lastname, firstname, phone, address)
VALUES
(2, 'STU-001', 'Doe', 'John', '09123456789', '123 Main Street, City'),
(NULL, 'STU-002', 'Smith', 'Jane', '09234567890', '456 Oak Avenue, Town'),
(NULL, 'STU-003', 'Johnson', 'Mike', '09345678901', '789 Pine Road, Village');