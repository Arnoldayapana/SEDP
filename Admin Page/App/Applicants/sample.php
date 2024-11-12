<?php
// Include the database connection
include("../../../Database/db.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the applicant_id and date from the form
    $applicant_id = $_POST['applicant_id'];
    $date = $_POST['date'];

    // Redirect if the date field is empty
    if (empty($date)) {
        header("Location: ../View/JobApplicants.php?error_msg=" . urlencode("All fields are required!"));
        exit;
    }

    // Check if the date already exists
    $checkStmt = $connection->prepare("SELECT COUNT(*) FROM scheduleinterview WHERE date = ?");
    $checkStmt->bind_param("s", $date);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    // If the date already exists, redirect with an error message
    if ($count > 0) {
        header("Location: ../View/JobApplicants.php?error_msg=" . urlencode("The interview date already exists!"));
        exit;
    }

    // Insert the date and applicant_id into the scheduleinterview table
    $stmt = $connection->prepare("INSERT INTO scheduleinterview (applicant_id, date) VALUES (?, ?)");
    $stmt->bind_param("is", $applicant_id, $date); // Bind applicant_id as integer and date as string

    if ($stmt->execute()) {
        // Update the applicant's status to "On Interview"
        $updateStatusStmt = $connection->prepare("UPDATE applicants SET status = 'On Interview' WHERE applicant_id = ?");
        $updateStatusStmt->bind_param("i", $applicant_id);
        $updateStatusStmt->execute();
        $updateStatusStmt->close();

        header("Location: ../View/JobApplicants.php?msg=" . urlencode("Interview scheduled successfully!"));
    } else {
        header("Location: ../View/JobApplicants.php?error_msg=" . urlencode("Error scheduling interview: " . $stmt->error));
    }

    // Close the prepared statement
    $stmt->close();
}
