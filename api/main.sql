-- Create database
CREATE DATABASE IF NOT EXISTS student_portal;
USE student_portal;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    msc_id VARCHAR(32) UNIQUE,
    role ENUM('member', 'officer', 'admin') DEFAULT 'member', 

    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    bulsu_email VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    password_updated TINYINT(1) DEFAULT 0,

    full_name VARCHAR(250),
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    middle_initial VARCHAR(5),
    last_name VARCHAR(100),
    name_suffix VARCHAR(20),
    age INT NOT NULL,
    birthdate DATE,
    gender ENUM('Male', 'Female', 'Other'),

    guardian_name VARCHAR(250) NOT NULL,
    relationship VARCHAR(50) NOT NULL,
    guardian_phone VARCHAR(20) NOT NULL,
    guardian_address TEXT,
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
    event_restriction ENUM('public', 'members', 'bulsuans', 'inviteOnly') DEFAULT 'public',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    capacity INT DEFAULT 0
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
    qr_code VARCHAR(50), 
    event_id INT NOT NULL,
    student_id INT NULL,
    first_name VARCHAR(100),
    middle_name VARCHAR(100), 
    last_name VARCHAR(100),
    suffix VARCHAR(20), 
    email VARCHAR(255),
    gender ENUM('Male', 'Female', 'Other'), 
    phone VARCHAR(20), 
    facebook_link VARCHAR(255), 
    participant_type ENUM('member', 'bulsuan', 'guest', 'officer') DEFAULT 'guest',
    program VARCHAR(255),
    college VARCHAR(255),
    year_level VARCHAR(50),
    section VARCHAR(50),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attendance_status ENUM('registered', 'attended', 'absent') DEFAULT 'registered',
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    UNIQUE KEY unique_registration (event_id, student_id),
    INDEX idx_qr_code (qr_code) 
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

-- Create committees table for msc committees
CREATE TABLE IF NOT EXISTS committees (
    committee_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    head_id INT(11),
    FOREIGN KEY(head_id) REFERENCES students(id)
);

-- Create committee_members table for linking students to committees
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
-- MAY 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('First Session of A Classes of 2nd Trimester A.Y. 2024-2025', '2025-05-03', 'Academic Event', '2024-2025'),
('Enrollment of Thesis-Dissertation, Writing/Comprehensive Examinations and New in Writing', '2025-05-05', 'Academic Event', '2024-2025'),
('Adding/Changing B/C Subjects, Dropping of Subjects (Graduate School)', '2025-05-10', 'Academic Event', '2024-2025'),
('Enrollment of Grade 7 Students', '2025-05-19', 'Academic Event', '2024-2025'),
('Enrollment of Grade 8 Students', '2025-05-20', 'Academic Event', '2024-2025'),
('Enrollment of Grade 9 Students', '2025-05-21', 'Academic Event', '2024-2025'),
('Enrollment of Grade 10 Students', '2025-05-22', 'Academic Event', '2024-2025'),
('Late Enrollees (Laboratory High School)', '2025-05-23', 'Academic Event', '2024-2025'),
('Orientation of Comprehensive Exam Takers, Thesis/Dissertation/Practice-Based/Capstone Project Writing', '2025-05-24', 'Academic Event', '2024-2025'),
('Review for Comprehensive Exam Takers', '2025-05-26', 'Academic Event', '2024-2025'),
('Last Session of A Classes of 2nd Trimester A.Y. 2024-2025', '2025-05-31', 'Academic Event', '2024-2025');

-- JUNE 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Online Enrollment for Post Academic Year Classes A.Y. 2024-2025', '2025-06-02', 'Academic Event', '2024-2025'),
('EID AL-ADHA – Feast of Sacrifice', '2025-06-06', 'Academic Holiday', '2024-2025'),
('First Session of B Classes of 2nd Trimester A.Y. 2024-2025', '2025-06-07', 'Academic Event', '2024-2025'),
('Start of Post Academic Year Classes A.Y. 2024-2025', '2025-06-09', 'Academic Event', '2024-2025'),
('Closing Ceremony & Recognition (Graduate School)', '2025-06-11', 'Academic Event', '2024-2025'),
('Submission of Entry Requirements of Qualified First Year Students and Online Enrollment for 1st Semester A.Y. 2025-2026', '2025-06-09', 'Academic Event', '2025-2026'),
('University Academic Council (Regular Graduation A.Y. 2024-2025)', '2025-06-10', 'Academic Event', '2024-2025'),
('Philippine Independence Day', '2025-06-12', 'Academic Holiday', '2024-2025'),
('Day 1 Comprehensive Examination (Graduate School)', '2025-06-13', 'Academic Event', '2024-2025'),
('Deadline for Application for Admission (Laboratory High School)', '2025-06-16', 'Academic Event', '2025-2026'),
('GSAT Applications for 3rd Trimester A.Y. 2024-2025', '2025-06-16', 'Academic Event', '2024-2025');

