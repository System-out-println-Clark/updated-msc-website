-- Create database
CREATE DATABASE IF NOT EXISTS student_portal;
USE student_portal;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    msc_id VARCHAR(32) UNIQUE,
    role ENUM('member', 'officer') DEFAULT 'member', 

    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,

    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    name_suffix VARCHAR(20),
    birthdate DATE,
    gender ENUM('Male', 'Female', 'Other'),

    student_no VARCHAR(50),
    year_level VARCHAR(20),
    college VARCHAR(100),
    program VARCHAR(100),
    section VARCHAR(50),
    address TEXT,
    phone VARCHAR(20),
    facebook_link VARCHAR(255),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    profile_image_path TEXT,
    is_active BOOLEAN
);

-- Create events table
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    event_time_start TIME,
    event_time_end TIME,
    location TEXT,
    event_type ENUM('onsite', 'online', 'hybrid') DEFAULT 'onsite',
    registration_required BOOLEAN DEFAULT FALSE,
    event_status ENUM('upcoming', 'canceled', 'completed') DEFAULT 'upcoming',
    description TEXT,
    event_image_url TEXT,
    event_batch_image TEXT,
    attendants INT DEFAULT 0,
    event_restriction ENUM('public', 'members', 'officers') DEFAULT 'public',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    date_posted DATETIME DEFAULT CURRENT_TIMESTAMP,
    posted_by VARCHAR(100) DEFAULT 'Admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_archived BOOLEAN DEFAULT FALSE,
    image_url VARCHAR(255) DEFAULT NULL
);

-- Create settings table for system configuration
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create event_registrations table for tracking registrations
CREATE TABLE IF NOT EXISTS event_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    student_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attendance_status ENUM('registered', 'attended', 'absent') DEFAULT 'registered',
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, student_id)
);

-- Create password_resets table for password recovery
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--Create committees table for msc committees
CREATE TABLE IF NOT EXISTS committees (
    committee_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    head_id INT(11),
    FOREIGN KEY(head_id) REFERENCES students(id)
);

--Create committee_members table for linking students to committees
CREATE TABLE IF NOT EXISTS committee_members (
    officer_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    id INT(11) NOT NULL,
    committee_id INT(11) NOT NULL,
    position VARCHAR(100),
    start_date DATE NOT NULL,
    end_date DATE,
    FOREIGN KEY(id) REFERENCES students(id),
    FOREIGN KEY(committee_id) REFERENCES committees(committee_id)
);

-- Insert default settings
INSERT INTO settings (key_name, value, description) VALUES
('school_year_code', '2526', 'Current school year code for ID generation'),
('system_name', 'MSC Student Portal', 'Name of the system'),
('admin_email', 'admin@example.com', 'Administrator email address')
ON DUPLICATE KEY UPDATE value = VALUES(value);

-- TESTING: University Calendar
-- Create university_calendar table
CREATE TABLE IF NOT EXISTS university_calendar (
    calendar_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    event_type ENUM('Academic Holiday', 'Academic Event', 'University Event') NOT NULL,
    school_year VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Auto-insertions for university_calendar from January to December 2025
-- January 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('New Year''s Day', '2025-01-01', 'Academic Holiday', '2024-2025'),
('Return of PVP Faculty', '2025-01-06', 'Academic Event', '2024-2025'),
('Online Enrollment Period', '2025-01-07', 'Academic Event', '2024-2025'),
('Start of Classes (2nd Semester A.Y. 2024 - 2025)', '2025-01-13', 'Academic Event', '2024-2025'),
('Change of Matriculation', '2025-01-13', 'Academic Event', '2024-2025'),
('Araw ng Republikang Filipino', '2025-01-23', 'Academic Holiday', '2024-2025'),
('Chinese Lunar New Year''s Day', '2025-01-29', 'Academic Holiday', '2024-2025');

-- February 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Edsa Revolution Anniversary Day', '2025-02-25', 'Academic Holiday', '2024-2025');

-- March 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Mid-Term Examination (2nd Semester AY 2024 - 2025)', '2025-03-10', 'Academic Event', '2024-2025'),
('Dropping with Evaluation / Start of Application of LOA', '2025-03-24', 'Academic Event', '2024-2025'),
('Eid al-Fitr (Festival of Breaking the Fast)', '2025-03-31', 'Academic Holiday', '2024-2025');

-- April 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Day of Valor (Araw ng Kagitingan)', '2025-04-09', 'Academic Holiday', '2024-2025'),
('Health Break', '2025-04-14', 'Academic Event', '2024-2025'),
('Maundy Thursday', '2025-04-17', 'Academic Holiday', '2024-2025'),
('Good Friday', '2025-04-18', 'Academic Holiday', '2024-2025'),
('Black Saturday', '2025-04-19', 'Academic Holiday', '2024-2025'),
('Easter Sunday', '2025-04-20', 'Academic Holiday', '2024-2025');

-- May 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Labor Day', '2025-05-01', 'Academic Holiday', '2024-2025'),
('Final Examination (Graduating Students)', '2025-05-05', 'Academic Event', '2024-2025'),
('Posting of Grades (Graduating Students)', '2025-05-12', 'Academic Event', '2024-2025'),
('Final Examination (Non - Graduating Students)', '2025-05-19', 'Academic Event', '2024-2025'),
('End of Classes (2nd Semester A.Y. 2024 - 2025)', '2025-05-24', 'Academic Event', '2024-2025'),
('Local Academic Deliberation', '2025-05-26', 'Academic Event', '2024-2025'),
('Posting of Grades (Non - Graduating Students)', '2025-05-26', 'Academic Event', '2024-2025'),
('Application of Petitioned Classes for Mid - Year Classes', '2025-05-26', 'Academic Event', '2024-2025');

-- June 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Enrollment Period for Mid - Year Classes', '2025-06-02', 'Academic Event', '2024-2025'),
('Eid al-Adha (Feast of Sacrifice)', '2025-06-06', 'Academic Holiday', '2024-2025'),
('Start of Mid - Year Classes', '2025-06-09', 'Academic Event', '2024-2025'),
('Change of Matriculation for Mid - Year Classes', '2025-06-09', 'Academic Event', '2024-2025');

-- July 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Online Enrollment Period for 3rd Year Students', '2025-07-07', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 2nd Year Students', '2025-07-10', 'Academic Event', '2025-2026'),
('Regular Graduation (84th Commencement Exercises)', '2025-07-14', 'University Event', '2024-2025'),
('Online Enrollment Period for New Students', '2025-07-14', 'Academic Event', '2025-2026'),
('Final Examination (Mid - Year Classes)', '2025-07-17', 'Academic Event', '2024-2025'),
('End of Mid - Year Classes', '2025-07-19', 'Academic Event', '2024-2025'),
('Start of 1st Semester A.Y. 2025 - 2026', '2025-07-21', 'Academic Event', '2025-2026'),
('Posting of Grades (Mid - Year Classes)', '2025-07-21', 'Academic Event', '2024-2025'),
('Online Enrollment Period for Irregular Students', '2025-07-24', 'Academic Event', '2025-2026'),
('Change of Matriculation for 1st semester', '2025-07-24', 'Academic Event', '2025-2026');

