<?php
include_once(__DIR__ . '/../Model/InterviewModel.php');

class InterviewController
{
    private $interviewModel;

    public function __construct()
    {
        $this->interviewModel = new InterviewModel();
    }

    // Method to retrieve all interviews
    public function getAllInterviews()
    {
        return $this->interviewModel->getAllInterviews();
    }

    // Method to retrieve interviews based on a filter (e.g., by applicant)
    public function getFilteredInterviews($search = '')
    {
        return $this->interviewModel->getFilteredInterviews($search);
    }

    // Method to update an interview
    public function updateInterview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->sanitizeInput($_POST);

            // Get the necessary fields from sanitized input
            $interviewId = $postData['interviewId'];
            $applicantId = $postData['applicantId'];
            $title = $postData['title'];
            $interviewDate = $postData['interviewDate'];
            $interviewType = $postData['interviewType'];
            $videocallLink = $postData['videocallLink'];
            $phoneNumber = $postData['phoneNumber'];
            $officeAddress = $postData['officeAddress'];
            $interviewDescription = $postData['interviewDescription'];
            $status = $postData['status'];
            $notes = $postData['notes'];

            // Call the model method to update the interview
            if ($this->interviewModel->updateInterview($interviewId, $applicantId, $title, $interviewDate, $interviewType, $videocallLink, $phoneNumber, $officeAddress, $interviewDescription, $status, $notes)) {
                header("Location: ../View/Interview.php?msg=success");
                exit();
            } else {
                header("Location: ../View/Interview.php?msg=error");
                exit();
            }
        } else {
            return false; // Invalid request method
        }
    }

    // Method to delete an interview
    public function deleteInterview($interviewId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->interviewModel->deleteInterview($interviewId)) {
                header("Location: ../View/Interview.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/Interview.php?msg=delete_error");
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
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
    }
    // Method to get interview history for a specific applicant
    public function getInterviewHistory($interviewApplicantId)
    {
        if (empty($interviewApplicantId)) {
            return []; // Return an empty array if applicantId is not provided
        }

        return $this->interviewModel->getInterviewsByApplicantId($interviewApplicantId);
    }
    public function updateInterviewNotes($interviewId, $notes)
    {
        if (empty($interviewId) || empty($notes)) {
            return ['success' => false, 'message' => 'Invalid data provided'];
        }
        return $this->interviewModel->updateNotes($interviewId, $notes);
    }


    public function createInterview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decode JSON data
            $postData = json_decode(file_get_contents('php://input'), true);

            // Log the data to check if it's correct
            error_log('Received data: ' . print_r($postData, true));

            // Call the model method to create a new interview
            $result = $this->interviewModel->createInterview($postData);

            if ($result === true) {
                // Return success response
                echo json_encode(['success' => true, 'message' => 'Interview created successfully']);
            } else {
                // Return failure response
                echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Unknown error']);
            }
            exit();
        }

        // Invalid request method
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }
}

$controller = new InterviewController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'create':
            $controller->createInterview();
            break;
        case 'update':
            $controller->updateInterview();
            break;
        case 'delete':
            $controller->deleteInterview($_GET['interviewId']);
            break;
        case 'history':
            if (isset($_GET['interviewApplicantId'])) {
                $interviewApplicantId = intval($_GET['interviewApplicantId']);
                echo json_encode($controller->getInterviewHistory($interviewApplicantId));
            }
            break;
        case 'updateNote':
            // Read the raw POST data (JSON payload)
            $input = json_decode(file_get_contents('php://input'), true);

            // Check if the required parameters are present in the JSON
            if (isset($input['interviewId'], $input['notes'])) {
                $interviewId = intval($input['interviewId']);
                $notes = trim($input['notes']);
                echo json_encode($controller->updateInterviewNotes($interviewId, $notes));
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            }
            break;

        default:
            // Default action
            break;
    }
} else {
    // Default action (viewing interviews)
    //$controller->viewAllInterviews();
}
