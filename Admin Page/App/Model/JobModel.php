<?php

class JobModel
{
    private $pdo;

    public function __construct()
    {
        // Include the database class file
        include_once(__DIR__ . '/../../../Database/database.php');

        // Create a new instance of the Database class
        $database = new Database();

        // Get the PDO connection
        $this->pdo = $database->connect(); // Use the connect method to establish a connection
    }

    // Method to retrieve the title by jobId with validation and prepared statement
    public function getJobById($jobId)
    {
        if (is_numeric($jobId)) {
            $sql = "SELECT jobtitle, jobDescription, jobQualification, jobKeyResponsibilities FROM tbljob WHERE JobId = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$jobId]);

            // Fetch the job details
            $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the job details if found, else return null
            return $jobDetails ?: null;
        } else {
            error_log("Invalid job ID provided: " . htmlspecialchars($jobId));
            return null;
        }
    }

    public function getAllJobs()
    {
        $sql = "SELECT * FROM tbljob";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createJob($title, $description, $qualification, $keyResponsibilities)
    {
        try {
            // Prepare SQL query with placeholders for parameters
            $stmt = $this->pdo->prepare("INSERT INTO tblJob (jobTitle, jobDescription, jobQualification, jobKeyResponsibilities) VALUES (?, ?, ?, ?)");

            // Execute the query with the provided parameters
            $stmt->execute([$title, $description, $qualification, $keyResponsibilities]);
            return true;
        } catch (PDOException $e) {
            // Log error information in case of failure
            error_log("SQL Error during insert: " . $e->getMessage());
            return false;
        }
    }

    public function updateJob($jobId, $title, $description, $qualification, $keyResponsibilities)
    {
        if (is_numeric($jobId)) {
            try {
                // Prepare the SQL statement with placeholders
                $stmt = $this->pdo->prepare("UPDATE tblJob SET jobTitle = ?, jobDescription = ?, jobQualification = ?, jobKeyResponsibilities = ? WHERE jobId = ?");

                // Execute the query with the provided parameters
                $stmt->execute([$title, $description, $qualification, $keyResponsibilities, $jobId]);
                return true;
            } catch (PDOException $e) {
                // Log the error for debugging
                error_log("SQL Error during update: " . $e->getMessage());
                return false;
            }
        } else {
            error_log("Invalid job ID provided: " . htmlspecialchars($jobId));
            return false;
        }
    }

    public function deleteJob($jobId)
    {
        if (is_numeric($jobId)) {
            try {
                $stmt = $this->pdo->prepare("DELETE FROM tblJob WHERE jobId = ?");
                return $stmt->execute([$jobId]);
            } catch (PDOException $e) {
                error_log("SQL Error during delete: " . $e->getMessage());
                return false;
            }
        } else {
            error_log("Invalid job ID provided: " . htmlspecialchars($jobId));
            return false;
        }
    }

    // Method to retrieve job offers with filtering and search criteria
    public function getFilteredJob($search)
    {
        $sql = "SELECT * FROM tblJob WHERE 1=1";

        // Apply search logic if search term is provided
        if (!empty($search)) {
            $sql .= " AND jobTitle LIKE :search";
        }

        $stmt = $this->pdo->prepare($sql);

        // Bind search parameter if it exists
        if (!empty($search)) {
            $searchParam = "%" . $search . "%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
