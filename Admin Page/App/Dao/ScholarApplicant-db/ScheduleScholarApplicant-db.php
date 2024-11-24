<?php
// Include the database connection
include("../../../../Database/db.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the scholar_id and interview_date from the form
    $scholar_id = $_POST['scholar_id'];
    $interview_date = $_POST['interview_date'];

    // Validate input fields
    if (empty($scholar_id) || empty($interview_date)) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("All fields are required!"));
        exit;
    }

    // Validate scholar_id is numeric
    if (!is_numeric($scholar_id)) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("Invalid Scholar ID."));
        exit;
    }

    // Ensure the date is in the correct format for MySQL
    $interview_date = date('Y-m-d H:i:s', strtotime($interview_date));

    // Check if the scholar_id exists in the scholar_applicant table
    $checkScholarStmt = $connection->prepare("SELECT COUNT(*) FROM scholar_applicant WHERE scholar_id = ?");
    $checkScholarStmt->bind_param("i", $scholar_id);
    $checkScholarStmt->execute();
    $checkScholarStmt->bind_result($exists);
    $checkScholarStmt->fetch();
    $checkScholarStmt->close();

    if ($exists === 0) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("Scholar ID does not exist."));
        exit;
    }

    // Check if the scholar_id is already scheduled for an interview
    $checkInterviewStmt = $connection->prepare("SELECT COUNT(*) FROM scholar_interview WHERE scholar_id = ?");
    $checkInterviewStmt->bind_param("i", $scholar_id);
    $checkInterviewStmt->execute();
    $checkInterviewStmt->bind_result($alreadyScheduled);
    $checkInterviewStmt->fetch();
    $checkInterviewStmt->close();

    if ($alreadyScheduled > 0) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("Interview schedule is already set for this applicant."));
        exit;
    }

    // Check if the interview date already exists in scholar_interview
    $checkDateStmt = $connection->prepare("SELECT COUNT(*) FROM scholar_interview WHERE interview_date = ?");
    $checkDateStmt->bind_param("s", $interview_date);
    $checkDateStmt->execute();
    $checkDateStmt->bind_result($dateExists);
    $checkDateStmt->fetch();
    $checkDateStmt->close();

    if ($dateExists > 0) {
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("The interview date already exists!"));
        exit;
    }

    // Insert the scholar_id and interview_date into the scholar_interview table
    $stmt = $connection->prepare("INSERT INTO scholar_interview (scholar_id, interview_date) VALUES (?, ?)");
    $stmt->bind_param("is", $scholar_id, $interview_date);

    if ($stmt->execute()) {
        // Update the applicant's status to "On-Interview"
        $updateStatusStmt = $connection->prepare("UPDATE scholar_applicant SET application_status = 'On-Interview' WHERE scholar_id = ?");
        $updateStatusStmt->bind_param("i", $scholar_id);

        if ($updateStatusStmt->execute()) {
            // Redirect with success message
            header("Location: ../../View/ScholarApplicant.php?msg=" . urlencode("Interview scheduled successfully!"));
        } else {
            // Redirect with error message for status update
            header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("Error updating status: " . $updateStatusStmt->error));
        }

        $updateStatusStmt->close();
    } else {
        // Redirect with error message for insertion
        header("Location: ../../View/ScholarApplicant.php?error_msg=" . urlencode("Error scheduling interview: " . $stmt->error));
    }

    // Close the prepared statement
    $stmt->close();
}
