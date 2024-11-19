<?php
// Include the database connection
include("../../../../Database/db.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the scholar_id and date from the form
    $scholar_id = $_POST['scholar_id'];
    $interview_date = $_POST['interview_date'];

    // Redirect if the interview_date field is empty
    if (empty($interview_date)) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("All fields are required!"));
        exit;
    }

    // Ensure the date is in the correct format for MySQL
    $interview_date = date('Y-m-d H:i:s', strtotime($interview_date));

    // Check if the date already exists in the database
    $checkStmt = $connection->prepare("SELECT COUNT(*) FROM scholar_applicant WHERE interview_date = ?");
    $checkStmt->bind_param("s", $interview_date);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    // If the date already exists, redirect with an error message
    if ($count > 0) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("The interview date already exists!"));
        exit;
    }

    // Update the interview date for the given scholar_id
    $stmt = $connection->prepare("UPDATE scholar_applicant SET interview_date = ? WHERE scholar_id = ?");
    $stmt->bind_param("si", $interview_date, $scholar_id); // Bind interview_date as string and scholar_id as integer

    if ($stmt->execute()) {
        // Update the applicant's status to "On Interview"
        $updateStatusStmt = $connection->prepare("UPDATE scholar_applicant SET application_status = 'On Interview' WHERE scholar_id = ?");
        $updateStatusStmt->bind_param("i", $scholar_id);
        $updateStatusStmt->execute();
        $updateStatusStmt->close();

        // Redirect with success message
        header("Location: ../../View/ScholarApplicant.php?msg=" . urlencode("Interview scheduled successfully!"));
    } else {
        // Redirect with error message if the update fails
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("Error scheduling interview: " . $stmt->error));
    }

    // Close the prepared statement
    $stmt->close();
}
