
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About KamerGuide</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .btn-brown {
            background-color: #5D4037;
            color: #fff;
        }
        .btn-brown:hover {
            background-color: #3E2723;
            color: #fff;
        }
        .section-title {
            color: #5D4037;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .about-hero {
            background: linear-gradient(90deg, #5D4037 60%, #fff 100%);
            color: #fff;
            padding: 60px 0 40px 0;
        }
        .about-hero h1 {
            font-weight: bold;
            font-size: 2.8rem;
        }
        .about-hero p {
            font-size: 1.2rem;
        }
        .icon-circle {
            background: #5D4037;
            color: #fff;
            border-radius: 50%;
            padding: 12px;
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .feature-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(93,64,55,0.08);
        }
        .team-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #5D4037;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include("sidebar.php"); ?>
        <div class="flex-grow-1">
            <!-- Hero Section -->
            <section class="about-hero text-white">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1>About <span style="color: #FFD54F;">KamerGuide</span></h1>
                            <p>
                                KamerGuide is Cameroon’s leading digital platform dedicated to empowering students, parents, and educators with the information and tools they need to make informed decisions about higher education, scholarships, and career opportunities.
                            </p>
                            <a href="explore.php" class="btn btn-brown btn-lg mt-3"><i class="fas fa-university me-2"></i>Explore Universities</a>
                        </div>
                        <div class="col-lg-4 text-center d-none d-lg-block">
                            <img src="../assets/images/about-hero.png" alt="About KamerGuide" class="img-fluid" style="max-height: 220px;">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Our Mission & Vision -->
            <section class="container py-5">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h2 class="section-title"><i class="fas fa-bullseye icon-circle"></i>Our Mission</h2>
                        <p>
                            To bridge the gap between students and educational opportunities by providing a comprehensive, user-friendly platform for exploring universities, programs, scholarships, and career paths in Cameroon and beyond.
                        </p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h2 class="section-title"><i class="fas fa-eye icon-circle"></i>Our Vision</h2>
                        <p>
                            To become the most trusted educational resource in Africa, inspiring and guiding the next generation of leaders, innovators, and professionals.
                        </p>
                    </div>
                </div>
            </section>

            <!-- What We Offer -->
            <section class="container py-4">
                <h2 class="section-title text-center mb-5"><i class="fas fa-star icon-circle"></i>What We Offer</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card feature-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-university icon-circle"></i>University Explorer</h5>
                                <p class="card-text">
                                    Discover detailed profiles of universities across Cameroon, including programs, facilities, admission requirements, and campus life.
                                </p>
                                <a href="explore.php" class="btn btn-brown btn-sm">Browse Universities</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card feature-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-award icon-circle"></i>Scholarship Finder</h5>
                                <p class="card-text">
                                    Access a curated list of scholarships and financial aid opportunities to support your academic journey.
                                </p>
                                <a href="scholarships.php" class="btn btn-brown btn-sm">Find Scholarships</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card feature-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-briefcase icon-circle"></i>Career Guides</h5>
                                <p class="card-text">
                                    Explore career paths, requirements, and guidance to help you choose the right program and university for your future.
                                </p>
                                <a href="careers.php" class="btn btn-brown btn-sm">Explore Careers</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Why Choose KamerGuide -->
            <section class="container py-5">
                <h2 class="section-title text-center mb-5"><i class="fas fa-question-circle icon-circle"></i>Why Choose KamerGuide?</h2>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-users icon-circle mb-3"></i>
                                <h6 class="card-title">Student-Centered</h6>
                                <p class="card-text">Our platform is designed with students in mind, making it easy to find relevant information and opportunities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-shield-alt icon-circle mb-3"></i>
                                <h6 class="card-title">Trusted & Reliable</h6>
                                <p class="card-text">We verify all universities and scholarships to ensure you get accurate and up-to-date information.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-globe-africa icon-circle mb-3"></i>
                                <h6 class="card-title">Local & Global Reach</h6>
                                <p class="card-text">While focused on Cameroon, we also feature international programs and opportunities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-headset icon-circle mb-3"></i>
                                <h6 class="card-title">Support & Guidance</h6>
                                <p class="card-text">Our team is here to answer your questions and guide you every step of the way.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Meet the Team -->
            <section class="container py-5">
                <h2 class="section-title text-center mb-5"><i class="fas fa-users icon-circle"></i>Meet Our Team</h2>
                <div class="row justify-content-center">
                    <div class="col-md-3 text-center mb-4">
                        <img src="../assets/images/team1.jpg" alt="Team Member" class="team-img mb-2">
                        <h6 class="mb-0">Petuel Baifem</h6>
                        <small class="text-muted">Founder & Lead Developer</small>
                    </div>
                    <div class="col-md-3 text-center mb-4">
                        <img src="../assets/images/team2.jpg" alt="Team Member" class="team-img mb-2">
                        <h6 class="mb-0">Jane Doe</h6>
                        <small class="text-muted">Content Manager</small>
                    </div>
                    <div class="col-md-3 text-center mb-4">
                        <img src="../assets/images/team3.jpg" alt="Team Member" class="team-img mb-2">
                        <h6 class="mb-0">John Smith</h6>
                        <small class="text-muted">Student Advisor</small>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="container py-5 text-center">
                <h2 class="section-title mb-4">Ready to Start Your Journey?</h2>
                <a href="register.php" class="btn btn-brown btn-lg mx-2"><i class="fas fa-user-plus me-2"></i>Join KamerGuide</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg mx-2" style="border-color: #5D4037; color: #5D4037;"><i class="fas fa-envelope me-2"></i>Contact Us</a>
            </section>

            <?php include("../footer.php"); ?>
        </div>
    </div>
</body>
</html>