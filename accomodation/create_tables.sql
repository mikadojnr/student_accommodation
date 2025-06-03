-- Step 1: Create and use the database
CREATE DATABASE IF NOT EXISTS student_accommodation;
USE student_accommodation;

-- Step 2: Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('student', 'landlord') NOT NULL,
    phone VARCHAR(20) NOT NULL,
    university VARCHAR(100),
    bio TEXT,
    profile_image VARCHAR(255),
    is_verified BOOLEAN DEFAULT FALSE,
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_user_type (user_type),
    INDEX idx_university (university)
);

-- Step 3: Properties table
CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    property_type ENUM('room', 'apartment', 'house', 'studio') NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    deposit DECIMAL(10,2) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    campus_distance DECIMAL(5,2),
    amenities JSON,
    views INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_property_type (property_type),
    INDEX idx_city (city),
    INDEX idx_price (price),
    INDEX idx_available (is_available),
    FULLTEXT idx_search (title, description, address)
);

-- Step 4: Property Images table
CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    INDEX idx_property_id (property_id),
    INDEX idx_primary (is_primary)
);

-- Step 5: Verifications table
CREATE TABLE verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    identity_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    address_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    student_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    biometric_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    identity_document_path VARCHAR(255),
    address_document_path VARCHAR(255),
    student_document_path VARCHAR(255),
    biometric_image_path VARCHAR(255),
    identity_rejection_reason TEXT,
    address_rejection_reason TEXT,
    student_rejection_reason TEXT,
    biometric_rejection_reason TEXT,
    trust_score INT DEFAULT 0,
    verification_id VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_verification (user_id),
    INDEX idx_user_id (user_id),
    INDEX idx_trust_score (trust_score)
);

-- Step 6: Messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    property_id INT NOT NULL,
    message TEXT NOT NULL,
    is_encrypted BOOLEAN DEFAULT TRUE,
    is_read BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    INDEX idx_sender (sender_id),
    INDEX idx_recipient (recipient_id),
    INDEX idx_property (property_id),
    INDEX idx_conversation (sender_id, recipient_id, property_id),
    INDEX idx_created_at (created_at)
);

-- Step 7: Saved properties table
CREATE TABLE saved_properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_property (user_id, property_id),
    INDEX idx_user_id (user_id),
    INDEX idx_property_id (property_id)
);

-- Step 8: Reports table
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_user_id INT,
    property_id INT,
    reason ENUM('scam', 'fake_listing', 'inappropriate', 'spam', 'other') NOT NULL,
    description TEXT,
    status ENUM('pending', 'reviewed', 'resolved') DEFAULT 'pending',
    admin_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
    INDEX idx_reporter (reporter_id),
    INDEX idx_reported_user (reported_user_id),
    INDEX idx_property (property_id),
    INDEX idx_status (status),
    INDEX idx_reason (reason)
);

-- Step 9: Sessions table
CREATE TABLE sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);

-- Step 10: Activity logs
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Step 11: Views
CREATE VIEW verified_users AS
SELECT u.*, v.trust_score, v.identity_status, v.biometric_status
FROM users u
LEFT JOIN verifications v ON u.id = v.user_id
WHERE v.identity_status = 'verified' AND v.biometric_status = 'verified';

CREATE VIEW available_properties AS
SELECT p.*, u.first_name, u.last_name, v.trust_score,
       (SELECT image_path FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
FROM properties p
JOIN users u ON p.user_id = u.id
LEFT JOIN verifications v ON u.id = v.user_id
WHERE p.is_available = TRUE
ORDER BY p.created_at DESC;

-- Step 12: Insert sample data
INSERT INTO users (first_name, last_name, email, password, user_type, phone)
VALUES ('John', 'Doe', 'john.doe@example.com', 'hashed_password', 'landlord', '1234567890');

INSERT INTO properties (user_id, title, description, property_type, price, deposit, address, city, bedrooms, bathrooms, is_available)
VALUES (1, 'Cozy Student Apartment', 'A nice apartment near campus', 'apartment', 500.00, 1000.00, '123 Campus Rd', 'Lagos', 2, 1, TRUE);
