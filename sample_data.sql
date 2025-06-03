-- Sample data for SecureStay Student Accommodation Platform

-- Insert sample users with realistic data
INSERT INTO users (first_name, last_name, email, password, user_type, phone, university, created_at) VALUES
-- Landlords
('John', 'Smith', 'john.smith@landlord.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', '+44 7700 900123', NULL, '2023-01-15 10:30:00'),
('Michael', 'Brown', 'michael.brown@landlord.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', '+44 7700 900125', NULL, '2023-02-20 14:15:00'),
('Jennifer', 'Taylor', 'jennifer.taylor@landlord.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', '+44 7700 900127', NULL, '2023-03-10 09:45:00'),
('Robert', 'Davis', 'robert.davis@landlord.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', '+44 7700 900129', NULL, '2023-04-05 16:20:00'),

-- Students
('Sarah', 'Johnson', 'sarah.johnson@student.ox.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44 7700 900124', 'University of Oxford', '2023-05-12 11:00:00'),
('Emma', 'Wilson', 'emma.wilson@student.cam.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44 7700 900126', 'University of Cambridge', '2023-05-15 13:30:00'),
('James', 'Anderson', 'james.anderson@student.ucl.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44 7700 900128', 'University College London', '2023-05-18 15:45:00'),
('Sophie', 'Clark', 'sophie.clark@student.ed.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44 7700 900130', 'University of Edinburgh', '2023-05-20 10:15:00'),
('Daniel', 'Lewis', 'daniel.lewis@student.manchester.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44 7700 900131', 'University of Manchester', '2023-05-22 12:00:00'),
('Olivia', 'Walker', 'olivia.walker@student.bham.ac.uk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+44 7700 900132', 'University of Birmingham', '2023-05-25 14:30:00');

-- Insert verification records for users
INSERT INTO verifications (user_id, identity_status, address_status, student_status, biometric_status, trust_score, created_at) VALUES
-- Landlord verifications
(1, 'verified', 'verified', NULL, 'verified', 95, '2023-01-16 10:30:00'),
(2, 'verified', 'verified', NULL, 'verified', 90, '2023-02-21 14:15:00'),
(3, 'verified', 'pending', NULL, 'verified', 75, '2023-03-11 09:45:00'),
(4, 'pending', 'pending', NULL, 'pending', 25, '2023-04-06 16:20:00'),

-- Student verifications
(5, 'verified', 'verified', 'verified', 'verified', 98, '2023-05-13 11:00:00'),
(6, 'verified', 'verified', 'verified', 'verified', 95, '2023-05-16 13:30:00'),
(7, 'verified', 'pending', 'verified', 'verified', 85, '2023-05-19 15:45:00'),
(8, 'verified', 'verified', 'verified', 'pending', 80, '2023-05-21 10:15:00'),
(9, 'pending', 'pending', 'pending', 'pending', 20, '2023-05-23 12:00:00'),
(10, 'verified', 'verified', 'verified', 'verified', 92, '2023-05-26 14:30:00');

-- Insert comprehensive property listings
INSERT INTO properties (user_id, title, description, property_type, address, city, postal_code, price, deposit, bedrooms, bathrooms, campus_distance, amenities, is_available, created_at) VALUES

-- Oxford Properties
(1, 'Modern Student Room Near Oxford University', 'Spacious single room in a contemporary house share, perfect for undergraduate and postgraduate students. The room is fully furnished with a comfortable single bed, large study desk with ergonomic chair, built-in wardrobe, and excellent natural light. Shared facilities include a modern kitchen with dishwasher, comfortable living area, and clean bathroom. Located in the heart of Oxford, just 10 minutes walk from the city center and main university buildings. Excellent transport links to all colleges. All bills included in rent (electricity, gas, water, council tax, and high-speed WiFi). The house has a friendly, international community of students. Viewing highly recommended.', 'room', '123 Cowley Road', 'Oxford', 'OX4 1HP', 650.00, 650.00, 1, 1, 1.2, '["WiFi", "Desk", "Wardrobe", "Shared Kitchen", "Laundry", "Bills Included", "Dishwasher", "Central Heating"]', 1, '2023-06-01 10:00:00'),

