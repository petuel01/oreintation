-- Migration: Create student_reviews table for university reviews
CREATE TABLE IF NOT EXISTS student_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    student_id INT NOT NULL,
    rating_overall INT NOT NULL CHECK (rating_overall BETWEEN 1 AND 5),
    comment TEXT,
    review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);
