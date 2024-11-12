<?php
include_once(__DIR__ . '/../Model/EmploymentTypeModel.php');

class EmploymentTypeController
{
    private $employmentTypeModel;

    public function __construct()
    {
        $this->employmentTypeModel = new EmploymentTypeModel();
    }
    public function getAllEmploymentTypes()
    {
        // Call the method from the model to get data
        return $this->employmentTypeModel->getAllEmploymentTypes();
    }
    // Method to retrieve job offers based on search and filter criteria
    public function getFilteredEmploymentType($search = '')
    {
        // Call the method from the model to get filtered data
        return $this->employmentTypeModel->getFilteredEmploymentType($search);
    }

    public function createEmploymentType()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            error_log(print_r($postData, true)); // Log sanitized POST data   

            // Call the update method
            if ($this->employmentTypeModel->createEmploymentType($postData)) {
                // Redirect on success
                header("Location: ../View/RecruitmentSetup.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the update fails
                header("Location: ../View/RecruitmentSetup.php?msg=error");
                exit();
            }
        } else {
            return false; // Indicate invalid request method
        }
    }

    public function deleteEmploymentType($employmentTypeId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employmentTypeId = $_POST['employmentTypeId'] ?? '';

            if ($this->employmentTypeModel->deleteEmploymentType($employmentTypeId)) {
                header("Location: ../View/RecruitmentSetup.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/RecruitmentSetup.php?msg=delete_error");
                exit();
            }
        }
    }

    public function updateEmploymentType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            // Log sanitized POST data
            error_log(print_r($postData, true));

            // Get the necessary fields from sanitized input
            $employmentTypeId = $postData['employmentTypeId'] ?? '';
            $employmentType = $postData['employmentType'] ?? '';

            // Call the update method
            if ($this->employmentTypeModel->updateEmploymentType($employmentTypeId, $employmentType)) {
                // Redirect on success
                header("Location: ../View/RecruitmentSetup.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the update fails
                header("Location: ../View/RecruitmentSetup.php?msg=error");
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

$controller = new EmploymentTypeController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update':
            $controller->updateEmploymentType();
            break;
        case 'create':
            $controller->createEmploymentType();
            break;
        case 'delete':
            $controller->deleteEmploymentType($employmentTypeId);
            break;
        default:
            // Handle unknown action
            header("location: ../View/RecruitmentSetup.php?msg=" . urlencode("Unknown action."));
            break;
    }
} else {
}