(1, 'Luxury Studio Apartment - Oxford City Center', 'Beautiful self-contained studio apartment in a prestigious Victorian building, completely refurbished to the highest standards. Perfect for postgraduate students or young professionals. The studio features a double bed with premium mattress, fully equipped kitchenette with modern appliances (fridge, microwave, hob, sink), private en-suite bathroom with shower, dedicated study area with built-in desk and shelving, and ample storage throughout. High-speed fiber WiFi included. Located in the heart of Oxford city center, within walking distance of all major colleges, libraries, shops, and restaurants. Secure entry building with intercom system. All bills included. Available immediately for minimum 6-month tenancy.', 'studio', '45 High Street', 'Oxford', 'OX1 4AP', 850.00, 850.00, 1, 1, 0.8, '["WiFi", "Private Kitchen", "Private Bathroom", "Bills Included", "Furnished", "Security Entry", "City Center", "Double Bed"]', 1, '2023-06-02 11:30:00'),

(2, 'Charming Victorian House Room - Jericho', 'Large double room available in a beautiful Victorian terraced house in the desirable Jericho area of Oxford. This characterful property retains many original features while offering modern conveniences. The room is bright and airy with high ceilings, original sash windows, and period fireplace (decorative). Furnished with double bed, wardrobe, chest of drawers, and study desk. Shared facilities include spacious kitchen/dining room, comfortable lounge, bathroom, and separate WC. Small private garden perfect for relaxation. Located in trendy Jericho with excellent local pubs, cafes, and the famous Phoenix Picturehouse cinema. Easy cycling distance to all colleges and departments. Parking permit available. Ideal for mature students who appreciate character and location.', 'room', '78 Walton Street', 'Oxford', 'OX2 6ED', 720.00, 720.00, 1, 1, 1.5, '["WiFi", "Double Bed", "Garden", "Parking", "Character Property", "Jericho Location", "Shared Kitchen", "Period Features"]', 1, '2023-06-03 14:15:00'),

-- Cambridge Properties
(2, 'Modern Shared House Near Cambridge University', 'Excellent double room in a purpose-built student house, designed specifically for postgraduate students and researchers. This contemporary property offers the perfect balance of privacy and community living. The room features a comfortable double bed, large built-in wardrobe, study desk with ergonomic chair, and excellent storage solutions. Shared facilities include a spacious open-plan kitchen/living area with modern appliances, dishwasher, and comfortable seating area for socializing. Two modern bathrooms ensure minimal waiting times. High-speed fiber internet throughout. Located on Mill Road, famous for its diverse restaurants, independent shops, and vibrant community atmosphere. Excellent bus links to all university sites and the city center. Bike storage provided. All bills included. Perfect for international students and researchers.', 'room', '156 Mill Road', 'Cambridge', 'CB1 3DP', 680.00, 680.00, 1, 2, 2.1, '["WiFi", "Double Bed", "Shared Kitchen", "Modern Appliances", "Bike Storage", "Bills Included", "Mill Road Location", "International Community"]', 1, '2023-06-04 09:45:00'),

(3, 'Spacious Two Bedroom Apartment - Cambridge Center', 'Outstanding two-bedroom apartment in a modern development, perfect for sharing between two students or for a couple. This bright and airy apartment features an open-plan kitchen/living area with modern fitted kitchen including dishwasher, oven, hob, and large fridge-freezer. The living area has comfortable seating and dining space. Both bedrooms are generously sized doubles with built-in wardrobes and study areas. Modern bathroom with bath and shower. Additional features include central heating, double glazing, and secure entry system. Located in the heart of Cambridge with easy walking access to all colleges, the university library, and city center amenities. Excellent transport links. Parking space available. Ideal for serious students who value quality accommodation and central location.', 'apartment', '12 Parker Street', 'Cambridge', 'CB1 1JL', 1200.00, 1200.00, 2, 1, 1.5, '["WiFi", "Private Kitchen", "Furnished", "Central Heating", "Parking", "City Center", "Modern Development", "Secure Entry"]', 1, '2023-06-05 16:20:00'),