-- August 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Local Academic Deliberation (Mid - Year Classes)', '2025-08-01', 'Academic Event', '2024-2025'),
('Bulacan Foundation Day', '2025-08-15', 'University Event', '2025-2026'),
('University Academic Council', '2025-08-18', 'Academic Event', '2025-2026'),
('Ninoy Aquino Day', '2025-08-21', 'Academic Holiday', '2025-2026'),
('National Heroes Day', '2025-08-25', 'Academic Holiday', '2025-2026'),
('Marcelo H. del Pilar Day', '2025-08-30', 'Academic Holiday', '2025-2026');

-- September 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('127th Anniversary of the Malolos Congress', '2025-09-15', 'University Event', '2025-2026'),
('Mid-term Examination (1st semester A.Y. 2025-2026)', '2025-09-16', 'Academic Event', '2025-2026'),
('Last week of Schedule of dropping with evaluation / submission application of LOA', '2025-09-23', 'Academic Event', '2025-2026');

-- October 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('University Health Break', '2025-10-06', 'Academic Event', '2025-2026'),
('Faculty Appreciation Day', '2025-10-10', 'University Event', '2025-2026'),
('Application for Admission (Incoming freshmen for 1st Semester A.Y. 2026-2027)', '2025-10-13', 'Academic Event', '2025-2026'),
('University Intramurals', '2025-10-20', 'University Event', '2025-2026');

-- November 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('All Saints'' Day', '2025-11-01', 'Academic Holiday', '2025-2026'),
('All Souls'' Day', '2025-11-02', 'Academic Holiday', '2025-2026'),
('Final Examination (1st Semester A.Y. 2025-2026)', '2025-11-16', 'Academic Event', '2025-2026'),
('End of classes for 1st Semester A.Y. 2025-2026', '2025-11-22', 'Academic Event', '2025-2026'),
('Online Posting of Grades', '2025-11-24', 'Academic Event', '2025-2026'),
('Online enrollment period', '2025-11-27', 'Academic Event', '2025-2026'),
('Bonifacio Day', '2025-11-30', 'Academic Holiday', '2025-2026');

-- December 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Start of Classes 2nd Semester A.Y. 2025-2026', '2025-12-01', 'Academic Event', '2025-2026'),
('Change of Matriculation', '2025-12-01', 'Academic Event', '2025-2026'),
('University Foundation Week', '2025-12-01', 'University Event', '2025-2026'),
('Local Academic Deliberation (Mid-Academic Year Graduation)', '2025-12-09', 'Academic Event', '2025-2026'),
('University-wide Christmas Party', '2025-12-19', 'University Event', '2025-2026'),
('Christmas Day', '2025-12-25', 'Academic Holiday', '2025-2026'),
('Rizal Day', '2025-12-30', 'Academic Holiday', '2025-2026'),
('Last day of the year', '2025-12-31', 'Academic Holiday', '2025-2026');