<?php

include_once(__DIR__ . '/../Model/InterviewJobApplicantModel.php');
include_once(__DIR__ . '/../Model/JobApplicantModel.php');

class InterviewJobApplicantController
{
    private $InterviewJobApplicantModel;
    private $JobApplicantModel;

    public function __construct()
    {
        $this->InterviewJobApplicantModel = new InterviewJobApplicantModel();
        $this->JobApplicantModel = new JobApplicantModel();
    }
    public function transferApplicantToInterview($applicantId)
    {
        // Retrieve the applicant data
        $applicant = $this->JobApplicantModel->getApplicantbyId($applicantId);

        if ($applicant) {
            // Create a new row in `tblInterviewJobApplicant` with the copied data
            $createResult = $this->InterviewJobApplicantModel->createInterviewApplicant([
                'uniqueId' => $applicant['uniqueId'],
                'name' => $applicant['applicantName'],
                'email' => $applicant['email'],
                'contactNumber' => $applicant['contactNumber'],
                'appliedDate' => $applicant['appliedDate'],
                'formFileName' => $applicant['formFileName'] ?? null,
                'formfileSize' => $applicant['formfileSize'] ?? null,
                'formfileType' => $applicant['formfileType'] ?? null,
                'letterFileName' => $applicant['letterFileName'] ?? null,
                'letterFileSize' => $applicant['letterFileSize'] ?? null,
                'letterFileType' => $applicant['letterFileType'] ?? null,
                'photoFileName' => $applicant['photoFileName'] ?? null,
                'photoFileSize' => $applicant['photoFileSize'] ?? null,
                'photoFileType' => $applicant['photoFileType'] ?? null,
                'jobPostId' => $applicant['jobPostId'],
                'interviewStageDate' => $applicant['appliedDate'],
                'interviewDatetime' => $applicant['interviewDatetime'] ?? null,
            ]);

            if ($createResult) {
                // Delete the applicant from `tblJobApplicant`
                $deleteResult = $this->JobApplicantModel->deleteApplicant($applicantId);

                if ($deleteResult) {
                    return ['success' => true];
                }

                return ['success' => false, 'message' => 'Failed to delete applicant from tblJobApplicant'];
            }

            return ['success' => false, 'message' => 'Failed to insert data into tblInterviewJobApplicant'];
        }

        return ['success' => false, 'message' => 'Applicant not found'];
    }

    public function ViewApplicantById($interviewApplicantId)
    {
        $result = $this->InterviewJobApplicantModel->getApplicantbyId($interviewApplicantId);

        if ($result) {
            echo json_encode([
                "success" => true,
                "data" => $result // Include the entire array
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Applicant not found.",
            ]);
        }
    }
    // Method to retrieve job applicants based on search and filter criteria
    public function getFilteredJobApplicants($filter = '', $search = '')
    {
        // Call the method from the model to get filtered data
        return $this->InterviewJobApplicantModel->getJobApplicantsWithFilters($filter, $search);
    }

    public function markViewedApplicant()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve and sanitize applicant ID
            $interviewApplicantId = isset($_POST['interviewApplicantId']) ? intval($_POST['interviewApplicantId']) : 0;

            if ($interviewApplicantId > 0) {
                // Call the model method to mark as viewed
                $result = $this->InterviewJobApplicantModel->markAsViewed($interviewApplicantId);

                // Return JSON response
                echo json_encode(['success' => $result]);
            } else {
                // Return error response if applicant ID is invalid
                echo json_encode(['success' => false, 'message' => 'Invalid applicant ID.']);
            }
            exit;
        } else {
            // Return error response if not a POST request
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }
    }
}

// Instantiate and handle requests
$controller = new InterviewJobApplicantController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'transfer':
            $applicantId = intval($_GET['applicantId']);
            $result = $controller->transferApplicantToInterview($applicantId);
            echo json_encode($result);
            break;
        case 'ViewApplicantById':
            $interviewApplicantId = $_GET['interviewApplicantId'] ?? null;
            $controller->ViewApplicantById($interviewApplicantId);
            break;
        case 'markViewed':
            $controller->markViewedApplicant();
            break;
    }
}
