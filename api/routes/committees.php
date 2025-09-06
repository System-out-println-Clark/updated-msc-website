<?php
/**
 * Committees Routes
 */

require_once __DIR__ . '/../controllers/CommitteeController.php';

$controller = new CommitteeController();
$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// GET /api/committees
if ($method === 'GET' && preg_match('#^/updated-msc-website/api/committees$#', $url)) {
    $controller->getAll();

// GET /api/committees/{id}
} elseif ($method === 'GET' && preg_match('#^/updated-msc-website/api/committees/(\d+)$#', $url, $matches)) {
    $controller->getById($matches[1]);

// POST /api/committees
} elseif ($method === 'POST' && preg_match('#^/updated-msc-website/api/committees$#', $url)) {
    $controller->create();

// PUT /api/committees/{id}
} elseif ($method === 'PUT' && preg_match('#^/updated-msc-website/api/committees/(\d+)$#', $url, $matches)) {
    $controller->update($matches[1]);

// DELETE /api/committees/{id}
} elseif ($method === 'DELETE' && preg_match('#^/updated-msc-website/api/committees/(\d+)$#', $url, $matches)) {
    $controller->delete($matches[1]);
}