-- JULY 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Enrollment of Freshman and Incoming Second Year Medical Students', '2025-07-01', 'Academic Event', '2025-2026'),
('Last Session of B Classes of 2nd Trimester A.Y. 2024-2025', '2025-07-05', 'Academic Event', '2024-2025'),
('Application of Petitioned Classes for 1st Semester A.Y. 2025-2026', '2025-07-07', 'Academic Event', '2025-2026'),
('First Session of C Classes of 2nd Trimester A.Y. 2024-2025', '2025-07-12', 'Academic Event', '2024-2025'),
('GS Admission Test for 3rd Trimester A.Y. 2024-2025', '2025-07-13', 'Academic Event', '2024-2025'),
('Regular Graduation A.Y. 2024-2025 (84th Commencement Exercises)', '2025-07-14', 'Academic Event', '2024-2025'),
('Interview of GSAT Passers', '2025-07-16', 'Academic Event', '2024-2025'),
('Online Enrollment Period for 2nd Year Regular Students', '2025-07-16', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 3rd Year Regular Students', '2025-07-17', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 4th and 5th Year Regular Students', '2025-07-18', 'Academic Event', '2025-2026'),
('Online Enrollment for Irregular/Shiftee/Transferee/Second Degree Undergraduates', '2025-07-19', 'Academic Event', '2025-2026'),
('Final Examination (Post Academic Year Classes A.Y. 2024-2025)', '2025-07-17', 'Academic Event', '2024-2025'),
('End of Classes for Post Academic Year A.Y. 2024-2025', '2025-07-19', 'Academic Event', '2024-2025'),
('Start of Classes for 1st Semester A.Y. 2025-2026', '2025-07-21', 'Academic Event', '2025-2026');

-- AUGUST 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Local Academic Deliberation (Post Academic Year Graduation A.Y. 2024-2025)', '2025-08-01', 'Academic Event', '2024-2025'),
('Enrollment Period for 1st Semester A.Y. 2025-2026', '2025-08-04', 'Academic Event', '2025-2026'),
('Freshmen Orientation for 1st Semester A.Y. 2025-2026', '2025-08-09', 'Academic Event', '2025-2026'),
('Start of Classes for 1st Semester A.Y. 2025-2026', '2025-08-11', 'Academic Event', '2025-2026'),
('First Periodical Exam (Graduate School)', '2025-08-13', 'Academic Event', '2025-2026'),
('Bulacan Foundation Day', '2025-08-15', 'Academic Holiday', '2025-2026'),
('University Academic Council (Post Academic Year Graduation A.Y. 2024-2025)', '2025-08-16', 'Academic Event', '2024-2025'),
('Ninoy Aquino Day', '2025-08-21', 'Academic Holiday', '2025-2026'),
('First Session of A Classes of 3rd Trimester A.Y. 2024-2025', '2025-08-23', 'Academic Event', '2024-2025'),
('National Heroes Day', '2025-08-26', 'Academic Holiday', '2025-2026'),
('Marcelo H. Del Pilar Day', '2025-08-30', 'Academic Holiday', '2025-2026');

-- SEPTEMBER 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Meeting with Parents and Election of PTA Officers (LHS)', '2025-09-05', 'Academic Event', '2025-2026'),
('Preliminary Examination', '2025-09-08', 'Academic Event', '2025-2026'),
('Feast of Nativity of the Blessed Virgin Mary', '2025-09-08', 'Academic Holiday', '2025-2026'),
('127th Anniversary of the Malolos Congress', '2025-09-15', 'Academic Event', '2025-2026'),
('Midterm Examination (1st Semester A.Y. 2025-2026)', '2025-09-16', 'Academic Event', '2025-2026'),
('Medicine Week Celebration', '2025-09-17', 'Academic Event', '2025-2026'),
('National Law Day', '2025-09-19', 'Academic Event', '2025-2026'),
('Last Session of A Classes of 3rd Trimester A.Y. 2024-2025', '2025-09-27', 'Academic Event', '2024-2025');

