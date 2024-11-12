<?php

class BranchModel
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

    public function getAllBranch()
    {
        $sql = "SELECT * FROM tblbranch";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteBranch($branchId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tblBranch WHERE branchId = ?");
        return $stmt->execute([$branchId]);
    }

    public function updateBranch($branchId, $name, $country, $region, $province, $city)
    {

        // Sanitize input data
        $branchId = htmlspecialchars(trim($branchId));
        $name = htmlspecialchars(trim($name));
        $country = htmlspecialchars(trim($country));
        $region = htmlspecialchars(trim($region));
        $province = htmlspecialchars(trim($province));
        $city = htmlspecialchars(trim($city));

        // Prepare the SQL statement
        $stmt = $this->pdo->prepare("UPDATE tblBranch SET name = ?, country = ?, region = ? , province = ?, city = ? WHERE branchId = ?");

        // Attempt to execute the statement with the provided parameters
        if ($stmt->execute([$name, $country, $region, $province, $city, $branchId])) {
            // Log success
            error_log("branch updated successfully: ID $branchId");
            return true; // Return true for successful execution
        } else {
            // Log the error for debugging
            error_log("SQL Error during update: " . print_r($stmt->errorInfo(), true));

            // Return false for unsuccessful execution
            return false;
        }
    }

    public function createBranch($postData)
    {
        // Attributes
        $name = htmlspecialchars(trim($postData['name']));
        $country = htmlspecialchars(trim($postData['country']));
        $region = htmlspecialchars(trim($postData['region']));
        $province = htmlspecialchars(trim($postData['province']));
        $city = htmlspecialchars(trim($postData['city']));

        // SQL query to insert name and location
        $stmt = $this->pdo->prepare("INSERT INTO tblBranch (name, country, region, province, city) VALUES (?, ?, ?, ?, ?)");

        if ($stmt->execute([$name, $country, $region, $province, $city])) {
            return true;
        } else {
            error_log("SQL Error during insert: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    // Method to retrieve job offers with filtering and search criteria
    public function getFilteredBranch($search)
    {
        $sql = "SELECT * FROM tblBranch WHERE 1=1";

        // Apply search logic
        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
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
}
