<?php
// Include the database connection
include("../../../Database/db.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the scholar_id and date from the form
    $scholar_id = $_POST['scholar_id'];
    $interview_date = $_POST['interview_date'];

    // Redirect if the interview_date field is empty
    if (empty($interview_date)) {
        header("Location: ../View/ScholarApplicant.php?error_msg=" . urlencode("All fields are required!"));
        exit;
    }

    // Check if the interview_date already exists
    $checkStmt = $connection->prepare("SELECT COUNT(*) FROM scholar_interview WHERE interview_date = ?");
    $checkStmt->bind_param("s", $interview_date);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    // If the interview_date already exists, redirect with an error message
    if ($count > 0) {
        header("Location: ../View/ScholarApplicant.php?error_msg=" . urlencode("The interview date already exists!"));
        exit;
    }

    // Insert the interview_date and scholar_id into the scheduleinterview table
    $stmt = $connection->prepare("INSERT INTO scholar_interview (scholar_id, interview_date) VALUES (?, ?)");
    $stmt->bind_param("is", $scholar_id, $interview_date);

    if ($stmt->execute()) {
        // Update the applicant's status to "On Interview"
        $updateStatusStmt = $connection->prepare("UPDATE scholar_applicant SET application_status = 'On-Interview' WHERE scholar_id = ?");
        $updateStatusStmt->bind_param("i", $scholar_id);
        $updateStatusStmt->execute();
        $updateStatusStmt->close();

        header("Location: ../View/ScholarApplicant.php?msg=" . urlencode("Interview scheduled successfully!"));
        exit;
    } else {
        header("Location: ../View/ScholarApplicant.php?error_msg=" . urlencode("Error scheduling interview: " . $stmt->error));
        exit;
    }

    // Close the prepared statement
    $stmt->close();
}