-- OCTOBER 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Last Day of Dropping of Subjects (JD and MLS)', '2025-10-03', 'Academic Event', '2025-2026'),
('First Session of B Classes of 3rd Trimester A.Y. 2024-2025', '2025-10-04', 'Academic Event', '2024-2025'),
('Midterm Mock Bar Examinations Day 1 (JD 4th Year)', '2025-10-05', 'Academic Event', '2025-2026'),
('University Health Break', '2025-10-06', 'Academic Holiday', '2025-2026'),
('Celebration of Teacher’s Day (Laboratory High School)', '2025-10-07', 'Academic Event', '2025-2026'),
('BulSU Faculty Appreciation Day', '2025-10-10', 'Academic Event', '2025-2026'),
('Second Periodical Exam (Graduate School)', '2025-10-16', 'Academic Event', '2025-2026'),
('University Intramurals 2025', '2025-10-20', 'Academic Event', '2025-2026'),
('Graduate School Admission Test for 1st Trimester A.Y. 2025-2026', '2025-10-26', 'Academic Event', '2025-2026');

-- NOVEMBER 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('All Saints Day', '2025-11-01', 'Academic Holiday', '2025-2026'),
('All Souls Day (Observance)', '2025-11-02', 'Academic Event', '2025-2026'),
('Last Session of B Classes of 3rd Trimester A.Y. 2024-2025', '2025-11-08', 'Academic Event', '2024-2025'),
('First Session of C Classes of 3rd Trimester A.Y. 2024-2025', '2025-11-15', 'Academic Event', '2024-2025'),
('Final Examination (1st Semester A.Y. 2025-2026)', '2025-11-16', 'Academic Event', '2025-2026'),
('End of Classes for 1st Semester A.Y. 2025-2026', '2025-11-22', 'Academic Event', '2025-2026'),
('Encoding of Grades for 3rd Trimester A.Y. 2024-2025 for Classes A and B', '2025-11-17', 'Academic Event', '2024-2025'),
('Enrollment of Continuing Students', '2025-11-24', 'Academic Event', '2025-2026'),
('Online Posting of Grades (1st Semester A.Y. 2025-2026)', '2025-11-24', 'Academic Event', '2025-2026'),
('Application of Petitioned Classes for 2nd Semester A.Y. 2025-2026', '2025-11-27', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 1st & 2nd Year Regular Students', '2025-11-27', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 3rd, 4th & 5th Year Regular Students', '2025-11-28', 'Academic Event', '2025-2026'),
('Online Enrollment Period for Irregular Students, Late Enrollees & with Approved Petition Classes', '2025-11-29', 'Academic Event', '2025-2026'),
('Enrollment of Thesis-Dissertation Writing/Comprehensive Examinations and New in Writing', '2025-11-24', 'Academic Event', '2025-2026'),
('Bonifacio Day (Regular Holiday)', '2025-11-30', 'Academic Holiday', '2025-2026'),
('Onsite Enrollment for New Students for the 1st Trimester A.Y. 2025-2026', '2025-11-30', 'Academic Event', '2025-2026');

