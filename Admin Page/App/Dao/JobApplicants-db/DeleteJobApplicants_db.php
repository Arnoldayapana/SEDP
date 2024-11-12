<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["applicant_id"])) {
        $applicant_id = $_POST["applicant_id"];

        include('../../../../Database/db.php');

        // Step 1: Delete the record with the specifieds applicant_id
        $sql = "DELETE FROM applicants WHERE applicant_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $applicant_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Step 2: Renumber remaining applicant_id values after deletion
            $sql_reorder = "SET @new_id = 0";
            $connection->query($sql_reorder);

            // Update the applicant_id by incrementing with new sequential values
            $sql_update_ids = "UPDATE applicants SET applicant_id = (@new_id := @new_id + 1) ORDER BY applicant_id";
            $connection->query($sql_update_ids);

            // Step 3: Reset AUTO_INCREMENT to the correct value
            $sql_reset_ai = "ALTER TABLE applicants AUTO_INCREMENT = 1";
            $connection->query($sql_reset_ai);

            $successMessage = "Applicant deleted and IDs reordered successfully!";
        } else {
            $errorMessage = "Failed to delete the Applicant.";
            header("location:../../View/JobApplicants.php?error_msg=$errorMessage");
            exit;
        }

        $stmt->close();
        $connection->close();

        // Redirect back to the Applicant page
        header("location:../../View/JobApplicants.php?msg=$successMessage");
        exit;
    }
}
