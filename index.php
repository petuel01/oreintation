<?php
session_start();
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
        /* ...existing styles... */
        .hero {
            background: url('assets/hero-bg.jpg') no-repeat center center/cover;
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .hero h1 { font-weight: bold; font-size: 3rem; }
        .hero p { font-size: 1.2rem; }
        .search-bar { margin-top: 20px; }
        .search-bar input,
        .search-bar select {
            border: 2px solid #5D4037;
            border-radius: 5px;
            padding: 10px;
        }
        .search-bar input:focus,
        .search-bar select:focus {
            outline: none;
            border-color: #4E342E;
            box-shadow: 0 0 5px rgba(93, 64, 55, 0.5);
        }
        .search-bar button {
            background-color: #5D4037;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .search-bar button:hover { background-color: #4E342E; }
        .featured { background: #fff; padding: 50px 0; }
        .card { border: none; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); transition: 0.3s; }
        .card:hover { transform: scale(1.05); }
        .card-img-top { width: 100%; height: 200px; object-fit: cover; }
        .btn-primary { background-color: #5D4037; border: none; }
        .btn-primary:hover { background-color: #4E342E; }
        .stars { color: #FFD700; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; }
        .navbar { background-color: #5D4037; color: white; width: 100%; position: fixed; top: 0; left: 0; z-index: 1000; }
        .navbar a { color: white; text-decoration: none; }
        .navbar a:hover { color: #D7CCC8; }
        .mobile-menu { position: fixed; top: 0; left: -250px; width: 250px; height: 100%; background-color: #5D4037; color: white; z-index: 1000; transition: all 0.3s ease; overflow-y: auto; padding-top: 20px; }
        .mobile-menu.active { left: 0; }
        .mobile-menu .close-btn { position: absolute; top: 10px; right: 15px; font-size: 20px; cursor: pointer; color: white; }
        .mobile-menu ul { list-style: none; padding: 0; }
        .mobile-menu ul li { padding: 15px 20px; }
        .mobile-menu ul li a { color: white; text-decoration: none; display: block; }
        .mobile-menu ul li a:hover { background-color: #D7CCC8; border-radius: 4px; }
        .menu-btn { display: none; background-color: #5D4037; color: white; border: none; padding: 10px 15px; cursor: pointer; z-index: 1100; border-radius: 4px; font-size: 1.5rem; position: fixed; top: 3.5px; left: 3.5px; }
        .menu-btn:hover { background-color: #4E342E; }
        @media (max-width: 768px) {
            .menu-btn { display: block; }
            .navbar .navbar-collapse { display: none; }
            .navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; }
            .navbar-brand { font-size: 1.8rem; font-weight: 2rem; color: white; margin-left: 25px; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">KamerGuide</a>
        <button class="menu-btn" id="openMenu">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="student/explore.php">Explore Universities</a></li>
                <li class="nav-item"><a class="nav-link" href="student/carreer.php">Carrers</a></li>
                <li class="nav-item"><a class="nav-link" href="student/about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="student/contact.php">Contact Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="student/apply.php">Apply for Admission</a></li>
                    <li class="nav-item"><a class="nav-link" href="student/scholarships.php">Scholarships</a></li>
                    <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link btn btn-light text-dark" href="auth/login.php">Login/Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <span class="close-btn" id="closeMenu">&times;</span>
    <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="student/explore.php">Explore Universities</a></li>
        <li class="nav-item"><a class="nav-link" href="student/carreer.php">Carrers</a></li>
        <li><a href="student/about.php">About Us</a></li>
        <li><a href="student/contact.php">Contact Us</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="apply.php">Apply for Admission</a></li>
            <li><a href="scholarships.php">Scholarships</a></li>
            <li><a href="auth/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login/Register</a></li>
        <?php endif; ?>
    </ul>
</div>

<script>
    // JavaScript for mobile menu
    const mobileMenu = document.getElementById('mobileMenu');
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    openMenu.addEventListener('click', () => { mobileMenu.classList.add('active'); });
    closeMenu.addEventListener('click', () => { mobileMenu.classList.remove('active'); });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) { mobileMenu.classList.remove('active'); }
    });
</script>

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
                // Updated query to join location table and get city/region
                $query = "SELECT 
                            u.id, 
                            u.name, 
                            u.logo, 
                            u.motto, 
                            l.city, 
                            l.region, 
                            IFNULL(AVG(r.rating_overall), 0) AS average_rating
                          FROM universities u
                          LEFT JOIN locations l ON u.location_id = l.id
                          LEFT JOIN student_reviews r ON u.id = r.university_id
                          GROUP BY u.id
                          LIMIT 10";
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
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($row['city']) ?>
                                    <?= (!empty($row['city']) && !empty($row['region'])) ? ', ' : '' ?>
                                    <?= htmlspecialchars($row['region']) ?>
                                </p>
                                <!-- Display Star Rating -->
                                <p class="card-text stars">
                                    <?php
                                    $rating = round($row['average_rating']);
                                    if ($rating > 0):
                                        for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star<?= $i <= $rating ? '' : '-o' ?>"></i>
                                        <?php endfor; ?>
                                        <span>(<?= number_format($row['average_rating'], 1) ?>)</span>
                                    <?php else: ?>
                                        <span>No reviews yet</span>
                                    <?php endif; ?>
                                </p>
                                <a href="student/university_details.php?id=<?= $row['id'] ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    
    <section class="container my-5">
        <h2 class="text-center mb-4">Popular Programs</h2>
        <div class="row">
            <?php
            $prog = $conn->query("SELECT program_name, COUNT(*) as count FROM programs GROUP BY program_name ORDER BY count DESC LIMIT 6");
            while ($p = $prog->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($p['program_name']) ?></h5>
                            <a href="explore.php?program=<?= urlencode($p['program_name']) ?>" class="btn btn-outline-primary btn-sm">View Universities</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <section class="container my-5">
        <h2 class="text-center mb-4">Scholarship Opportunities</h2>
        <div class="row">
            <?php
            $sch = $conn->query("SELECT scholarship_name, description FROM scholarships LIMIT 3");
            while ($s = $sch->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($s['scholarship_name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($s['description'],0,80)) ?>...</p>
                            <a href="scholarships.php" class="btn btn-outline-success btn-sm">Learn More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <section class="container my-5">
        <h2 class="text-center mb-4">Explore Career Guides</h2>
        <div class="row">
            <?php
            $careers = $conn->query("SELECT title, category, description FROM careers LIMIT 3");
            while ($c = $careers->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($c['title']) ?> <span class="badge bg-secondary"><?= htmlspecialchars($c['category']) ?></span></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($c['description'],0,80)) ?>...</p>
                            <a href="careers.php" class="btn btn-outline-info btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student'): ?>
    <section class="container my-5">
        <h2 class="text-center mb-4">Your Recent Applications</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead><tr><th>University</th><th>Program</th><th>Status</th></tr></thead>
                <tbody>
                <?php
                $uid = $_SESSION['user_id'];
                $apps = $conn->query("SELECT a.status, u.name as university, p.program_name FROM applications a
                                      JOIN universities u ON a.university_id = u.id
                                      JOIN programs p ON a.program_id = p.id
                                      WHERE a.user_id = $uid ORDER BY a.id DESC LIMIT 5");
                while ($a = $apps->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($a['university']) ?></td>
                        <td><?= htmlspecialchars($a['program_name']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($a['status'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php include("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>