-- DECEMBER 2025
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Start of Classes for 2nd Semester A.Y. 2025-2026', '2025-12-01', 'Academic Event', '2025-2026'),
('Change of Matriculation (2nd Semester A.Y. 2025-2026)', '2025-12-01', 'Academic Event', '2025-2026'),
('University Foundation Week 2024 / LHS Week', '2025-12-01', 'Academic Event', '2025-2026'),
('Final Mock Bar Examinations Day 1 (JD 4th Year)', '2025-12-07', 'Academic Event', '2025-2026'),
('Feast of the Immaculate Conception (Special Non-Working Holiday)', '2025-12-08', 'Academic Holiday', '2025-2026'),
('Local Academic Deliberation (Mid – Academic Year Graduation A.Y. 2025-2026)', '2025-12-09', 'Academic Event', '2025-2026'),
('Final Examinations Week (JD 1st to 3rd Years)', '2025-12-09', 'Academic Event', '2025-2026'),
('Final Mock Bar Examinations Day 2 (JD 4th Year)', '2025-12-10', 'Academic Event', '2025-2026'),
('Comprehensive Examinations Week (MLS)', '2025-12-11', 'Academic Event', '2025-2026'),
('Final Mock Bar Examinations Day 3 (JD 4th Year)', '2025-12-14', 'Academic Event', '2025-2026'),
('Last Session of C Classes of 3rd Trimester A.Y. 2024-2025', '2025-12-15', 'Academic Event', '2024-2025'),
('Encoding of Grades for 3rd Trimester A.Y. 2024-2025 for C Classes', '2025-12-15', 'Academic Event', '2024-2025'),
('Final Examinations (Revalida) Week (JD 4th Year)', '2025-12-15', 'Academic Event', '2025-2026'),
('Remedial/Special Exam if granted', '2025-12-16', 'Academic Event', '2025-2026'),
('Departmental Examinations Week (JD 1st to 3rd Years)', '2025-12-16', 'Academic Event', '2025-2026'),
('LHS Homeroom Christmas Party', '2025-12-17', 'Academic Event', '2025-2026'),
('Orientation for New Students', '2025-12-18', 'Academic Event', '2025-2026'),
('University-wide Christmas Party 2025', '2025-12-19', 'Academic Event', '2025-2026'),
('First Session of A Classes of 1st Trimester A.Y. 2025-2026 / Onsite Classes (2nd meeting/PM – Year End Party)', '2025-12-20', 'Academic Event', '2025-2026'),
('Christmas Break', '2025-12-22', 'Academic Holiday', '2025-2026'),
('Christmas Day (Regular Holiday)', '2025-12-25', 'Academic Holiday', '2025-2026'),
('Rizal Day (Regular Holiday)', '2025-12-30', 'Academic Holiday', '2025-2026'),
('Last Day of the Year (New Year’s Eve) – Special Non-Working Holiday', '2025-12-31', 'Academic Holiday', '2025-2026');

-- JANUARY 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('New Year’s Day (Regular Holiday)', '2026-01-01', 'Academic Holiday', '2025-2026'),
('Resumption of Classes', '2026-01-05', 'Academic Event', '2025-2026'),
('Last Day for Late Enrollment', '2026-01-10', 'Academic Event', '2025-2026'),
('First Session of B Classes of 1st Trimester A.Y. 2025-2026', '2026-01-10', 'Academic Event', '2025-2026'),
('University Research Colloquium', '2026-01-12', 'Academic Event', '2025-2026'),
('Last Day of Encoding of Grades for 3rd Trimester A.Y. 2024-2025 (Classes A, B & C)', '2026-01-13', 'Academic Event', '2024-2025'),
('Deadline of Filing Application for Graduation (2nd Semester A.Y. 2025-2026)', '2026-01-16', 'Academic Event', '2025-2026'),
('Last Day for Change of Matriculation', '2026-01-17', 'Academic Event', '2025-2026'),
('Feast of Sto. Niño (Cebu Holiday)', '2026-01-18', 'Academic Holiday', '2025-2026'),
('Start of Midterm Examinations', '2026-01-19', 'Academic Event', '2025-2026'),
('End of Midterm Examinations', '2026-01-24', 'Academic Event', '2025-2026'),
('LHS Parent-Teacher Conference', '2026-01-24', 'Academic Event', '2025-2026'),
('Last Session of A Classes of 1st Trimester A.Y. 2025-2026', '2026-01-31', 'Academic Event', '2025-2026');

-- FEB 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('First Session of C Classes of 1st Trimester A.Y. 2025-2026', '2026-02-01', 'Academic Event', '2025-2026'),
('Chinese New Year (Special Non-Working Holiday)', '2026-02-17', 'Academic Holiday', '2025-2026'),
('EDSA People Power Revolution Anniversary (Special Non-Working Holiday)', '2026-02-25', 'Academic Holiday', '2025-2026');

