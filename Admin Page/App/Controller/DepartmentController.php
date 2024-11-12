<?php
include_once(__DIR__ . '/../Model/DepartmentModel.php');

class DepartmentController
{
    private $departmentModel;

    public function __construct()
    {
        $this->departmentModel = new DepartmentModel();
    }
    public function getAllDepartmentsWithBranchLocations()
    {
        // Call the method from the model to get data
        return $this->departmentModel->getAllDepartmentsWithBranchLocations();
    }

    public function getAllDepartmentsWithBranch()
    {
        // Call the method from the model to get data
        return $this->departmentModel->getAllDepartmentsWithBranch();
    }
    // Method to retrieve job offers based on search and filter criteria
    public function getFilteredDepartment($search = '')
    {
        // Call the method from the model to get filtered data
        return $this->departmentModel->getFilteredDepartment($search);
    }

    public function createDepartment()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            error_log(print_r($postData, true)); // Log sanitized POST data   

            // Call the update method
            if ($this->departmentModel->createDepartment($postData)) {
                // Redirect on success
                header("Location: ../View/Department.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the update fails
                header("Location: ../View/Department.php?msg=error");
                exit();
            }
        } else {
            return false; // Indicate invalid request method
        }
    }

    public function deleteDepartment($departmentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $departmentId = $_POST['departmentId'] ?? '';

            if ($this->departmentModel->deleteDepartment($departmentId)) {
                header("Location: ../View/Department.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/Department.php?msg=delete_error");
                exit();
            }
        }
    }

    public function updateDepartment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            // Log sanitized POST data
            error_log(print_r($postData, true));
 
            // Get the necessary fields from sanitized input
            $departmentId = $postData['departmentId'] ?? '';
            $DepartmentName = $postData['DepartmentName'] ?? '';
            $branchId = $postData['branchId'] ?? '';

            // Call the update method
            if ($this->departmentModel->updateDepartment($departmentId, $DepartmentName, $branchId)) {
                // Redirect on success
                header("Location: ../View/Department.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the update fails
                header("Location: ../View/Department.php?msg=error");
                exit();
            }
        } else {
            return false; // Indicate invalid request method
        }
    }

    // Method to sanitize input data
    private function sanitizeInput($data)
    {
        if (is_array($data)) {
            return array_map(function ($value) {
                return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            }, $data);
        } else {
            // Handle single value (string)
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
    }
}

$controller = new DepartmentController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update':
            $controller->updateDepartment();
            break;
        case 'create':
            $controller->createDepartment();
            break;
        case 'delete':
            $controller->deleteDepartment($branchId);
            break;
        default:
            // Handle unknown action
            // header("location: ../../../JobApplicantPage/View/JobApplication.php?msg=" . urlencode("Unknown action."));
            break;
    }
} else {
    // Default action (viewing applicant status)
    //$controller->ViewApplicantStatus();
}
