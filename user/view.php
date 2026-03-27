<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Get user's student info if exists
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Information System</title>
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
                    <i class="bi bi-person"></i>
                    My Profile
                </h1>
                <a href="dashboard.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <?php if ($student): ?>
                <div class="profile-view-card">
                    <div class="profile-view-header">
                        <div class="profile-view-avatar">
                            <?php echo strtoupper(substr($student['firstname'], 0, 1) . substr($student['lastname'], 0, 1)); ?>
                        </div>
                        <div class="profile-view-title">
                            <h2><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></h2>
                            <span class="student-badge"><?php echo htmlspecialchars($student['student_number']); ?></span>
                        </div>
                    </div>
                    
                    <div class="profile-view-details">
                        <div class="detail-section">
                            <h3>
                                <i class="bi bi-person"></i>
                                Personal Information
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Student Number</label>
                                    <p><?php echo htmlspecialchars($student['student_number']); ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Last Name</label>
                                    <p><?php echo htmlspecialchars($student['lastname']); ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>First Name</label>
                                    <p><?php echo htmlspecialchars($student['firstname']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="detail-section">
                            <h3>
                                <i class="bi bi-envelope"></i>
                                Contact Information
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Email</label>
                                    <p><?php echo htmlspecialchars($student['email']); ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Phone Number</label>
                                    <p><?php echo $student['phone'] ? htmlspecialchars($student['phone']) : 'Not provided'; ?></p>
                                </div>
                                <div class="detail-item full-width">
                                    <label>Address</label>
                                    <p><?php echo $student['address'] ? htmlspecialchars($student['address']) : 'Not provided'; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="detail-section">
                            <h3>
                                <i class="bi bi-calendar"></i>
                                Account Information
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Username</label>
                                    <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Account Type</label>
                                    <p><?php echo ucfirst($_SESSION['role']); ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Registered On</label>
                                    <p><?php echo date('F j, Y g:i A', strtotime($student['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-person" style="font-size: 3rem;"></i>
                    <h3>No Student Profile</h3>
                    <p>Your student profile has not been created yet. Please contact the administrator.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>