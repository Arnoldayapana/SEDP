<?php
require_once(__DIR__ . '/../../../lib/htmlpurifier/library/HTMLPurifier.auto.php');

class JobPostModel
{
    private $pdo;

    public function __construct()
    {
        // Include the database class file
        include_once(__DIR__ . '/../../../Database/database.php');


        // include("../../../../Database/database.php"); // Adjust the path as necessary

        // Create a new instance of the Database class
        $database = new Database();

        // Get the PDO connection
        $this->pdo = $database->connect(); // Use the connect method to establish a connection
    }

    // Method to retrieve the job offer name by jobOfferId
    public function getJobIdById($jobPostId)
    {
        $sql = "SELECT JobId FROM tbljobpost WHERE JobPostId = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$jobPostId]);

        // Fetch the job offer
        $jobId = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the JobId if found, else return an empty string
        return $jobId ? $jobId['JobId'] : '';
    }
    public function getJobPosts($limit = 6)
    {
        $sql = " 
        SELECT 
            j.jobId,               
            j.jobTitle,
            j.jobDescription,
            j.jobQualification,
            j.jobKeyResponsibilities,   
            jp.jobPostId,
            jp.applicantSize,
            jp.minimumSalary,
            jp.maximumSalary,
            jp.datePosted,
            jp.expiryDate,
            d.departmentId,
            d.name AS departmentName,
            br.country,
            br.region,
            br.province,
            br.city, 
            be.benefitId,
            be.benefit,
            emp.employmentTypeId,
            emp.employmentType
        FROM 
            tblJobPost jp
        JOIN 
            tblJob j ON jp.jobId = j.jobId
        JOIN 
            tblDepartment d ON jp.departmentId = d.departmentId
        JOIN 
            tblBranch br ON d.branchId = br.branchId
        JOIN 
            tblBenefit be ON jp.benefitId = be.benefitId
        JOIN 
            tblEmploymentType emp ON jp.employeeTypeId = emp.employmentTypeId
        LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT); // Bind the limit parameter
        $stmt->execute();

