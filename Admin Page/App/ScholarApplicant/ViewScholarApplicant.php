<?php
session_start();
require_once('../../../Database/db.php');
require_once('../../../lib/TCPDF-main/tcpdf.php');
require_once('../../../lib/FPDI-master/src/autoload.php');

use setasign\Fpdi\TcpdfFpdi;

$title = 'Scholar Information | SEDP HRMS';
$page = 'Scholar applicant';

// Get the scholar_id from the URL
$scholar_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch employee data
$sql = "SELECT * FROM scholar_applicant WHERE scholar_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $scholar_id);
$stmt->execute();
$result = $stmt->get_result();
$scholar = $result->fetch_assoc();

// Check if the scholar exists
if (!$scholar) {
    echo "<div class='alert alert-danger' role='alert'>Scholar not found.</div>";
    exit;
}

// Define file path
$filePath = '../../../uploads/resumes/' . $scholar['resume'];

// Check if the file exists
if (!file_exists($filePath)) {
    echo "<div class='alert alert-danger' role='alert'>File not found.</div>";
    exit;
}

// Function to view file
function viewPDF($filePath)
{
    $pdf = new TcpdfFpdi();
    $pdf->AddPage();

    // Set source file and import page
    $pageCount = $pdf->setSourceFile($filePath);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 0, 0, 210, 297); // Full page

    $pdf->Output('view.pdf', 'I'); // I for inline display
}

// Function to download file
function downloadPDF($filePath, $filename)
{
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filePath);
    exit;
}

// Handle view or download request
if (isset($_GET['action']) && $_GET['action'] == 'view') {
    viewPDF($filePath);
    exit;
} elseif (isset($_GET['action']) && $_GET['action'] == 'download') {
    downloadPDF($filePath, $scholar['resume']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Landing Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--<link rel="stylesheet" href="../../public/assets/css/employee/emInfo.css">-->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'poppins', sans-serif;
        }

        /* Container for the photo and upload button */
        .photo-container {
            position: relative;
            width: 200px;
            height: 200px;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
        }

        /* Display selected or default photo */
        .photo-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Hidden file input */
        .photo-upload-input {
            display: none;
        }

        /* Upload button positioned in the bottom right */
        .upload-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-button:hover {
            background-color: #0056b3;
        }
    </style>

    <!--Nav-->
    <nav class="navbar navbar-expand-lg navbar-light bg-gradient bg-opacity-75" style="background-color: #003c3c;">
        <div class="container d-flex mb-1">
            <a class="navbar-brand text-white align-text-center fw-bolder fs-5" href="../index.php">
                SEDP Simbag Sa Pag-Asenso Inc.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <section>
        <div class="container mb-5 mt-4 bg-white p-3">
            <div class="bg-white m-2 p-3">
                <form action="../../Admin Page/App/Controller/JobApplicantController.php?action=apply" method="POST" enctype="multipart/form-data">
                    <div class="row g-0">
                        <div class="d-flex align-items-start" style="gap: 15px;">
                            <!-- First Column: Photo -->
                            <div style="flex: 0 0 auto;">
                                <img src="../../../Assets/Images/SEDPlogo.jpg" alt="Applicant Photo" class="img-fluid"
                                    style="max-width: 100%; border-radius: 5px; border: 2px solid lightgrey; padding: 6px;">
                            </div>

                            <!-- Second Column: Job Information -->
                            <div style="flex: 1;">
                                <div class="d-flex">
                                    <p class="text-offset mb-1 mt-2" style="font-size: 16px;">Scholar</p>
                                    <a href="../View/ScholarApplicant.php" class="btn btn-dark ms-auto">Back</a>
                                </div>
                                <h3 class="mb-1 fw-bold">Applicant Information</h3>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="row mb-4">
                        <!-- Column 1: Profile Photo -->
                        <div class="col-md-2 mb-4">
                            <p class="mb-0" style="font-size: 16px;"><strong>Profile Photo</strong></p>
                            <div class="photo-container">
                                <img id="profilePhotoPreview" src="../../../Assets/Images/applicant.jpg" alt="Profile Photo" class="photo-preview">
                                <input type="file" id="profilePhoto" name="photoFileName" class="photo-upload-input" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Column 2: Form Fields -->
                        <div class="col-md-8 mt-3 ms-5">
                            <!-- Name Input -->
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label for="fullname" class="col-form-label">Full Name</label>
                                    <input name="fullname" type="text" class="form-control" placeholder="<?php echo htmlspecialchars($scholar['name']); ?>" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <label for="School" class="col-form-label">School</label>
                                    <input name="School" type="text" class="form-control" placeholder="<?php echo htmlspecialchars($scholar['school']); ?>" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <label for="Grade Level" class="col-form-label">Grade Level</label>
                                    <input name="Grade Level" type="text" class="form-control" placeholder="<?php echo htmlspecialchars($scholar['GradeLevel']); ?>" disabled>
                                </div>
                            </div>
                            <!-- Email Input -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="email" class="col-sm-6 col-form-label">Email Address:</label>
                                    <input name="email" type="email" class="form-control" placeholder="<?php echo htmlspecialchars($scholar['email']); ?>" disabled>
                                </div>
                                <!-- Contact Number Input -->
                                <div class="col-6">
                                    <label for="contactNumber" class="col-sm-6 col-form-label">Contact Number: </label>
                                    <input type="text" id="contactNumber" name="contactNumber" class="form-control"
                                        placeholder="<?php echo htmlspecialchars($scholar['contact']); ?>" disabled>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex">
                            <!-- Applicant form File Upload -->
                            <div class="col-4 mb-3">
                                <p class="mb-3" style="font-size: 16px;"><strong>Application form </strong></p>
                                <div class="col-sm-6">
                                    <p>Uploaded File : <?php echo htmlspecialchars($scholar['resume']); ?></p>
                                </div>
                                <div class="view-file">
                                    <a href="?id=<?php echo $scholar_id; ?>&action=view" class="btn btn-info btn-sm me-2">View File</a>
                                    <a href="?id=<?php echo $scholar_id; ?>&action=download" class="btn btn-primary btn-sm">Download File</a>
                                </div>
                            </div>


                            <!-- Cover letter -->
                            <div class="col-6 ms-md-5 form-group mb-3">
                                <p style="font-size: 16px;" class="mb-0"><strong>Cover Letter</strong></p>
                                <label for="message" class="form-label">Additional Information</label>
                                <textarea class="form-control" placeholder="<?php echo htmlspecialchars($scholar['message']); ?>" rows="4" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        </div>
    </section>
    <script src="../../Assets/Js/tooltip.js" defer></script>


</html>