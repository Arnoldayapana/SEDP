<?php

class EmploymentTypeModel
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

    public function getAllEmploymentTypes()
    {
        $query = "
            SELECT * FROM tblemploymenttype 
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative arrays
    }

    public function deleteEmploymentType($employmentTypeId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tblemploymenttype WHERE employmentTypeId = ?");
        return $stmt->execute([$employmentTypeId]);
    }

    public function updateEmploymentType($employmentTypeId, $employmentType)
    {

        // Sanitize input data
        $employmentTypeId = htmlspecialchars(trim($employmentTypeId));
        $employmentType = htmlspecialchars(trim($employmentType));


        // Prepare the SQL statement
        $stmt = $this->pdo->prepare("UPDATE tblemploymenttype SET employmentType = ? WHERE employmentTypeId = ?");

        // Attempt to execute the statement with the provided parameters
        if ($stmt->execute([$employmentType, $employmentTypeId])) {
            // Log success
            error_log("EmploymentType updated successfully: ID $employmentTypeId");
            return true; // Return true for successful execution
        } else {
            // Log the error for debugging
            error_log("SQL Error during update: " . print_r($stmt->errorInfo(), true));

            // Return false for unsuccessful execution
            return false;
        }
    }

    public function createEmploymentType($postData)
    {
        // Attributes
        $employmentType = '';

        $employmentType  = htmlspecialchars(trim($postData['employmentType']));

        // SQL query with placeholders for parameters
        $stmt = $this->pdo->prepare("INSERT INTO tblemploymenttype (employmentType) VALUES (?)");

        // Execute the query with the provided parameters
        if ($stmt->execute([$employmentType])) {
            return true;
        } else {
            // Log error information in case of failure
            error_log("SQL Error during insert: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    // Method to retrieve job offers with filtering and search criteria
    public function getFilteredEmploymentType($search)
    {
        $query = "
        SELECT * FROM tblemploymenttype
        WHERE 1=1
    ";

        // Apply search logic
        if (!empty($search)) {
            $query .= " AND employmentType LIKE :search";
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
