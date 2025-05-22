<!-- filepath: c:\xampp\htdocs\oreintation\admin\sidebar.php -->
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

    /* Content area */
    .main-content {
        margin-left: 250px;
        padding: 1rem;
        transition: margin-left 0.3s ease;
    }

    /* Mobile styles */
    .mobile-menu-button {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-250px);
            width: 250px;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .mobile-menu-button {
            display: block;
        }
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
    // Sidebar toggle function for mobile
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>