-- MARCH 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Mid-Year Academic Graduation Rites', '2026-03-01', 'Academic Event', '2025-2026'),
('Final Examinations for Graduating Students', '2026-03-02', 'Academic Event', '2025-2026'),
('Deadline for Submission of Grades for Graduating Students', '2026-03-06', 'Academic Event', '2025-2026'),
('Local Academic Deliberation (2nd Semester A.Y. 2025-2026)', '2026-03-09', 'Academic Event', '2025-2026'),
('University Academic Council Meeting', '2026-03-11', 'Academic Event', '2025-2026'),
('University Senate Meeting', '2026-03-12', 'Academic Event', '2025-2026'),
('University Recognition Day', '2026-03-13', 'Academic Event', '2025-2026'),
('LHS Recognition Rites', '2026-03-14', 'Academic Event', '2025-2026'),
('University Commencement Exercises', '2026-03-15', 'Academic Event', '2025-2026'),
('Last Session of B Classes of 1st Trimester A.Y. 2025-2026', '2026-03-21', 'Academic Event', '2025-2026'),
('Final Examinations for All Students (2nd Semester A.Y. 2025-2026)', '2026-03-23', 'Academic Event', '2025-2026'),
('End of Classes for 2nd Semester A.Y. 2025-2026', '2026-03-28', 'Academic Event', '2025-2026');

-- APRIL 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Holy Thursday (Regular Holiday)', '2026-04-02', 'Academic Holiday', '2025-2026'),
('Good Friday (Regular Holiday)', '2026-04-03', 'Academic Holiday', '2025-2026'),
('Black Saturday (Special Non-Working Holiday)', '2026-04-04', 'Academic Holiday', '2025-2026'),
('Araw ng Kagitingan (Regular Holiday)', '2026-04-09', 'Academic Holiday', '2025-2026'),
('Last Session of C Classes of 1st Trimester A.Y. 2025-2026', '2026-04-11', 'Academic Event', '2025-2026'),
('Start of Summer Classes (A.Y. 2025-2026)', '2026-04-13', 'Academic Event', '2025-2026'),
('End of Encoding of Grades for 1st Trimester A.Y. 2025-2026 (Classes A, B & C)', '2026-04-18', 'Academic Event', '2025-2026'),
('Labor Day (Regular Holiday)', '2026-04-30', 'Academic Holiday', '2025-2026');

