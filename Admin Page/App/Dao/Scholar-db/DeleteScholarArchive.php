<?php
// Include database connection
include("../../../../Database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_scholar_id'])) {
    $scholarId = $_POST['delete_scholar_id'];

    // Prepare delete query
    $query = "DELETE FROM recipient_archive WHERE re_archive_id = ?";
    $stmt = $connection->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $scholarId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Success: Redirect to Scholar archive page with a success message
            header("Location: ../../View/Scholar-Archive.php?msg=Scholar successfully deleted.");
        } else {
            // Error: Redirect to Scholar archive page with an error message
            header("Location: ../../View/Scholar-Archive.php?error_msg=Failed to delete Scholar.");
        }
        $stmt->close();
    } else {
        // SQL preparation failed
        header("Location: ../../View/Scholar-Archive.php?error_msg=Error preparing delete query.");
    }
    $connection->close();
}
