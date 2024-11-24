<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["employee_id"])) {
        $employee_id = $_POST["employee_id"];

        include('../../../../Database/db.php');

        // Step 1: Archive the employee data
        // Insert the employee data into the employee_archive table before deleting from employees
        $archive_sql = "INSERT INTO employee_archive (employee_id, username, branch, department, email, ContactNumber, hire_date, archived_at)
                        SELECT employee_id, username, branch, department, email, ContactNumber, hire_date, NOW()
                        FROM employees
                        WHERE employee_id = ?";
        $archive_stmt = $connection->prepare($archive_sql);
        $archive_stmt->bind_param("i", $employee_id);
        $archive_stmt->execute();

        if ($archive_stmt->affected_rows > 0) {
            // Step 2: Proceed to delete the record from employees table
            $sql = "DELETE FROM employees WHERE employee_id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $employee_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Success: Redirect to employee page with success message
                header("location:../../View/Employee.php?msg=Employee data archived and deleted successfully!");
                exit;
            } else {
                // If employee deletion fails
                header("location:../../View/Employee.php?failed_msg=Failed to delete the employee.");
                exit;
            }

            $stmt->close();
        } else {
            // If archiving fails
            header("location:../../View/Employee.php?failed_msg=Failed to archive the employee data.");
            exit;
        }

        $archive_stmt->close();
        $connection->close();
    }
}
