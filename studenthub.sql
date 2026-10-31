-- =======================================================
-- StudentHub Portal - Complete Database Schema & Seed Data
-- Course: ITUE203 - Web Development Frameworks
-- Practical 15: Final Integration, Testing, Deployment
-- Student: 25CS076 - Patel Pratham Bhavik
-- =======================================================

CREATE DATABASE IF NOT EXISTS `studenthub` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studenthub`;

-- -------------------------------------------------------
-- Table: users
-- -------------------------------------------------------
DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `academic_records`;
DROP TABLE IF EXISTS `feedback`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `course` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('student', 'admin') NOT NULL DEFAULT 'student',
  `city` VARCHAR(50) DEFAULT 'Nadiad',
  `dob` DATE DEFAULT '2006-01-12',
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table: courses
-- -------------------------------------------------------
CREATE TABLE `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_code` VARCHAR(20) NOT NULL UNIQUE,
  `course_name` VARCHAR(100) NOT NULL,
  `credits` INT NOT NULL DEFAULT 4,
  `faculty` VARCHAR(100) NOT NULL,
  `semester` VARCHAR(20) NOT NULL DEFAULT 'Semester 3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table: academic_records
-- -------------------------------------------------------
CREATE TABLE `academic_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(50) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `marks` INT NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Pass',
  FOREIGN KEY (`student_id`) REFERENCES `users` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table: events
-- -------------------------------------------------------
CREATE TABLE `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(100) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `event_date` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `venue` VARCHAR(100) NOT NULL,
  `capacity` INT NOT NULL DEFAULT 100,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table: event_registrations
-- -------------------------------------------------------
CREATE TABLE `event_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `student_id` VARCHAR(50) NOT NULL,
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_registration` (`event_id`, `student_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `users` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table: contact_messages
-- -------------------------------------------------------
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('New', 'Read', 'Replied') DEFAULT 'New',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Table: feedback
-- -------------------------------------------------------
CREATE TABLE `feedback` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `rating` INT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `category` VARCHAR(50) DEFAULT 'General',
  `comments` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- Seed Initial Data
-- =======================================================

-- Users (Admin & Students)
-- Passwords:
-- Admin: Admin@123  ($2y$10$S7QpeNfnrqFYhQzOZd9vV.6zuTYIOZKU1DYd5r.ETwE/nw9sCr2Xe)
-- Students: Student@123 ($2y$10$oqkMpLa3Ecxsq8mnwrM2tOWZJogB/w2ozDZQuWPTO0UdslwnXYwMy)

INSERT INTO `users` (`student_id`, `name`, `email`, `phone`, `course`, `password`, `role`, `city`, `dob`, `status`) VALUES
('ADMIN01', 'System Administrator', 'admin@studenthub.com', '+91 9998887770', 'Administration', '$2y$10$S7QpeNfnrqFYhQzOZd9vV.6zuTYIOZKU1DYd5r.ETwE/nw9sCr2Xe', 'admin', 'Anand', '1995-05-15', 'Active'),
('25CS076', 'Patel Pratham Bhavik', 'student@studenthub.com', '+91 9876543210', 'B.Tech', '$2y$10$oqkMpLa3Ecxsq8mnwrM2tOWZJogB/w2ozDZQuWPTO0UdslwnXYwMy', 'student', 'Nadiad', '2006-01-12', 'Active'),
('101', 'Sapan Desai', 'sapan.desai@studenthub.com', '+91 9822334455', 'BCA', '$2y$10$oqkMpLa3Ecxsq8mnwrM2tOWZJogB/w2ozDZQuWPTO0UdslwnXYwMy', 'student', 'Vadodara', '2005-09-20', 'Active'),
('103', 'Om Patel', 'om.patel@studenthub.com', '+91 9877665544', 'MCA', '$2y$10$oqkMpLa3Ecxsq8mnwrM2tOWZJogB/w2ozDZQuWPTO0UdslwnXYwMy', 'student', 'Ahmedabad', '2004-11-05', 'Inactive'),
('104', 'Aarav Shah', 'aarav.shah@studenthub.com', '+91 9811223344', 'BBA', '$2y$10$oqkMpLa3Ecxsq8mnwrM2tOWZJogB/w2ozDZQuWPTO0UdslwnXYwMy', 'student', 'Surat', '2006-03-18', 'Active');

