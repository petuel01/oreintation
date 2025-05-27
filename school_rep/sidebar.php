<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
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
    overflow-y: auto;
    z-index: 2000;
    top: 0;
}

    .sidebar.active {
        left: 0;
    }

    .sidebar-header {
        padding: 1rem;
        background: rgb(60, 30, 10);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-header h4 {
        margin: 0;
    }

    .close-btn {
        display: block;
        font-size: 1.5rem;
        cursor: pointer;
        color: #fff;
    }

    .nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        display: block;
    }

    .nav-link {
        color: #fff;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: 0.3s;
        width: 100%;
        background-color: transparent;
        box-sizing: border-box;
    }

    .nav-link i {
        margin-right: 1rem;
        min-width: 20px;
        text-align: center;
    }

    .nav-link span {
        flex-grow: 1;
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

    .sidebar-toggle {
        background: transparent;
        color: #fff;
        border: none;
        padding: 0;
        font-size: 1.5rem;
        cursor: pointer;
        margin-right: 0.75rem;
    }

    /* Topbar (only on mobile) */
    .topbar {
        display: none;
        background-color: #4e280f;
        color: #fff;
        height: 56px;
        padding: 0 1rem;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .topbar .topbar-content {
        display: flex;
        align-items: center;
    }

    .topbar .topbar-title {
        font-size: 1.2rem;
        font-weight: bold;
    }

    /* Responsive styles */
    @media (max-width: 991px) {
        .sidebar {
        left: -260px;
    }

    .sidebar.active {
        left: 0;
    }

    .main-content {
        margin-left: 0;
        padding-top: 70px;
    }

    .topbar {
        display: flex;
    }
}
</style>

<!-- Topbar (responsive only) -->
<div class="topbar">
    <div class="topbar-content">
        <button class="sidebar-toggle" id="sidebarToggle">&#9776;</button>
        <span class="topbar-title">KamerGuide</span>
    </div>
</div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>KamerGuide</h4>
        <span class="close-btn" id="closeSidebar">&times;</span>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_faculties.php" class="nav-link <?= $current_page === 'manage_faculties.php' ? 'active' : ''; ?>">
                <i class="fas fa-building"></i> <span>Manage Faculties</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_programs.php" class="nav-link <?= $current_page === 'manage_programs.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> <span>Manage Programs</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_scholarships.php" class="nav-link <?= $current_page === 'manage_scholarships.php' ? 'active' : ''; ?>">
                <i class="fas fa-graduation-cap"></i> <span>Manage Scholarships</span>
            </a>
        </li>
        <li class="nav-item">
    <a href="manage_fees.php" class="nav-link <?= $current_page === 'manage_fees.php' ? 'active' : ''; ?>">
        <i class="fas fa-money-bill-wave"></i> <span>Manage Fees</span>
    </a>
        </li>
        <li class="nav-item">
            <a href="view_applications.php" class="nav-link <?= $current_page === 'view_applications.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> <span>View Applications</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="view_reviews.php" class="nav-link <?= $current_page === 'view_reviews.php' ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i> <span>View Reviews</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="settings.php" class="nav-link <?= $current_page === 'settings.php' ? 'active' : ''; ?>">
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

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('closeSidebar');

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.add('active');
    });

    closeBtn.addEventListener('click', function () {
        sidebar.classList.remove('active');
    });

    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 991) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>