-- London Properties
(3, 'Student Room Near UCL - Bloomsbury', 'Fantastic single room in a Georgian townhouse in the heart of Bloomsbury, just minutes from UCL, SOAS, and Birkbeck. This characterful property combines period charm with modern student living. The room is well-proportioned with high ceilings, large sash window providing excellent natural light, and original features. Furnished with single bed, wardrobe, desk, and chair. Shared facilities include a large kitchen with dining area, comfortable common room with TV, and two bathrooms. The location is unbeatable - walking distance to the British Museum, numerous libraries, cafes, and restaurants. Excellent transport links with multiple tube stations nearby (Russell Square, Goodge Street, Tottenham Court Road). Perfect for students who want to experience authentic London living while being close to university. Bills included except electricity.', 'room', '89 Gower Street', 'London', 'WC1E 6HJ', 950.00, 950.00, 1, 2, 0.3, '["WiFi", "Character Property", "UCL Walking Distance", "Transport Links", "Bloomsbury Location", "Period Features", "Shared Kitchen"]', 1, '2023-06-06 12:00:00'),

(4, 'Modern Studio - King\'s Cross', 'Brand new studio apartment in a contemporary development near King\'s Cross Station. This stylish studio is perfect for students attending UCL, King\'s College, or other central London universities. Features include a comfortable double bed with storage underneath, modern kitchenette with all appliances, private bathroom with shower, and dedicated study area. Floor-to-ceiling windows provide excellent natural light and city views. Building amenities include 24-hour concierge, gym, communal study areas, and roof terrace. Located in the vibrant King\'s Cross area with excellent restaurants, bars, and cultural attractions. Outstanding transport links with King\'s Cross St. Pancras station providing access to six tube lines and international rail services. Perfect for students who want modern, independent living in central London.', 'studio', '234 York Way', 'London', 'N1 9AA', 1100.00, 1100.00, 1, 1, 2.5, '["WiFi", "Modern Development", "Gym", "Concierge", "Transport Hub", "City Views", "Private Kitchen", "Private Bathroom"]', 1, '2023-06-07 15:30:00'),

-- Edinburgh Properties
(4, 'Historic Edinburgh Room - Old Town', 'Unique opportunity to live in Edinburgh\'s famous Old Town, just steps from the Royal Mile and Edinburgh Castle. This characterful room is located in a traditional Scottish tenement building with stunning views over the city. The room features high ceilings, original wooden floors, and period details while offering modern comfort. Furnished with single bed, antique wardrobe, writing desk, and comfortable chair. Shared facilities include a traditional Scottish kitchen and bathroom. The location is exceptional - walking distance to the University of Edinburgh, National Library, numerous museums, and the vibrant Grassmarket area. Perfect for students who want to experience authentic Edinburgh living in a historic setting. Ideal for literature, history, or arts students who will appreciate the cultural significance of the location.', 'room', '67 High Street', 'Edinburgh', 'EH1 1SR', 580.00, 580.00, 1, 1, 0.5, '["WiFi", "Historic Building", "Old Town Location", "Castle Views", "Period Features", "Cultural Area", "University Walking Distance"]', 1, '2023-06-08 11:15:00'),

