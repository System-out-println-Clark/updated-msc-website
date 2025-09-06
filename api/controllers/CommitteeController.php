<?php
/**
 * Committee Controller
 * Handles committee operations (CRUD)
 */

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../models/Committee.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

CorsConfig::setup();

class CommitteeController
{
    private $committeeModel;

    public function __construct()
    {
        $this->committeeModel = new Committee();
    }

    /**
     * Get all committees
     */
    public function getAll()
    {
        try {
            $committees = $this->committeeModel->getAll();
            Response::success($committees);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get committee by ID
     */
    public function getById($id)
    {
        try {
            $committee = $this->committeeModel->findById($id);
            if (!$committee) {
                Response::notFound("Committee not found");
            }
            Response::success($committee);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Create committee (Officer only)
     */
    public function create()
    {
        try {
            AuthMiddleware::requireOfficer();
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                Response::validationError(['request' => 'Invalid JSON or empty body']);
            }

            $required = ['name'];
            $errors = Validator::validateRequired($data, $required);
            if (!empty($errors)) {
                Response::validationError($errors);
            }

            $sanitized = Validator::sanitize($data);
            $committee = $this->committeeModel->create($sanitized);

            Response::success($committee, "Committee created successfully", 201);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Update committee (Officer only)
     */
    public function update($id)
    {
        try {
            AuthMiddleware::requireOfficer();
            $committee = $this->committeeModel->findById($id);
            if (!$committee) {
                Response::notFound("Committee not found");
            }

            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                Response::validationError(['request' => 'Invalid JSON or empty body']);
            }

            $sanitized = Validator::sanitize($data);
            $result = $this->committeeModel->update($id, $sanitized);

            if ($result) {
                $updated = $this->committeeModel->findById($id);
                Response::success($updated, "Committee updated successfully");
            } else {
                Response::serverError("Failed to update committee");
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Delete committee (Officer only)
     */
    public function delete($id)
    {
        try {
            AuthMiddleware::requireOfficer();
            $committee = $this->committeeModel->findById($id);
            if (!$committee) {
                Response::notFound("Committee not found");
            }

            $result = $this->committeeModel->delete($id);
            if ($result) {
                Response::success(null, "Committee deleted successfully");
            } else {
                Response::serverError("Failed to delete committee");
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }
}