-- Courses
INSERT INTO `courses` (`course_code`, `course_name`, `credits`, `faculty`, `semester`) VALUES
('ITUE203', 'Web Development Frameworks', 4, 'Prof. CSPIT Faculty', 'Semester 3'),
('ITUE204', 'Database Management Systems', 4, 'Prof. DBMS Faculty', 'Semester 3'),
('CS201', 'Data Structures & Algorithms', 4, 'Prof. DSA Faculty', 'Semester 3'),
('CS202', 'Computer Networks', 3, 'Prof. Networks Faculty', 'Semester 3'),
('CS205', 'Discrete Mathematics', 3, 'Prof. Math Faculty', 'Semester 3'),
('HS101', 'Professional Communication', 2, 'Prof. Humanities Faculty', 'Semester 3');

-- Academic Records for 25CS076
INSERT INTO `academic_records` (`student_id`, `subject`, `marks`, `status`) VALUES
('25CS076', 'Web Development Frameworks', 85, 'Pass'),
('25CS076', 'Database Management Systems', 90, 'Pass'),
('25CS076', 'Programming & Data Structures', 88, 'Pass'),
('25CS076', 'Computer Networks', 91, 'Pass'),
('25CS076', 'Discrete Mathematics', 82, 'Pass'),
('25CS076', 'Professional Communication', 89, 'Pass');

-- Events
INSERT INTO `events` (`id`, `title`, `category`, `event_date`, `description`, `venue`, `capacity`) VALUES
(1, 'Independence Day Celebration', 'National Event', '15 August 2026', 'Cultural Program, Patriotic Song Competition, Speech, and Flag Hoisting Ceremony.', 'Central Auditorium', 250),
(2, 'Tech Fest 2026', 'Technical Festival', '20 September 2026', 'Coding Hackathon, Robotics Arena, Web Development Challenge, and AI Workshop.', 'Tech Arena Lab 3', 300),
(3, 'Annual Sports Meet', 'Sports Event', '10 October 2026', 'Cricket League, Football Championship, Badminton, Table Tennis, and Athletics.', 'University Sports Complex', 200),
(4, 'Cultural Youth Festival', 'Cultural Event', '05 November 2026', 'Dance, Drama, Musical Night, Classical Singing, and Fashion Showcase.', 'Open Air Amphitheatre', 400);

-- Event Registrations
INSERT INTO `event_registrations` (`event_id`, `student_id`) VALUES
(1, '25CS076'),
(2, '25CS076');

-- Contact Messages
INSERT INTO `contact_messages` (`name`, `email`, `subject`, `message`, `status`) VALUES
('Patel Pratham Bhavik', 'student@studenthub.com', 'Exam Hall Ticket Inquiry', 'Hello, when will the odd semester examination hall tickets be available for download?', 'Read'),
('Rohan Sharma', 'rohan@example.com', 'Hostel Admission Details', 'Please provide details about the hostel accommodation and fee structure for third semester.', 'New'),
('Ananya Verma', 'ananya@example.com', 'Library Card Renewal', 'Requesting extension for borrowing books during the semester exam preparation period.', 'New');

-- Feedback
INSERT INTO `feedback` (`name`, `email`, `rating`, `category`, `comments`) VALUES
('Patel Pratham Bhavik', 'student@studenthub.com', 5, 'Portal Usability', 'StudentHub Portal makes tracking academic attendance, grades, and event registrations effortless! The dark mode is great.'),
('Sapan Desai', 'sapan.desai@studenthub.com', 4, 'Feature Request', 'Great UI design. Would love to see an integrated mobile push notification system for upcoming events.'),
('Kavya Mehta', 'kavya@example.com', 5, 'Academics', 'The course overview and result dashboard are very intuitive and quick to load.');