-- Manchester Properties
(1, 'Contemporary Student Apartment - Northern Quarter', 'Stylish one-bedroom apartment in Manchester\'s trendy Northern Quarter, perfect for postgraduate students or young professionals. This modern apartment features an open-plan living/kitchen area with contemporary fittings, separate bedroom with double bed and built-in storage, and modern bathroom. The kitchen is fully equipped with all appliances including dishwasher and washer/dryer. Located in the heart of the Northern Quarter, famous for its independent music venues, vintage shops, street art, and vibrant nightlife. Walking distance to Manchester Metropolitan University and excellent transport links to the University of Manchester. The area offers an authentic Manchester experience with numerous cafes, bars, and cultural venues. Perfect for students who want to be at the center of Manchester\'s creative scene.', 'apartment', '45 Oldham Street', 'Manchester', 'M1 1JG', 750.00, 750.00, 1, 1, 1.8, '["WiFi", "Northern Quarter", "Modern Apartment", "Washer/Dryer", "Cultural Area", "Music Venues", "Independent Living"]', 1, '2023-06-09 13:45:00'),

-- Birmingham Properties
(2, 'Student House Share - Selly Oak', 'Large double room in a popular student house in Selly Oak, the heart of Birmingham\'s student community. This well-maintained Victorian house offers excellent value for money and a true student experience. The room is spacious with double bed, wardrobe, desk, and chair. Shared facilities include a large kitchen/dining area, comfortable lounge with TV, and two bathrooms. The house has a friendly, multicultural atmosphere with students from various courses and countries. Located on a quiet residential street but close to all amenities including shops, restaurants, and pubs. Excellent bus links to the University of Birmingham (10 minutes) and city center (15 minutes). Perfect for undergraduate students who want an authentic student house experience in Birmingham\'s most popular student area. All bills included.', 'room', '123 Heeley Road', 'Birmingham', 'B29 6EJ', 420.00, 420.00, 1, 2, 2.0, '["WiFi", "Selly Oak", "Student Community", "Bills Included", "University Bus Route", "Multicultural", "Victorian House"]', 1, '2023-06-10 10:30:00');

-- Insert property images with realistic descriptions
INSERT INTO property_images (property_id, image_path, is_primary, created_at) VALUES
-- Oxford Modern Room
(1, '/placeholder.svg?height=400&width=600&text=Modern+Student+Room+Oxford', 1, '2023-06-01 10:05:00'),
(1, '/placeholder.svg?height=300&width=400&text=Study+Desk+Area', 0, '2023-06-01 10:06:00'),
(1, '/placeholder.svg?height=300&width=400&text=Shared+Kitchen', 0, '2023-06-01 10:07:00'),
(1, '/placeholder.svg?height=300&width=400&text=Living+Area', 0, '2023-06-01 10:08:00'),

-- Oxford Studio
(2, '/placeholder.svg?height=400&width=600&text=Luxury+Studio+Oxford', 1, '2023-06-02 11:35:00'),
(2, '/placeholder.svg?height=300&width=400&text=Kitchenette', 0, '2023-06-02 11:36:00'),
(2, '/placeholder.svg?height=300&width=400&text=Private+Bathroom', 0, '2023-06-02 11:37:00'),

-- Oxford Victorian Room
(3, '/placeholder.svg?height=400&width=600&text=Victorian+Room+Jericho', 1, '2023-06-03 14:20:00'),
(3, '/placeholder.svg?height=300&width=400&text=Period+Features', 0, '2023-06-03 14:21:00'),
(3, '/placeholder.svg?height=300&width=400&text=Garden+View', 0, '2023-06-03 14:22:00'),

-- Cambridge Shared House
(4, '/placeholder.svg?height=400&width=600&text=Cambridge+Shared+House', 1, '2023-06-04 09:50:00'),
(4, '/placeholder.svg?height=300&width=400&text=Modern+Kitchen', 0, '2023-06-04 09:51:00'),
(4, '/placeholder.svg?height=300&width=400&text=Bike+Storage', 0, '2023-06-04 09:52:00'),

-- Cambridge Apartment
(5, '/placeholder.svg?height=400&width=600&text=Two+Bed+Apartment+Cambridge', 1, '2023-06-05 16:25:00'),
(5, '/placeholder.svg?height=300&width=400&text=Open+Plan+Living', 0, '2023-06-05 16:26:00'),
(5, '/placeholder.svg?height=300&width=400&text=Double+Bedroom', 0, '2023-06-05 16:27:00'),

