<!-- filepath: c:\xampp\htdocs\oreintation\index.php -->
<?php
include("config/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniGuide - Find Universities in Cameroon</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Hero Section */
        .hero {
            background: url('assets/hero-bg.jpg') no-repeat center center/cover;
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .hero h1 {
            font-weight: bold;
            font-size: 3rem;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .search-bar {
            margin-top: 20px;
        }
        .search-bar input,
        .search-bar select {
            border: 2px solid #5D4037; /* Dark brown */
            border-radius: 5px;
            padding: 10px;
        }
        .search-bar input:focus,
        .search-bar select:focus {
            outline: none;
            border-color: #4E342E; /* Darker brown */
            box-shadow: 0 0 5px rgba(93, 64, 55, 0.5);
        }
        .search-bar button {
            background-color: #5D4037; /* Dark brown */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .search-bar button:hover {
            background-color: #4E342E; /* Darker brown */
        }

        /* Featured Section */
        .featured {
            background: #fff;
            padding: 50px 0;
        }
        .card {
            border: none;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .btn-primary {
            background-color: #5D4037; /* Dark brown */
            border: none;
        }
        .btn-primary:hover {
            background-color: #4E342E; /* Darker brown */
        }

        /* Star Rating */
        .stars {
            color: #FFD700; /* Gold color for stars */
        }

        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include("sidebar.php"); ?>
<br>
<br>

<div class="content">
    <!-- Hero Section -->
    <?php include("hero.php"); ?>
<br>
    <!-- Featured Universities Section -->
    <section class="featured">
        <div class="container">
            <h2 class="text-center">Featured Universities</h2>
            <div class="row">
                               <?php
                $query = "SELECT 
                            u.id, 
                            u.name, 
                            u.logo, 
                            u.motto, 
                            u.location, 
                            IFNULL(AVG(r.rating_overall), 0) AS average_rating
                          FROM universities u
                          LEFT JOIN student_reviews r ON u.id = r.university_id
                          GROUP BY u.id
                          LIMIT 6"; // Limit to 6 universities for display
                $result = $conn->query($query);
                if (!$result) {
                    die("Error fetching universities: " . $conn->error);
                }
                while ($row = $result->fetch_assoc()): ?>
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
                                <p class="card-text"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['location']) ?></p>
                                <!-- Display Star Rating -->
                                <p class="card-text stars">
                                    <?php
                                    $rating = round($row['average_rating']); // Round the average rating
                                    if ($rating > 0): // If there are reviews
                                        for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star<?= $i <= $rating ? '' : '-o' ?>"></i>
                                        <?php endfor; ?>
                                        <span>(<?= number_format($row['average_rating'], 1) ?>)</span>
                                    <?php else: // No reviews yet ?>
                                        <span>No reviews yet</span>
                                    <?php endif; ?>
                                </p>
                                <a href="university_details.php?id=<?= $row['id'] ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</div>

<!-- Footer -->
<?php include("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>