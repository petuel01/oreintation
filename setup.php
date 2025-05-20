<!-- filepath: c:\xampp\htdocs\oreintation\setup.php -->
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

// Users Table
$conn->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'school_rep', 'student') NOT NULL,
    university_id INT DEFAULT NULL,
    status ENUM('pending', 'approved') DEFAULT 'approved',
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE SET NULL
)");

// Universities Table
$conn->query("
CREATE TABLE IF NOT EXISTS universities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    logo VARCHAR(255),
    motto TEXT,
    established_year INT,
    type ENUM('Public', 'Private', 'Religious', 'International') NOT NULL,
    accreditation_status VARCHAR(255),
    website VARCHAR(255),
    location VARCHAR(255), -- Added for city/region
    description TEXT, -- Added for university overview
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Locations Table
$conn->query("
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    country VARCHAR(100) NOT NULL,
    region VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    map_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Contacts Table
$conn->query("
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    whatsapp VARCHAR(50),
    telegram VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Social Links Table
$conn->query("
CREATE TABLE IF NOT EXISTS social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    twitter VARCHAR(255),
    youtube VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Faculties Table
$conn->query("
CREATE TABLE IF NOT EXISTS faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    faculty_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Programs Table
$conn->query("
CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    program_name VARCHAR(255) NOT NULL,
    duration VARCHAR(50),
    degree_type VARCHAR(100),
    language VARCHAR(100),
    admission_requirements TEXT,
    fees DECIMAL(10, 2), -- Added for program fees
    scholarship_availability BOOLEAN DEFAULT FALSE, -- Added for scholarship availability
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE
)");

// Fees Table
$conn->query("
CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    local_tuition DECIMAL(10, 2),
    international_tuition DECIMAL(10, 2),
    application_fee DECIMAL(10, 2),
    other_fees TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
)");

// Scholarships Table
$conn->query("
CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    scholarship_name VARCHAR(255) NOT NULL,
    description TEXT,
    eligibility TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE
)");

// Applications Table
$conn->query("
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    student_name VARCHAR(255) NOT NULL,
    program_id INT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending', -- Added for application status
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
)");

// Student Reviews Table
$conn->query("
CREATE TABLE IF NOT EXISTS student_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
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

// Blog / News Table
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