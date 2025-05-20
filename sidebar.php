<!-- filepath: c:\xampp\htdocs\oreintation\sidebar.php -->
<?php
session_start();
?>

<style>
    /* Navbar styles */
    .navbar {
        background-color: #5D4037; /* Dark brown color */
        color: white;
        width: 100%; /* Extend the navbar to full width */
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }
   

    .navbar a {
        color: white;
        text-decoration: none;
    }

    .navbar a:hover {
        color: #D7CCC8; /* Light brown hover effect */
    }

    /* Mobile menu styles */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background-color: #5D4037; /* Dark brown color */
        color: white;
        z-index: 1000;
        transition: all 0.3s ease;
        overflow-y: auto;
        padding-top: 20px;
    }

    .mobile-menu.active {
        left: 0;
    }

    .mobile-menu .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: white;
    }

    .mobile-menu ul {
        list-style: none;
        padding: 0;
    }

    .mobile-menu ul li {
        padding: 15px 20px;
    }

    .mobile-menu ul li a {
        color: white;
        text-decoration: none;
        display: block;
    }

    .mobile-menu ul li a:hover {
        background-color: #D7CCC8; /* Light brown hover effect */
        border-radius: 4px;
    }

    /* Hamburger menu button */
    .menu-btn {
        display: none;
        background-color: #5D4037; /* Dark brown color */
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        z-index: 1100;
        border-radius: 4px;
        font-size: 1.5rem; /* Increased size */
        position: fixed;
        top: 3.5px; /* Positioned 3.5px from the top */
        left: 3.5px; /* Positioned 3.5px from the left */
    }

    .menu-btn:hover {
        background-color: #4E342E; /* Darker brown */
    }

    @media (max-width: 768px) { /* Adjusted breakpoint for smaller screens */
        .menu-btn {
            display: block;
        }

        .navbar .navbar-collapse {
            display: none; /* Hide horizontal links in mobile view */
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }
        .navbar-brand {
        font-size: 1.8rem;
        font-weight: 2rem; /* Increased size */
        color: white;
        margin-left: 25px; /* Added margin for spacing */
    }
    }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">KamerGuide</a>
        <button class="menu-btn" id="openMenu">
            <i class="fas fa-bars"></i> <!-- Three-line button -->
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="explore.php">Explore Universities</a></li>
                <li class="nav-item"><a class="nav-link" href="how_it_works.php">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Links for Logged-In Users -->
                    <li class="nav-item"><a class="nav-link" href="apply.php">Apply for Admission</a></li>
                    <li class="nav-item"><a class="nav-link" href="scholarships.php">Scholarships</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- Login/Register Link for Unauthorized Users -->
                    <li class="nav-item"><a class="nav-link btn btn-light text-dark" href="login.php">Login/Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <span class="close-btn" id="closeMenu">&times;</span>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="explore.php">Explore Universities</a></li>
        <li><a href="how_it_works.php">How It Works</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="apply.php">Apply for Admission</a></li>
            <li><a href="scholarships.php">Scholarships</a></li>
            <li><a href="logout.php">Logout</a></li>
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

    // Open the mobile menu
    openMenu.addEventListener('click', () => {
        mobileMenu.classList.add('active');
    });

    // Close the mobile menu
    closeMenu.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
    });

    // Reset the mobile menu when resizing to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            mobileMenu.classList.remove('active');
        }
    });
</script>