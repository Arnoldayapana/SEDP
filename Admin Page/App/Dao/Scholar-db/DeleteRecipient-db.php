<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["recipient_id"])) {
        $recipient_id = $_POST["recipient_id"];

        include('../../../../Database/db.php');

        try {
            // Step 1: Archive the recipient data
            $archive_sql = "
                INSERT INTO recipient_archive (re_archive_id, name, school, branch, password, email, contact, admission_date, archived_at)
                SELECT recipient_id, name, school, branch, password, email, contact, admission_date, NOW()
                FROM recipient
                WHERE recipient_id = ?";
            $archive_stmt = $connection->prepare($archive_sql);
            $archive_stmt->bind_param("i", $recipient_id);
            $archive_stmt->execute();

            if ($archive_stmt->affected_rows > 0) {
                // Step 2: Delete the recipient record
                $delete_sql = "DELETE FROM recipient WHERE recipient_id = ?";
                $delete_stmt = $connection->prepare($delete_sql);
                $delete_stmt->bind_param("i", $recipient_id);
                $delete_stmt->execute();

                if ($delete_stmt->affected_rows > 0) {
                    // Redirect on success
                    header("Location: ../../View/recipients.php?msg=Recipient archived and deleted successfully!");
                    exit;
                } else {
                    // If deletion fails
                    throw new Exception("Failed to delete recipient.");
                }
            } else {
                // If archiving fails
                throw new Exception("Failed to archive recipient data.");
            }
        } catch (Exception $e) {
            // Redirect with error message
            header("Location: ../../View/recipients.php?error_msg=" . urlencode($e->getMessage()));
            exit;
        } finally {
            // Clean up resources
            if (isset($archive_stmt)) $archive_stmt->close();
            if (isset($delete_stmt)) $delete_stmt->close();
            $connection->close();
        }
    }
}