-- London UCL Room
(6, '/placeholder.svg?height=400&width=600&text=UCL+Bloomsbury+Room', 1, '2023-06-06 12:05:00'),
(6, '/placeholder.svg?height=300&width=400&text=Georgian+Features', 0, '2023-06-06 12:06:00'),
(6, '/placeholder.svg?height=300&width=400&text=Common+Room', 0, '2023-06-06 12:07:00'),

-- London Studio
(7, '/placeholder.svg?height=400&width=600&text=Kings+Cross+Studio', 1, '2023-06-07 15:35:00'),
(7, '/placeholder.svg?height=300&width=400&text=City+Views', 0, '2023-06-07 15:36:00'),
(7, '/placeholder.svg?height=300&width=400&text=Roof+Terrace', 0, '2023-06-07 15:37:00'),

-- Edinburgh Room
(8, '/placeholder.svg?height=400&width=600&text=Edinburgh+Old+Town', 1, '2023-06-08 11:20:00'),
(8, '/placeholder.svg?height=300&width=400&text=Castle+Views', 0, '2023-06-08 11:21:00'),
(8, '/placeholder.svg?height=300&width=400&text=Historic+Interior', 0, '2023-06-08 11:22:00'),

-- Manchester Apartment
(9, '/placeholder.svg?height=400&width=600&text=Northern+Quarter+Apartment', 1, '2023-06-09 13:50:00'),
(9, '/placeholder.svg?height=300&width=400&text=Modern+Kitchen', 0, '2023-06-09 13:51:00'),
(9, '/placeholder.svg?height=300&width=400&text=Street+Art+View', 0, '2023-06-09 13:52:00'),

-- Birmingham House
(10, '/placeholder.svg?height=400&width=600&text=Selly+Oak+House', 1, '2023-06-10 10:35:00'),
(10, '/placeholder.svg?height=300&width=400&text=Student+Kitchen', 0, '2023-06-10 10:36:00'),
(10, '/placeholder.svg?height=300&width=400&text=Lounge+Area', 0, '2023-06-10 10:37:00');

-- Insert realistic messages between users
INSERT INTO messages (sender_id, recipient_id, property_id, message, is_read, created_at) VALUES
-- Inquiries about Oxford properties
(5, 1, 1, 'Hi John, I\'m a DPhil student at Oxford and very interested in the room on Cowley Road. The location looks perfect for my needs. Would it be possible to arrange a viewing this weekend? I\'m available Saturday afternoon or Sunday morning. Also, could you tell me a bit more about the other housemates? Thanks!', 0, '2023-06-15 14:30:00'),
(1, 5, 1, 'Hello Sarah! Thanks for your interest in the room. I\'d be happy to arrange a viewing. Saturday afternoon works well - how about 2 PM? The house currently has 3 other postgrad students: one from Engineering, one from English Literature, and one from Economics. They\'re all very friendly and respectful. The atmosphere is studious but social. Let me know if Saturday works for you!', 1, '2023-06-15 16:45:00'),
(5, 1, 1, 'Saturday at 2 PM sounds perfect! That\'s great to hear about the other students - it sounds like a good academic environment. I\'m studying Medieval History, so I appreciate a quiet space for research. Should I bring any documents with me for the viewing? Looking forward to meeting you!', 0, '2023-06-15 18:20:00'),

(6, 1, 2, 'Hello, I\'m interested in the studio apartment on High Street. I\'m starting my Master\'s in September and this looks ideal for independent study. Is it still available? Also, what\'s the minimum tenancy period?', 0, '2023-06-16 10:15:00'),
(1, 6, 2, 'Hi Emma! Yes, the studio is still available. The minimum tenancy is 6 months, but most students stay for the full academic year. It\'s perfect for postgrad study - very quiet and you have complete privacy. Would you like to arrange a viewing?', 1, '2023-06-16 12:30:00'),

