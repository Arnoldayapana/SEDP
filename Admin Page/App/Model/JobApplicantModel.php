<?php
class JobApplicantModel
{
    private $pdo; // Database connection

    public function __construct()
    {
        // Include the database class file
        include_once(__DIR__ . '/../../../Database/database.php');

        // Create a new instance of the Database class
        $database = new Database();

        // Get the PDO connection
        $this->pdo = $database->connect(); // Use the connect method to establish a connection
    }

    public function getApplicantInterviewDatebyUniqId($uniqId)
    {
        $sql = "SELECT interviewDatetime FROM tbljobapplicant WHERE uniqueId = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$uniqId]);

        $interviewDatetime = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return false if no result is found, otherwise return the array
        return $interviewDatetime ?: false;
    }


    public function getJobApplicantsWithFilters($filter, $search)
    {
        $sql = "SELECT 
        -- From tblJobApplicant
        ja.applicantId,
        ja.uniqueId,
        ja.name AS applicantName,
        ja.email,
        ja.contactNumber,
        ja.appliedDate,
        ja.formFileName,
        ja.formFileSize,
        ja.formFileType,
        
        ja.letterFileName,
        ja.letterFileSize,
        ja.letterFileType,

        ja.photoFileName,
        ja.photoFileSize,
        ja.photoFileType,

        ja.status,
        ja.jobPostId,

        -- From tblJobPost
        jo.jobPostId AS jobPostId,  -- Aliased to avoid conflicts
        jo.jobId,
        jo.departmentId,

        -- From tblJob
        j.jobId,
        j.jobTitle,

        -- From tblDepartment
        d.departmentId,
        d.name AS departmentName,
        d.branchId,

        -- From tblBranch
        b.branchId,
        b.name AS branchName,
        b.country,
        b.region,
        b.province,
        b.city

    FROM 
        tbljobapplicant ja
    JOIN 
        tblJobPost jo ON ja.jobPostId = jo.jobPostId
    JOIN 
        tblJob j ON jo.jobId = j.jobId
    JOIN 
        tblDepartment d ON jo.departmentId = d.departmentId
    JOIN 
        tblBranch b ON d.branchId = b.branchId
    WHERE 1=1
    ";

        // Apply search logic
        if (!empty($search)) {
            $sql .= " AND ja.name LIKE :search";
        }

        // Apply filter logic
        if ($filter === 'newest') {
            $sql .= " ORDER BY ja.appliedDate DESC";
        } elseif ($filter === 'oldest') {
            $sql .= " ORDER BY ja.appliedDate ASC";
        }

        $stmt = $this->pdo->prepare($sql);

        // Bind search parameter if it exists
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function viewApplicantStatus($statusId)
    {

        $sql = "SELECT status FROM tbljobapplicant WHERE uniqueId = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$statusId]);

        // Fetch the status
        $Status = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the Status if found, else return an empty string
        return $Status ? $Status['status'] : 'Please insert valid Id';
    }

    // Function to create an applicant with file upload handling
    public function createApplicant($uniqueIdentifier, $postData, $formFileName, $formFileSize, $formFileType, $letterFileName, $letterFileSize, $letterFileType, $photoFileName, $photoFileSize, $photoFileType)
    {

        error_log(print_r([$letterFileName, $letterFileSize, $letterFileType], true)); // Log the values before insertion

        // Attributes
        $name = htmlspecialchars(trim($postData['lastName'])) . ', ' .
            htmlspecialchars(trim($postData['firstName'])) . ', ' .
            htmlspecialchars(trim($postData['middleName']));
        $email = htmlspecialchars(trim($postData['email']));
        $contactNumber = htmlspecialchars(trim($postData['contactNumber']));
        $jobPostId = $postData['jobPostId'];
        $status = "Pending"; // Default status

        // Store application data in the database (including file metadata)
        $sql = "INSERT INTO tbljobapplicant (uniqueId, name, email, contactNumber, appliedDate, formFileName, formFileSize, formFileType, letterFileName, letterFileSize, letterFileType, photoFileName, photoFileSize, photoFileType, status, jobPostId) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute([$uniqueIdentifier, $name, $email, $contactNumber, $formFileName, $formFileSize, $formFileType, $letterFileName, $letterFileSize, $letterFileType, $photoFileName, $photoFileSize, $photoFileType, $status, $jobPostId])) {
            return $uniqueIdentifier;
        } else {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
            $errorMessage = "There was an error submitting your application. Please try again.";
            header("location:../../../JobApplicantPage/View/JobApplication.php?msg=" . urlencode($errorMessage));
            exit();
        }
    }


    public function deleteApplicant($applicantId)
    {
        // Retrieve file names associated with the applicant
        $stmt = $this->pdo->prepare("SELECT formFileName, letterFileName, photoFileName FROM tbljobapplicant WHERE applicantId = ?");
        $stmt->execute([$applicantId]);
        $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check and delete the form file if it exists
        if ($applicant && $applicant['formFileName']) {
            $formFileName = $applicant['formFileName'];
            $formFilePath = "../../../Database/uploads/" . $formFileName; // Define the file path

            // Delete the form file from the directory if it exists
            if (file_exists($formFilePath)) {
                unlink($formFilePath); // Delete the file
            }
        }

        // Check and delete the letter file if it exists
        if ($applicant && $applicant['letterFileName']) {
            $letterFileName = $applicant['letterFileName'];
            $letterFilePath = "../../../Database/uploads/" . $letterFileName; // Define the file path

            // Delete the letter file from the directory if it exists
            if (file_exists($letterFilePath)) {
                unlink($letterFilePath); // Delete the file
            }
        }

        // Check and delete the photo file if it exists
        if ($applicant && $applicant['photoFileName']) {
            $photoFileName = $applicant['photoFileName'];
            $photoFilePath = "../../../Database/uploads/" . $photoFileName; // Define the file path

            // Delete the photo file from the directory if it exists
            if (file_exists($photoFilePath)) {
                unlink($photoFilePath); // Delete the file
            }
        }

        // Delete the applicant from the database
        $stmt = $this->pdo->prepare("DELETE FROM tbljobapplicant WHERE applicantId = ?");
        return $stmt->execute([$applicantId]);
    }


    // Method to update applicant status
    public function updateStatus($applicantId, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE tblJobApplicant SET status = ? WHERE applicantId = ?");
        $stmt->execute([$status, $applicantId]);

        return $stmt->rowCount() > 0; // Return true if updated
    }

    // Method to update applicant status and interview date-time
    public function updateStatusAndInterview($applicantId, $status, $interviewDatetime)
    {
        // Prepare SQL statement
        $stmt = $this->pdo->prepare("UPDATE tblJobApplicant SET status = ?, interviewDatetime = ? WHERE applicantId = ?");

        // Execute the statement with the parameters
        $stmt->execute([$status, $interviewDatetime, $applicantId]);

        // Return true if at least one row was updated
        return $stmt->rowCount() > 0;
    }
}
