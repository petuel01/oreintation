<!-- filepath: c:\xampp\htdocs\oreintation\explore.php -->
<?php

include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Universities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../student/sidebar.php'; ?>
    <div class="container mt-5">
        <h1>Explore Universities</h1>
        <p>Discover universities, their programs, and admission requirements.</p>
        <!-- Placeholder for university listings -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">University Name</h5>
                        <p class="card-text">Brief description of the university.</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <!-- Add more university cards here -->
        </div>
    </div>
</body>
</html><!-- filepath: c:\xampp\htdocs\oreintation\explore.php -->
<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Universities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container mt-5">
        <h1>Explore Universities</h1>
        <p>Discover universities, their programs, and admission requirements.</p>

        <div class="row">
            <?php
            $query = "SELECT id, name, logo, motto, type, website FROM universities";
            $result = $conn->query($query);

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($row['logo']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['motto']); ?></p>
                            <p><strong>Type:</strong> <?= htmlspecialchars($row['type']); ?></p>
                            <a href="<?= htmlspecialchars($row['website']); ?>" target="_blank" class="btn btn-primary">Visit Website</a>
                        </div>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <p>No universities found.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>