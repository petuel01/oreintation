<?php
session_start();
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Universities</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .university-card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            background: #f8f8f8;
        }
        .university-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(93,64,55,0.08);
        }
    </style>
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

            $default_logo = "../assets/default-university.png"; // Adjust path as needed

            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    // Check if logo exists and is not empty
                    $logo_path = (!empty($row['logo']) && file_exists("../" . $row['logo'])) ? "../" . $row['logo'] : $default_logo;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card university-card">
                        <img src="<?= htmlspecialchars($logo_path); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['motto']); ?></p>
                            <p><strong>Type:</strong> <?= htmlspecialchars($row['type']); ?></p>
                            <div class="mb-2" id="rating-<?= $row['id'] ?>">
                                <span class="text-warning">Loading rating...</span>
                            </div>
                            <a href="university_details.php?id=<?= $row['id']; ?>" class="btn btn-primary">View Details</a>
                            <a href="<?= htmlspecialchars($row['website']); ?>" target="_blank" class="btn btn-outline-secondary">Visit Website</a>
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
    <script>
    // Load ratings for all universities
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        $result = $conn->query("SELECT id FROM universities");
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        fetch('get_university_rating.php?university_id=<?= $row['id'] ?>')
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
                html += `<span class=\"ms-2 text-dark\" style=\"font-size:1rem;\">${avg}/5 (${data.count} reviews)</span>`;
                document.getElementById('rating-<?= $row['id'] ?>').innerHTML = html;
            });
        <?php endwhile; endif; ?>
    });
    </script>
</body>
</html>