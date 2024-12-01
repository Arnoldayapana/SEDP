<?php
// download.php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = "../../../Scholar Page/Uploads/" . $file;

    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($filepath);
        exit;
    } else {
        echo "File not found.";
    }
}
