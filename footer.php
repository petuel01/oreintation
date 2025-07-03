
<!-- filepath: c:\xampp\htdocs\oreintation\footer.php -->
<footer style="background-color: #5D4037; color: #fff; padding-top: 40px; padding-bottom: 20px;">
    <div class="container">
        <div class="row mb-4">
            <!-- Logo & About -->
            <div class="col-md-4 mb-4">
                <h4 class="mb-3" style="font-weight: bold; letter-spacing: 1px;">
                    <i class="fas fa-graduation-cap me-2"></i>KamerGuide
                </h4>
                <p>
                    KamerGuide is your trusted companion for exploring universities, programs, scholarships, and career opportunities in Cameroon and beyond. Empowering students to make informed decisions for a brighter future.
                </p>
            </div>
            <!-- Navigation Links -->
            <div class="col-md-2 mb-4">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white text-decoration-none d-flex align-items-center mb-2"><i class="fas fa-home me-2"></i>Home</a></li>
                    <li><a href="student/explore.php" class="text-white text-decoration-none d-flex align-items-center mb-2"><i class="fas fa-university me-2"></i>Explore Universities</a></li>
                    <li><a href="student/carreer.php" class="text-white text-decoration-none d-flex align-items-center mb-2"><i class="fas fa-briefcase me-2"></i>Career Guides</a></li>
                    <li><a href="student/scholarships.php" class="text-white text-decoration-none d-flex align-items-center mb-2"><i class="fas fa-award me-2"></i>Scholarships</a></li>
                    <li><a href="student/about.php" class="text-white text-decoration-none d-flex align-items-center mb-2"><i class="fas fa-info-circle me-2"></i>About Us</a></li>
                    <li><a href="student/contact.php" class="text-white text-decoration-none d-flex align-items-center mb-2"><i class="fas fa-envelope me-2"></i>Contact</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@kamerguide.com</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> +237 6XX XXX XXX</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Yaoundé, Cameroon</li>
                </ul>
                <div class="mt-3">
                    <span>Follow us:</span>
                    <a href="#" class="text-white text-decoration-none mx-1"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white text-decoration-none mx-1"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white text-decoration-none mx-1"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white text-decoration-none mx-1"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-white text-decoration-none mx-1"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <!-- Newsletter -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Newsletter</h5>
                <form>
                    <div class="input-group mb-2">
                        <input type="email" class="form-control" placeholder="Your email" aria-label="Your email">
                        <button class="btn btn-warning" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </div>
                    <small>Get updates on universities, scholarships, and more.</small>
                </form>
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,0.1);">
        <div class="row align-items-center">
            <div class="col-md-6 text-start mb-2 mb-md-0">
                <small>
                    &copy; <?= date('Y') ?> KamerGuide. All Rights Reserved. | 
                    <a href="privacy.php" class="text-white text-decoration-underline">Privacy Policy</a> | 
                    <a href="terms.php" class="text-white text-decoration-underline">Terms & Conditions</a>
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small>
                    Powered by <a href="https://kamerguide.com" class="text-warning text-decoration-none">KamerGuide Team</a>
                </small>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 767px) {
            footer .row > div {
                text-align: center !important;
            }
            footer .col-md-6.text-end, footer .col-md-6.text-start {
                text-align: center !important;
            }
        }
    </style>
</footer>