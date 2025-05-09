<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Change if you have a password
$dbName = 'orientation_system'; 

// Connect to MySQL
$conn = new mysqli($host, $user, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbName);

// User roles table
$conn->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'school_rep', 'student') NOT NULL,
    university_id INT DEFAULT NULL,
    status ENUM('pending', 'approved') DEFAULT 'approved',
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Universities
$conn->query("
CREATE TABLE IF NOT EXISTS universities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(255),
    motto TEXT,
    established_year INT,
    type ENUM('Public', 'Private', 'Religious', 'International') NOT NULL,
    accreditation_status VARCHAR(255),
    website VARCHAR(255)
)");

// Location
$conn->query("
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    country VARCHAR(100),
    region VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    map_link VARCHAR(255),
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Contact
$conn->query("
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    email VARCHAR(255),
    phone VARCHAR(50),
    whatsapp VARCHAR(50),
    telegram VARCHAR(50),
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Social Links
$conn->query("
CREATE TABLE IF NOT EXISTS social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    twitter VARCHAR(255),
    youtube VARCHAR(255),
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Faculties
$conn->query("
CREATE TABLE IF NOT EXISTS faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    faculty_name VARCHAR(255),
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Programs
$conn->query("
CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT,
    program_name VARCHAR(255),
    duration VARCHAR(50),
    degree_type VARCHAR(100),
    language VARCHAR(100),
    admission_requirements TEXT,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE
)");

// Fees
$conn->query("
CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT,
    local_tuition DECIMAL(10, 2),
    international_tuition DECIMAL(10, 2),
    application_fee DECIMAL(10, 2),
    other_fees TEXT,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
)");

// Scholarships
$conn->query("
CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    scholarship_name VARCHAR(255),
    description TEXT,
    eligibility TEXT,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Accommodation
$conn->query("
CREATE TABLE IF NOT EXISTS accommodation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    on_campus BOOLEAN,
    cost_range VARCHAR(100),
    facilities TEXT,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Campus Facilities
$conn->query("
CREATE TABLE IF NOT EXISTS campus_facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    library BOOLEAN,
    sports_complex BOOLEAN,
    laboratories BOOLEAN,
    tech_centers BOOLEAN,
    cafeteria BOOLEAN,
    health_services BOOLEAN,
    religious_centers TEXT,
    clubs TEXT,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// International Info
$conn->query("
CREATE TABLE IF NOT EXISTS international_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    visa_support BOOLEAN,
    exchange_programs BOOLEAN,
    international_office_contact VARCHAR(255),
    integration_support TEXT,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Career Services
$conn->query("
CREATE TABLE IF NOT EXISTS career_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    internship_opportunities BOOLEAN,
    company_partnerships TEXT,
    job_placement_rate VARCHAR(50),
    career_counseling BOOLEAN,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Media Gallery
$conn->query("
CREATE TABLE IF NOT EXISTS media_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    media_type ENUM('Photo', 'Video'),
    media_url VARCHAR(255),
    description TEXT,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Student Reviews
$conn->query("
CREATE TABLE IF NOT EXISTS student_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT,
    rating_overall INT,
    rating_teaching INT,
    rating_facilities INT,
    rating_support INT,
    rating_career_services INT,
    comment TEXT,
    pros TEXT,
    cons TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Blog / News
$conn->query("
CREATE TABLE IF NOT EXISTS blog_news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    image VARCHAR(255),
    content LONGTEXT NOT NULL,
    author VARCHAR(100),
    category VARCHAR(100),
    tags VARCHAR(255),
    published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Insert default admin user
$defaultEmail = "baifempetuel@gmail.com";
$defaultPassword = "Admin123!";
$hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);
$defaultRole = "admin";

// Check if the default admin user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $defaultEmail);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Default admin user already exists.<br>";
} else {
    // Insert the default admin user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $defaultName = "Baifem Petuel";
    $defaultStatus = "approved";
    $stmt->bind_param("sssss", $defaultName, $defaultEmail, $hashedPassword, $defaultRole, $defaultStatus);

    if ($stmt->execute()) {
        echo "Default admin user created successfully.<br>";
    } else {
        die("Error inserting default admin user: " . $stmt->error);
    }
}

$stmt->close();
$conn->close();

echo "All tables created successfully.";
?>