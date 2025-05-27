<?php
// Start the session if not already started
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

    /* Sidebar (desktop) */
    .sidebar {
        background-color: rgb(78, 40, 15);
        color: #fff;
        height: 100vh;
        position: fixed;
        transition: all 0.3s ease;
        width: 250px;
        overflow-y: auto;
        z-index: 1000;
        left: 0;
        top: 0;
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
        display: none;
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
        .close-btn {
            display: block;
        }
    }
    @media (min-width: 992px) {
        .topbar {
            display: none !important;
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
            <a href="dashboard.php" class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : ''; ?>" id="dashboard">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="institutions.php" class="nav-link <?= $current_page === 'institutions.php' ? 'active' : ''; ?>" id="institutions">
                <i class="fas fa-university"></i> <span>Institutions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="careers.php" class="nav-link <?= $current_page === 'careers.php' ? 'active' : ''; ?>" id="careers">
                <i class="fas fa-book"></i> <span>Career Guides</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="approve_school_reps.php" class="nav-link <?= $current_page === 'approve_school_reps.php' ? 'active' : ''; ?>" id="approve-admins">
                <i class="fas fa-user-check"></i> <span>Approve Admins</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="users.php" class="nav-link <?= $current_page === 'users.php' ? 'active' : ''; ?>" id="users">
                <i class="fas fa-users"></i> <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="settings.php" class="nav-link <?= $current_page === 'settings.php' ? 'active' : ''; ?>" id="settings">
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

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.add('active');
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            sidebar.classList.remove('active');
        });
    }
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 991) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>
<br>
<br>
<br>
