<?php
session_start();
include("../config/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Career Paths & Related Programs - KamerGuide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .section-title {
            color: #5D4037;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }
        .career-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(93,64,55,0.08);
            margin-bottom: 30px;
            background: #f8f6f5;
        }
        .career-card .card-header {
            background: #5D4037;
            color: #fff;
            border-radius: 14px 14px 0 0;
        }
        .badge-brown {
            background: #5D4037;
            color: #fff;
        }
        .icon-circle {
            background: #5D4037;
            color: #fff;
            border-radius: 50%;
            padding: 10px;
            margin-right: 10px;
        }
        @media (max-width: 767px) {
            .career-card { padding: 10px; }
        }
    </style>
</head>
<body>
<?php include("sidebar.php"); ?>

<div class="container py-5">
    <h1 class="section-title text-center mb-5"><i class="fas fa-briefcase icon-circle"></i>Career Paths & Related Programs</h1>
    <div class="row">
        <?php
        // Fetch all careers and their related programs
        $careers_result = $conn->query("SELECT * FROM careers");
        if ($careers_result && $careers_result->num_rows > 0):
            while ($career = $careers_result->fetch_assoc()):
                // Fetch related programs for this career
                $related_programs = [];
                if (!empty($career['related_programs'])) {
                    $program_ids = array_map('intval', explode(',', $career['related_programs']));
                    if (count($program_ids)) {
                        $ids_str = implode(',', $program_ids);
                        $prog_query = "SELECT program_name, university_id FROM programs WHERE id IN ($ids_str)";
                        $prog_result = $conn->query($prog_query);
                        while ($prog = $prog_result->fetch_assoc()) {
                            // Fetch university name
                            $uni_name = '';
                            $uni_id = intval($prog['university_id']);
                            $uni_res = $conn->query("SELECT name FROM universities WHERE id=$uni_id");
                            if ($uni = $uni_res->fetch_assoc()) {
                                $uni_name = $uni['name'];
                            }
                            $related_programs[] = [
                                'program_name' => $prog['program_name'],
                                'university_name' => $uni_name
                            ];
                        }
                    }
                }
        ?>
        <div class="col-md-6">
            <div class="card career-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i><?= htmlspecialchars($career['title']) ?>
                        <span class="badge badge-brown ms-2"><?= htmlspecialchars($career['category']) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($career['description'])) ?></p>
                    <p><strong>Requirements:</strong> <?= htmlspecialchars($career['requirements']) ?></p>
                    <?php if (count($related_programs)): ?>
                        <div class="mt-3">
                            <strong>Related Programs:</strong>
                            <ul>
                                <?php foreach ($related_programs as $prog): ?>
                                    <li>
                                        <i class="fas fa-graduation-cap text-success"></i>
                                        <?= htmlspecialchars($prog['program_name']) ?>
                                        <span class="text-muted">at <?= htmlspecialchars($prog['university_name']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <span class="text-muted">No related programs listed yet.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
        ?>
        <div class="col-12 text-center">
            <div class="alert alert-warning py-5">
                <h4 class="mb-3"><i class="fas fa-exclamation-circle"></i> No Career Guides Available</h4>
                <p>Career guides and related programs will appear here once added by the admin.</p>
                <a href="explore.php" class="btn btn-lg" style="background:#5D4037; color:#fff;"><i class="fas fa-university me-2"></i>Explore Universities</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include("../footer.php"); ?>
</body>
</html>