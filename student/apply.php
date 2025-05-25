<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Admission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .btn-brown {
            background-color: #5D4037;
            color: #fff;
        }
        .btn-brown:hover {
            background-color: #3E2723;
            color: #fff;
        }
        .apply-info-icon {
            background: #5D4037;
            color: #fff;
            border-radius: 50%;
            padding: 10px;
            margin-right: 10px;
        }
    </style>
    <script>
        // AJAX to load programs based on selected university
        document.addEventListener('DOMContentLoaded', function() {
            const uniSelect = document.getElementById('university');
            const progSelect = document.getElementById('program');
            if (uniSelect) {
                uniSelect.addEventListener('change', function() {
                    const uniId = this.value;
                    progSelect.innerHTML = '<option value="">Loading...</option>';
                    fetch('get_programs.php?university_id=' + uniId)
                        .then(res => res.json())
                        .then(data => {
                            progSelect.innerHTML = '<option value="" disabled selected>Select a program</option>';
                            data.forEach(function(prog) {
                                progSelect.innerHTML += `<option value="${prog.id}">${prog.program_name}</option>`;
                            });
                        });
                });
            }
        });
    </script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container my-5">
        <h1 class="mb-4" style="color:#5D4037;"><i class="fas fa-file-alt me-2"></i>Apply for Admission</h1>
        <div class="row">
            <div class="col-md-7 mb-4">
                <form method="POST" action="process_application.php">
                    <div class="mb-3">
                        <label for="university" class="form-label">University <span class="text-danger">*</span></label>
                        <select class="form-select" id="university" name="university_id" required>
                            <option value="" disabled selected>Select a university</option>
                            <?php
                            $query = "SELECT id, name FROM universities";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="program" class="form-label">Program <span class="text-danger">*</span></label>
                        <select class="form-select" id="program" name="program_id" required>
                            <option value="" disabled selected>Select a program</option>
                            <!-- Populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="motivation" class="form-label">Motivation/Personal Statement</label>
                        <textarea class="form-control" id="motivation" name="motivation" rows="5" placeholder="Tell us why you want to join this program (optional)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-brown"><i class="fas fa-paper-plane me-2"></i>Submit Application</button>
                </form>
            </div>
            <div class="col-md-5">
                <div class="mb-4">
                    <h5 style="color:#5D4037;"><i class="fas fa-info-circle apply-info-icon"></i>Application Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-university apply-info-icon"></i> Choose your preferred university and program.</li>
                        <li class="mb-2"><i class="fas fa-file-alt apply-info-icon"></i> Fill in your motivation to stand out.</li>
                        <li><i class="fas fa-clock apply-info-icon"></i> You will be notified by email about your application status.</li>
                    </ul>
                </div>
                <div>
                    <h5 style="color:#5D4037;"><i class="fas fa-question-circle apply-info-icon"></i>Need Help?</h5>
                    <a href="contact.php" class="btn btn-brown btn-sm mt-2"><i class="fas fa-envelope me-1"></i>Contact Support</a>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>