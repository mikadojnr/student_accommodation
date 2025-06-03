-- Sample data for SecureStay

-- Insert sample users
INSERT INTO users (first_name, last_name, email, password, user_type, phone, university) VALUES
('John', 'Smith', 'john.smith@landlord.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', '+44123456789', NULL),
('Sarah', 'Johnson', 'sarah.johnson@student.ox.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44987654321', 'University of Oxford'),
('Mike', 'Wilson', 'mike.wilson@landlord.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', '+44555123456', NULL),
('Emma', 'Davis', 'emma.davis@student.cam.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44777888999', 'University of Cambridge'),
('David', 'Brown', 'david.brown@student.ucl.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44111222333', 'University College London');

-- Insert verification records
INSERT INTO verifications (user_id, identity_status, address_status, student_status, biometric_status, trust_score) VALUES
(1, 'verified', 'verified', 'pending', 'verified', 85),
(2, 'verified', 'verified', 'verified', 'verified', 95),
(3, 'verified', 'pending', 'pending', 'verified', 75),
(4, 'verified', 'verified', 'verified', 'verified', 90),
(5, 'pending', 'pending', 'pending', 'pending', 20);

-- Insert sample properties
INSERT INTO properties (user_id, title, description, property_type, price, deposit, address, city, postal_code, bedrooms, bathrooms, campus_distance, amenities) VALUES
(1, 'Modern Student Room in Oxford', 'Spacious single room in a shared house, 5 minutes walk to Oxford University. Fully furnished with desk, wardrobe, and single bed. Shared kitchen and bathroom facilities.', 'room', 450.00, 450.00, '123 High Street', 'Oxford', 'OX1 4AA', 1, 1, 0.5, '["WiFi", "Desk", "Wardrobe", "Shared Kitchen", "Laundry"]'),
(1, 'Cozy Studio Apartment', 'Self-contained studio apartment perfect for students. Includes kitchenette, private bathroom, and study area. Located near public transport links.', 'studio', 650.00, 650.00, '456 Park Road', 'Oxford', 'OX2 6BB', 1, 1, 1.2, '["WiFi", "Private Kitchen", "Private Bathroom", "Study Area", "Parking"]'),
(3, 'Shared House Room - Cambridge', 'Large double room in friendly shared house. Great location near Cambridge University. Includes all bills and high-speed internet.', 'room', 520.00, 520.00, '789 Mill Lane', 'Cambridge', 'CB2 1RX', 1, 1, 0.8, '["WiFi", "Bills Included", "Garden", "Bike Storage", "Shared Kitchen"]'),
(3, 'Modern Apartment Near UCL', 'Contemporary 2-bedroom apartment available for sharing. Perfect for students attending UCL or other London universities.', 'apartment', 800.00, 1600.00, '321 Gower Street', 'London', 'WC1E 6BT', 2, 2, 0.3, '["WiFi", "Modern Kitchen", "Living Room", "Central Heating", "Security Entry"]');

-- Insert property images
INSERT INTO property_images (property_id, image_path, is_primary) VALUES
(1, '/uploads/properties/room1_main.jpg', 1),
(1, '/uploads/properties/room1_desk.jpg', 0),
(1, '/uploads/properties/room1_kitchen.jpg', 0),
(2, '/uploads/properties/studio1_main.jpg', 1),
(2, '/uploads/properties/studio1_kitchen.jpg', 0),
(3, '/uploads/properties/cambridge_room_main.jpg', 1),
(3, '/uploads/properties/cambridge_room_garden.jpg', 0),
(4, '/uploads/properties/london_apt_main.jpg', 1),
(4, '/uploads/properties/london_apt_living.jpg', 0);

-- Insert sample messages
INSERT INTO messages (sender_id, recipient_id, property_id, message, is_read) VALUES
(2, 1, 1, 'Hi, I\'m interested in viewing the room on High Street. When would be a good time?', 1),
(1, 2, 1, 'Hello! Thanks for your interest. I\'m available for viewings this weekend. Would Saturday afternoon work for you?', 1),
(2, 1, 1, 'Saturday afternoon sounds perfect. What time would suit you best?', 0),
(4, 3, 3, 'Hello, I\'d like to know more about the room in Cambridge. Is it still available?', 1),
(3, 4, 3, 'Yes, the room is still available. Would you like to arrange a viewing?', 0);

-- Insert saved properties
INSERT INTO saved_properties (user_id, property_id) VALUES
(2, 1),
(2, 3),
(4, 3),
(4, 4),
(5, 1);

-- Insert sample reports
INSERT INTO reports (reporter_id, reported_user_id, property_id, reason, description, status) VALUES
(2, NULL, 1, 'other', 'Property description doesn\'t match the actual room size', 'pending'),
(4, NULL, 4, 'fake_listing', 'Suspicious pricing and contact information', 'reviewed');

-- Update property view counts
UPDATE properties SET views = FLOOR(RAND() * 100) + 10;

-- Insert activity logs
INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES
(1, 'login', 'User logged in successfully', '192.168.1.100'),
(2, 'property_view', 'Viewed property: Modern Student Room in Oxford', '192.168.1.101'),
(3, 'property_create', 'Created new property listing', '192.168.1.102'),
(4, 'message_send', 'Sent message about property inquiry', '192.168.1.103'),
(5, 'register', 'New user registration', '192.168.1.104');