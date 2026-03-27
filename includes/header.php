<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Get user initials for avatar
$initials = strtoupper(substr($_SESSION['username'], 0, 1));
$role_badge = $_SESSION['role'] === 'admin' ? 'Admin' : 'User';
?>
<nav class="navbar navbar-expand navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <span class="navbar-brand fw-semibold">Student Information System</span>
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                    <span class="avatar"><?php echo $initials; ?></span>
                    <div class="d-none d-md-block">
                        <div class="fw-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                        <small class="text-muted"><?php echo $role_badge; ?></small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>