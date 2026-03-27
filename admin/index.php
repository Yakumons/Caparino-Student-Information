<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Handle messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search) {
    $search_param = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT id, student_number, lastname, firstname, email, phone, address FROM students WHERE student_number LIKE ? OR lastname LIKE ? OR firstname LIKE ? OR email LIKE ? ORDER BY lastname ASC, firstname ASC");
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT id, student_number, lastname, firstname, email, phone, address FROM students ORDER BY lastname ASC, firstname ASC");
}

$students = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin Panel</title>
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
                    <i class="bi bi-people"></i>
                    Manage Students
                </h1>
                <a href="create.php" class="btn btn-dark">
                    <i class="bi bi-plus-lg me-2"></i>Add Student
                </a>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-3">
                <div class="card-body py-3">
                    <form method="GET" action="" class="row g-2 align-items-center">
                        <div class="col-auto flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Search students..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark btn-sm">Search</button>
                            <?php if ($search): ?>
                                <a href="index.php" class="btn btn-outline-secondary btn-sm">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if (count($students) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-uppercase small fw-semibold">No</th>
                                        <th class="text-uppercase small fw-semibold">Student Number</th>
                                        <th class="text-uppercase small fw-semibold">Last Name</th>
                                        <th class="text-uppercase small fw-semibold">First Name</th>
                                        <th class="text-uppercase small fw-semibold">Email</th>
                                        <th class="text-uppercase small fw-semibold">Phone</th>
                                        <th class="text-uppercase small fw-semibold">Address</th>
                                        <th class="text-uppercase small fw-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $index => $student): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><span class="badge bg-dark"><?php echo htmlspecialchars($student['student_number']); ?></span></td>
                                            <td><?php echo htmlspecialchars($student['lastname']); ?></td>
                                            <td><?php echo htmlspecialchars($student['firstname']); ?></td>
                                            <td><?php echo htmlspecialchars($student['email'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($student['phone'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars(substr($student['address'] ?? '-', 0, 30)); ?><?php echo strlen($student['address'] ?? '') > 30 ? '...' : ''; ?></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="update.php?id=<?php echo $student['id']; ?>" class="btn btn-outline-dark btn-sm" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-student-id="<?php echo $student['id']; ?>" data-student-name="<?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No students found</h5>
                            <p class="text-muted"><?php echo $search ? 'Try a different search term or ' : ''; ?><a href="create.php" class="text-dark fw-semibold">add a new student</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-0">Are you sure you want to delete <strong id="studentName"></strong>?</p>
                    <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="../process.php" class="d-inline" id="deleteForm">
                        <input type="hidden" name="action" value="delete_student">
                        <input type="hidden" name="id" id="deleteStudentId">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle delete modal
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const studentId = button.getAttribute('data-student-id');
            const studentName = button.getAttribute('data-student-name');
            
            document.getElementById('deleteStudentId').value = studentId;
            document.getElementById('studentName').textContent = studentName;
        });
    </script>
</body>
</html>
