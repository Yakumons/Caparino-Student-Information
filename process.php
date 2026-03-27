<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'login':
        handleLogin($conn);
        break;
    case 'signup':
        handleSignup($conn);
        break;
    case 'create_student':
        handleCreateStudent($conn);
        break;
    case 'update_student':
        handleUpdateStudent($conn);
        break;
    case 'delete_student':
        handleDeleteStudent($conn);
        break;
    default:
        header('Location: index.php');
        exit();
}

// Login Handler with SHA-256
function handleLogin($conn) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Please fill in all fields';
        header('Location: index.php');
        exit();
    }

    // Hash the password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Query with JOIN to get role name
    $stmt = $conn->prepare("
        SELECT u.id, u.username, u.email, u.password, r.name as role 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        WHERE u.username = ? AND u.password = ?
    ");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: user/dashboard.php');
        }
        exit();
    }

    $_SESSION['login_error'] = 'Invalid username or password';
    header('Location: index.php');
    exit();
}

// Signup Handler with SHA-256
function handleSignup($conn) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['signup_error'] = 'Please fill in all fields';
        header('Location: signup.php');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = 'Passwords do not match';
        header('Location: signup.php');
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['signup_error'] = 'Password must be at least 6 characters';
        header('Location: signup.php');
        exit();
    }

    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['signup_error'] = 'Username already exists';
        header('Location: signup.php');
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['signup_error'] = 'Email already exists';
        header('Location: signup.php');
        exit();
    }

    // Hash password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Get user role_id (default is 'user' which has id 2)
    $stmt = $conn->prepare("SELECT id FROM roles WHERE name = 'user'");
    $stmt->execute();
    $role_result = $stmt->get_result();
    $role_row = $role_result->fetch_assoc();
    $role_id = $role_row['id'];

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $hashed_password, $role_id);

    if ($stmt->execute()) {
        $_SESSION['signup_success'] = 'Account created successfully! Please login.';
        header('Location: index.php');
    } else {
        $_SESSION['signup_error'] = 'Registration failed. Please try again.';
        header('Location: signup.php');
    }
    exit();
}

// Create Student Handler
function handleCreateStudent($conn) {
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../index.php');
        exit();
    }

    $student_number = trim($_POST['student_number']);
    $email = trim($_POST['email']);
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validation
    if (empty($student_number) || empty($email) || empty($lastname) || empty($firstname)) {
        $_SESSION['form_error'] = 'Please fill in all required fields';
        header('Location: admin/create.php');
        exit();
    }

    // Check if student number exists
    $stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ?");
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['form_error'] = 'Student number already exists';
        header('Location: admin/create.php');
        exit();
    }

    // Check if email exists in students table
    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['form_error'] = 'Email already exists';
        header('Location: admin/create.php');
        exit();
    }

    // Insert student record directly into students table
    $stmt = $conn->prepare("INSERT INTO students (student_number, lastname, firstname, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_number, $lastname, $firstname, $email, $phone, $address);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Student added successfully!';
        header('Location: admin/index.php');
    } else {
        $_SESSION['form_error'] = 'Failed to add student. Please try again.';
        header('Location: admin/create.php');
    }
    exit();
}

// Update Student Handler
function handleUpdateStudent($conn) {
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../index.php');
        exit();
    }

    $id = intval($_POST['id']);
    $student_number = trim($_POST['student_number']);
    $email = trim($_POST['email']);
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validation
    if (empty($student_number) || empty($email) || empty($lastname) || empty($firstname)) {
        $_SESSION['form_error'] = 'Please fill in all required fields';
        header('Location: admin/update.php?id=' . $id);
        exit();
    }

    // Check if student number exists (excluding current student)
    $stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ? AND id != ?");
    $stmt->bind_param("si", $student_number, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['form_error'] = 'Student number already exists';
        header('Location: admin/update.php?id=' . $id);
        exit();
    }

    // Check if email exists for other students (excluding current student)
    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['form_error'] = 'Email already exists';
        header('Location: admin/update.php?id=' . $id);
        exit();
    }

    // Update student record directly
    $stmt = $conn->prepare("UPDATE students SET student_number = ?, lastname = ?, firstname = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $student_number, $lastname, $firstname, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Student updated successfully!';
        header('Location: admin/index.php');
    } else {
        $_SESSION['form_error'] = 'Failed to update student. Please try again.';
        header('Location: admin/update.php?id=' . $id);
    }
    exit();
}

// Delete Student Handler
function handleDeleteStudent($conn) {
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../index.php');
        exit();
    }

    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Student deleted successfully!';
    } else {
        $_SESSION['error_message'] = 'Failed to delete student.';
    }
    header('Location: admin/index.php');
    exit();
}
?>