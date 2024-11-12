<?php
include_once(__DIR__ . '/../Model/BenefitModel.php');

class BenefitController
{
    private $BenefitModel;

    public function __construct()
    {
        $this->BenefitModel = new BenefitModel();
    }
    public function getAllBenefits()
    {
        // Call the method from the model to get data
        return $this->BenefitModel->getAllBenefits();
    }
    // Method to retrieve job offers based on search and filter criteria
    public function getFilteredBenefit($search = '')
    {
        // Call the method from the model to get filtered data
        return $this->BenefitModel->getFilteredBenefit($search);
    }

    public function createBenefit()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            error_log(print_r($postData, true)); // Log sanitized POST data   

            // Call the update method
            if ($this->BenefitModel->createBenefit($postData)) {
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

    public function deleteBenefit($benefitId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $BenefitId = $_POST['benefitId'] ?? '';

            if ($this->BenefitModel->deleteBenefit($BenefitId)) {
                header("Location: ../View/RecruitmentSetup.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/RecruitmentSetup.php?msg=delete_error");
                exit();
            }
        }
    }

    public function updateBenefit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);

            // Log sanitized POST data
            error_log(print_r($postData, true));

            // Get the necessary fields from sanitized input
            $benefitId = $postData['benefitId'] ?? '';
            $benefit = $postData['benefit'] ?? '';

            // Call the update method
            if ($this->BenefitModel->updateBenefit($benefitId, $benefit)) {
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

$controller = new BenefitController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update':
            $controller->updateBenefit();
            break;
        case 'create':
            $controller->createBenefit();
            break;
        case 'delete':
            $controller->deleteBenefit($benefitId);
            break;
        default:
            // Handle unknown action
            header("location: ../View/RecruitmentSetup.php?msg=" . urlencode("Unknown action."));
            break;
    }
} else {
}
