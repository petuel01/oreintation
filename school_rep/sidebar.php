<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\sidebar.php -->
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
        <h4>School Rep Portal</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link" id="dashboard">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_programs.php" class="nav-link" id="programs">
                <i class="fas fa-book"></i> <span>Manage Programs</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_scholarships.php" class="nav-link" id="scholarships">
                <i class="fas fa-graduation-cap"></i> <span>Manage Scholarships</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="view_applications.php" class="nav-link" id="applications">
                <i class="fas fa-file-alt"></i> <span>View Applications</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="view_reviews.php" class="nav-link" id="reviews">
                <i class="fas fa-comments"></i> <span>View Reviews</span>
            </a>
        </li>
        <li class="nav-item">
    <a href="../settings.php" class="nav-link">
        <i class="fas fa-cog"></i> <span>Settings</span>
    </a>
</li>
        <li class="nav-item logout">
            <a href="../logout.php" class="nav-link">
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

    // Highlight active link based on current page
    const currentPage = window.location.pathname.split('/').pop();
    const links = document.querySelectorAll('.nav-link');

    links.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
</script>