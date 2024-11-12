<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["recipient_id"])) {
        $recipient_id = $_POST["recipient_id"];

        include('../../../../Database/db.php');

        // Step 1: Archive the recipient data
        // Insert the recipient data into the recipient_archive table before deleting from recipients
        $archive_sql = "INSERT INTO recipient_archive (recipient_id, name,password, branch, email, contact,admission_date, archived_at)
                        SELECT recipient_id, name,password, branch, email, contact, admission_date, NOW()
                        FROM recipient
                        WHERE recipient_id = ?";
        $archive_stmt = $connection->prepare($archive_sql);
        $archive_stmt->bind_param("i", $recipient_id);
        $archive_stmt->execute();

        if ($archive_stmt->affected_rows > 0) {
            // Step 2: Proceed to delete the record from employees table
            $sql = "DELETE FROM recipient WHERE recipient_id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $recipient_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Step 3: Renumber remaining employee_id values after deletion
                $sql_reorder = "SET @new_id = 0";
                $connection->query($sql_reorder);

                // Update the recipient_id by incrementing with new sequential values
                $sql_update_ids = "UPDATE recipient SET recipient_id = (@new_id := @new_id + 1) ORDER BY recipient_id";
                $connection->query($sql_update_ids);

                // Step 3: Reset AUTO_INCREMENT to the correct value
                $sql_reset_ai = "ALTER TABLE recipient AUTO_INCREMENT = 1";
                $connection->query($sql_reset_ai);
                // Redirect back to the recipient page

                // Success: Redirect to employee page with success message
                header("location:../../View/recipients.php?msg=Employee data archived and deleted successfully!");
                exit;
            } else {
                // If employee deletion fails
                header("location:../View/recipients.php?failed_msg=Failed to delete the employee.");
                exit;
            }
            $stmt->close();
        } else {
            // If archiving fails
            header("location:../../View/recipients.php?failed_msg=Failed to archive the employee data.");
            exit;
        }
        $archive_stmt->close();
        $connection->close();
    }
}
