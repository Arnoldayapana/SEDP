<?php

class DepartmentModel
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

    public function getAllDepartmentsWithBranchLocations()
    {
        $query = "
            SELECT 
                d.departmentId, 
                d.name AS DepartmentName,
                b.branchId,
                b.name AS BranchName,
                b.country,
                b.region,
                b.province,
                b.city 
            FROM 
                tblDepartment d
            JOIN 
                tblBranch b ON d.branchId = b.branchId;
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative arrays
    }

    public function getAllDepartmentsWithBranch()
    {
        $query = "
            SELECT 
                d.departmentId,
                d.name AS DepartmentName,

                b.name AS BranchName
                b.country,
                b.region,
                b.province,
                b.city 

            FROM 
                tblDepartment d
            JOIN 
                tblBranch b ON d.branchId = b.branchId;
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative arrays
    }

    public function deleteDepartment($departmentId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tblDepartment WHERE departmentId = ?");
        return $stmt->execute([$departmentId]);
    }

    public function updateDepartment($DepartmentId, $DepartmentName, $BranchId)
    {

        // Sanitize input data
        $departmentId = htmlspecialchars(trim($DepartmentId));
        $name = htmlspecialchars(trim($DepartmentName));
        $branchId = htmlspecialchars(trim($BranchId));

        // Prepare the SQL statement
        $stmt = $this->pdo->prepare("UPDATE tblDepartment SET name = ?, branchId = ? WHERE departmentId = ?");

        // Attempt to execute the statement with the provided parameters
        if ($stmt->execute([$name, $branchId, $departmentId])) {
            // Log success
            error_log("department updated successfully: ID $departmentId");
            return true; // Return true for successful execution
        } else {
            // Log the error for debugging
            error_log("SQL Error during update: " . print_r($stmt->errorInfo(), true));

            // Return false for unsuccessful execution
            return false;
        }
    }

    public function createDepartment($postData)
    {
        // Attributes
        $name = '';
        $branchId = '';

        $name  = htmlspecialchars(trim($postData['DepartmentName']));

        $branchId = htmlspecialchars(trim($postData['BranchId']));

        // SQL query with placeholders for parameters
        $stmt = $this->pdo->prepare("INSERT INTO tblDepartment (name, branchId) VALUES ( ?, ?)");

        // Execute the query with the provided parameters
        if ($stmt->execute([$name, $branchId])) {
            return true;
        } else {
            // Log error information in case of failure
            error_log("SQL Error during insert: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    // Method to retrieve job offers with filtering and search criteria
    public function getFilteredDepartment($search)
    {
        $query = "
        SELECT 
            d.departmentId,
            d.name AS DepartmentName,
            b.branchId,
            b.name AS BranchName,
            b.country,
            b.region,
            b.province,
            b.city 
        FROM 
            tblDepartment d
        JOIN 
            tblBranch b ON d.branchId = b.branchId
        WHERE 1=1
    ";

        // Apply search logic
        if (!empty($search)) {
            $query .= " AND d.name LIKE :search";
        }

        $stmt = $this->pdo->prepare($query);

        // Bind search parameter if it exists
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
