<?php
// Include the database connection file
include("../../../../Database/db.php");

// Initialize variables to avoid undefined variable issues
$title = $content = $audience = $imagePath = "";

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $content = mysqli_real_escape_string($connection, $_POST['content']);
    $audience = $_POST['audience']; // Getting selected audience

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageFolder = '../../../../uploads/images/'; // Folder where you want to store images

        // Ensure the directory exists
        if (!is_dir($imageFolder)) {
            mkdir($imageFolder, 0777, true);
        }

        // Generate a unique name for the image
        $imagePath = $imageFolder . time() . '_' . $imageName;

        // Move the uploaded image to the folder
        if (!move_uploaded_file($imageTmpName, $imagePath)) {
            echo "Error uploading the image.";
        }
    }

    // Prepare the SQL query to insert the announcement into the database
    $sql = "INSERT INTO announcements (title, content, image, audience)
            VALUES ('$title', '$content', '$imagePath', '$audience')";

    // Execute the query
    if (mysqli_query($connection, $sql)) {
        header("Location: ../../View/AdminDashboard.php?msg=Post+Uploaded+Successfully");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Close the connection
    mysqli_close($connection);
}
