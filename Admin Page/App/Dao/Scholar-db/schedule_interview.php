<?php
// Include the database connection file
include('../../../../Database/db.php');
session_start();

// Function to sanitize input data
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data));
}

// Check if the form is submitted
if (isset($_POST['scholar_id'])) {
    // Retrieve and sanitize form data
    $title = sanitizeInput($_POST['title']);
    $date = sanitizeInput($_POST['date']);
    $interviewType = sanitizeInput($_POST['interviewType']);
    $scholar_id = sanitizeInput($_POST['scholar_id']);

    $videocallLink = ($interviewType == 'video') ? sanitizeInput($_POST['videocallLink']) : NULL;
    $phoneNumber = ($interviewType == 'phone') ? sanitizeInput($_POST['phoneNumber']) : NULL;
    $officeAddress = ($interviewType == 'in-office') ? sanitizeInput($_POST['officeAddress']) : NULL;

    $interviewDescription_video = ($interviewType == 'video') ? sanitizeInput($_POST['interviewDescription_video']) : NULL;
    $interviewDescription_phone = ($interviewType == 'phone') ? sanitizeInput($_POST['interviewDescription_phone']) : NULL;
    $interviewDescription_office = ($interviewType == 'in-office') ? sanitizeInput($_POST['interviewDescription_office']) : NULL;

    // Validate required fields
    if (empty($title) || empty($date) || empty($interviewType) || empty($scholar_id)) {
        echo "All fields are required!";
        exit;
    }

    // Validate scholar_id exists in scholar_applicant table
    $scholarCheckQuery = "SELECT COUNT(*) FROM scholar_applicant WHERE scholar_id = ?";
    $scholarStmt = $connection->prepare($scholarCheckQuery);
    $scholarStmt->bind_param("i", $scholar_id);
    $scholarStmt->execute();
    $scholarStmt->bind_result($exists);
    $scholarStmt->fetch();
    $scholarStmt->close();

    if ($exists === 0) {
        echo "Error: Invalid scholar_id. The scholar does not exist.";
        exit;
    }

    // Check if the interview date is valid and not in the past
    if (strtotime($date) <= time()) {
        echo "Error: Interview date and time must be in the future.";
        exit;
    }

    // Ensure the connection is valid
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query to insert the interview data
    $sql = "INSERT INTO interviews (title, interview_date, interview_type, 
                                    videocall_link, phone_number, office_address, 
                                    interview_description_video, interview_description_phone, interview_description_office, 
                                    scholar_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $connection->prepare($sql)) {
        $stmt->bind_param(
            "sssssssssi",
            $title,
            $date,
            $interviewType,
            $videocallLink,
            $phoneNumber,
            $officeAddress,
            $interviewDescription_video,
            $interviewDescription_phone,
            $interviewDescription_office,
            $scholar_id
        );

        if ($stmt->execute()) {
            // Update scholar applicant status to "On-Interview"
            $updateStatusQuery = "UPDATE scholar_applicant SET application_status = 'On-Interview' WHERE scholar_id = ?";
            if ($updateStmt = $connection->prepare($updateStatusQuery)) {
                $updateStmt->bind_param("i", $scholar_id);
                if ($updateStmt->execute()) {
                    header("Location: ../../View/ScholarApplicant.php?msg=Interview scheduled successfully and status updated to 'On-Interview'!");
                    exit;
                } else {
                    error_log("Error updating status: " . $updateStmt->error);
                    echo "Error updating scholar status.";
                }
                $updateStmt->close();
            } else {
                error_log("SQL Prepare Error: " . $connection->error);
                echo "Error preparing the update query.";
            }
        } else {
            error_log("SQL Error: " . $stmt->error);
            echo "Error scheduling interview. Please try again later.";
        }

        $stmt->close();
    } else {
        error_log("SQL Prepare Error: " . $connection->error);
        echo "Error preparing the query.";
    }

    $connection->close();
}
