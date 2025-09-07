<?php
/**
 * Committee Controller
 */

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../models/Committee.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

CorsConfig::setup();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class CommitteeController
{
    private $committeeModel;

    public function __construct()
    {
        $this->committeeModel = new Committee();
    }

    public function getAll()
    {
        try {
            $committees = $this->committeeModel->getAll();
            Response::success($committees);
        } catch (Exception $e) {
            error_log("CommitteeController getAll() error: " . $e->getMessage());
            Response::serverError("Failed to fetch committees.");
        }
    }

    public function getById($id)
    {
        try {
            $committee = $this->committeeModel->findById($id);
            if (!$committee) Response::notFound("Committee not found");
            Response::success($committee);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function create()
    {
        try {
            AuthMiddleware::requireOfficer();

            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) Response::validationError(['request' => 'Invalid JSON']);

            $errors = Validator::validateRequired($data, ['name']);
            if (!empty($errors)) Response::validationError($errors);

            $committee = $this->committeeModel->create($data);
            Response::success($committee, "Committee created successfully", 201);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) Response::validationError(['request' => 'Invalid JSON']);

            $committee = $this->committeeModel->findById($id);
            if (!$committee) Response::notFound("Committee not found");

            $updated = $this->committeeModel->update($id, $data);
            Response::success($updated, "Committee updated successfully");
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $committee = $this->committeeModel->findById($id);
            if (!$committee) Response::notFound("Committee not found");

            $this->committeeModel->delete($id);
            Response::success(null, "Committee deleted successfully");
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }
}