        // Fetch all the job offers as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getJobPostsWithAllFilters($filter_time, $filter_type, $filter_job, $search)
    {
        $sql = " 
        SELECT 
            j.jobId,               
            j.title,
            j.description AS JobDescription,
            j.qualification,
            j.keyResponsibilities,   
            jp.jobPostId,
            jp.applicantSize,
            jp.minimumSalary,
            jp.maximumSalary,
            jp.datePosted,
            jp.expiryDate,
            d.departmentId,
            d.name AS departmentName,
            br.branchId,
            br.country,
            br.region,
            br.province,
            br.city, 
            be.benefitId,
            be.benefit,
            emp.employmentTypeId,
            emp.employmentType
        FROM 
            tblJobPost jp
        JOIN 
            tblJob j ON jp.jobId = j.jobId
        JOIN 
            tblDepartment d ON jp.departmentId = d.departmentId
        JOIN 
            tblBranch br ON d.branchId = br.branchId
        JOIN 
            tblBenefit be ON jp.benefitId = be.benefitId
        JOIN 
            tblEmploymentType emp ON jp.employeeTypeId = emp.employmentTypeId
        LIMIT :limit";


        // Apply search logic
        if (!empty($search)) {
            $sql .= " AND j.title LIKE :search";
        }

        // Apply filter logic based on time
        if ($filter_time) {
            $dateCondition = '';
            switch ($filter_time) {
                case '3d':
                    $dateCondition = 'DATE_SUB(CURDATE(), INTERVAL 3 DAY)';
                    break;
                case '7d':
                    $dateCondition = 'DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
                    break;
                case '14d':
                    $dateCondition = 'DATE_SUB(CURDATE(), INTERVAL 14 DAY)';
                    break;
                case '30d':
                    $dateCondition = 'DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
                    break;
            }
            if ($dateCondition) {
                $sql .= " AND jp.datePosted >= $dateCondition";
            }
        }

        // Apply filter logic based on job type
        if ($filter_type) {
            $sql .= " AND emp.employmentType = :filter_type";
        }

        // Apply filter logic for sorting job offers
        if ($filter_job === 'newest') {
            $sql .= " ORDER BY jp.datePosted DESC";
        } elseif ($filter_job === 'oldest') {
            $sql .= " ORDER BY jp.datePosted ASC";
        }

        $stmt = $this->pdo->prepare($sql);

        // Bind search parameter if it exists
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        // Bind filter type parameter if it exists
        if ($filter_type) {
            $stmt->bindParam(':filter_type', $filter_type, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to retrieve job offers with filtering and search criteria
    public function getJobPostsWithFilters($filter, $search)
    {
        $sql = " 
    SELECT 
        jp.jobPostId,
        jp.applicantSize,
        jp.minimumSalary,
        jp.maximumSalary,
        jp.datePosted,
        jp.expiryDate,
        
        j.jobId,
        j.jobTitle,
        
        d.departmentId,
        d.name AS departmentName,
        
        et.employmentTypeId,
        et.employmentType,
        
        b.branchId,
        b.country,
        b.region,
        b.province,
        b.city, 
        
        GROUP_CONCAT(bf.benefit SEPARATOR ', ') AS benefits
    FROM 
        tblJobPost jp
        LEFT JOIN tblJob j ON jp.jobId = j.jobId
        LEFT JOIN tblDepartment d ON jp.departmentId = d.departmentId
        LEFT JOIN tblEmploymentType et ON jp.employeeTypeId = et.employmentTypeId
        LEFT JOIN tblBranch b ON d.branchId = b.branchId
        LEFT JOIN tblJobPostBenefit jb ON jp.jobPostId = jb.jobPostId
        LEFT JOIN tblBenefit bf ON jb.benefitId = bf.benefitId
    GROUP BY 
        jp.jobPostId
    ";

        // Apply search logic
        if (!empty($search)) {
            // Move the WHERE clause before the GROUP BY
            $sql .= " HAVING j.jobTitle LIKE :search";
        }

        // Apply filter logic
        if ($filter === 'newest') {
            $sql .= " ORDER BY jp.datePosted DESC";
        } elseif ($filter === 'oldest') {
            $sql .= " ORDER BY jp.datePosted ASC";
        } else {
            // Default ordering
            $sql .= " ORDER BY jp.jobPostId";
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


    // Method to update job offer
    public function updateJobPost($jobPostId, $postData)
    {
        // Sanitize and prepare variables
        $jobPostId = trim($jobPostId);
        $applicantSize = trim($postData['applicantSize'] ?? '');
        $minimumSalary = str_replace(',', '', trim($postData['minimumSalary'] ?? ''));
        $maximumSalary = str_replace(',', '', trim($postData['maximumSalary'] ?? ''));
        $jobPostDescription = trim($postData['jobPostDescription']);
        $jobPostKeyResponsibilities = trim($postData['jobPostKeyResponsibilities']);
        $jobPostQualification = trim($postData['jobPostQualification']);
        $expiryDate = isset($postData['expiryDate']) && !empty(trim($postData['expiryDate'])) ? trim($postData['expiryDate']) : null;
        $jobId = trim($postData['jobId'] ?? '');
        $departmentId = trim($postData['departmentId'] ?? null); // Allow NULL
        $employeeTypeId = trim($postData['employeeTypeId'] ?? null); // Allow NULL
        $benefitIds = trim($postData['benefitId'] ?? ''); // Comma-separated benefit IDs

        // Update the main job post data in tblJobPost
        $stmt = $this->pdo->prepare("UPDATE tblJobPost SET applicantSize = ?, minimumSalary = ?, maximumSalary = ?, jobPostDescription= ?, jobPostKeyResponsibilities= ?, jobPostQualification= ?, expiryDate = ?, jobId = ?, departmentId = ?, employeeTypeId = ? WHERE jobPostId = ?");

        if ($stmt->execute([$applicantSize, $minimumSalary, $maximumSalary, $jobPostDescription, $jobPostKeyResponsibilities, $jobPostQualification, $expiryDate, $jobId, $departmentId, $employeeTypeId, $jobPostId])) {
            // Delete existing benefits associated with this job post
            $stmtDeleteBenefits = $this->pdo->prepare("DELETE FROM tblJobPostBenefit WHERE jobPostId = ?");
            $stmtDeleteBenefits->execute([$jobPostId]);

            // Insert updated benefits into tblJobPostBenefit
            if (!empty($benefitIds)) {
                $benefitIdsArray = explode(',', $benefitIds); // Split comma-separated benefit IDs
                $stmtInsertBenefit = $this->pdo->prepare("INSERT INTO tblJobPostBenefit (jobPostId, benefitId) VALUES (?, ?)");

                foreach ($benefitIdsArray as $benefitId) {
                    $stmtInsertBenefit->execute([$jobPostId, $benefitId]);
                }
            }

            error_log("Job post updated successfully: ID $jobPostId");
            return true; // Successful update
        } else {
            // Log error for debugging
            error_log("SQL Error during update: " . print_r($stmt->errorInfo(), true));
            return false; // Update failed
        }
    }


    public function createJobPost($postData)
    {
        // Initialize variables from the post data
        $applicantSize = trim($postData['applicantSize'] ?? '');
        $minimumSalary = str_replace(',', '', trim($postData['minimumSalary'] ?? ''));
        $maximumSalary = str_replace(',', '', trim($postData['maximumSalary'] ?? ''));
        $jobPostDescription = trim($postData['jobPostDescription'] ?? '');
        $jobPostKeyResponsibilities = trim($postData['jobPostKeyResponsibilities'] ?? '');
        $jobPostQualification = trim($postData['jobPostQualification'] ?? '');
        $expiryDate = empty(trim($postData['expiryDate'])) ? null : trim($postData['expiryDate']);
        $jobId = trim($postData['jobId'] ?? '');
        $departmentId = trim($postData['departmentId'] ?? null); // Allow NULL
        $employeeTypeId = trim($postData['employeeTypeId'] ?? null); // Allow NULL
        $benefitIds = trim($postData['benefitId'] ?? ''); // Comma-separated benefit IDs

        // Step 1: Insert the job post into tbljobpost
        $stmt = $this->pdo->prepare("INSERT INTO tbljobpost 
        (applicantSize, minimumSalary, maximumSalary, datePosted, jobPostDescription, 
         jobPostKeyResponsibilities, jobPostQualification, expiryDate, jobId, departmentId, employeeTypeId) 
        VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt->execute([
            $applicantSize,
            $minimumSalary,
            $maximumSalary,
            $jobPostDescription,
            $jobPostKeyResponsibilities,
            $jobPostQualification,
            $expiryDate,
            $jobId,
            $departmentId,
            $employeeTypeId
        ])) {
            // Get the last inserted jobPostId
            $jobPostId = $this->pdo->lastInsertId();

            // Step 2: Insert benefits into tbljobpostbenefit if any were provided
            if (!empty($benefitIds)) {
                // Split the comma-separated benefit IDs into an array
                $benefitIdsArray = explode(',', $benefitIds);
                $stmtBenefit = $this->pdo->prepare("INSERT INTO tbljobpostbenefit (jobPostId, benefitId) VALUES (?, ?)");

                // Insert each benefit ID for the job post
                foreach ($benefitIdsArray as $benefitId) {
                    // Ensure each benefitId is trimmed to avoid unwanted spaces
                    $stmtBenefit->execute([$jobPostId, trim($benefitId)]);
                }
            }

            return true; // Return true if all insertions were successful
        } else {
            // Log error information if job post insertion fails
            error_log("SQL Error during job post insert: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }


    public function deleteJobPost($jobPostId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tblJobPost WHERE jobPostId = ?");
        return $stmt->execute([$jobPostId]);
    }

    public function handleSearchAndFilters($search, $filter_time, $filter_type, $filter_minSalary)
    {
        // Start building the SQL query with WHERE 1=1 for flexible conditions
        $query = "
            SELECT 
                j.jobId,               
                j.jobTitle,
                j.jobDescription,
                j.jobQualification,
                j.jobKeyResponsibilities,   
    
                jp.jobPostId,
                jp.applicantSize,
                jp.minimumSalary,
                jp.maximumSalary,
                jp.datePosted,
                jp.expiryDate,
    
                d.departmentId,
                d.name AS departmentName,
    
                br.country,
                br.region,
                br.province,
                br.city,
    
                emp.employmentTypeId,
                emp.employmentType,
    
                GROUP_CONCAT(be.benefit ORDER BY be.benefit SEPARATOR ', ') AS benefits
            FROM 
                tblJobPost jp
            JOIN 
                tblJob j ON jp.jobId = j.jobId
            JOIN 
                tblDepartment d ON jp.departmentId = d.departmentId
            JOIN 
                tblBranch br ON d.branchId = br.branchId
            JOIN 
                tblEmploymentType emp ON jp.employeeTypeId = emp.employmentTypeId
            LEFT JOIN 
                tblJobPostBenefit jpb ON jp.jobPostId = jpb.jobPostId
            LEFT JOIN 
                tblBenefit be ON jpb.benefitId = be.benefitId
            WHERE 
                1=1";  // Acts as a base for adding filters dynamically

        // Apply search filter if provided
        if (!empty($search)) {
            $query .= " AND j.jobTitle LIKE :search";
        }

        // Apply filter_time if provided
        if ($filter_time === '3d') {
            $query .= " AND jp.datePosted >= CURDATE() - INTERVAL 3 DAY";
        } elseif ($filter_time === '7d') {
            $query .= " AND jp.datePosted >= CURDATE() - INTERVAL 7 DAY";
        } elseif ($filter_time === '14d') {
            $query .= " AND jp.datePosted >= CURDATE() - INTERVAL 14 DAY";
        } elseif ($filter_time === '30d') {
            $query .= " AND jp.datePosted >= CURDATE() - INTERVAL 30 DAY";
        }

        // Apply filter_type for employment type if provided
        if (!empty($filter_type)) {
            $query .= " AND emp.employmentType = :filter_type";
        }

        // Apply filter for minimum salary if provided
        if (!empty($filter_minSalary)) {
            $query .= " AND jp.minimumSalary >= :filter_minSalary";
        }

        // Filter for active job posts (optional)
        $query .= " AND (jp.expiryDate IS NULL OR jp.expiryDate >= CURDATE())";

        // Group by and order the results
        $query .= " 
            GROUP BY 
                jp.jobPostId, j.jobId, d.departmentId, br.branchId, emp.employmentTypeId
            ORDER BY 
                jp.datePosted DESC";

        // Prepare and execute the query with prepared statements
        $stmt = $this->pdo->prepare($query);

        // Bind parameters
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        if (!empty($filter_type)) {
            $stmt->bindValue(':filter_type', $filter_type, PDO::PARAM_STR);
        }
        if (!empty($filter_minSalary)) {
            $stmt->bindValue(':filter_minSalary', $filter_minSalary, PDO::PARAM_INT);
        }

        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJobPostById($jobPostId)
    {
        if (is_numeric($jobPostId)) {
            $sql = "
            SELECT 
                j.jobId,               
                j.jobTitle,      
                jp.jobPostId,
                jp.applicantSize,
                jp.minimumSalary,
                jp.maximumSalary,
                jp.jobPostDescription,
                jp.jobPostQualification,
                jp.jobPostKeyResponsibilities, 
                jp.datePosted,
                jp.expiryDate,
                d.departmentId,
                d.name AS departmentName,
                br.country,
                br.region,
                br.province,
                br.city,
                emp.employmentTypeId,
                emp.employmentType,
                GROUP_CONCAT(be.benefit ORDER BY be.benefit SEPARATOR ', ') AS benefits
            FROM 
                tblJobPost jp
            JOIN 
                tblJob j ON jp.jobId = j.jobId
            JOIN 
                tblDepartment d ON jp.departmentId = d.departmentId
            JOIN 
                tblBranch br ON d.branchId = br.branchId
            JOIN 
                tblEmploymentType emp ON jp.employeeTypeId = emp.employmentTypeId
            LEFT JOIN 
                tblJobPostBenefit jpb ON jp.jobPostId = jpb.jobPostId
            LEFT JOIN 
                tblBenefit be ON jpb.benefitId = be.benefitId
            WHERE 
                jp.jobPostId = ?
            GROUP BY 
                jp.jobPostId"; // Grouping by jobPostId to aggregate benefits properly

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$jobPostId]);

            // Fetch the job post details
            $jobPostDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the job post details if found, else return null
            return $jobPostDetails ?: null;
        } else {
            error_log("Invalid job post ID provided: " . htmlspecialchars($jobPostId));
            return null;
        }
    }
}
