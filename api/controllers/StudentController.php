<?php

/**
 * Student Controller
 * Handle student-related operations
 */

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

CorsConfig::setup();

class StudentController
{
    private $studentModel;

    public function __construct()
    {
        $this->studentModel = new Student();
    }

    /**
     * Get all students (Officer only)
     */
    public function getAll()
    {
        try {
            AuthMiddleware::requireOfficer();

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $role = isset($_GET['role']) ? $_GET['role'] : null;

            // Validate role if provided
            if ($role && !Validator::validateEnum($role, ['member', 'officer'])) {
                Response::validationError(['role' => 'Invalid role value']);
            }

            $students = $this->studentModel->getAll($page, $limit, $role);

            // Remove passwords from response
            $students = array_map(function ($student) {
                unset($student['password']);
                return $student;
            }, $students);

            Response::success([
                'students' => $students,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit
                ]
            ]);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get student by ID
     */
    public function getById($id)
    {
        try {
            $currentUserId = AuthMiddleware::authenticate();

            // Students can only view their own profile, officers can view any
            $currentUser = AuthMiddleware::getCurrentUser();
            if ($currentUser['role'] !== 'officer' && $currentUserId != $id) {
                Response::error('Insufficient privileges', 403);
            }

            $student = $this->studentModel->findById($id);

            if (!$student) {
                Response::notFound('Student not found');
            }

            // Remove password from response
            unset($student['password']);

            Response::success($student);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Update student profile
     */
    public function updateProfile($id)
    {
        try {
            $currentUserId = AuthMiddleware::authenticate();

            // Students can only update their own profile, officers can update any
            $currentUser = AuthMiddleware::getCurrentUser();
            if ($currentUser['role'] !== 'admin' && $currentUserId != $id) {
                Response::error('Insufficient privileges', 403);
            }

            $data = json_decode(file_get_contents("php://input"), true);

            // Required fields for profile update
            $requiredFields = [
            "phone", "bulsu_email",
            "facebook_link", "address", "birthdate", "age", "gender",
            "guardian_name", "relationship", "guardian_phone", "guardian_address"
            ];

            // Validate required fields
            $errors = Validator::validateRequired($data, $requiredFields);

            // Validate date format
            if (isset($data['birthdate']) && !Validator::validateDate($data['birthdate'])) {
                $errors['birthdate'] = 'Invalid date format. Use YYYY-MM-DD';
            }

            // Validate gender enum
            if (isset($data['gender']) && !Validator::validateEnum($data['gender'], ['Male', 'Female', 'Other'])) {
                $errors['gender'] = 'Invalid gender value';
            }

            // Validate phone if provided
            // if (!empty($data['phone']) && !Validator::validatePhone($data['phone'])) {
            //     $errors['phone'] = 'Invalid phone number format';
            // }

            if (!empty($data['phone'])) {
                if (!Validator::validatePhone($data['phone'])) {
                    $errors['phone'] = 'Invalid Philippine phone number';
                } else {
                    // Sanitize for consistent storage
                    $data['phone'] = Validator::validatePhone($data['phone']);
                }
            }


            if (!empty($errors)) {
                Response::validationError($errors);
            }

            // Sanitize input data
            $sanitizedData = Validator::sanitize($data);

            // Update profile
            $result = $this->studentModel->updateProfile($id, $sanitizedData);

            if ($result) {
                $updatedStudent = $this->studentModel->findById($id);
                unset($updatedStudent['password']);
                Response::success($updatedStudent, 'Profile updated successfully');
            } else {
                Response::serverError('Failed to update profile');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Toggle student active status (Officer only)
     */
    public function toggleActive($id)
    {
        try {
            AuthMiddleware::requireOfficer();

            $student = $this->studentModel->findById($id);
            if (!$student) {
                Response::notFound('Student not found');
            }

            $result = $this->studentModel->toggleActive($id);

            if ($result) {
                $updatedStudent = $this->studentModel->findById($id);
                unset($updatedStudent['password']);

                $status = $updatedStudent['is_active'] ? 'activated' : 'deactivated';
                Response::success($updatedStudent, "Student account {$status} successfully");
            } else {
                Response::serverError('Failed to update student status');
            }
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Get dashboard data for current user
     */
    public function getDashboardData()
    {
        try {
            $userId = AuthMiddleware::authenticate();
            $currentUser = AuthMiddleware::getCurrentUser();

            $dashboardData = [];

            if ($currentUser['role'] === 'officer') {
                // Officer dashboard data
                // Get total counts
                $totalMembers = count($this->studentModel->getAll(1, 1000, 'member'));
                $totalOfficers = count($this->studentModel->getAll(1, 1000, 'officer'));

                $dashboardData = [
                    'total_members' => $totalMembers,
                    'total_officers' => $totalOfficers,
                    'total_students' => $totalMembers + $totalOfficers,
                    'user_role' => 'officer'
                ];
            } else {
                // Member dashboard data
                $student = $this->studentModel->findById($userId);
                unset($student['password']);

                $dashboardData = [
                    'profile' => $student,
                    'user_role' => 'member'
                ];
            }

            Response::success($dashboardData);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /**
     * Search students (Officer only)
     */
    public function search()
    {
        try {
            AuthMiddleware::requireOfficer();

            $query = isset($_GET['q']) ? trim($_GET['q']) : '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

            if (empty($query)) {
                Response::validationError(['q' => 'Search query is required']);
            }

            // Simple search implementation - can be enhanced with full-text search
            $offset = ($page - 1) * $limit;

            // This would need to be implemented in the Student model
            // For now, returning empty results
            Response::success([
                'students' => [],
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'query' => $query
                ]
            ], 'Search functionality coming soon');
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /*
     * COUNT: All Students
    */
    public function countStudents()
    {
        $student = new Student();

        try {
            $count = $student->countAll();
            echo json_encode(['success' => true, 'data' => $count]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /*
     * CHART: Students by Program
    */
    public function countByCollege()
    {
        $student = new Student();

        try {
            $data = $student->countByCollege();
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /*
     * CHART: Students by Year Level
    */
    public function countByYearLevel()
    {
        $student = new Student();

        try {
            $data = $student->countByYearLevel();
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function uploadProfilePicture($id)
    {
        try {
            $currentUserId = AuthMiddleware::authenticate();
            $currentUser = AuthMiddleware::getCurrentUser();

            if ($currentUser['role'] !== 'officer' && $currentUserId != $id) {
                Response::error('Insufficient privileges', 403);
            }

            if (!isset($_FILES['profile']) || $_FILES['profile']['error'] !== UPLOAD_ERR_OK) {
                Response::error('No file uploaded', 400);
            }

            $file = $_FILES['profile'];
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                Response::error('Invalid file type. Only JPG and PNG allowed.', 400);
            }

            if ($file['size'] > $maxSize) {
                Response::error('File size exceeds 2MB limit.', 400);
            }

            // Auto-create upload folder 
            $uploadDir = __DIR__ . '/../../uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = 'profile_' . $id . '_' . time() . '.' . $ext;
            $filePath = $uploadDir . $fileName;
            $relativePath = '/uploads/profiles/' . $fileName; 
            $fullPath = 'https://bulsumsc.org' . $relativePath;

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                Response::error('Failed to move uploaded file.', 500);
            }

            $this->studentModel->updateProfile($id, ['profile_image_path' => $relativePath]);

            Response::success(['path' => $fullPath], 'Profile picture uploaded successfully.');
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function create()
    {
        try {
            AuthMiddleware::requireOfficer(); // Only officers can create new students

            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                Response::validationError(['error' => 'Invalid JSON payload']);
            }

            $requiredFields = ['username', 'email', 'password', 'first_name', 'last_name', 'birthdate', 'gender', 'student_no', 'year_level', 'college', 'program'];
            $errors = Validator::validateRequired($data, $requiredFields);

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            $student = $this->studentModel->create($data);
            unset($student['password']); // remove password from response

            Response::success($student, 'Student created successfully');
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Optional: Only allow officers to delete
            // AuthMiddleware::requireOfficer();

            $student = $this->studentModel->findById($id);
            if (!$student) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Student not found']);
                return;
            }

            $result = $this->studentModel->delete($id);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Student deleted successfully', 'data' => ['id' => $id]]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete student']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET: current student's membership status: Active/Inactive 
     */
    public function getMembershipStatus()
    {
        try {
            $currentUserId = AuthMiddleware::authenticate();
            $student = $this->studentModel->findById($currentUserId);

            if (!$student) {
                Response::notFound('Student not found');
            }

            $status = ($student['is_active'] == 1) ? 'Active' : 'Inactive';

            Response::success(['status' => $status]);
        } catch (Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    /*
     * COUNT: All Student (Members)
    */
    public function countMembers()
    {
        $student = new Student();

        try {
            $count = $student->countMembers();
            echo json_encode(['success' => true, 'data' => $count]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * TESTING: Get Student details by msc_id
     */
    public function getStudentsByMscIds()
    {
        header("Content-Type: application/json");

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!isset($input['msc_ids']) || !is_array($input['msc_ids'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "msc_ids must be provided as an array"
                ]);
                return;
            }

            $mscIds = array_filter($input['msc_ids']); // remove empty
            $students = $this->studentModel->getByMscIds($mscIds);

            echo json_encode([
                "success" => true,
                "data" => $students
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            ]);
        }
    }
}
