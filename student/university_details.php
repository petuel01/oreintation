<?php
session_start();
include("../config/db.php");
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
// Fetch career paths for each program (assuming a careers table with related_programs as comma-separated program IDs)
$careers_by_program = [];
$careers_query = "SELECT * FROM careers";
$careers_result = $conn->query($careers_query);
while ($career = $careers_result->fetch_assoc()) {
    if (!empty($career['related_programs'])) {
        $related = explode(',', $career['related_programs']);
        foreach ($related as $pid) {
            $pid = trim($pid);
            if (!isset($careers_by_program[$pid])) $careers_by_program[$pid] = [];
            $careers_by_program[$pid][] = $career;
        }
    }
}
// Fetch average rating and count
$rating_row = null;
$rating_stmt = $conn->prepare("SELECT AVG(rating_overall) as avg_rating, COUNT(*) as count FROM student_reviews WHERE university_id = ?");
$rating_stmt->bind_param("i", $university_id);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
if ($rating_result && $rating_result->num_rows > 0) {
    $rating_row = $rating_result->fetch_assoc();
}
$rating_stmt->close();
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
            background: url('../<?= htmlspecialchars($university['logo'] ?: "assets/default-university.png") ?>') no-repeat center center/cover;
            padding: 150px 0;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero-overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }
        .hero h1, .hero p { position: relative; z-index: 1; }
        .details-section {
            padding: 50px 0 30px 0;
        }
        .section-title {
            color: #5D4037;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }
        .info-card {
            background: #f8f6f5;
            border-left: 6px solid #5D4037;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(93,64,55,0.08);
            padding: 24px;
            margin-bottom: 30px;
        }
        .program-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(93,64,55,0.08);
            margin-bottom: 24px;
        }
        .program-card .card-header {
            background: #5D4037;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }
        .badge-brown {
            background: #5D4037;
            color: #fff;
        }
        .scholarship-card {
            border: 1px solid #5D4037;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #fff7f0;
        }
        .scholarship-card .card-header {
            background: #5D4037;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .icon-circle {
            background: #5D4037;
            color: #fff;
            border-radius: 50%;
            padding: 10px;
            margin-right: 10px;
        }
        @media (max-width: 767px) {
            .hero { padding: 80px 0; }
            .info-card, .program-card, .scholarship-card { padding: 16px; }
        }
    </style>
</head>
<body>
<?php include("sidebar.php"); ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <h1><?= htmlspecialchars($university['name']) ?></h1>
        <p><?= htmlspecialchars($university['motto']) ?></p>
        <div class="mt-3">
            <span id="star-rating" style="font-size:1.5rem; color: #FFD700;">
                <?php
                $avg = $rating_row && $rating_row['avg_rating'] !== null ? round($rating_row['avg_rating'], 1) : 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= floor($avg)) {
                        echo '<i class="fas fa-star"></i>';
                    } elseif ($i - $avg < 1 && $avg - floor($avg) >= 0.5) {
                        echo '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
                ?>
                <span class="ms-2 text-white" style="font-size:1rem;">
                    <?= $avg ?>/5 (<?= $rating_row ? intval($rating_row['count']) : 0 ?> reviews)
                </span>
            </span>
        </div>
    </div>
</section>
<!-- Review Section -->
<section class="details-section" id="review-section">
    <div class="container">
        <h3 class="section-title"><i class="fas fa-star icon-circle"></i>Student Reviews</h3>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student'): ?>
        <form id="review-form" class="mb-4" method="post" action="submit_review.php">
            <input type="hidden" name="university_id" value="<?= $university_id ?>">
            <div class="mb-2">
                <label for="rating" class="form-label">Your Rating:</label>
                <span id="star-input">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="far fa-star" data-value="<?= $i ?>" style="font-size:1.5rem; cursor:pointer;"></i>
                    <?php endfor; ?>
                </span>
                <input type="hidden" name="rating" id="rating-value" value="0">
            </div>
            <div class="mb-2">
                <label for="comment" class="form-label">Comment (optional):</label>
                <textarea class="form-control" name="comment" id="comment" rows="2" maxlength="500"></textarea>
            </div>
            <button type="submit" class="btn btn-dark-brown">Submit Review</button>
            <div id="review-message" class="mt-2"></div>
        </form>
        <?php else: ?>
            <div class="alert alert-info">Login as a student to leave a review.</div>
        <?php endif; ?>
        <div id="reviews-list"></div>
    </div>
</section>

<!-- University Info Section -->
<section class="details-section">
    <div class="container">
        <div class="info-card" style="padding: 40px 32px;">
            <h2 class="section-title mb-4">
                <i class="fas fa-info-circle icon-circle"></i>
                About <?= htmlspecialchars($university['name']) ?>
            </h2>
            <div class="row align-items-center">
                <div class="col-md-8 mb-4 mb-md-0">
                    <p class="fs-5" style="line-height:1.8;"><?= nl2br(htmlspecialchars($university['description'])) ?></p>
                    <ul class="list-unstyled mb-0 fs-6">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            <strong>Location:</strong>
                            <span class="ms-1"><?= htmlspecialchars($university['location']) ?></span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-2 text-success"></i>
                            <strong>Contact:</strong>
                            <span class="ms-1"><?= htmlspecialchars($university['contact']) ?></span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            <strong>Established:</strong>
                            <span class="ms-1"><?= htmlspecialchars($university['established_year']) ?></span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-university me-2 text-warning"></i>
                            <strong>Type:</strong>
                            <span class="ms-1"><?= htmlspecialchars($university['type']) ?></span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-certificate me-2 text-info"></i>
                            <strong>Accreditation:</strong>
                            <span class="ms-1"><?= htmlspecialchars($university['accreditation_status']) ?></span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-globe me-2 text-secondary"></i>
                            <strong>Website:</strong>
                            <a href="<?= htmlspecialchars($university['website']) ?>" target="_blank" class="ms-1 text-decoration-underline"><?= htmlspecialchars($university['website']) ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-3 bg-white rounded shadow d-inline-block" style="min-width:180px;">
                        <img src="../<?= htmlspecialchars($university['logo'] ?: "assets/default-university.png") ?>" alt="Logo" class="img-fluid rounded" style="max-height: 140px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs & Career Paths Section -->
<section class="details-section" style="background: #f5ede7;">
    <div class="container">
        <h3 class="section-title"><i class="fas fa-graduation-cap icon-circle"></i>Featured Programs & Career Paths</h3>
        <div class="row">
            <?php if ($programs_result->num_rows > 0): ?>
                <?php while ($program = $programs_result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="card program-card">
                            <div class="card-header">
                                <h5 class="mb-0"><?= htmlspecialchars($program['program_name']) ?>
                                    <span class="badge badge-brown ms-2"><?= htmlspecialchars($program['degree_type'] ?? 'Program') ?></span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Duration:</strong> <?= htmlspecialchars($program['duration']) ?></p>
                                <p><strong>Admission Requirements:</strong> <?= htmlspecialchars($program['admission_requirements']) ?></p>
                                <?php
                                $pid = $program['id'];
                                if (!empty($careers_by_program[$pid])): ?>
                                    <div class="mt-3">
                                        <strong>Career Paths:</strong>
                                        <ul>
                                            <?php foreach ($careers_by_program[$pid] as $career): ?>
                                                <li>
                                                    <i class="fas fa-briefcase text-success"></i>
                                                    <strong><?= htmlspecialchars($career['title']) ?></strong>
                                                    <span class="badge bg-secondary ms-2"><?= htmlspecialchars($career['category']) ?></span>
                                                    <p class="mb-1"><?= htmlspecialchars(substr($career['description'],0,80)) ?>...</p>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">No career paths listed for this program yet.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No programs available for this university.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Scholarships Section -->
<section class="details-section">
    <div class="container">
        <h3 class="section-title"><i class="fas fa-award icon-circle"></i>Scholarships</h3>
        <div class="row">
            <?php if ($scholarships_result->num_rows > 0): ?>
                <?php while ($scholarship = $scholarships_result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="card scholarship-card">
                            <div class="card-header">
                                <strong><?= htmlspecialchars($scholarship['name']) ?></strong>
                                <span class="badge badge-brown ms-2">Deadline: <?= htmlspecialchars($scholarship['deadline']) ?></span>
                            </div>
                            <div class="card-body">
                                <p><?= htmlspecialchars($scholarship['description']) ?></p>
                                <p><strong>Eligibility:</strong> <?= htmlspecialchars($scholarship['eligibility']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No scholarships available for this university.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
// ...existing code...

// Fetch location info from location table
$location_info = null;
if (!empty($university['location_id'])) {
    $loc_stmt = $conn->prepare("SELECT * FROM location WHERE id = ?");
    $loc_stmt->bind_param("i", $university['location_id']);
    $loc_stmt->execute();
    $location_result = $loc_stmt->get_result();
    if ($location_result && $location_result->num_rows > 0) {
        $location_info = $location_result->fetch_assoc();
    }
    $loc_stmt->close();
}
?>

<!-- ...existing HTML... -->

<!-- Location Details Section -->
<?php if ($location_info): ?>
<section class="details-section">
    <div class="container">
        <div class="info-card" style="padding: 32px;">
            <h2 class="section-title mb-4">
                <i class="fas fa-map-marker-alt icon-circle"></i>
                Location Details
            </h2>
            <ul class="list-unstyled mb-0 fs-6">
                <li class="mb-3">
                    <i class="fas fa-flag me-2 text-danger"></i>
                    <strong>Country:</strong>
                    <span class="ms-1"><?= htmlspecialchars($location_info['country']) ?></span>
                </li>
                <li class="mb-3">
                    <i class="fas fa-location-arrow me-2 text-warning"></i>
                    <strong>Region:</strong>
                    <span class="ms-1"><?= htmlspecialchars($location_info['region']) ?></span>
                </li>
                <li class="mb-3">
                    <i class="fas fa-city me-2 text-primary"></i>
                    <strong>City:</strong>
                    <span class="ms-1"><?= htmlspecialchars($location_info['city']) ?></span>
                </li>
                <li class="mb-3">
                    <i class="fas fa-map me-2 text-success"></i>
                    <strong>Address:</strong>
                    <span class="ms-1"><?= htmlspecialchars($location_info['address']) ?></span>
                </li>
                <?php if (!empty($location_info['map_link'])): ?>
                <li class="mb-3">
                    <i class="fas fa-map-pin me-2 text-info"></i>
                    <strong>Map:</strong>
                    <a href="<?= htmlspecialchars($location_info['map_link']) ?>" target="_blank" class="ms-1 text-decoration-underline">View on Map</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ...rest of your file... -->

<?php include("../footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Star input for review form
const stars = document.querySelectorAll('#star-input i');
const ratingInput = document.getElementById('rating-value');
let selectedRating = 0;
stars.forEach(star => {
    star.addEventListener('mouseenter', function() {
        const val = parseInt(this.getAttribute('data-value'));
        highlightStars(val);
    });
    star.addEventListener('mouseleave', function() {
        highlightStars(selectedRating);
    });
    star.addEventListener('click', function() {
        selectedRating = parseInt(this.getAttribute('data-value'));
        ratingInput.value = selectedRating;
        highlightStars(selectedRating);
    });
});
function highlightStars(val) {
    stars.forEach((star, idx) => {
        if (idx < val) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
}
// Review form AJAX submit
const reviewForm = document.getElementById('review-form');
if (reviewForm) {
    reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(reviewForm);
        fetch('submit_review.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const msg = document.getElementById('review-message');
            if (data.success) {
                msg.innerHTML = '<span class="text-success">Review submitted!</span>';
                reviewForm.reset();
                selectedRating = 0;
                highlightStars(0);
                loadReviews();
            } else {
                msg.innerHTML = '<span class="text-danger">' + (data.message || 'Failed to submit review.') + '</span>';
            }
        });
    });
}
// Load reviews list
function loadReviews() {
    fetch('get_reviews.php?university_id=<?= $university_id ?>')
        .then(res => res.text())
        .then(html => {
            document.getElementById('reviews-list').innerHTML = html;
        });
    // Update average rating
    fetch('get_university_rating.php?university_id=<?= $university_id ?>')
        .then(res => res.json())
        .then(data => {
            let avg = data.avg ? parseFloat(data.avg) : 0;
            let html = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= Math.floor(avg)) {
                    html += '<i class="fas fa-star"></i>';
                } else if (i - avg < 1 && avg - Math.floor(avg) >= 0.5) {
                    html += '<i class="fas fa-star-half-alt"></i>';
                } else {
                    html += '<i class="far fa-star"></i>';
                }
            }
            html += `<span class=\"ms-2 text-white\" style=\"font-size:1rem;\">${avg}/5 (${data.count} reviews)</span>`;
            document.getElementById('star-rating').innerHTML = html;
        });
}
loadReviews();
</script>
</body>
</html>