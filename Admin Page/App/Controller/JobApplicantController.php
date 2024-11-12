<?php
session_start();
include_once(__DIR__ . '/../Model/JobApplicantModel.php');
include_once(__DIR__ . '/../Model/JobPostModel.php');
require_once(__DIR__ . '/../../../lib/fpdf/fpdf.php'); //include the fpdf file

class JobApplicantController
{
    private $jobApplicantmodel;

    public function __construct()
    {
        $this->jobApplicantmodel = new JobApplicantModel();
    }

    // Method to display all job applicants
    public function ViewApplicantInterviewDatebyUniqId($uniqid)
    {
        return $this->jobApplicantmodel->getApplicantInterviewDatebyUniqId($uniqid);
    }

    // Method to display job applicants status
    public function ViewApplicantStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $statusId = $this->sanitizeInput($_GET['uniqueId']);
            $status = $this->jobApplicantmodel->viewApplicantStatus($statusId);

            // return the status for direct display
            return $status;
        }
    }
    public function submitApplication()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $postData = $this->sanitizeInput($_POST);
            $fileData = $_FILES;

            error_log(print_r($postData, true)); // Log POST data for debugging
            error_log(print_r($fileData, true)); // Log file data for debugging

            // Initialize an array to store any errors
            $errorMessages = [];

            // Initialize file-related variables
            $formFileName = null;
            $formFileSize = null;
            $formFileType = null;

            $letterFileName = null;
            $letterFileSize = null;
            $letterFileType = null;

            $photoFileName = null;
            $photoFileSize = null;
            $photoFileType = null;

            // Directory to store uploaded files
            $target_dir = "../../../Database/uploads/";
            $allowedTypes = ['pdf', 'docx', 'doc']; // Allowed file types for form and letter
            $allowedImageTypes = ['jpeg', 'jpg', 'png', 'gif'];

            // Check for the profile photo upload
            if (isset($fileData['photoFileName']) && $fileData['photoFileName']['error'] === UPLOAD_ERR_OK) {
                $photoFileType = strtolower(pathinfo($fileData['photoFileName']['name'], PATHINFO_EXTENSION));

                if (in_array($photoFileType, $allowedImageTypes) && $fileData['photoFileName']['size'] < 2000000) {
                    $photoFileName = uniqid('photo_', true) . '.' . $photoFileType;
                    $target_file = $target_dir . $photoFileName;

                    if (!move_uploaded_file($fileData['photoFileName']['tmp_name'], $target_file)) {
                        $errorMessages[] = "There was an error uploading your photo.";
                    } else {
                        $photoFileSize = $fileData['photoFileName']['size'];
                        $photoFileType = $fileData['photoFileName']['type'];
                    }
                } else {
                    $errorMessages[] = "Invalid photo file type or size. Please upload a valid image.";
                }
            } else {
                // No photo uploaded, set default values for placeholder
                $photoFileName = '../../Assets/Images/userimage.png';
                $photoFileSize = 0;
                $photoFileType = 'image/png';
            }

            // Check for the application form file upload
            if (isset($fileData['formFileName']) && $fileData['formFileName']['error'] === UPLOAD_ERR_OK) {
                $formFileType = strtolower(pathinfo($fileData['formFileName']['name'], PATHINFO_EXTENSION));

                if (in_array($formFileType, $allowedTypes) && $fileData['formFileName']['size'] < 2000000) {
                    $formFileName = uniqid('form_file_', true) . '.' . $formFileType;
                    $target_file = $target_dir . $formFileName;

                    if (!move_uploaded_file($fileData['formFileName']['tmp_name'], $target_file)) {
                        $errorMessages[] = "There was an error uploading your form file.";
                    } else {
                        $formFileSize = $fileData['formFileName']['size'];
                        $formFileType = $fileData['formFileName']['type'];
                    }
                } else {
                    $errorMessages[] = "Invalid form file type or size. Please upload a valid document.";
                }
            } else {
                $errorMessages[] = "Application form upload failed. Please try again.";
            }

            // Determine which cover letter option was selected
            $coverLetterOption = $postData['coverLetterOption'] ?? '';

            // Handle cover letter upload or conversion
            if ($coverLetterOption === 'upload' && isset($fileData['letter']) && $fileData['letter']['error'] === UPLOAD_ERR_OK) {
                // Handle file upload
                $letterFileType = strtolower(pathinfo($fileData['letter']['name'], PATHINFO_EXTENSION));

                if (in_array($letterFileType, $allowedTypes) && $fileData['letter']['size'] < 2000000) {
                    $letterFileName = uniqid('letter_file_', true) . '.' . $letterFileType;
                    $target_file = $target_dir . $letterFileName;

                    if (!move_uploaded_file($fileData['letter']['tmp_name'], $target_file)) {
                        $errorMessages[] = "Error uploading your cover letter file.";
                    } else {
                        $letterFileSize = $fileData['letter']['size'];
                        $letterFileType = $fileData['letter']['type'];
                    }
                } else {
                    $errorMessages[] = "Invalid cover letter file type or size. Please upload a valid document.";
                }
            } elseif ($coverLetterOption === 'write') { // Write Cover Letter
                if (empty($postData['coverLetterText'])) {
                    $errorMessages[] = "Please provide a cover letter.";
                } else {
                    // Convert textarea cover letter to PDF
                    $pdf = new FPDF();
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->MultiCell(0, 10, htmlspecialchars(trim($postData['coverLetterText'])));

                    $letterFileName = uniqid('letter_', true) . '.pdf';
                    $target_file = $target_dir . $letterFileName;

                    $pdf->Output('F', $target_file);
                    $letterFileSize = filesize($target_file);
                    $letterFileType = 'application/pdf';
                }
            } elseif ($coverLetterOption === 'none') {
                // No cover letter is included; set cover letter fields to null
                $letterFileName = null;
                $letterFileSize = null;
                $letterFileType = null;
            }

            // If there are no errors, proceed to create the applicant
            if (empty($errorMessages)) {
                // Prepare data for database insertion
                $uniqueId = "applicant_" . uniqid('', true); // Generates a unique ID
                $this->jobApplicantmodel->createApplicant($uniqueId, $postData, $formFileName, $formFileSize, $formFileType, $letterFileName, $letterFileSize, $letterFileType, $photoFileName, $photoFileSize, $photoFileType);

                $jobId = isset($postData['job_id']) ? $postData['job_id'] : '';
                // Set session variables for displaying modal and passing unique ID
                $_SESSION['showModal'] = true;
                $_SESSION['uniqueIdentifier'] = $uniqueId;
                $_SESSION['jobId'] = $jobId;
                header("Location: ../../../JobApplicantPage/View/JobApplication.php");
                //header("Location:../../../JobApplicantPage/View/JobApplication.php?uniqueIdentifier=" . urlencode($uniqueId) . "&showModal=true&job_id=" . urlencode($jobId));
                exit();
            } else {
                // Combine error messages into a single string
                $errorMessage = implode(" ", $errorMessages);
                // Redirect with error message
                header("Location:../../../JobApplicantPage/View/JobApplication.php?msg=" . urlencode($errorMessage));
                exit();
            }
        } else {
            header("Location:../../../JobApplicantPage/View/JobApplication.php?msg=Invalid request");
            exit();
        }
    }


    public function deleteApplicant($applicantId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $applicantId = $_POST['applicantId'] ?? '';

            if ($this->jobApplicantmodel->deleteApplicant($applicantId)) {
                header("Location: ../View/JobApplicants.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/JobApplicants.php?msg=delete_error");
                exit();
            }
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

    // Method to retrieve job applicants based on search and filter criteria
    public function getFilteredJobApplicants($filter = '', $search = '')
    {
        // Call the method from the model to get filtered data
        return $this->jobApplicantmodel->getJobApplicantsWithFilters($filter, $search);
    }

    // Method to update applicant status
    public function updateApplicantStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $applicantId = intval($_POST['applicantId']);
            $status = $_POST['status']; // "reviewed"

            // Call the model method to update the status
            $isUpdated = $this->jobApplicantmodel->updateStatus($applicantId, $status);

            // Return response
            echo json_encode(['success' => $isUpdated]);
        }
    }

    // Method to update applicant status and set interview date and time
    public function updateApplicantScheduleInterview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get applicant ID, status, and interview date-time from POST request
            $applicantId = intval($_POST['applicantId']);
            $status = "Schedule Interview";  // Set status to "Schedule Interview"
            $interviewDatetime = $_POST['interviewDatetime'];  // Expected format: "YYYY-MM-DD HH:MM:SS"

            // Call the model method to update status and interview date-time
            $isUpdated = $this->jobApplicantmodel->updateStatusAndInterview($applicantId, $status, $interviewDatetime);

            // Return response
            echo json_encode(['success' => $isUpdated]);
        }
    }
}

// Instantiate the controller
$controller = new JobApplicantController();

// Handle the action based on the request
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'viewAll':
            $controller->ViewApplicantInterviewDatebyUniqId($uniqueId);
            break;
        case 'apply':
            $controller->submitApplication();
            break;
        case 'viewStatus':
            $controller->ViewApplicantStatus();
            break;
        case 'delete':
            $controller->deleteApplicant($applicantId);
            break;
        case 'updateStatus':
            $controller->updateApplicantStatus();
            break;
        case 'scheduleInterview':
            $controller->updateApplicantScheduleInterview();
            break;
        default:
            // Handle unknown action
            break;
    }
} else {
    // Default action (viewing applicant status)
    //$controller->ViewApplicantStatus();
}
