<?php
/**
 * Committee Routes
 */

require_once __DIR__ . '/../controllers/CommitteeController.php';

$controller = new CommitteeController();
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

$apiIndex = array_search('api', $segments);
$commIndex = array_search('committees', $segments);

$id = null;
if ($commIndex !== false) {
    $id = $segments[$commIndex + 1] ?? null;
    if ($id !== null && !is_numeric($id)) {
        $id = null; // ignore non-numeric IDs
    }
}

switch ($method) {
    case 'GET':
        if ($id) {
            $controller->getById($id);
        } else {
            $controller->getAll();
        }
        break;

    case 'POST':
        $controller->create();
        break;

    case 'PUT':
        if ($id) {
            $controller->update($id);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Committee ID required for update']);
        }
        break;

    case 'DELETE':
        if ($id) {
            $controller->delete($id);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Committee ID required for delete']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
