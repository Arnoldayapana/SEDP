<?php

include_once(__DIR__ . '/../Model/JobModel.php');
require_once(__DIR__ . '/../../../lib/htmlpurifier/library/HTMLPurifier.auto.php');

class JobController
{
    private $jobModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
    }

    // Method to get the Job details by ID
    public function getJobDetails($jobId)
    {
        $jobId = filter_var($jobId, FILTER_VALIDATE_INT);
        if ($jobId === false) {
            return null; // Invalid ID
        }

        $jobDetails = $this->jobModel->getJobById($jobId);
        return $jobDetails ?: null;
    }

    public function getAllJobs()
    {
        return $this->jobModel->getAllJobs();
    }

    public function getFilteredJob($search = '')
    {
        $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
        return $this->jobModel->getFilteredJob($search);
    }

    public function createJob()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->sanitizeInput($_POST);

            $title = $postData['jobTitle'] ?? '';
            $description = $postData['jobDescription'] ?? '';
            $qualification = $postData['jobQualification'] ?? '';
            $keyResponsibilities = $postData['jobKeyResponsibilities'] ?? '';

            if ($this->jobModel->createJob($title, $description, $qualification, $keyResponsibilities)) {
                header("Location: ../View/RecruitmentSetup.php?msg=success");
                exit();
            } else {
                header("Location: ../View/RecruitmentSetup.php?msg=error");
                exit();
            }
        }
        return false;
    }

    public function deleteJob($jobId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jobId = filter_var($jobId, FILTER_VALIDATE_INT);
            if ($jobId === false) {
                header("Location: ../View/RecruitmentSetup.php?msg=invalid_id");
                exit();
            }

            if ($this->jobModel->deleteJob($jobId)) {
                header("Location: ../View/RecruitmentSetup.php?msg=deleted");
                exit();
            } else {
                header("Location: ../View/RecruitmentSetup.php?msg=delete_error");
                exit();
            }
        }
    }

    public function updateJob()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->sanitizeInput($_POST);

            $jobId = filter_var($postData['jobId'] ?? '', FILTER_VALIDATE_INT);
            $title = $postData['title'] ?? '';
            $description = $postData['description'] ?? '';
            $qualification = $postData['qualification'] ?? '';
            $keyResponsibilities = $postData['keyResponsibilities'] ?? '';

            if ($this->jobModel->updateJob($jobId, $title, $description, $qualification, $keyResponsibilities)) {
                header("Location: ../View/RecruitmentSetup.php?msg=success");
                exit();
            } else {
                header("Location: ../View/RecruitmentSetup.php?msg=error");
                exit();
            }
        }
        return false;
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

$controller = new JobController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update':
            $controller->updateJob();
            break;
        case 'create':
            $controller->createJob();
            break;
        case 'delete':
            $jobId = $_POST['jobId'] ?? '';
            $controller->deleteJob($jobId);
            break;
        default:
            break;
    }
}
