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
        top: 0;
        left: -250px; /* Hidden by default */
        width: 250px;
        overflow-y: auto;
        z-index: 1100; /* Higher than the menu button */
        transition: all 0.3s ease;
    }

    .sidebar.show {
        left: 0; /* Slide in from the left */
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
        font-size: 1.5rem;
        color: white;
        background: none;
        border: none;
        cursor: pointer;
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

    /* Hamburger menu button */
    .menu-btn {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1000; /* Lower than the sidebar */
        background-color: rgb(78, 40, 15);
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .menu-btn:hover {
        background-color: #555;
    }

    .menu-btn.active {
        background-color: rgb(78, 40, 15); /* Dark brown when sidebar is open */
    }

    /* Overlay */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
        z-index: 999; /* Below the sidebar */
        display: none; /* Hidden by default */
    }
    .hori{
        width: 100%;
        height: 5rem;
        background-color: rgb(78, 40, 15);
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        transition: background-color 0.3s ease;
    }

    .overlay.show {
        display: block; /* Show overlay when sidebar is open */
    }

    @media (max-width: 768px) {
        .menu-btn {
            display: block; /* Show button in responsive mode */
        }

        .sidebar {
            width: 200px; /* Smaller sidebar width for mobile */
        }
    }
</style>

<!-- Hamburger Menu Button -->
 <div class="hori">
<button class="menu-btn" id="openSidebar">☰</button>
</div>
<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>School Rep Portal</h4>
        <button class="close-btn" id="closeSidebar">&times;</button>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link" id="dashboard">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_faculties.php" class="nav-link" id="faculties">
                <i class="fas fa-building"></i> <span>Manage Faculties</span>
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
    // JavaScript for toggling the sidebar
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const openSidebar = document.getElementById('openSidebar');
    const closeSidebar = document.getElementById('closeSidebar');

    // Open the sidebar
    openSidebar.addEventListener('click', () => {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        openSidebar.classList.add('active'); // Change button color
    });

    // Close the sidebar
    closeSidebar.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        openSidebar.classList.remove('active'); // Reset button color
    });

    // Close the sidebar when clicking on the overlay
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        openSidebar.classList.remove('active'); // Reset button color
    });

    // Highlight active link based on current page
    const currentPage = window.location.pathname.split('/').pop();
    const links = document.querySelectorAll('.nav-link');

    links.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
</script>