-- Cambridge inquiries
(7, 2, 4, 'Hi Michael, I\'m a PhD student starting at Cambridge in October. The room on Mill Road looks great - I love the Mill Road area! Is the room still available? I\'m particularly interested in the international community you mentioned. Could you tell me more about the current housemates?', 0, '2023-06-17 09:45:00'),
(2, 7, 4, 'Hello James! Yes, the room is available from September. The house currently has students from Germany, Italy, and the UK. Everyone is doing postgrad degrees in different subjects - it makes for interesting dinner conversations! The area is fantastic for international food and culture. When would you like to view?', 1, '2023-06-17 11:20:00'),

(8, 3, 5, 'Hello Jennifer, I\'m looking for accommodation for my partner and myself - we\'re both starting PhDs at Cambridge. The two-bedroom apartment looks perfect. Are couples welcome? We\'re both very quiet and studious. When might we be able to view it?', 0, '2023-06-18 15:30:00'),
(3, 8, 5, 'Hi Sophie! Yes, couples are absolutely welcome. The apartment is perfect for two people and very popular with PhD couples. It\'s quiet and has excellent study spaces. I have availability for viewings this week. Are you free Thursday or Friday afternoon?', 1, '2023-06-18 17:15:00'),

-- London inquiries
(9, 3, 6, 'Hi, I\'m starting my Master\'s at UCL in September and the Bloomsbury location is exactly what I\'m looking for. Is the room still available? I\'m particularly interested in the Georgian character - I love period buildings! Also, what\'s the atmosphere like in the house?', 0, '2023-06-19 13:20:00'),
(3, 9, 6, 'Hello Daniel! The room is available and the Georgian features really are beautiful - high ceilings and original details throughout. The house has a lovely academic atmosphere with students from UCL and SOAS. Everyone respects each other\'s study time but we often have interesting discussions over dinner. Would you like to arrange a viewing?', 1, '2023-06-19 15:45:00'),

(10, 4, 7, 'Hello Robert, I\'m very interested in the King\'s Cross studio. I\'m starting my Master\'s at King\'s College and the location would be perfect for my commute. The modern amenities look fantastic. Is it available from September? Also, what\'s the building community like?', 0, '2023-06-20 11:00:00'),
(4, 10, 7, 'Hi Olivia! Yes, it\'s available from September. The building has a great mix of young professionals and postgrad students. The communal areas are well-used and there\'s a real sense of community. The gym and study spaces are excellent. The location really can\'t be beaten for transport links. Shall we arrange a viewing?', 1, '2023-06-20 13:30:00');

