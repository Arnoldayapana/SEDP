<?php

class BenefitModel
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

    public function getAllBenefits()
    {
        $query = "
            SELECT * FROM tblbenefit 
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative arrays
    }

    public function deleteBenefit($benefitId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tblbenefit WHERE benefitId = ?");
        return $stmt->execute([$benefitId]);
    }

    public function updateBenefit($benefitId, $benefit)
    {

        // Sanitize input data
        $benefitId = htmlspecialchars(trim($benefitId));
        $benefit = htmlspecialchars(trim($benefit));


        // Prepare the SQL statement
        $stmt = $this->pdo->prepare("UPDATE tblbenefit SET benefit = ? WHERE benefitId = ?");

        // Attempt to execute the statement with the provided parameters
        if ($stmt->execute([$benefit, $benefitId])) {
            // Log success
            error_log("Benefit updated successfully: ID $benefitId");
            return true; // Return true for successful execution
        } else {
            // Log the error for debugging
            error_log("SQL Error during update: " . print_r($stmt->errorInfo(), true));

            // Return false for unsuccessful execution
            return false;
        }
    }

    public function createBenefit($postData)
    {
        // Attributes
        $benefit = '';

        $benefit  = htmlspecialchars(trim($postData['benefit']));

        // SQL query with placeholders for parameters
        $stmt = $this->pdo->prepare("INSERT INTO tblbenefit (benefit) VALUES (?)");

        // Execute the query with the provided parameters
        if ($stmt->execute([$benefit])) {
            return true;
        } else {
            // Log error information in case of failure
            error_log("SQL Error during insert: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    // Method to retrieve job offers with filtering and search criteria
    public function getFilteredBenefit($search)
    {
        $query = "
        SELECT * FROM tblbenefit
        WHERE 1=1
    ";

        // Apply search logic
        if (!empty($search)) {
            $query .= " AND benefit LIKE :search";
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
