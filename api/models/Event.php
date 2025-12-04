<?php

/**
 * Event Model
 * Handle all event-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Event
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new event
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO events (
                event_name, event_date, event_time_start, event_time_end,
                location, event_type, registration_required, event_status,
                description, event_image_url, event_batch_image, event_restriction,
                capacity
            ) VALUES (
                :event_name, :event_date, :event_time_start, :event_time_end,
                :location, :event_type, :registration_required, :event_status,
                :description, :event_image_url, :event_batch_image, :event_restriction,
                :capacity
            )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'event_name' => $data['event_name'],
                'event_date' => $data['event_date'],
                'event_time_start' => $data['event_time_start'],
                'event_time_end' => $data['event_time_end'],
                'location' => $data['location'],
                'event_type' => $data['event_type'],
                'registration_required' => $data['registration_required'] ?? false,
                'event_status' => $data['event_status'] ?? 'upcoming',
                'description' => $data['description'],
                'event_image_url' => $data['event_image_url'] ?? null,
                'event_batch_image' => $data['event_batch_image'] ?? null,
                'event_restriction' => $data['event_restriction'] ?? 'public',
                'capacity' => $data['capacity'] ?? 0
            ]);

            return $this->findById($this->db->lastInsertId());
        } catch (Exception $e) {
            throw new Exception("Failed to create event: " . $e->getMessage());
        }
    }

    /**
     * Find event by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE event_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get all events with pagination and filters
     */
    public function getAll($page = 1, $limit = 20, $filters = [])
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM events WHERE 1=1";
        $params = [];

        // Apply filters
        if (isset($filters['status'])) {
            $sql .= " AND event_status = :status";
            $params['status'] = $filters['status'];
        }

        if (isset($filters['type'])) {
            $sql .= " AND event_type = :type";
            $params['type'] = $filters['type'];
        }

        if (isset($filters['restriction'])) {
            $sql .= " AND event_restriction = :restriction";
            $params['restriction'] = $filters['restriction'];
        }

        if (isset($filters['date_from'])) {
            $sql .= " AND event_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $sql .= " AND event_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }

        $sql .= " ORDER BY event_date ASC, event_time_start ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Update Event [Allow partial updates]
     */
    public function update($id, $data)
    {
        try {
            $fields = [
                'event_name',
                'event_date',
                'event_time_start',
                'event_time_end',
                'location',
                'event_type',
                'registration_required',
                'event_status',
                'description',
                'event_image_url',
                'event_batch_image',
                'event_restriction',
                'capacity'
            ];

            $setParts = [];
            $params = ['id' => $id];

            foreach ($fields as $field) {
                if (array_key_exists($field, $data)) {
                    $setParts[] = "$field = :$field";
                    // Convert empty strings to null for optional fields
                    $params[$field] = $data[$field] === "" ? null : $data[$field];
                }
            }

            if (empty($setParts)) {
                throw new Exception("No data provided for update.");
            }

            $sql = "UPDATE events SET " . implode(", ", $setParts) . ", updated_at = CURRENT_TIMESTAMP WHERE event_id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            throw new Exception("Failed to update event: " . $e->getMessage());
        }
    }


    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE events 
                SET event_status = :event_status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE event_id = :id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'event_status' => $status,
                'id' => $id
            ]);
        } catch (Exception $e) {
            throw new Exception("Failed to update event: " . $e->getMessage());
        }
    }

    /*
     * Cancel event
    */
    public function cancelEvent($id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE events SET `event_status` = 'canceled' WHERE event_id = :id");
            //UPDATE events SET `event_status` = 'canceled' WHERE event_id = 63
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Failed to delete event: " . $e->getMessage());
        }
    }

    /**
     * Cancel registration by email (for ALL participant types including members and officers)
     */
    public function cancelRegistrationByEmail($eventId, $email)
    {
        try {
            // First, check if the registration exists
            $checkStmt = $this->db->prepare("
                SELECT id FROM event_registrations 
                WHERE event_id = ? AND email = ? 
                AND attendance_status = 'registered'  -- Only cancel if still registered (not attended)
            ");
            $checkStmt->execute([$eventId, $email]);
            
            if (!$checkStmt->fetch()) {
                return false; // No registration found
            }

            // Delete the registration
            $stmt = $this->db->prepare("
                DELETE FROM event_registrations 
                WHERE event_id = ? AND email = ? 
                AND attendance_status = 'registered'
            ");
            $result = $stmt->execute([$eventId, $email]);

            // If a registration was deleted, decrement the attendants count
            if ($result && $stmt->rowCount() > 0) {
                $this->db->prepare("UPDATE events SET attendants = attendants - 1 WHERE event_id = ?")
                    ->execute([$eventId]);
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception("Failed to cancel registration by email: " . $e->getMessage());
        }
    }

    /**
     * Delete event
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM events WHERE event_id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Failed to delete event: " . $e->getMessage());
        }
    }

    /*
    public function getUpcoming($limit = 10) //Default: 10
    {
        $sql = "SELECT * FROM events 
                WHERE event_status = 'upcoming' 
                AND event_date >= CURDATE()
                ORDER BY event_date ASC, event_time_start ASC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    */

    /**
     * GET: ALL Events
     */
    public function getEvents($limit = null)
    {
        $sql = "SELECT * FROM events 
            ORDER BY event_date ASC, event_time_start ASC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * GET: Upcoming events
     */
    /*
    public function getUpcoming($limit = null)
    {
        $sql = "SELECT * FROM events 
            WHERE event_status = 'upcoming' 
            AND event_date >= CURDATE()
            ORDER BY event_date ASC, event_time_start ASC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
    */
    public function getUpcoming($limit = null)
    {
        $sql = "SELECT 
                e.*,
                COUNT(er.id) AS total_registered,
                SUM(CASE WHEN er.attendance_status = 'attended' THEN 1 ELSE 0 END) AS total_attended,
                SUM(CASE WHEN er.attendance_status = 'registered' THEN 1 ELSE 0 END) AS total_still_registered
            FROM events e
            LEFT JOIN event_registrations er ON e.event_id = er.event_id
            WHERE e.event_status = 'upcoming' 
            AND e.event_date >= CURDATE()
            GROUP BY e.event_id
            ORDER BY e.event_date ASC, e.event_time_start ASC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($events as &$event) {
            $totalRegistered = (int)$event['total_registered'];
            $totalAttended = (int)$event['total_attended'];
            $event['attendance_rate'] = $totalRegistered > 0
                ? round(($totalAttended / $totalRegistered) * 100, 2)
                : 0;
        }

        return $events;
    }

    public function getUniversityCalendar($limit = null)
    {
        $sql = "SELECT * FROM university_calendar";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get upcoming events: for student calendar
     */
    public function getUpcomingEventsCalendar($limit = null)
    {
        $sql = "SELECT * FROM events 
            WHERE event_status = 'upcoming' 
            AND event_date >= CURDATE()
            ORDER BY event_date ASC, event_time_start ASC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get canceled events
     */
    public function getCanceled($limit = 10) //Default: 10
    {
        $sql = "SELECT * FROM events 
                WHERE event_status = 'canceled' 
                AND event_date >= CURDATE()
                ORDER BY event_date ASC, event_time_start ASC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get past/completed events
     */
    public function getPast($limit = 10) //Default: 10
    {
        $sql = "SELECT * FROM events 
                WHERE event_status = 'completed' 
                AND event_date < CURDATE()
                ORDER BY event_date ASC, event_time_start ASC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get events for calendar
     */
    public function getCalendarEvents($startDate, $endDate)
    {
        $sql = "SELECT event_id, event_name, event_date, event_time_start, event_time_end, event_status,
                       event_type, event_status, location, description
                FROM events 
                WHERE event_date BETWEEN :start_date AND :end_date
                AND event_status = 'upcoming'
                ORDER BY event_date ASC, event_time_start ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Get university calendar events 
     */
    public function getUnivCalendarEvents($startDate, $endDate)
    {
        $sql = "SELECT calendar_id, event_name, event_date, event_type, school_year
                FROM university_calendar
                WHERE event_date BETWEEN :start_date AND :end_date
                ORDER BY event_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'start_date' => $startDate,
            'end_date'   => $endDate
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Register a Member or Officer
     */
    public function registerMember($eventId, $studentId)
    {
        try {
            // First, get student info to check email
            $studentStmt = $this->db->prepare("
                SELECT 
                    first_name,
                    middle_name,
                    last_name,
                    name_suffix,
                    email,
                    gender,
                    phone,
                    facebook_link,
                    student_no,
                    program,
                    college,
                    year_level,
                    section,
                    role
                FROM students
                WHERE id = :student_id
            ");
            $studentStmt->execute(['student_id' => $studentId]);
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

            if (!$student) {
                throw new Exception("Student information not found.");
            }

            // Determine participant type based on role
            $participantType = $student['role'] === 'officer' ? 'officer' : 'member';

            // Check if this email is already registered for this event
            $checkEmailStmt = $this->db->prepare("
                SELECT id FROM event_registrations
                WHERE event_id = :event_id AND email = :email
                AND attendance_status = 'registered'
            ");
            $checkEmailStmt->execute([
                'event_id' => $eventId,
                'email' => $student['email']
            ]);
            
            if ($checkEmailStmt->fetch()) {
                return [
                    "success" => false,
                    "message" => "duplicate_email",
                    "email" => $student['email'],
                    "participant_type" => $participantType
                ];
            }

            // Generate QR code for member/officer
            $countStmt = $this->db->prepare("
                SELECT COUNT(*) AS participant_count 
                FROM event_registrations 
                WHERE event_id = :event_id AND participant_type IN ('member', 'officer')
            ");
            $countStmt->execute(['event_id' => $eventId]);
            $row = $countStmt->fetch(PDO::FETCH_ASSOC);

            $participantNumber = $row['participant_count'] + 1;
            $qrCode = strtoupper($participantType) . "-" . str_pad($participantNumber, 3, '0', STR_PAD_LEFT);

            $stmt = $this->db->prepare("
                INSERT INTO event_registrations (
                    event_id,
                    student_id,
                    participant_type,
                    qr_code,  
                    first_name,
                    middle_name,
                    last_name,
                    suffix,
                    email,
                    gender,
                    phone,
                    facebook_link,
                    program,
                    college,
                    year_level,
                    section,
                    registration_date
                ) VALUES (
                    :event_id,
                    :student_id,
                    :participant_type,
                    :qr_code,  
                    :first_name,
                    :middle_name,
                    :last_name,
                    :suffix,
                    :email,
                    :gender,
                    :phone,
                    :facebook_link,
                    :program,
                    :college,
                    :year_level,
                    :section,
                    NOW()
                )
            ");

            $stmt->execute([
                'event_id' => $eventId,
                'student_id' => $studentId,
                'participant_type' => $participantType,
                'qr_code' => $qrCode,
                'first_name' => $student['first_name'],
                'middle_name' => $student['middle_name'] ?? null,
                'last_name' => $student['last_name'],
                'suffix' => $student['name_suffix'] ?? null,
                'email' => $student['email'],
                'gender' => $student['gender'],
                'phone' => $student['phone'] ?? null,
                'facebook_link' => $student['facebook_link'] ?? null,
                'program' => $student['program'] ?? null,
                'college' => $student['college'] ?? null,
                'year_level' => $student['year_level'] ?? null,
                'section' => $student['section'] ?? null
            ]);

            $registrationId = $this->db->lastInsertId();

            $this->db->prepare("
                UPDATE events 
                SET attendants = attendants + 1 
                WHERE event_id = :event_id
            ")->execute(['event_id' => $eventId]);

            return [
                "message" => "Successfully registered as a " . $participantType . ".",
                "data" => [
                    "registration_id" => $registrationId,
                    "event_id" => $eventId,
                    "participant_type" => $participantType,
                    "qr_code" => $qrCode,
                    "full_name" => trim($student['first_name'] . ' ' . ($student['middle_name'] ?? '') . ' ' . $student['last_name'])
                ]
            ];
        } catch (Exception $e) {
            throw new Exception("Member/officer registration failed: " . $e->getMessage());
        }
    }

    // Helper method to count member registrations
    private function getMemberRegistrationCount($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS member_count 
            FROM event_registrations 
            WHERE event_id = :event_id AND participant_type = 'member'
        ");
        $stmt->execute(['event_id' => $eventId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['member_count'] ?? 0;
    }


    /**
     * Cancel a Pre-Registration
     */
    public function cancelRegistration($eventId, $userId)
    {
        $sql = "DELETE FROM event_registrations WHERE event_id = ? AND student_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$eventId, $userId]);
    }

    /**
     * Check if a user is registered for an event
     */

    public function checkUserRegistration($eventId, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM event_registrations 
                WHERE event_id = ? AND student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$eventId, $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['count'] > 0; // returns true/false
    }
    
    /**
     * Check if an email is registered for an event (for ALL participant types)
     */
    public function checkEmailRegistration($eventId, $email)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count, participant_type, attendance_status
                FROM event_registrations 
                WHERE event_id = ? AND email = ?
                AND attendance_status = 'registered'
            ");
            $stmt->execute([$eventId, $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row['count'] > 0) {
                return [
                    'registered' => true,
                    'participant_type' => $row['participant_type'],
                    'attendance_status' => $row['attendance_status']
                ];
            }
            
            return ['registered' => false, 'participant_type' => null, 'attendance_status' => null];
        } catch (Exception $e) {
            throw new Exception("Failed to check email registration: " . $e->getMessage());
        }
    }

    /**
     * Register a BulSUan participant (BulSU students/employees only)
     */
    public function registerBulSUan($eventId, $data)
    {
        try {
            $required = ['first_name', 'last_name', 'email', 'gender', 'student_id', 'program', 'college', 'year_level'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            // Check if this email already registered for this event
            $checkStmt = $this->db->prepare("
                SELECT id FROM event_registrations 
                WHERE event_id = :event_id AND email = :email
            ");
            $checkStmt->execute([
                'event_id' => $eventId,
                'email' => $data['email']
            ]);

            if ($checkStmt->fetch()) {
                return [
                    "success" => false,
                    "message" => "duplicate_email", 
                    "email" => $data['email']
                ];
            }

            // Count existing bulsuan registrations for this event
            $countStmt = $this->db->prepare("
                SELECT COUNT(*) AS bulsuan_count 
                FROM event_registrations 
                WHERE event_id = :event_id AND participant_type = 'bulsuan'
            ");
            $countStmt->execute(['event_id' => $eventId]);
            $row = $countStmt->fetch(PDO::FETCH_ASSOC);

            $bulsuanNumber = $row['bulsuan_count'] + 1;
            $qrCode = "BULSUAN-" . str_pad($bulsuanNumber, 3, '0', STR_PAD_LEFT); // ADDED QR CODE

            // Insert the registration with QR code
            $stmt = $this->db->prepare("
                INSERT INTO event_registrations (
                    event_id, 
                    participant_type, 
                    qr_code, 
                    first_name,
                    middle_name,
                    last_name,
                    suffix,
                    email,
                    gender,
                    phone,
                    facebook_link,
                    program,
                    college,
                    year_level,
                    section,
                    registration_date
                ) VALUES (
                    :event_id,
                    'bulsuan', 
                    :qr_code,  
                    :first_name,
                    :middle_name,
                    :last_name,
                    :suffix,
                    :email,
                    :gender,
                    :phone,
                    :facebook,
                    :program,
                    :college,
                    :year_level,
                    :section,
                    NOW()
                )
            ");

            $stmt->execute([
                'event_id' => $eventId,
                'qr_code' => $qrCode,  // ADDED QR CODE
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'] ?? null,
                'email' => $data['email'],
                'gender' => $data['gender'],
                'phone' => $data['phone'] ?? null,
                'facebook' => $data['facebook'] ?? null,
                'program' => $data['program'],
                'college' => $data['college'],
                'year_level' => $data['year_level'],
                'section' => $data['section'] ?? null
            ]);

            // Get the auto-increment ID that was just created
            $registrationId = $this->db->lastInsertId();

            // Update event attendants count
            $this->db->prepare("UPDATE events SET attendants = attendants + 1 WHERE event_id = :event_id")
                ->execute(['event_id' => $eventId]);

            return [
                "registration_id" => $registrationId,
                "event_id" => $eventId,
                "participant_type" => "bulsuan",
                "qr_code" => $qrCode,  // ADDED QR CODE TO RESPONSE
                "student_id" => $data['student_id'],
                "full_name" => trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name'])
            ];
        } catch (Exception $e) {
            throw new Exception("BulSUan registration failed: " . $e->getMessage());
        }
    }

    /**
     * Register a PUBLIC participant (non-BulSU individuals OR logged-in members)
     */
    
    public function registerPublic($eventId, $data)
    {
        try {
            $required = ['first_name', 'last_name', 'email', 'gender'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            // Check if this email already registered for this event
            $checkStmt = $this->db->prepare("
                SELECT id FROM event_registrations 
                WHERE event_id = :event_id AND email = :email
            ");
            $checkStmt->execute([
                'event_id' => $eventId,
                'email' => $data['email']
            ]);

            if ($checkStmt->fetch()) {
                return [
                    "success" => false,
                    "message" => "duplicate_email", 
                    "email" => $data['email']
                ];
            }

            $studentId = $data['student_id'] ?? null;
            $participantType = 'guest'; // Default
            
            // If student_id is provided, check if it's a member/officer
            if ($studentId) {
                $studentCheck = $this->db->prepare("
                    SELECT id, role FROM students WHERE id = :student_id
                ");
                $studentCheck->execute(['student_id' => $studentId]);
                $studentInfo = $studentCheck->fetch(PDO::FETCH_ASSOC);
                
                if ($studentInfo) {
                    // This is a logged-in member/officer
                    $participantType = $studentInfo['role']; // 'member' or 'officer'
                } else {
                    $studentId = null; // Student doesn't exist
                }
            }

            // Count registrations based on participant type
            $countStmt = $this->db->prepare("
                SELECT COUNT(*) AS type_count 
                FROM event_registrations 
                WHERE event_id = :event_id AND participant_type = :participant_type
            ");
            $countStmt->execute([
                'event_id' => $eventId,
                'participant_type' => $participantType
            ]);
            $row = $countStmt->fetch(PDO::FETCH_ASSOC);

            $typeNumber = $row['type_count'] + 1;
            
            // Generate appropriate QR code based on participant type
            if ($participantType === 'officer') {
                $qrCode = "OFFICER-" . str_pad($typeNumber, 3, '0', STR_PAD_LEFT);
            } elseif ($participantType === 'member') {
                $qrCode = "MEMBER-" . str_pad($typeNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $qrCode = "GUEST-" . str_pad($typeNumber, 3, '0', STR_PAD_LEFT);
            }

            // Insert new registration
            $stmt = $this->db->prepare("
                INSERT INTO event_registrations (
                    event_id, 
                    student_id,
                    participant_type, 
                    qr_code,
                    first_name,
                    middle_name,
                    last_name,
                    suffix,
                    email,
                    gender,
                    phone,
                    facebook_link,
                    program,
                    college,
                    year_level,
                    section,
                    registration_date
                ) VALUES (
                    :event_id, 
                    :student_id,
                    :participant_type,
                    :qr_code,
                    :first_name,
                    :middle_name,
                    :last_name,
                    :suffix,
                    :email,
                    :gender,
                    :phone,
                    :facebook,
                    :program,
                    :college,
                    :year_level,
                    :section,
                    NOW()
                )
            ");

            $stmt->execute([
                'event_id' => $eventId,
                'student_id' => $studentId,
                'participant_type' => $participantType,
                'qr_code' => $qrCode,
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'] ?? null,
                'email' => $data['email'],
                'gender' => $data['gender'],
                'phone' => $data['phone'] ?? null,
                'facebook' => $data['facebook'] ?? null,
                'program' => $data['program'] ?? null,
                'college' => $data['college'] ?? null,
                'year_level' => $data['year_level'] ?? null,
                'section' => $data['section'] ?? null
            ]);

            $registrationId = $this->db->lastInsertId();

            $this->db->prepare("
                UPDATE events SET attendants = attendants + 1 
                WHERE event_id = :event_id
            ")->execute(['event_id' => $eventId]);

            return [
                "qr_code" => $qrCode,
                "event_id" => $eventId,
                "student_id" => $studentId,
                "registration_id" => $registrationId,
                "participant_type" => $participantType,
                "full_name" => trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name'])
            ];
        } catch (Exception $e) {
            throw new Exception("Public registration failed: " . $e->getMessage());
        }
    }

    /**
     * Get event registrations
     */
    public function getRegistrations($eventId)
    {
        $sql = "SELECT er.*, s.first_name, s.last_name, s.msc_id, s.email, s.student_no
                FROM event_registrations er
                JOIN students s ON er.student_id = s.id
                WHERE er.event_id = :event_id
                ORDER BY er.registration_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['event_id' => $eventId]);

        return $stmt->fetchAll();
    }

    /**
     * Update attendance status
     */
    public function updateAttendanceStatus($eventId, $studentId, $status)
    {
        try {
            $stmt = $this->db->prepare("UPDATE event_registrations SET attendance_status = :status WHERE event_id = :event_id AND student_id = :student_id");
            return $stmt->execute([
                'status' => $status,
                'event_id' => $eventId,
                'student_id' => $studentId
            ]);
        } catch (Exception $e) {
            throw new Exception("Failed to update attendance status: " . $e->getMessage());
        }
    }

    /**
     * Get: Registered Events by a Student (on Dashboard)
     */
    public function getEventsByStudent($studentId)
    {
        $sql = "SELECT e.event_id, e.event_name, e.event_date, e.event_time_start, e.location, e.description, e.event_status, e.event_batch_image, er.attendance_status
            FROM event_registrations er
            JOIN events e ON er.event_id = e.event_id
            WHERE er.student_id = :student_id
            AND e.event_status = 'upcoming'
            ORDER BY e.event_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);

        return $stmt->fetchAll();
    }

    /**
     * GET: Attended Events by a Student (on Event Badges)
     */
    public function getAttendedEventsByStudent($studentId)
    {
        $sql = "SELECT e.event_id, e.event_name, e.event_date, e.event_time_start, e.location, e.event_batch_image, er.attendance_status
            FROM event_registrations er
            JOIN events e ON er.event_id = e.event_id
            WHERE er.student_id = :student_id
            AND er.attendance_status = 'attended'
            ORDER BY e.event_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);

        return $stmt->fetchAll();
    }

    /**
     * COUNT: All Events
     */
    public function countAll()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM events");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * COUNT: Attended & Pre-Registered events (student) 
     */
    public function getStudentEventStats($studentId)
    {
        $stmt = $this->db->prepare("SELECT
        SUM(CASE 
            WHEN er.attendance_status = 'registered' 
                AND e.event_date >= CURDATE() 
            THEN 1 ELSE 0 END) AS registered_count,
        SUM(CASE 
            WHEN er.attendance_status = 'attended' 
            THEN 1 ELSE 0 END) AS attended_count
        FROM event_registrations er
        JOIN events e ON er.event_id = e.event_id
        WHERE er.student_id = :studentId
        ");

        $stmt->execute([':studentId' => $studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: ['registered_count' => 0, 'attended_count' => 0];
    }


    /**
     * COUNT: Events (Upcoming)
     */
    public function countUpcomingEvents()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM events WHERE `event_status` = 'upcoming'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * GET: Event Status counts 
     */
    public function getStatusCounts()
    {
        $stmt = $this->db->prepare("
        SELECT event_status, COUNT(*) AS total
        FROM events
        GROUP BY event_status
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * GET: Events per Month
     */
    public function getEventsPerMonth()
    {
        $sql = "SELECT MONTH(event_date) AS month, COUNT(*) AS total
            FROM events
            GROUP BY month
            ORDER BY month ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * TESTING: Verify Attendance
     */
    public function importAttendance($eventId, $students)
    {
        try {
            $inserted = 0;

            foreach ($students as $student) {
                if (empty($student['msc_id'])) {
                    continue;
                }

                $stmt = $this->db->prepare("SELECT id FROM students WHERE msc_id = :msc_id");
                $stmt->execute(['msc_id' => $student['msc_id']]);
                $studentRow = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($studentRow) {
                    $studentId = $studentRow['id'];

                    $stmt2 = $this->db->prepare("
                    INSERT INTO event_registrations (event_id, student_id, attendance_status)
                    VALUES (:event_id, :student_id, 'attended')
                    ON DUPLICATE KEY UPDATE attendance_status = 'attended'
                ");
                    $stmt2->execute([
                        'event_id' => $eventId,
                        'student_id' => $studentId
                    ]);

                    $inserted++;
                }
            }

            return [
                "success" => true,
                "imported" => $inserted
            ];
        } catch (Exception $e) {
            throw new Exception("Failed to import attendance: " . $e->getMessage());
        }
    }

    /**
     * GET: Event Participants (for Event Details page)
     */
    public function getEventParticipants($eventId)
    {
        try {
            //Event details
            $eventSql = "
            SELECT 
                event_id,
                event_name,
                event_date,
                event_time_start,
                event_time_end,
                location,
                event_type,
                event_status,
                description,
                event_image_url,
                event_batch_image
            FROM events
            WHERE event_id = :event_id
        ";

            $stmtEvent = $this->db->prepare($eventSql);
            $stmtEvent->execute(['event_id' => $eventId]);
            $event = $stmtEvent->fetch(PDO::FETCH_ASSOC);

            if (!$event) {
                throw new Exception("Event not found.");
            }

            //Participants list
            $participantSql = "
            SELECT 
                er.id,
                er.student_id,
                er.participant_type,
                er.attendance_status,
                er.registration_date,
                er.first_name AS g_first_name,
                er.middle_name AS g_middle_name,
                er.last_name AS g_last_name,
                s.msc_id,
                s.student_no,
                COALESCE(s.first_name, er.first_name) AS first_name,
                COALESCE(s.middle_name, er.middle_name) AS middle_name,
                COALESCE(s.last_name, er.last_name) AS last_name,
                COALESCE(s.year_level, er.year_level) AS year_level,
                COALESCE(s.program, er.program) AS program,
                COALESCE(s.college, er.college) AS college
            FROM event_registrations er
            LEFT JOIN students s ON er.student_id = s.id
            WHERE er.event_id = :event_id
            ORDER BY er.registration_date DESC
        ";

            $stmtParticipants = $this->db->prepare($participantSql);
            $stmtParticipants->execute(['event_id' => $eventId]);
            $participants = $stmtParticipants->fetchAll(PDO::FETCH_ASSOC);

            $countSql = "
            SELECT 
                COUNT(*) AS total_registered,
                SUM(attendance_status = 'attended') AS total_attended,
                SUM(attendance_status = 'registered') AS total_still_registered
            FROM event_registrations
            WHERE event_id = :event_id
        ";

            $stmtCount = $this->db->prepare($countSql);
            $stmtCount->execute(['event_id' => $eventId]);
            $counts = $stmtCount->fetch(PDO::FETCH_ASSOC);

            $totalRegistered      = (int)($counts['total_registered'] ?? 0);
            $totalAttended        = (int)($counts['total_attended'] ?? 0);
            $totalStillRegistered = (int)($counts['total_still_registered'] ?? 0);
            $attendanceRate       = $totalRegistered > 0
                ? round(($totalAttended / $totalRegistered) * 100, 2)
                : 0;

            $eventDetails = array_merge($event, [
                "total_registered"       => $totalRegistered,
                "total_attended"         => $totalAttended,
                "total_still_registered" => $totalStillRegistered,
                "attendance_rate"        => $attendanceRate
            ]);

            $formattedParticipants = [];

            foreach ($participants as $p) {
                $fullName = trim(
                    implode(" ", array_filter([
                        $p['first_name'],
                        $p['middle_name'],
                        $p['last_name']
                    ]))
                );

                $formattedParticipants[] = [
                    "student_id"        => $p['student_id'] ?? null,
                    "msc_id"            => $p['msc_id'] ?? null,
                    "student_no"        => $p['student_no'] ?? null,
                    "first_name"        => $p['first_name'],
                    "middle_name"       => $p['middle_name'],
                    "last_name"         => $p['last_name'],
                    "fullName"          => $fullName,
                    "year_level"        => $p['year_level'] ?? "-",
                    "program"           => $p['program'] ?? "-",
                    "college"           => $p['college'] ?? "-",
                    "participant_type"  => $p['participant_type'],
                    "attendance_status" => $p['attendance_status'],
                    "registration_date" => $p['registration_date']
                ];
            }

            return [
                "event"        => $eventDetails,
                "participants" => $formattedParticipants
            ];
        } catch (Exception $e) {
            throw new Exception("Failed to fetch event participants: " . $e->getMessage());
        }
    }


    /**
     * GET: for Event Dashboard
     */
    /*
    public function findEventById($eventId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM events WHERE event_id = :event_id");
            $stmt->execute(['event_id' => $eventId]);
            $event = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$event) {
                return null;
            }

            // Aggregate counts from event_registrations
            $countSql = "
            SELECT 
                COUNT(*) AS total_registered,
                SUM(attendance_status = 'attended') AS total_attended,
                SUM(attendance_status = 'registered') AS total_still_registered
                FROM event_registrations
                WHERE event_id = :event_id
                ";
            $stmt2 = $this->db->prepare($countSql);
            $stmt2->execute(['event_id' => $eventId]);
            $counts = $stmt2->fetch(PDO::FETCH_ASSOC);

            $totalRegistered       = (int)($counts['total_registered'] ?? 0);
            $totalAttended         = (int)($counts['total_attended'] ?? 0);
            $totalStillRegistered  = (int)($counts['total_still_registered'] ?? 0);
            $attendanceRate        = $totalRegistered > 0 ? round(($totalAttended / $totalRegistered) * 100, 2) : 0;

            $event['total_registered']       = $totalRegistered;
            $event['total_attended']         = $totalAttended;
            $event['total_still_registered'] = $totalStillRegistered;
            $event['attendance_rate']        = $attendanceRate;

            return $event;
        } catch (Exception $e) {
            throw new Exception("Failed to fetch event details: " . $e->getMessage());
        }
    }
    */

    public function findEventById($eventId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM events WHERE event_id = :event_id");
            $stmt->execute(['event_id' => $eventId]);
            $event = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$event) {
                return null;
            }
            // Aggregate counts from event_registrations
            $countSql = "
        SELECT 
            COUNT(*) AS total_registered,
            SUM(attendance_status = 'attended') AS total_attended,

            SUM(participant_type = 'member') AS total_member_registered,
            SUM(participant_type = 'member' AND attendance_status = 'attended') AS total_member_attended,
            
            SUM(participant_type = 'guest') AS total_guest_registered,
            SUM(participant_type = 'guest' AND attendance_status = 'attended') AS total_guest_attended
            FROM event_registrations
            WHERE event_id = :event_id
            ";
            $stmt2 = $this->db->prepare($countSql);
            $stmt2->execute(['event_id' => $eventId]);
            $counts = $stmt2->fetch(PDO::FETCH_ASSOC);

            $totalRegistered       = (int)($counts['total_registered'] ?? 0);
            $totalAttended         = (int)($counts['total_attended'] ?? 0);

            $totalMemberRegistered  = (int)($counts['total_member_registered'] ?? 0);
            $totalMemberAttended  = (int)($counts['total_member_attended'] ?? 0);
            $totalGuestRegistered  = (int)($counts['total_guest_registered'] ?? 0);
            $totalGuestAttended  = (int)($counts['total_guest_attended'] ?? 0);
            $attendanceRate        = $totalRegistered > 0 ? round(($totalAttended / $totalRegistered) * 100, 2) : 0;

            $event['total_registered']       = $totalRegistered;
            $event['total_attended']         = $totalAttended;

            $event['total_member_registered'] = $totalMemberRegistered;
            $event['total_member_attended'] = $totalMemberAttended;
            $event['total_guest_registered'] = $totalGuestRegistered;
            $event['total_guest_attended'] = $totalGuestAttended;
            $event['attendance_rate']        = $attendanceRate;
            return $event;
        } catch (Exception $e) {
            throw new Exception("Failed to fetch event details: " . $e->getMessage());
        }
    }

    public function markAttendanceByIdentifiers($eventId, $identifiers)
    {
        try {
            $conn = Database::getInstance()->getConnection();

            if (empty($identifiers)) {
                return ['updated' => 0, 'message' => 'No identifiers provided'];
            }

            $identifiers = array_map('trim', $identifiers);
            $placeholders = implode(',', array_fill(0, count($identifiers), '?'));

            $linkSql = "UPDATE event_registrations er
                    INNER JOIN students s ON er.email = s.email
                    SET er.student_id = s.id,
                        er.participant_type = 'member'
                    WHERE er.event_id = ?
                    AND er.student_id IS NULL
                    AND s.msc_id IN ($placeholders)";

            $linkParams = array_merge([$eventId], $identifiers);
            $linkStmt = $conn->prepare($linkSql);
            $linkStmt->execute($linkParams);

            $linked = $linkStmt->rowCount();
            error_log("Linked $linked records by email");

            $updateSql = "UPDATE event_registrations er
                      LEFT JOIN students s ON er.student_id = s.id
                      SET er.attendance_status = 'attended'
                      WHERE er.event_id = ?
                      AND er.attendance_status = 'registered'
                      AND (
                          er.qr_code IN ($placeholders)
                          OR s.msc_id IN ($placeholders)
                      )";

            $updateParams = array_merge(
                [$eventId],
                $identifiers,
                $identifiers
            );

            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute($updateParams);

            return [
                'updated' => $updateStmt->rowCount(),
                'linked' => $linked,
                'event_id' => (int)$eventId
            ];
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            throw new Exception("Failed to mark attendance: " . $e->getMessage());
        }
    }
}
