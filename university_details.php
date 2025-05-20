<!-- filepath: c:\xampp\htdocs\oreintation\university_details.php -->
<?php
include("config/db.php");

// Get the university ID from the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid university ID.");
}

$university_id = intval($_GET['id']);

// Fetch university details
$query = "SELECT * FROM universities WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $university_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("University not found.");
}

$university = $result->fetch_assoc();

// Fetch featured programs
$programs_query = "SELECT * FROM programs WHERE university_id = ?";
$stmt = $conn->prepare($programs_query);
$stmt->bind_param("i", $university_id);
$stmt->execute();
$programs_result = $stmt->get_result();

// Fetch scholarships
$scholarships_query = "SELECT * FROM scholarships WHERE university_id = ?";
$stmt = $conn->prepare($scholarships_query);
$stmt->bind_param("i", $university_id);
$stmt->execute();
$scholarships_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($university['name']) ?> - Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero {
            background: url('<?= htmlspecialchars($university['logo'] ?: "assets/default-university.png") ?>') no-repeat center center/cover;
            padding: 150px 0;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero-overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .hero h1 {
            position: relative;
            z-index: 1;
            font-size: 3rem;
            font-weight: bold;
        }
        .hero p {
            position: relative;
            z-index: 1;
            font-size: 1.2rem;
        }
        .details-section {
            padding: 50px 0;
        }
        .btn-primary {
            background-color: #5D4037; /* Dark brown */
            border: none;
        }
        .btn-primary:hover {
            background-color: #4E342E; /* Darker brown */
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include("sidebar.php"); ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <h1><?= htmlspecialchars($university['name']) ?></h1>
        <p><?= htmlspecialchars($university['motto']) ?></p>
    </div>
</section>

<!-- University Details Section -->
<section class="details-section">
    <div class="container">
        <h2>About <?= htmlspecialchars($university['name']) ?></h2>
        <p><?= nl2br(htmlspecialchars($university['description'])) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($university['location']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($university['contact']) ?></p>
        <p><strong>Established Year:</strong> <?= htmlspecialchars($university['established_year']) ?></p>
        <p><strong>Type:</strong> <?= htmlspecialchars($university['type']) ?></p>
        <p><strong>Accreditation Status:</strong> <?= htmlspecialchars($university['accreditation_status']) ?></p>
        <p><strong>Website:</strong> <a href="<?= htmlspecialchars($university['website']) ?>" target="_blank"><?= htmlspecialchars($university['website']) ?></a></p>
    </div>
</section>

<!-- Featured Programs Section -->
<section class="details-section">
    <div class="container">
        <h3>Featured Programs</h3>
        <?php if ($programs_result->num_rows > 0): ?>
            <ul>
                <?php while ($program = $programs_result->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($program['program_name']) ?></strong> - <?= htmlspecialchars($program['duration']) ?>
                        <p><?= htmlspecialchars($program['admission_requirements']) ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No programs available for this university.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Scholarships Section -->
<section class="details-section">
    <div class="container">
        <h3>Scholarships</h3>
        <?php if ($scholarships_result->num_rows > 0): ?>
            <ul>
                <?php while ($scholarship = $scholarships_result->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($scholarship['name']) ?></strong> - Deadline: <?= htmlspecialchars($scholarship['deadline']) ?>
                        <p><?= htmlspecialchars($scholarship['description']) ?></p>
                        <p><strong>Eligibility:</strong> <?= htmlspecialchars($scholarship['eligibility']) ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No scholarships available for this university.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <p>© 2025 UniGuide. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>