-- Insert saved properties (students saving properties they\'re interested in)
INSERT INTO saved_properties (user_id, property_id, created_at) VALUES
(5, 1, '2023-06-15 14:35:00'),  -- Sarah saved Oxford room
(5, 2, '2023-06-16 10:20:00'),  -- Sarah also saved Oxford studio
(6, 2, '2023-06-16 10:20:00'),  -- Emma saved Oxford studio
(6, 4, '2023-06-17 14:30:00'),  -- Emma saved Cambridge room
(7, 4, '2023-06-17 09:50:00'),  -- James saved Cambridge room
(7, 5, '2023-06-18 16:00:00'),  -- James saved Cambridge apartment
(8, 5, '2023-06-18 15:35:00'),  -- Sophie saved Cambridge apartment
(8, 6, '2023-06-19 20:00:00'),  -- Sophie saved London room
(9, 6, '2023-06-19 13:25:00'),  -- Daniel saved London room
(9, 7, '2023-06-20 14:00:00'),  -- Daniel saved London studio
(10, 7, '2023-06-20 11:05:00'), -- Olivia saved London studio
(10, 9, '2023-06-21 10:00:00'); -- Olivia saved Manchester apartment

-- Insert property views for analytics
INSERT INTO property_views (property_id, ip_address, created_at) VALUES
-- Multiple views for popular properties
(1, '192.168.1.100', '2023-06-15 10:00:00'),
(1, '192.168.1.101', '2023-06-15 11:30:00'),
(1, '192.168.1.102', '2023-06-15 14:20:00'),
(1, '192.168.1.103', '2023-06-16 09:15:00'),
(1, '192.168.1.104', '2023-06-16 16:45:00'),

(2, '192.168.1.105', '2023-06-16 08:30:00'),
(2, '192.168.1.106', '2023-06-16 12:00:00'),
(2, '192.168.1.107', '2023-06-16 15:30:00'),

(4, '192.168.1.108', '2023-06-17 09:00:00'),
(4, '192.168.1.109', '2023-06-17 13:45:00'),
(4, '192.168.1.110', '2023-06-17 18:20:00'),

(6, '192.168.1.111', '2023-06-19 10:30:00'),
(6, '192.168.1.112', '2023-06-19 14:15:00'),

(7, '192.168.1.113', '2023-06-20 09:45:00'),
(7, '192.168.1.114', '2023-06-20 16:30:00');

-- Insert sample reports (for testing moderation features)
INSERT INTO reports (reporter_id, reported_user_id, property_id, reason, description, status, created_at) VALUES
(7, NULL, 3, 'misleading_info', 'The property description mentions a garden but the images don\'t show any outdoor space. Could be misleading for potential tenants.', 'pending', '2023-06-18 14:30:00'),
(9, 4, 7, 'suspicious_pricing', 'The rent seems unusually low for a King\'s Cross studio with these amenities. Might be too good to be true.', 'reviewed', '2023-06-20 16:45:00');

-- Insert activity logs for user tracking
INSERT INTO activity_logs (user_id, action, description, ip_address, created_at) VALUES
-- User registration activities
(5, 'register', 'New student user registration', '192.168.1.100', '2023-05-12 11:00:00'),
(6, 'register', 'New student user registration', '192.168.1.101', '2023-05-15 13:30:00'),
(7, 'register', 'New student user registration', '192.168.1.102', '2023-05-18 15:45:00'),

-- Login activities
(1, 'login', 'Landlord login successful', '192.168.1.200', '2023-06-15 09:00:00'),
(5, 'login', 'Student login successful', '192.168.1.100', '2023-06-15 14:25:00'),
(2, 'login', 'Landlord login successful', '192.168.1.201', '2023-06-17 08:30:00'),

-- Property activities
(1, 'property_create', 'Created new property listing: Modern Student Room Near Oxford University', '192.168.1.200', '2023-06-01 10:00:00'),
(1, 'property_create', 'Created new property listing: Luxury Studio Apartment - Oxford City Center', '192.168.1.200', '2023-06-02 11:30:00'),
(5, 'property_view', 'Viewed property: Modern Student Room Near Oxford University', '192.168.1.100', '2023-06-15 14:30:00'),
(5, 'property_save', 'Saved property: Modern Student Room Near Oxford University', '192.168.1.100', '2023-06-15 14:35:00'),

-- Message activities
(5, 'message_send', 'Sent inquiry message about property', '192.168.1.100', '2023-06-15 14:30:00'),
(1, 'message_send', 'Replied to property inquiry', '192.168.1.200', '2023-06-15 16:45:00'),

-- Verification activities
(5, 'verification_start', 'Started identity verification process', '192.168.1.100', '2023-05-13 10:30:00'),
(5, 'verification_complete', 'Completed full verification process', '192.168.1.100', '2023-05-13 11:00:00');

-- Update property view counts based on the views inserted
UPDATE properties SET views = (
    SELECT COUNT(*) FROM property_views WHERE property_views.property_id = properties.id
);

-- Add some additional realistic data
UPDATE properties SET 
    views = views + FLOOR(RAND() * 50) + 10,
    updated_at = created_at
WHERE id <= 10;
