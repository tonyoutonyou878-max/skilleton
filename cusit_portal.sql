-- MySQL schema and seed data for CUSIT Smart Campus Portal

DROP DATABASE IF EXISTS cusit_portal;
CREATE DATABASE cusit_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cusit_portal;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) DEFAULT 1
);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    priority ENUM('Low','Medium','High') NOT NULL DEFAULT 'Medium',
    subject VARCHAR(255) NOT NULL,
    details TEXT NOT NULL,
    status ENUM('Open','In Progress','Resolved','Escalated') NOT NULL DEFAULT 'Open',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(150) NOT NULL,
    seats_total INT NOT NULL DEFAULT 20,
    seats_taken INT NOT NULL DEFAULT 0,
    event_date DATE NOT NULL,
    status ENUM('Open','Closed','Full') NOT NULL DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE event_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    student_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (event_id, student_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE fyp_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    project_title VARCHAR(255) NOT NULL,
    supervisor VARCHAR(150) NOT NULL,
    milestone_status ENUM('Not Started','In Progress','Completed') NOT NULL DEFAULT 'Not Started',
    review_status ENUM('Pending','Approved','Needs Revision') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@cusit.edu', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin'),
('Student User', 'student@cusit.edu', '703b0a3d6ad75b649a28adde7d83c6251da457549263bc7ff45ec709b0a8448b', 'student');

INSERT INTO announcements (title, message) VALUES
('Campus Network Upgrade', 'The campus network will undergo maintenance this weekend from 10 PM to 2 AM. Plan accordingly.'),
('Library Access Extended', 'The campus library hours are extended during final exams for student convenience. Enjoy the quiet study spaces!');

INSERT INTO events (title, description, location, seats_total, seats_taken, event_date, status) VALUES
('Career Fair 2026', 'Attend industry booths from top IT employers and enhance your CV with live feedback.', 'Main Auditorium', 150, 42, '2026-06-20', 'Open'),
('AI Research Workshop', 'Hands-on workshop covering AI tools, best practices, and campus research collaboration.', 'Lab 3', 40, 32, '2026-06-10', 'Open'),
('Campus Health Seminar', 'A seminar on student wellbeing, time management, and mental health resources.', 'Conference Room', 80, 80, '2026-05-25', 'Full');

INSERT INTO complaints (student_id, category, priority, subject, details) VALUES
(2, 'Facilities', 'High', 'Broken Classroom Projector', 'The projector in room 203 is not turning on and classes are affected.'),
(2, 'WiFi', 'Medium', 'Dorm network drops', 'The dormitory WiFi disconnects frequently, especially at night.');

INSERT INTO fyp_groups (student_id, project_title, supervisor, milestone_status, review_status) VALUES
(2, 'Smart Attendance System', 'Dr. Aslam Khan', 'In Progress', 'Pending');
