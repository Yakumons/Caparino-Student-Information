<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get statistics
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$recent_students = $conn->query("SELECT COUNT(*) as count FROM students WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Information System</title>
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
                    Admin Dashboard
                </h1>
                <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon-box bg-dark d-flex align-items-center justify-content-center rounded" style="width: 44px; height: 44px;">
                                <i class="bi bi-people text-white fs-5"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold"><?php echo $total_students; ?></h3>
                                <small class="text-muted">Total Students</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon-box bg-dark d-flex align-items-center justify-content-center rounded" style="width: 44px; height: 44px;">
                                <i class="bi bi-person-plus text-white fs-5"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold"><?php echo $total_users; ?></h3>
                                <small class="text-muted">Total Users</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon-box bg-dark d-flex align-items-center justify-content-center rounded" style="width: 44px; height: 44px;">
                                <i class="bi bi-calendar-plus text-white fs-5"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold"><?php echo $recent_students; ?></h3>
                                <small class="text-muted">New This Week</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="create.php" class="btn btn-dark">
                            <i class="bi bi-plus-lg me-2"></i>Add New Student
                        </a>
                        <a href="index.php" class="btn btn-outline-dark">
                            <i class="bi bi-people me-2"></i>View All Students
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>