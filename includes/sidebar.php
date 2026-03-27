<?php
// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));

$is_admin = $_SESSION['role'] === 'admin';
?>
<nav class="sidebar">
    <div class="sidebar-header">
        <i class="bi bi-mortarboard-fill fs-2"></i>
        <h3>SIS</h3>
    </div>
    
    <ul class="sidebar-menu nav flex-column">
        <?php if ($is_admin): ?>
            <!-- Admin Menu -->
            <li class="nav-item <?php echo ($current_page == 'dashboard.php' && $current_dir == 'admin') ? 'active' : ''; ?>">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'index.php' && $current_dir == 'admin') ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-people"></i>
                    Students
                </a>
            </li>
        <?php else: ?>
            <!-- User Menu -->
            <li class="nav-item <?php echo ($current_page == 'dashboard.php' && $current_dir == 'user') ? 'active' : ''; ?>">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'view.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="view.php">
                    <i class="bi bi-person"></i>
                    My Profile
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
