<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'school_rep' || $_SESSION['status'] !== 'approved') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db.php';

$university_id = $_SESSION['university_id'];

// Fetch all programs for this university (for dropdown and table)
$programs = $conn->query("SELECT id, name FROM programs WHERE university_id = $university_id");

// Handle add/edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'];
    $local_tuition = $_POST['local_tuition'];
    $international_tuition = $_POST['international_tuition'];
    $application_fee = $_POST['application_fee'];
    $other_fees = $_POST['other_fees'];

    // Check if fees already exist for this program
    $exists = $conn->query("SELECT id FROM fees WHERE program_id = $program_id")->fetch_assoc();
    if ($exists) {
        // Update
        $stmt = $conn->prepare("UPDATE fees SET local_tuition=?, international_tuition=?, application_fee=?, other_fees=?, updated_at=NOW() WHERE program_id=?");
        $stmt->bind_param("ddddi", $local_tuition, $international_tuition, $application_fee, $other_fees, $program_id);
        $stmt->execute();
        $message = "Fees updated!";
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO fees (program_id, local_tuition, international_tuition, application_fee, other_fees, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("idddd", $program_id, $local_tuition, $international_tuition, $application_fee, $other_fees);
        $stmt->execute();
        $message = "Fees added!";
    }
}

// Fetch all programs and their fees (LEFT JOIN to show all programs)
$program_fees = $conn->query(
    "SELECT p.id as program_id, p.name as program_name, f.id as fee_id, f.local_tuition, f.international_tuition, f.application_fee, f.other_fees, f.updated_at
     FROM programs p
     LEFT JOIN fees f ON p.id = f.program_id
     WHERE p.university_id = $university_id
     ORDER BY p.name"
);

// For edit: fetch fee details if editing
$edit_fee = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_result = $conn->query(
        "SELECT * FROM fees WHERE id = $edit_id LIMIT 1"
    );
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_fee = $edit_result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Program Fees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        body { background: #f7f3f0; }
        .main-content { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(93,64,55,0.10); margin: 40px auto; max-width: 900px; }
        .form-title { color: #5D4037; font-weight: bold; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
        .form-title i { color: #5D4037; font-size: 2rem; }
        .form-label { color: #5D4037; font-weight: 500; }
        .btn-primary { background: #5D4037; border: none; }
        .btn-primary:hover { background: #4E342E; }
        .table thead { background: #5D4037; color: #fff; }
        .table td, .table th { vertical-align: middle; }
        .edit-btn { color: #5D4037; }
        .edit-btn:hover { color: #3e2723; }
        @media (max-width: 991px) {
            .main-content { margin: 80px 8px 24px 8px; }
            .table-responsive { font-size: 0.95rem; }
        }
    </style>
</head>
<body>
<div class="d-flex flex-column flex-md-row">
    <nav class="sidebar">
        <?php include("sidebar.php"); ?>
    </nav>
    <div class="main-content flex-grow-1 p-4 mt-4">
        <div class="form-title">
            <i class="fas fa-money-bill-wave"></i>
            Manage Program Fees
        </div>
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" action="" class="mb-4">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-book"></i> Program</label>
                <select name="program_id" class="form-control" required <?= $edit_fee ? 'readonly disabled' : '' ?>>
                    <option value="">Select Program</option>
                    <?php
                    // Reset pointer if editing
                    $programs->data_seek(0);
                    while ($row = $programs->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"
                            <?php
                            if ($edit_fee && $edit_fee['program_id'] == $row['id']) echo 'selected';
                            ?>>
                            <?= htmlspecialchars($row['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if ($edit_fee): ?>
                    <input type="hidden" name="program_id" value="<?= $edit_fee['program_id'] ?>">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user-graduate"></i> Local Tuition</label>
                <input type="number" step="0.01" name="local_tuition" class="form-control" required
                       value="<?= $edit_fee ? htmlspecialchars($edit_fee['local_tuition']) : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-globe"></i> International Tuition</label>
                <input type="number" step="0.01" name="international_tuition" class="form-control" required
                       value="<?= $edit_fee ? htmlspecialchars($edit_fee['international_tuition']) : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-file-invoice-dollar"></i> Application Fee</label>
                <input type="number" step="0.01" name="application_fee" class="form-control" required
                       value="<?= $edit_fee ? htmlspecialchars($edit_fee['application_fee']) : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-coins"></i> Other Fees (Registration, etc.)</label>
                <input type="number" step="0.01" name="other_fees" class="form-control"
                       value="<?= $edit_fee ? htmlspecialchars($edit_fee['other_fees']) : '' ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-2"></i><?= $edit_fee ? 'Update Fees' : 'Save Fees' ?>
            </button>
            <?php if ($edit_fee): ?>
                <a href="manage_fees.php" class="btn btn-secondary w-100 mt-2">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Local Tuition</th>
                        <th>International Tuition</th>
                        <th>Application Fee</th>
                        <th>Other Fees</th>
                        <th>Last Updated</th>
                        <th>Edit/Add</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($program_fees && $program_fees->num_rows > 0): ?>
                    <?php while ($fee = $program_fees->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fee['program_name']) ?></td>
                            <td><?= $fee['local_tuition'] !== null ? number_format($fee['local_tuition'], 2) : '<span class="text-muted">-</span>' ?></td>
                            <td><?= $fee['international_tuition'] !== null ? number_format($fee['international_tuition'], 2) : '<span class="text-muted">-</span>' ?></td>
                            <td><?= $fee['application_fee'] !== null ? number_format($fee['application_fee'], 2) : '<span class="text-muted">-</span>' ?></td>
                            <td><?= $fee['other_fees'] !== null ? number_format($fee['other_fees'], 2) : '<span class="text-muted">-</span>' ?></td>
                            <td><?= $fee['updated_at'] ? htmlspecialchars($fee['updated_at']) : '<span class="text-muted">-</span>' ?></td>
                            <td>
                                <?php if ($fee['fee_id']): ?>
                                    <a href="manage_fees.php?edit=<?= $fee['fee_id'] ?>" class="edit-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="manage_fees.php?edit=add&program_id=<?= $fee['program_id'] ?>" class="btn btn-sm btn-success" title="Add Fees">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No programs found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>