<?php
// filepath: c:\xampp\htdocs\oreintation\index.php
include("config/db.php");

// Fetch universities from the database
$query = "SELECT name, logo, motto, website FROM universities LIMIT 6"; // Limit to 6 universities for display
$result = $conn->query($query);
if (!$result) {
    die("Error fetching universities: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniGuide - Find Your Perfect University</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdf8f4;
        }
        .navbar {
            background: #fff;
            padding: 15px;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-primary {
            background: #ff7f50;
            border: none;
        }
        .btn-outline-primary {
            border: 2px solid #ff7f50;
            color: #ff7f50;
        }
        .hero {
            padding: 100px 0;
            background: #fdf8f4;
        }
        .hero h1 {
            font-weight: bold;
            font-size: 2.5rem;
        }
        .hero img {
            max-width: 100%;
        }
        .featured {
            background: #fff;
            padding: 50px 0;
        }
        .card {
            border: none;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-img-top {
            width: 100%;
            height: 200px; /* Fixed height for all images */
            object-fit: cover; /* Ensures the image fits within the box without distortion */
        }
        .testimonial {
            padding: 50px;
            background: #ffedd5;
        }
        .footer {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">UniGuide</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Universities</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Careers</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-3" href="#">Get Started</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero text-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Find the Perfect University for Your Future!</h1>
                <p>Explore institutions, get career guidance, and make the best decision for your future.</p>
                <a href="#" class="btn btn-primary">Start Exploring</a>
                <a href="#" class="btn btn-outline-primary">Get Career Advice</a>
            </div>
            <div class="col-md-6">
                <img src="assets/a.jpeg" alt="Hero Image">
            </div>
        </div>
    </div>
</section>

<!-- Featured Universities -->
<section class="featured">
    <div class="container">
        <h2 class="text-center">Featured Universities</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                        <?php if (!empty($row['logo'])): ?>
                            <img src="<?= htmlspecialchars($row['logo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                        <?php else: ?>
                            <img src="assets/default-university.png" class="card-img-top" alt="Default University">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['motto']) ?></p>
                            <?php if (!empty($row['website'])): ?>
                                <a href="<?= htmlspecialchars($row['website']) ?>" target="_blank" class="btn btn-primary">Visit Website</a>
                            <?php else: ?>
                                <span class="btn btn-secondary disabled">No Website</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonial text-center">
    <div class="container">
        <h2>What Students Say</h2>
        <p>"UniGuide helped me find my dream university and career path!" - Jane Doe</p>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <p>© 2025 UniGuide. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>