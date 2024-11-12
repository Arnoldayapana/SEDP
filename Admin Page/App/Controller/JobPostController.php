<?php

include_once(__DIR__ . '/../Model/JobPostModel.php');
require_once(__DIR__ . '/../../../lib/htmlpurifier/library/HTMLPurifier.auto.php');

class JobPostController
{
    private $jobPostModel;

    public function __construct()
    {
        $this->jobPostModel = new JobPostModel();
    }

    // Method to get the Job post details by ID
    public function getJobPostDetails($jobPostId)
    {
        $jobPostId = filter_var($jobPostId, FILTER_VALIDATE_INT);

        if ($jobPostId === false) {
            error_log("Invalid job post ID provided: " . htmlspecialchars($jobPostId));
            return null; // Invalid ID
        }

        $jobPostDetails = $this->jobPostModel->getJobPostById($jobPostId);

        if ($jobPostDetails === null) {
            error_log("Job post not found for ID: " . htmlspecialchars($jobPostId));
        }

        return $jobPostDetails;
    }


    // Method to get the job offer name by ID
    public function getJobId($jobPostId)
    {
        return $this->jobPostModel->getJobIdById($jobPostId);
    }

    public function displayJobPosts()
    {
        $jobPosts = $this->jobPostModel->getJobPosts(); // Get job offers from the model

        // Handle empty or failed fetch
        if (!$jobPosts) {
            $jobPosts = []; // Return an empty array in case of failure
        }

        return $jobPosts; // Return the job offers
    }

    // Method to retrieve job offers based on search and filter criteria
    public function getFilteredJobPosts($filter = '', $search = '')
    {
        return $this->jobPostModel->getJobPostsWithFilters($filter, $search);
    }

    // For job landing page
    public function getAllFilteredJobPost()
    {
        $filter_time = $_GET['filter_time'] ?? '';
        $filter_type = $_GET['filter_type'] ?? '';
        $filter_job = $_GET['filter_job'] ?? '';
        $search = $_GET['search'] ?? '';

        // Ensure the model method supports all parameters passed here
        return $this->jobPostModel->getJobPostsWithFilters($filter_time, $filter_type, $filter_job, $search);
    }

    public function displayFilteredJobPosts()
    {
        $jobPosts = $this->getAllFilteredJobPost();

        include '../Views/jobPostsView.php'; // Adjust the path as needed
    }

    // Method to get all job offers
    public function getJobPosts()
    {
        return $this->jobPostModel->getJobPosts();
    }

    // Method to handle job offer update
    public function updateJobPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate input data
            $postData = $this->sanitizeInput($_POST);
            error_log(print_r($postData, true)); // Log sanitized POST data

            // Call the model's updateJobPost function with jobPostId and postData
            if ($this->jobPostModel->updateJobPost($postData['jobPostId'], $postData)) {
                // Redirect on success
                header("Location: ../View/JobPosting.php?msg=success");
                exit();
            } else {
                // Redirect with error message if the update fails
                header("Location: ../View/JobPosting.php?msg=error");
                exit();
            }
        } else {
            return false; // Invalid request method
        }
    }

    // Method to handle creating a new job offer
    public function createJobPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input data
            $postData = $this->sanitizeInput($_POST);
            error_log(print_r($postData, true)); // Log sanitized POST data

            // Call the create method
            if ($this->jobPostModel->createJobPost($postData)) {
                // Redirect on success
                header("Location: ../View/JobPosting.php?msg=success");
                exit(); // Make sure to exit after a redirect
            } else {
                // Handle the error if the create fails
                header("Location: ../View/JobPosting.php?msg=error");
                exit();
            }
        } else {
            return false; // Indicate invalid request method
        }
    }

    // Method to delete job offer
    public function deleteJobPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jobPostId = $_POST['jobPostId'] ?? '';

            if (empty($jobPostId)) {
                header("Location: ../View/ReqcruitmentPage.php?msg=missing_id");
                exit();
            }

            if ($this->jobPostModel->deleteJobPost($jobPostId)) {
                header("Location: ../View/JobPosting.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/JobPosting.php?msg=delete_error");
                exit();
            }
        }
    }

    // For job landing page
    public function handleSearchAndFilters()
    {
        $search = $_GET['search'] ?? '';
        $filter_time = $_GET['filter_time'] ?? '';
        $filter_type = $_GET['filter_type'] ?? '';
        $filter_minSalary = $_GET['filter_minSalary'] ?? '';

        return $this->jobPostModel->handleSearchAndFilters($search, $filter_time, $filter_type, $filter_minSalary);
    }
    // Method to sanitize input data
    private function sanitizeInput($data)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,b,i,u,br,ul,ol,li,strong,em');

        $purifier = new HTMLPurifier($config);

        if (is_array($data)) {
            return array_map(function ($value) use ($purifier) {
                return $purifier->purify(trim($value));
            }, $data);
        } else {
            return $purifier->purify(trim($data));
        }
    }
}

$controller = new JobPostController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update':
            $controller->updateJobPost();
            break;
        case 'create':
            $controller->createJobPost();
            break;
        case 'delete':
            $controller->deleteJobPost();
            break;
        default:
            break;
    }
}
