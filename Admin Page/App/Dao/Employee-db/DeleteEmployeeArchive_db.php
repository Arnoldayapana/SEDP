<?php
// Include database connection
include("../../../../Database/db.php");

if (isset($_POST['delete_employee_id'])) {
    $employeeId = $_POST['delete_employee_id'];

    // Prepare delete query
    $query = "DELETE FROM employee_archive WHERE employee_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $employeeId);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Success: Redirect to the employee archive page with a success message
        header("Location: ../../View/Employee-Archive.php?msg=Employee successfully deleted");
        exit();
    } else {
        // Error: Redirect to the employee archive page with an error message
        header("Location: ../../View/Employee-Archive.php?error_msg=Failed to delete employee");
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}
