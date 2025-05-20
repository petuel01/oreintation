<style>
        /* Hero Section */
        .hero {
            position: relative;
            background: url('assets/hero-bg.jpg') no-repeat center center/cover;
            padding: 100px 0;
            color: white;
            text-align: center;
            min-height: 400px; /* Ensures enough height for content */
        }
        
        .hero-overlay {
            background: linear-gradient(to right, rgba(93, 64, 55, 0.9), rgba(93, 64, 55, 0.5)), url('assets/a.jpeg') no-repeat center center/cover;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 100px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        
        .hero h1 {
            font-weight: bold;
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.4rem;
            margin-bottom: 30px;
        }
        
        .search-bar {
            margin-top: 20px;
        }
        
        .search-bar input,
        .search-bar select {
            border: 2px solid #5D4037; /* Dark brown */
            border-radius: 5px;
            padding: 10px;
        }
        
        .search-bar input:focus,
        .search-bar select:focus {
            outline: none;
            border-color: #4E342E; /* Darker brown */
            box-shadow: 0 0 5px rgba(93, 64, 55, 0.5);
        }
        
        .search-bar button {
            background-color: #5D4037; /* Dark brown */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        .search-bar button:hover {
            background-color: #4E342E; /* Darker brown */
        }
        
        @media (max-width: 768px) {
            .hero {
                min-height: 500px; /* Increased height for mobile */
            }
        
            .hero-overlay {
                padding: 50px 20px;
            }
        
            .search-bar .row > div {
                margin-bottom: 15px;
            }
        }
        
        /* Add space below the hero section */
        .hero + .featured {
            margin-top: 50px;
        }
</style>
<!-- filepath: c:\xampp\htdocs\oreintation\hero.php -->
<!-- filepath: c:\xampp\htdocs\oreintation\hero.php -->
<!-- filepath: c:\xampp\htdocs\oreintation\hero.php -->
<section class="hero">
    <div class="hero-overlay">
        <div class="container text-center">
            <h1>KamerGuide</h1>
            <p>Your ultimate guide to universities in Cameroon</p>
            <form method="GET" action="search_results.php" class="search-bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <input type="text" name="query" class="form-control" placeholder="Search by university name">
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <select name="location" class="form-control">
                            <option value="">Select Location</option>
                            <option value="Douala">Douala</option>
                            <option value="Yaounde">Yaounde</option>
                            <option value="Bamenda">Bamenda</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <select name="degree_type" class="form-control">
                            <option value="">Select Degree Type</option>
                            <option value="Bachelor's">Bachelor's</option>
                            <option value="Master's">Master's</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>