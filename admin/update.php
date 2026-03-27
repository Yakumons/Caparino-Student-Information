<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = intval($_GET['id']);

// Get student data directly from students table
$stmt = $conn->prepare("SELECT id, student_number, lastname, firstname, email, phone, address FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit();
}

$student = $result->fetch_assoc();

// Handle form errors
$form_error = isset($_SESSION['form_error']) ? $_SESSION['form_error'] : '';
unset($_SESSION['form_error']);

// Get form values if re-displaying (for error case)
$student_number = isset($_SESSION['old_student_number']) ? $_SESSION['old_student_number'] : $student['student_number'];
$lastname = isset($_SESSION['old_lastname']) ? $_SESSION['old_lastname'] : $student['lastname'];
$firstname = isset($_SESSION['old_firstname']) ? $_SESSION['old_firstname'] : $student['firstname'];
$email = isset($_SESSION['old_email']) ? $_SESSION['old_email'] : ($student['email'] ?? '');
$phone = isset($_SESSION['old_phone']) ? $_SESSION['old_phone'] : $student['phone'];
$address = isset($_SESSION['old_address']) ? $_SESSION['old_address'] : $student['address'];
unset($_SESSION['old_student_number'], $_SESSION['old_lastname'], $_SESSION['old_firstname'], $_SESSION['old_email'], $_SESSION['old_phone'], $_SESSION['old_address']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="dashboard-page">
    <?php include '../includes/header.php'; ?>
    
    <div class="main-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>
                    <i class="bi bi-pencil-square"></i>
                    Update Student
                </h1>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>

            <?php if ($form_error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($form_error); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="../process.php" method="POST">
                    <input type="hidden" name="action" value="update_student">
                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="student_number" class="form-label">Student Number *</label>
                            <input type="text" class="form-control" id="student_number" name="student_number" value="<?php echo htmlspecialchars($student_number); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lastname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="firstname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-check-lg me-2"></i>Update Student
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>