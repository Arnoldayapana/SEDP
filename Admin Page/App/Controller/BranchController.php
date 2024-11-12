<?php
include_once(__DIR__ . '/../Model/BranchModel.php');

class BranchController
{
    private $branchModel;

    public function __construct()
    {
        $this->branchModel = new BranchModel();
    }
    public function getAllBranch()
    {
        // Call the method from the model to get data
        return $this->branchModel->getAllBranch();
    }

    // Method to retrieve job offers based on search and filter criteria
    public function getFilteredBranch($search = '')
    {
        // Call the method from the model to get filtered data
        return $this->branchModel->getFilteredBranch($search);
    }

    public function createBranch()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            error_log(print_r($postData, true)); // Log sanitized POST data   

            // Call the update method
            if ($this->branchModel->createBranch($postData)) {
                // Redirect on success
                header("Location: ../View/Branch.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the update fails
                header("Location: ../View/Branch.php?msg=error");
                exit();
            }
        } else {
            return false; // Indicate invalid request method
        }
    }

    public function deleteBranch($branchId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $branchId = $_POST['branchId'] ?? '';

            if ($this->branchModel->deleteBranch($branchId)) {
                header("Location: ../View/Branch.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/Branch.php?msg=delete_error");
                exit();
            }
        }
    }

    public function updateBranch()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            // Log sanitized POST data
            error_log(print_r($postData, true));

            // Get the necessary fields from sanitized input
            $branchId = $postData['branchId'];
            $name = $postData['name'];

            $country = $postData['countryName'];
            $region = $postData['regionName'];
            $province = $postData['provinceName'];
            $city = $postData['cityName'];


            // Call the update method
            if ($this->branchModel->updateBranch($branchId, $name, $country, $region, $province, $city)) {
                // Redirect on success
                header("Location: ../View/Branch.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the update fails
                header("Location: ../View/Branch.php?msg=error");
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

$controller = new BranchController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update':
            $controller->updateBranch();
            break;
        case 'create':
            $controller->createBranch();
            break;
        case 'delete':
            $controller->deleteBranch($branchId);
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
