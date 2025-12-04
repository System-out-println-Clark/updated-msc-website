<?php

/**
 * Event Routes
 * Handle event-related API endpoints
 */

require_once __DIR__ . '/../controllers/EventController.php';

$eventController = new EventController();
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

// Debug logging
error_log("Event route - Path: " . $path);
error_log("Event route - Path segments: " . print_r($pathSegments, true));

// Find the 'api' segment and get the endpoint after 'events'
$apiIndex = array_search('api', $pathSegments);
$eventsIndex = array_search('events', $pathSegments);

if ($eventsIndex !== false) {
    $endpoint = $pathSegments[$eventsIndex + 1] ?? '';
    $id = $pathSegments[$eventsIndex + 2] ?? null;
    $action = $pathSegments[$eventsIndex + 3] ?? null;
} elseif ($apiIndex !== false) {
    // Path like /msc-website-backend-main/api/events/upcoming
    $endpoint = $pathSegments[$apiIndex + 2] ?? '';
    $id = $pathSegments[$apiIndex + 3] ?? null;
    $action = $pathSegments[$apiIndex + 4] ?? null;
} else {
    // Direct path like /events/upcoming (after rewrite)
    $endpoint = $pathSegments[1] ?? '';
    $id = $pathSegments[2] ?? null;
    $action = $pathSegments[3] ?? null;
}

error_log("Event route - Endpoint: " . $endpoint);
error_log("Event route - ID: " . ($id ?? 'null'));
error_log("Event route - Action: " . ($action ?? 'null'));

switch ($method) {
    case 'GET':
        if ($endpoint === 'allEvents') {
            $eventController->getEvents();
        } else if ($endpoint === 'upcoming') {
            $eventController->getUpcoming();
        } else if ($endpoint === 'upcomingPreview') {
            $eventController->getUpcoming(3);
        } else if ($endpoint === 'upcomingPreviewMember') {
            $eventController->getUpcoming(4);
        } else if ($endpoint === 'universityCalendar') {
            $eventController->getUniversityCalendar();
        } else if ($endpoint === 'upcomingCalendar') {
            $eventController->getUpcomingEventsCalendar();
        } else if ($endpoint === 'canceled') {
            $eventController->getCanceled();
        } else if ($endpoint === 'past') {
            $eventController->getPast();
        } elseif ($endpoint === 'msc-calendar') {
            $eventController->getCalendarEvents();
        } elseif ($endpoint === 'university-calendar') {
            $eventController->getUnivCalendarEvents();
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'registrations') {
            $eventController->getRegistrations($endpoint);
        } else if ($endpoint && is_numeric($endpoint) && $id === 'participantsList') {
            $eventController->getEventParticipants($endpoint);
        } else if (is_numeric($endpoint) && isset($id) && $id === 'eventDashboard') {
            $eventController->getEventById($endpoint);
        } elseif ($endpoint && is_numeric($endpoint)) {
            $eventController->getById($endpoint);
        } elseif ($endpoint === 'count') {
            $eventController->countEvents();
        } else if ($endpoint === 'countRecorded') {
            $eventController->countUpcomingEvents();
        } elseif ($endpoint === 'status-count') {
            $eventController->getStatusCounts();
        } elseif ($endpoint === 'monthly-distribution') {
            $eventController->getEventsPerMonth();
        } else if ($endpoint === 'student-event-stats' && is_numeric($id)) {
            $eventController->countStudentEventStats($id);
        } elseif ($endpoint === 'student' && is_numeric($id)) {
            $eventController->getEventsByStudent($id);
        } else if ($endpoint === 'attended' && is_numeric($id)) {
            $eventController->getAttendedEventsByStudent($id);
        } elseif ($endpoint === '' || $endpoint === 'all') {
            $eventController->getAll();
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'check-registration' && isset($_GET['user_id'])) {
            $eventController->isUserRegistered($endpoint, $_GET['user_id']);
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'check-email' && isset($_GET['email'])) {
    $eventController->checkEmailRegistration($endpoint, $_GET['email']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Event endpoint not found: ' . $endpoint]);
        }
        break;


    // case 'POST':
    //     if ($endpoint && is_numeric($endpoint) && $id === 'register') {
    //         // endpoint is actually the event ID, id is 'register'
    //         $eventController->register($endpoint);
    //     } elseif ($endpoint === '' || $endpoint === 'create') {
    //         $eventController->create();
    //     } elseif ($endpoint && is_numeric($endpoint) && $id === 'status') {
    //         $eventController->updateStatus($endpoint);
    //     } elseif ($endpoint && is_numeric($endpoint)) {
    //         $eventController->update($endpoint);
    //     } else {
    //         http_response_code(404);
    //         echo json_encode(['success' => false, 'message' => 'POST endpoint not found: ' . $endpoint]);
    //     }
    //     break;

    /*
    case 'POST':
        if ($endpoint && is_numeric($endpoint) && $id === 'register') {
            $eventController->register($endpoint);
        } elseif ($endpoint === '' || $endpoint === 'create') {
            $eventController->create();
        } else if($endpoint && is_numeric($endpoint) && $id === 'cancel'){
            $eventController->cancelEvent($endpoint);
        } elseif ($endpoint === 'upload-image') {
            $eventController->uploadEventImage('event');
        } elseif ($endpoint === 'upload-badge') {
            $eventController->uploadEventImage('badge');
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'status') {
            $eventController->updateStatus($endpoint);
        } else if($endpoint && is_numeric($endpoint) && $id === 'import-csv'){
            $eventController->importAttendance($endpoint);
        } elseif ($endpoint && is_numeric($endpoint)) {
            $eventController->update($endpoint);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'POST endpoint not found: ' . $endpoint]);
        }
        break;
    */
    case 'POST':
        if ($endpoint && is_numeric($endpoint) && $id === 'register') {
            $eventController->register($endpoint);
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'cancel-pre-registration') {
            $eventController->cancelRegistration($endpoint);
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'cancel-by-email') {
            $eventController->cancelRegistrationByEmail($endpoint);
        } elseif ($endpoint === '' || $endpoint === 'create') {
            $eventController->create();
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'cancel') {
            $eventController->cancelEvent($endpoint);
        } elseif ($endpoint === 'upload-image') {
            $eventController->uploadEventImage('event');
        } elseif ($endpoint === 'upload-badge') {
            $eventController->uploadEventImage('badge');
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'status') {
            $eventController->updateStatus($endpoint);
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'import-attendance') {
            $eventController->importAttendance($endpoint);
        } elseif ($endpoint && is_numeric($endpoint) && $id === 'mark-attendance') {
            $eventController->markAttendance($endpoint);
        } elseif ($endpoint && is_numeric($endpoint)) {
            $eventController->update($endpoint);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'POST endpoint not found: ' . $endpoint]);
        }
        break;

    case 'PUT':
        if ($endpoint && is_numeric($endpoint) && $id === 'attendance' && $action) {
            // endpoint is event ID, id is 'attendance', action is student ID
            $eventController->updateAttendance($endpoint, $action);
        } elseif ($endpoint && is_numeric($endpoint)) {
            // endpoint is actually the ID
            $eventController->update($endpoint);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'PUT endpoint not found']);
        }
        break;

    case 'DELETE':
        if ($endpoint && is_numeric($endpoint)) {
            // endpoint is actually the ID
            $eventController->delete($endpoint);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'DELETE endpoint not found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
