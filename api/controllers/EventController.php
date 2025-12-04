<?php

/**
 * Event Controller
 * Handle event-related operations
 */

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

CorsConfig::setup();

class EventController
{
    private $eventModel;

    public function __construct()
    {
        $this->eventModel = new Event();
    }

    /**
     * Create new event (Officer only)
     */
    public function create()
    {
        try {
            AuthMiddleware::requireOfficer();

            $data = json_decode(file_get_contents("php://input"), true);

            // Required fields for event creation
            $requiredFields = [
                'event_name',
                'event_date',
                'event_time_start',
                'event_time_end',
                'location',
                'description'
            ];

            // Validate required fields
            $errors = Validator::validateRequired($data, $requiredFields);

            // Validate date format
            if (isset($data['event_date']) && !Validator::validateDate($data['event_date'])) {
                $errors['event_date'] = 'Invalid date format. Use YYYY-MM-DD';
            }

            // Validate time formats
            if (isset($data['event_time_start']) && !Validator::validateTime($data['event_time_start'])) {
                $errors['event_time_start'] = 'Invalid time format. Use HH:MM';
            }

            if (isset($data['event_time_end']) && !Validator::validateTime($data['event_time_end'])) {
                $errors['event_time_end'] = 'Invalid time format. Use HH:MM';
            }

            // Validate enum values
            if (isset($data['event_type']) && !Validator::validateEnum($data['event_type'], ['onsite', 'online', 'hybrid'])) {
                $errors['event_type'] = 'Invalid event type';
            }

            if (isset($data['event_status']) && !Validator::validateEnum($data['event_status'], ['upcoming', 'canceled', 'completed'])) {
                $errors['event_status'] = 'Invalid event status';
            }

            if (isset($data['event_restriction']) && !Validator::validateEnum($data['event_restriction'], ['public', 'members', 'bulsuans', 'inviteOnly'])) {
                $errors['event_restriction'] = 'Invalid event restriction';
            }

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            // Set defaults
            $data['event_type'] = $data['event_type'] ?? 'onsite';
            $data['event_status'] = $data['event_status'] ?? 'upcoming';
            $data['event_restriction'] = $data['event_restriction'] ?? 'public';
            $data['registration_required'] = $data['registration_required'] ?? false;

            // Sanitize input data
            $sanitizedData = Validator::sanitize($data);

            // Create event
            $event = $this->eventModel->create($sanitizedData);

            Response::success($event, 'Event created successfully', 201);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Upload Event Image (poster or badge)
     */
    public function uploadEventImage($type = 'event')
    {
        try {
            AuthMiddleware::requireOfficer();

            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                Response::error('No file uploaded or upload error', 400);
            }

            $file = $_FILES['image'];
            $allowedExtensions = ['jpg', 'jpeg', 'png']; // 
            $maxSize = 5 * 1024 * 1024; // 5MB limit

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                Response::error('Invalid file type. Only JPG and PNG allowed.', 400);
            }

            if ($file['size'] > $maxSize) {
                Response::error('File size exceeds 5MB limit.', 400);
            }

            $uploadDir = __DIR__ . '/../../uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = $type . '_' . time() . '_' . uniqid() . '.' . $ext;
            $filePath = $uploadDir . $fileName;

            $relativePath = '/updated-msc-website/uploads/events/' . $fileName;

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                Response::error('Failed to move uploaded file.', 500);
            }

            Response::success(['path' => $relativePath], ucfirst($type) . ' image uploaded successfully.');
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get all events
     */
    public function getAll()
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

            // Build filters
            $filters = [];
            if (isset($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            }
            if (isset($_GET['type'])) {
                $filters['type'] = $_GET['type'];
            }
            if (isset($_GET['restriction'])) {
                $filters['restriction'] = $_GET['restriction'];
            }
            if (isset($_GET['date_from'])) {
                $filters['date_from'] = $_GET['date_from'];
            }
            if (isset($_GET['date_to'])) {
                $filters['date_to'] = $_GET['date_to'];
            }

            $events = $this->eventModel->getAll($page, $limit, $filters);

            Response::success([
                'events' => $events,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit
                ],
                'filters' => $filters
            ]);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get event by ID
     */
    public function getById($id)
    {
        try {
            $event = $this->eventModel->findById($id);

            if (!$event) {
                Response::notFound('Event not found');
            }

            Response::success($event);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * GET: for Event Dashboard
     */
    public function getEventById($eventId)
    {
        try {
            $event = $this->eventModel->findEventById($eventId);

            if (!$event) {
                echo json_encode([
                    "success" => false,
                    "message" => "Event not found"
                ]);
                return;
            }

            echo json_encode([
                "success" => true,
                "data"    => $event
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Update event (Officer only)
     */
    /*
    public function update($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $event = $this->eventModel->findById($id);
            if (!$event) {
                Response::notFound('Event not found');
            }

            $data = json_decode(file_get_contents("php://input"), true);

            // Required fields for event update
            $requiredFields = [
                'event_name',
                'event_date',
                'event_time_start',
                'event_time_end',
                'location',
                'description',
                'event_status'
            ];

            // Validate required fields
            $errors = Validator::validateRequired($data, $requiredFields);

            // Validate date format
            if (isset($data['event_date']) && !Validator::validateDate($data['event_date'])) {
                $errors['event_date'] = 'Invalid date format. Use YYYY-MM-DD';
            }

            // Validate time formats
            if (isset($data['event_time_start']) && !Validator::validateTime($data['event_time_start'])) {
                $errors['event_time_start'] = 'Invalid time format. Use HH:MM';
            }

            if (isset($data['event_time_end']) && !Validator::validateTime($data['event_time_end'])) {
                $errors['event_time_end'] = 'Invalid time format. Use HH:MM';
            }

            // Validate enum values
            if (isset($data['event_type']) && !Validator::validateEnum($data['event_type'], ['onsite', 'online', 'hybrid'])) {
                $errors['event_type'] = 'Invalid event type';
            }

            if (isset($data['event_status']) && !Validator::validateEnum($data['event_status'], ['upcoming', 'canceled', 'completed'])) {
                $errors['event_status'] = 'Invalid event status';
            }

            if (isset($data['event_restriction']) && !Validator::validateEnum($data['event_restriction'], ['public', 'members', 'officers'])) {
                $errors['event_restriction'] = 'Invalid event restriction';
            }

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            // Sanitize input data
            $sanitizedData = Validator::sanitize($data);

            // Update event
            $result = $this->eventModel->update($id, $sanitizedData);

            if ($result) {
                $updatedEvent = $this->eventModel->findById($id);
                Response::success($updatedEvent, 'Event updated successfully');
            } else {
                Response::serverError('Failed to update event');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }
    */

    /**
     * Update event (Officer only)
     */
    public function update($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $event = $this->eventModel->findById($id);
            if (!$event) {
                Response::notFound('Event not found');
            }

            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || !is_array($data)) {
                Response::validationError(['message' => 'Invalid or empty JSON data']);
            }

            $errors = [];

            // Optional validation for fields that are present
            if (isset($data['event_date']) && !Validator::validateDate($data['event_date'])) {
                $errors['event_date'] = 'Invalid date format. Use YYYY-MM-DD';
            }

            if (isset($data['event_time_start']) && !Validator::validateTime($data['event_time_start'])) {
                $errors['event_time_start'] = 'Invalid time format. Use HH:MM';
            }

            if (isset($data['event_time_end']) && !Validator::validateTime($data['event_time_end'])) {
                $errors['event_time_end'] = 'Invalid time format. Use HH:MM';
            }

            if (isset($data['event_type']) && !Validator::validateEnum($data['event_type'], ['onsite', 'online', 'hybrid'])) {
                $errors['event_type'] = 'Invalid event type';
            }

            if (isset($data['event_status']) && !Validator::validateEnum($data['event_status'], ['upcoming', 'canceled', 'completed'])) {
                $errors['event_status'] = 'Invalid event status';
            }

            if (isset($data['event_restriction']) && !Validator::validateEnum($data['event_restriction'], ['public', 'members', 'officers'])) {
                $errors['event_restriction'] = 'Invalid event restriction';
            }

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            // Sanitize the data
            $sanitizedData = Validator::sanitize($data);

            // Update event (partial updates handled in model)
            $result = $this->eventModel->update($id, $sanitizedData);

            if ($result) {
                $updatedEvent = $this->eventModel->findById($id);
                Response::success($updatedEvent, 'Event updated successfully');
            } else {
                Response::serverError('Failed to update event');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function updateStatus($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $event = $this->eventModel->findById($id);
            if (!$event) {
                Response::notFound('Event not found');
            }

            $data = json_decode(file_get_contents("php://input"), true);

            // Required fields for event update
            $requiredField = [
                'event_status'
            ];

            // Validate required fields
            $errors = Validator::validateRequired($data, $requiredField);

            if (isset($data['event_status']) && !Validator::validateEnum($data['event_status'], ['upcoming', 'canceled', 'completed'])) {
                $errors['event_status'] = 'Invalid event status';
            }

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            // Sanitize input data
            $sanitizedData = Validator::sanitize($data);

            // Update event status
            $result = $this->eventModel->updateStatus($id, $sanitizedData['event_status']);

            if ($result) {
                $updatedEvent = $this->eventModel->findById($id);
                Response::success($updatedEvent, 'Event status updated successfully');
            } else {
                Response::serverError('Failed to update event status');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Cancel event
     */
    public function cancelEvent($id)
    {
        try {
            // Optional: require authentication/role if needed
            AuthMiddleware::requireOfficer(); // Uncomment if only officers can cancel

            $event = $this->eventModel->findById($id);
            if (!$event) {
                Response::notFound('Event not found');
                return;
            }

            $result = $this->eventModel->cancelEvent($id);

            if ($result) {
                Response::success(null, 'Event cancelled successfully');
            } else {
                Response::serverError('Failed to cancel event');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Cancel pre-registration by email (for guests, BulSUans, members, and officers)
     */
    public function cancelRegistrationByEmail($eventId)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'] ?? null;

            if (!$email) {
                return Response::error("Email is required.");
            }

            $result = $this->eventModel->cancelRegistrationByEmail($eventId, $email);

            if ($result) {
                return Response::success(null, "Pre-registration cancelled successfully.");
            } else {
                return Response::error("No active registration found for this email.");
            }
        } catch (Exception $e) {
            return Response::serverError($e->getMessage());
        }
    }


    /**
     * Delete event (Officer only)
     */
    public function delete($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $event = $this->eventModel->findById($id);
            if (!$event) {
                Response::notFound('Event not found');
            }

            $result = $this->eventModel->delete($id);

            if ($result) {
                Response::success(null, 'Event deleted successfully');
            } else {
                Response::serverError('Failed to delete event');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get upcoming events: default
     */
    // public function getUpcoming()
    // {
    //     try {
    //         $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; //Default: 10
    //         $events = $this->eventModel->getUpcoming($limit);

    //         Response::success($events);
    //     } catch (Exception $e) {
    //         Response::serverError($e->getMessage());
    //     }
    // }
    public function getUpcoming($limit = null)
    {
        try {
            if ($limit === null) {
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            }

            $events = $this->eventModel->getUpcoming($limit);
            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function getUniversityCalendar()
    {
        try {
            AuthMiddleware::authenticate();

            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
            $events = $this->eventModel->getUniversityCalendar($limit);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function getUpcomingEventsCalendar()
    {
        try {
            AuthMiddleware::authenticate();

            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
            $events = $this->eventModel->getUpcomingEventsCalendar($limit);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get canceled events
     */
    public function getCanceled()
    {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; //Default: 10
            $events = $this->eventModel->getCanceled($limit);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get past/completed events
     */
    public function getPast()
    {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; //Default: 10
            $events = $this->eventModel->getPast($limit);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get calendar events
     */
    public function getCalendarEvents()
    {
        try {
            $startDate = $_GET['start'] ?? date('Y-m-01');
            $endDate = $_GET['end'] ?? date('Y-m-t');

            // Validate date formats
            if (!Validator::validateDate($startDate) || !Validator::validateDate($endDate)) {
                Response::validationError(['date' => 'Invalid date format. Use YYYY-MM-DD']);
            }

            $events = $this->eventModel->getCalendarEvents($startDate, $endDate);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get univeristy calendar events
     */
    public function getUnivCalendarEvents()
    {
        try {
            $startDate = $_GET['start'] ?? date('Y-m-01');
            $endDate = $_GET['end'] ?? date('Y-m-t');

            if (!Validator::validateDate($startDate) || !Validator::validateDate($endDate)) {
                Response::validationError(['date' => 'Invalid date format. Use YYYY-MM-DD']);
            }

            $events = $this->eventModel->getUnivCalendarEvents($startDate, $endDate);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Register for an event
     */
    public function register($eventId)
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $event = $this->eventModel->findById($eventId);
            if (!$event) {
                return Response::error("Event not found");
            }

            $restriction = strtolower($event['event_restriction'] ?? '');

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $userId = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? null;
            $isLoggedIn = !empty($userId);

            $result = null;
            switch ($restriction) {
                case 'members':
                    session_start();
                    $userId = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? null;
                    if (!$userId) return Response::unauthorized("Login required to register as a member.");
                    $result = $this->eventModel->registerMember($eventId, $userId);
                    break;
                case 'bulsuans':
                    // For BulSUans, check if user is logged in as a member
                    if ($isLoggedIn) {
                        // Pass student_id for logged-in BulSU members
                        $input['student_id'] = $userId;
                    }
                    $result = $this->eventModel->registerBulSUan($eventId, $input);
                    break;
                case 'public':
                    // For public events, check if user is logged in
                    if ($isLoggedIn) {
                        // Pass student_id for logged-in members registering for public events
                        $input['student_id'] = $userId;
                    }
                    $result = $this->eventModel->registerPublic($eventId, $input);
                    break;
                default:
                    return Response::error("Invalid event restriction type.");
            }

            // Handle the response - check for duplicate email case
            if (isset($result['success']) && $result['success'] === false && $result['message'] === 'duplicate_email') {
                return Response::success([
                    'duplicate' => true,
                    'email' => $result['email']
                ], "This email is already registered for this event.");
            }

            // Handle successful registration with QR code
            if (isset($result['qr_code'])) {
                return Response::success($result, "Registration successful! Please keep your QR code for verification.");
            } else if (isset($result['message']) && isset($result['data'])) {
                return Response::success($result['data'], $result['message']);
            } else if (isset($result['registration_id'])) {
                return Response::success($result);
            } else {
                return Response::success($result);
            }
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    /**
     * Check if Member is registered for an event
     */
    public function isUserRegistered($eventId, $userId)
    {
        try {
            $registered = $this->eventModel->checkUserRegistration($eventId, $userId);

            echo json_encode([
                'success' => true,
                'registered' => $registered
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Check if email is registered for an event
     */
    public function checkEmailRegistration($eventId, $email)
    {
        try {
            $registered = $this->eventModel->checkEmailRegistration($eventId, $email);
            Response::success(['registered' => $registered]);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Cancel pre-registration for an event (Member only)
     */
    public function cancelRegistration($eventId)
    {
        try {
            session_start();
            $userId = $_SESSION['user_id'] ?? null;

            if (!$userId) {
                return Response::unauthorized("Login required to cancel registration.");
            }

            $result = $this->eventModel->cancelRegistration($eventId, $userId);

            if ($result) {
                return Response::success(null, "Pre-registration cancelled successfully.");
            } else {
                return Response::error("You are not registered for this event.");
            }
        } catch (Exception $e) {
            return Response::serverError($e->getMessage());
        }
    }

    /**
     * Get event registrations (Officer only)
     */
    public function getRegistrations($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $event = $this->eventModel->findById($id);
            if (!$event) {
                Response::notFound('Event not found');
            }

            $registrations = $this->eventModel->getRegistrations($id);

            Response::success([
                'event' => $event,
                'registrations' => $registrations,
                'total_registered' => count($registrations)
            ]);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Update attendance status (Officer only)
     */
    public function updateAttendance($eventId, $studentId)
    {
        try {
            AuthMiddleware::requireOfficer();

            $data = json_decode(file_get_contents("php://input"), true);

            // Validate required fields
            $errors = Validator::validateRequired($data, ['attendance_status']);
            if (!empty($errors)) {
                Response::validationError($errors);
            }

            // Validate status enum
            if (!Validator::validateEnum($data['attendance_status'], ['registered', 'attended', 'absent'])) {
                Response::validationError(['attendance_status' => 'Invalid attendance status']);
            }

            $result = $this->eventModel->updateAttendanceStatus($eventId, $studentId, $data['attendance_status']);

            if ($result) {
                Response::success(null, 'Attendance status updated successfully');
            } else {
                Response::serverError('Failed to update attendance status');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * GET: All Events
     */
    public function getEvents()
    {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;

            $events = $this->eventModel->getEvents($limit);

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }


    /**
     * Get: Registered Events by a Student
     */
    public function getEventsByStudent($studentId)
    {
        try {
            $events = $this->eventModel->getEventsByStudent($studentId);

            if (!$events) {
                Response::success([], 'No registered events found');
            } else {
                Response::success($events);
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get: Attended Events
     */
    public function getAttendedEventsByStudent($studentId)
    {
        try {
            $events = $this->eventModel->getAttendedEventsByStudent($studentId);

            if (!$events) {
                Response::success([], 'No attended events found');
            } else {
                Response::success($events);
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * COUNT: All Events
     */
    public function countEvents()
    {
        $event = new Event();

        try {
            $count = $event->countAll();
            echo json_encode(['success' => true, 'data' => $count]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * COUNT: Attended & Pre-Registered events (student)
     */
    public function countStudentEventStats($studentId)
    {
        $event = new Event();
        try {
            $counts = $event->getStudentEventStats($studentId);
            echo json_encode(['success' => true, 'data' => $counts]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * COUNT: Events (Upcoming)
     */
    public function countUpcomingEvents()
    {
        $event = new Event();

        try {
            $count = $event->countUpcomingEvents();
            echo json_encode(['success' => true, 'data' => $count]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET: Event Status counts 
     */
    public function getStatusCounts()
    {
        try {
            $statusCounts = $this->eventModel->getStatusCounts();

            Response::success($statusCounts);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * GET: Events per Month
     */
    public function getEventsPerMonth()
    {
        try {
            $events = $this->eventModel->getEventsPerMonth();

            Response::success($events);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * TESTING: Verify Attendance
     */
    public function importAttendance($eventId)
    {
        try {
            AuthMiddleware::requireOfficer(); // officers only

            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['students']) || !is_array($data['students'])) {
                Response::validationError(['students' => 'Invalid or missing students data']);
            }

            $result = $this->eventModel->importAttendance($eventId, $data['students']);
            Response::success($result, "Attendance imported successfully.");
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /** 
     * TESTING: Event Participants
     */
    public function getEventParticipants($eventId)
    {
        try {
            AuthMiddleware::requireOfficer();

            if (!$eventId) {
                Response::validationError(['event_id' => 'Missing event ID']);
                return;
            }

            $result = $this->eventModel->getEventParticipants($eventId);

            if ($result) {
                Response::success($result, "Event participants loaded successfully.");
            } else {
                Response::notFound("Event not found or no participants.");
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Mark attendance for multiple participants
     */
    public function markAttendance($eventId)
    {
        try {
            AuthMiddleware::requireOfficer();

            $input = json_decode(file_get_contents('php://input'), true);

            $identifiers = $input['identifiers'] ?? null;

            if (empty($identifiers) || !is_array($identifiers)) {
                Response::validationError([
                    'identifiers' => 'Identifiers array is required'
                ]);
                return;
            }

            $event = $this->eventModel->findById($eventId);
            if (!$event) {
                Response::notFound('Event not found');
                return;
            }

            $result = $this->eventModel->markAttendanceByIdentifiers($eventId, $identifiers);

            if ($result['updated'] > 0) {
                Response::success(
                    $result,
                    "Successfully marked {$result['updated']} participant(s) as attended"
                );
            } else {
                Response::success(
                    $result,
                    "No attendance records were updated. Participants may already be marked as attended or not found."
                );
            }
        } catch (Exception $e) {
            error_log("Error marking attendance: " . $e->getMessage());
            Response::serverError('Failed to mark attendance: ' . $e->getMessage());
        }
    }
}
