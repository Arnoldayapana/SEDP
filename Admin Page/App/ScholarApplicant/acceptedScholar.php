<?php
// Check if the scholar_id is provided
if (isset($_POST['scholar_id'])) {
    $scholar_id = intval($_POST['scholar_id']); // Sanitize the input

    // Include the database connection
    include("../../../Database/db.php");

    // Update the application status to "Accepted"
    $sql = "UPDATE scholar_applicant SET application_status = 'Accepted' WHERE scholar_id = $scholar_id";
    if ($connection->query($sql) === TRUE) {
        echo "success";  // Return success if the query executed successfully
    } else {
        echo "Error: " . $connection->error;  // Return error message if query fails
    }

    $connection->close();
} else {
    echo "No scholar ID provided.";  // Return error if no scholar_id is sent
}