-- MAY 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Labor Day (Regular Holiday)', '2026-05-01', 'Academic Holiday', '2025-2026'),
('First Session of A Classes for 2nd Trimester A.Y. 2025-2026', '2026-05-02', 'Academic Event', '2025-2026'),
('Start of Classes for Post Academic Year A.Y. 2025-2026', '2026-05-04', 'Academic Event', '2025-2026'),
('University Academic Council (Regular Graduation A.Y. 2025-2026)', '2026-05-08', 'Academic Event', '2025-2026'),
('Change of Matriculation for Post Academic Year Classes A.Y. 2025-2026', '2026-05-05', 'Academic Event', '2025-2026'),
('Change of Matriculation for Post Academic Year Classes A.Y. 2025-2026', '2026-05-06', 'Academic Event', '2025-2026'),
('Change of Matriculation for Post Academic Year Classes A.Y. 2025-2026', '2026-05-07', 'Academic Event', '2025-2026'),
('Submission of Entry Requirements of Qualified First Year Student and Online Enrollment (Freshmen) for 1st Semester A.Y. 2026-2027', '2026-05-13', 'Academic Event', '2025-2026'),
('Submission of Entry Requirements of Qualified First Year Student and Online Enrollment (Freshmen) for 1st Semester A.Y. 2026-2027', '2026-05-14', 'Academic Event', '2025-2026'),
('Submission of Entry Requirements of Qualified First Year Student and Online Enrollment (Freshmen) for 1st Semester A.Y. 2026-2027', '2026-05-15', 'Academic Event', '2025-2026'),
('Submission of Entry Requirements of Qualified First Year Student and Online Enrollment (Freshmen) for 1st Semester A.Y. 2026-2027', '2026-05-16', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-04', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-05', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-06', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-07', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-08', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-09', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-10', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-11', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-12', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-13', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-14', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-15', 'Academic Event', '2025-2026'),
('Dropping of Subject/s (Graduate School)', '2026-05-16', 'Academic Event', '2025-2026'),
('Orientation of Comprehensive Exam Takers, Thesis/Dissertation/Practice-based/Capstone Project Writing', '2026-05-16', 'Academic Event', '2025-2026'),
('Midterm Mock Bar Examinations Day 1 (JD 4th Year)', '2026-05-17', 'Academic Event', '2025-2026'),
('Midterm Mock Bar Examinations Day 2 (JD 4th Year)', '2026-05-23', 'Academic Event', '2025-2026'),
('Midterm Mock Bar Examinations Day 3 (JD 4th Year)', '2026-05-24', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 2nd Year Regular Students without Post Academic Year Classes', '2026-05-24', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 2nd Year Regular Students without Post Academic Year Classes', '2026-05-25', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 2nd Year Regular Students without Post Academic Year Classes', '2026-05-26', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 2nd Year Regular Students without Post Academic Year Classes', '2026-05-27', 'Academic Event', '2025-2026'),
('Special/Remedial Exams', '2026-05-25', 'Academic Event', '2025-2026'),
('Special/Remedial Exams', '2026-05-26', 'Academic Event', '2025-2026'),
('Oral Examinations (Revalida) Week (JD 4th Year)', '2026-05-25', 'Academic Event', '2025-2026'),
('Oral Examinations (Revalida) Week (JD 4th Year)', '2026-05-26', 'Academic Event', '2025-2026'),
('Oral Examinations (Revalida) Week (JD 4th Year)', '2026-05-27', 'Academic Event', '2025-2026'),
('Oral Examinations (Revalida) Week (JD 4th Year)', '2026-05-28', 'Academic Event', '2025-2026'),
('Oral Examinations (Revalida) Week (JD 4th Year)', '2026-05-29', 'Academic Event', '2025-2026'),
('Final Examinations Week (JD 1st to 3rd Years)', '2026-05-25', 'Academic Event', '2025-2026'),
('Final Examinations Week (JD 1st to 3rd Years)', '2026-05-26', 'Academic Event', '2025-2026'),
('Final Examinations Week (JD 1st to 3rd Years)', '2026-05-27', 'Academic Event', '2025-2026'),
('Final Examinations Week (JD 1st to 3rd Years)', '2026-05-28', 'Academic Event', '2025-2026'),
('Final Examinations Week (JD 1st to 3rd Years)', '2026-05-29', 'Academic Event', '2025-2026'),
('EID AL-ADHA - Feast of the Sacrifice (Regular Holiday)', '2026-05-27', 'Academic Holiday', '2025-2026'),
('Online Posting of Grades (College of Medicine)', '2026-05-27', 'Academic Event', '2025-2026'),
('Comprehensive Examinations Week (MLS)', '2026-05-28', 'Academic Event', '2025-2026'),
('Comprehensive Examinations Week (MLS)', '2026-05-29', 'Academic Event', '2025-2026'),
('Comprehensive Examinations Week (MLS)', '2026-05-30', 'Academic Event', '2025-2026'),
('Comprehensive Examinations Week (MLS)', '2026-05-31', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 3rd Year Regular Students without Post Academic Year Classes', '2026-05-28', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 3rd Year Regular Students without Post Academic Year Classes', '2026-05-29', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 3rd Year Regular Students without Post Academic Year Classes', '2026-05-30', 'Academic Event', '2025-2026'),
('Online Enrollment Period for 3rd Year Regular Students without Post Academic Year Classes', '2026-05-31', 'Academic Event', '2025-2026'),
('Last Session of A Classes of 2nd Trimester A.Y. 2025-2026', '2026-05-30', 'Academic Event', '2025-2026');

-- JUNE 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Enrollment Period for 4th Year Regular Students without Post Academic Year Classes', '2026-06-01', 'Academic Event', '2025-2026'),
('Enrollment Period for 4th Year Regular Students without Post Academic Year Classes', '2026-06-02', 'Academic Event', '2025-2026'),
('Enrollment Period for 4th Year Regular Students without Post Academic Year Classes', '2026-06-03', 'Academic Event', '2025-2026'),
('Enrollment Period for 4th Year Regular Students without Post Academic Year Classes', '2026-06-04', 'Academic Event', '2025-2026'),
('Independence Day (Regular Holiday)', '2026-06-12', 'Academic Holiday', '2025-2026'),
('First Periodical Exam (Post Academic Year Classes)', '2026-06-18', 'Academic Event', '2025-2026'),
('First Periodical Exam (Post Academic Year Classes)', '2026-06-19', 'Academic Event', '2025-2026'),
('Last Session of A Classes of 2nd Trimester A.Y. 2025-2026', '2026-06-20', 'Academic Event', '2025-2026'),
('End of Classes (Graduate School)', '2026-06-21', 'Academic Event', '2025-2026'),
('Online Posting of Grades (Graduate School)', '2026-06-24', 'Academic Event', '2025-2026'),
('Last Session of B Classes of 2nd Trimester A.Y. 2025-2026', '2026-06-27', 'Academic Event', '2025-2026');

-- JULY 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Pre-Finals Examinations (Post Academic Year Classes)', '2026-07-02', 'Academic Event', '2025-2026'),
('Pre-Finals Examinations (Post Academic Year Classes)', '2026-07-03', 'Academic Event', '2025-2026'),
('Charter Day', '2026-07-05', 'Academic Holiday', '2025-2026'),
('Final Examinations Week (Post Academic Year Classes)', '2026-07-09', 'Academic Event', '2025-2026'),
('Final Examinations Week (Post Academic Year Classes)', '2026-07-10', 'Academic Event', '2025-2026'),
('Final Examinations Week (Post Academic Year Classes)', '2026-07-11', 'Academic Event', '2025-2026'),
('Deliberation of Grades (Post Academic Year Classes)', '2026-07-13', 'Academic Event', '2025-2026'),
('Submission of Grades (Post Academic Year Classes)', '2026-07-14', 'Academic Event', '2025-2026'),
('Posting of Grades (Post Academic Year Classes)', '2026-07-15', 'Academic Event', '2025-2026'),
('Last Session of B Classes of 2nd Trimester A.Y. 2025-2026', '2026-07-25', 'Academic Event', '2025-2026');

-- AUGUST 2026
INSERT INTO university_calendar (event_name, event_date, event_type, school_year) VALUES
('Onsite Enrollment for New Students for the 3rd Trimester AY 2025-2026', '2026-08-01', 'Academic Event', '2025-2026'),
('Onsite Enrollment for New Students for the 3rd Trimester AY 2025-2026', '2026-08-02', 'Academic Event', '2025-2026'),
('Onsite Enrollment for New Students for the 3rd Trimester AY 2025-2026', '2026-08-03', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-03', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-04', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-05', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-06', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-07', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-08', 'Academic Event', '2025-2026'),
('Enrolment of Continuing Students/ Thesis-Dissertation Writing/ Comprehensive Examination and New in Writing', '2026-08-09', 'Academic Event', '2025-2026'),
('Last Session of C Classes of 2nd Trimester AY 2025-2026', '2026-08-08', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for A and B Classes', '2026-08-10', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for A and B Classes', '2026-08-11', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for A and B Classes', '2026-08-12', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for A and B Classes', '2026-08-13', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for A and B Classes', '2026-08-14', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for A and B Classes', '2026-08-15', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for C Class only', '2026-08-17', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for C Class only', '2026-08-18', 'Academic Event', '2025-2026'),
('Encoding of Grades for 2nd Trimester AY 2025-2026 for C Class only', '2026-08-19', 'Academic Event', '2025-2026'),
('Orientation of the New Students for the 3rd Trimester AY 2025-2026', '2026-08-20', 'Academic Event', '2025-2026'),
('Ninoy Aquino Day (Special Non-Working Holiday)', '2026-08-21', 'Academic Holiday', '2025-2026'),
('First Session of A Classes of 3rd Trimester AY 2025-2026', '2026-08-22', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-24', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-25', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-26', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-27', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-28', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-29', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-30', 'Academic Event', '2025-2026'),
('Dropping of subject/s (Graduate School)', '2026-08-31', 'Academic Event', '2025-2026'),
('Marcelo H. del Pilar Day (Special Non-working Holiday)', '2026-08-30', 'Academic Holiday', '2025-2026'),
('National Heroes Day (Regular Holiday)', '2026-08-31', 'Academic Holiday', '2025-2026');

