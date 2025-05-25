<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Sidebar */
    .sidebar {
        background-color: rgb(78, 40, 15);
        color: #fff;
        height: 100vh;
        position: fixed;
        transition: all 0.3s ease;
        width: 250px;
        overflow: hidden;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 1rem;
        background: rgb(60, 30, 10);
    }

    .sidebar-header h4 {
        margin: 0;
    }

    .nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link {
        color: #fff;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: 0.3s;
    }

    .nav-link i {
        margin-right: 1rem;
    }

    .nav-link.active {
        background-color: rgb(95, 64, 23);
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .logout .nav-link {
        color: #ff4d4d;
    }

    .main-content {
        margin-left: 250px;
        padding: 1rem;
        transition: margin-left 0.3s ease;
    }
</style>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>KamerGuide</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : ''; ?>" id="dashboard">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_faculties.php" class="nav-link <?= $current_page === 'manage_faculties.php' ? 'active' : ''; ?>" id="faculties">
                <i class="fas fa-building"></i> <span>Manage Faculties</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_programs.php" class="nav-link <?= $current_page === 'manage_programs.php' ? 'active' : ''; ?>" id="programs">
                <i class="fas fa-book"></i> <span>Manage Programs</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_scholarships.php" class="nav-link <?= $current_page === 'manage_scholarships.php' ? 'active' : ''; ?>" id="scholarships">
                <i class="fas fa-graduation-cap"></i> <span>Manage Scholarships</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="view_applications.php" class="nav-link <?= $current_page === 'view_applications.php' ? 'active' : ''; ?>" id="applications">
                <i class="fas fa-file-alt"></i> <span>View Applications</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="view_reviews.php" class="nav-link <?= $current_page === 'view_reviews.php' ? 'active' : ''; ?>" id="reviews">
                <i class="fas fa-comments"></i> <span>View Reviews</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="../settings.php" class="nav-link <?= $current_page === 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> <span>Settings</span>
            </a>
        </li>
        <li class="nav-item logout">
            <a href="../auth/logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>