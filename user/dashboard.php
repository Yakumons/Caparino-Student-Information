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
    <title>User Dashboard - Student Information System</title>
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
                    <i class="bi bi-speedometer2"></i>
                    User Dashboard
                </h1>
                <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>

            <?php if ($student): ?>
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($student['firstname'], 0, 1) . substr($student['lastname'], 0, 1)); ?>
                        </div>
                        <div class="profile-title">
                            <h2><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></h2>
                            <span class="student-badge"><?php echo htmlspecialchars($student['student_number']); ?></span>
                        </div>
                    </div>
                    
                    <div class="profile-info">
                        <div class="info-item">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <label>Email</label>
                                <p><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>
                        </div>

                        <?php if ($student['phone']): ?>
                            <div class="info-item">
                                <i class="bi bi-telephone"></i>
                                <div>
                                    <label>Phone</label>
                                    <p><?php echo htmlspecialchars($student['phone']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($student['address']): ?>
                            <div class="info-item">
                                <i class="bi bi-geo-alt"></i>
                                <div>
                                    <label>Address</label>
                                    <p><?php echo htmlspecialchars($student['address']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <i class="bi bi-calendar"></i>
                            <div>
                                <label>Registered On</label>
                                <p><?php echo date('F j, Y', strtotime($student['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="view.php" class="btn btn-dark">
                            <i class="bi bi-eye me-2"></i>View Full Profile
                        </a>
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