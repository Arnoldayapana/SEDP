<?php
session_start();
require_once(__DIR__ . '/../../Admin Page/App/Controller/JobPostController.php');
//instance of jobPostController
$jobPostController = new JobPostController();


// Check if the job_id key exists in the POST array
if (isset($_POST['job_id'])) {
    $jobId = $_POST['job_id'];
} elseif (isset($_GET['job_id'])) {
    $jobId = $_GET['job_id'];
} elseif (isset($_SESSION['jobId'])) {
    $jobId =  $_SESSION['jobId'];
} else {
    // Handle the case when job_id is not set
    die('No job post found with the given ID.');
}

// Get job post details based on the retrieved job post ID
$getJobPostDetails = $jobPostController->getJobPostDetails($jobId);
// Check if job post details were found
if ($getJobPostDetails) {
    // Assign job post details to separate variables
    $jobTitle = $getJobPostDetails['jobTitle'];
    $jobBenefits = $getJobPostDetails['benefits'];
    $jobPostDescription = $getJobPostDetails['jobPostDescription'];
    $jobPostQualification = $getJobPostDetails['jobPostQualification'];
    $jobPostKeyResponsibilities = $getJobPostDetails['jobPostKeyResponsibilities'];
} else {
    echo "No job post found with the given ID .";
}

// Initialize variables
$name = "";
$file = null;
$status = "Pending"; // Default status
$jobPostId = "";
$positionApplied = "";
$showModal = false;
$uniqueIdentifier = '';

// Check if the modal should be shown
if (isset($_SESSION['showModal']) && $_SESSION['showModal'] === true) {
    $showModal = true;
    $uniqueIdentifier = htmlspecialchars($_SESSION['uniqueIdentifier']);
    // Unset session variables after use to avoid showing the modal again
    unset($_SESSION['showModal']);
    unset($_SESSION['uniqueIdentifier']);
}
/*
// Check if modal should be shown
$showModal = isset($_GET['showModal']) ? true : false;
$uniqueIdentifier = $_GET['uniqueIdentifier'] ?? '';
*/
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
            <nav aria-label="breadcrumb" class="my-0.5">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="../../">Home</a></li>
                    <li class="breadcrumb-item"><a href="../../JobApplicantPage/">Job Posts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Job Application</li>
                </ol>
            </nav>
            <div class="bg-white m-2 p-3">
                <form action="../../Admin Page/App/Controller/JobApplicantController.php?action=apply" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($jobId); ?>"><!-- Hidden field for jobPostId -->
                    <input type="hidden" name="jobPostId" value="<?php echo htmlspecialchars($jobId); ?>">
                    <div class="row g-0">
                        <div class="d-flex align-items-start" style="gap: 15px;">
                            <!-- First Column: Photo -->
                            <div style="flex: 0 0 auto;">
                                <img src="../../Assets/Images/SEDPlogo.jpg" alt="Applicant Photo" class="img-fluid"
                                    style="max-width: 100%; border-radius: 5px; border: 2px solid lightgrey; padding: 6px;">
                            </div>

                            <!-- Second Column: Job Information -->
                            <div style="flex: 1;">
                                <p class="text-offset" style="font-size: 13px; margin-bottom: 1px;">Applying for</p>
                                <h3 class="mb-1 fw-bold"><?php echo ($jobTitle); ?></h3>
                                <a href="https://sedp.ph/" target="_blank" data-bs-toggle="tooltip" title="View SEDP" style="text-decoration: underline; color: inherit; font-size:16px; margin-bottom: 1px; display: inline-block;">
                                    SEDP-Simbag Sa Pag-Asenso, Inc.
                                </a>
                                <br>
                                <a href="#" style="text-decoration: underline; color: inherit; font-size:13px; margin-top: 1px;"
                                    data-bs-toggle="offcanvas" data-bs-target="#viewDescription" aria-controls="offcanvasRight" title="View Job Description">
                                    View job description
                                </a>
                            </div>
                        </div>

                    </div>

                    <hr class="mb-4">

                    <div class="row mb-4">
                        <!-- Column 1: Profile Photo -->
                        <div class="col-md-2 ">
                            <p class="mb-0" style="font-size: 16px;"><strong>Profile Photo</strong></p>
                            <div class="photo-container">
                                <img id="profilePhotoPreview" src="../../Assets/Images/applicant.jpg" alt="Profile Photo" class="photo-preview">
                                <input type="file" id="profilePhoto" name="photoFileName" class="photo-upload-input" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                                <label for="profilePhoto" class="upload-button">
                                    <i class="bi bi-upload"></i> Upload
                                </label>
                            </div>
                            <p class="text-body-secondary ms-1" style="font-size: 12px;">Accepted file types: .jpg, .jpeg, .png (2MB limit).</p>
                        </div>

                        <!-- Column 2: Form Fields -->
                        <div class="col-md-8 mt-3 ms-5">
                            <!-- Hidden Position Applied Field -->
                            <input type="hidden" id="positionApplied" name="positionApplied" value="<?php echo ($jobTitle); ?>">

                            <!-- Name Input -->
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label for="lastName" class="col-form-label">Last Name</label>
                                    <input name="lastName" type="text" class="form-control" placeholder="DelaCruz" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="firstName" class="col-form-label">First Name</label>
                                    <input name="firstName" type="text" class="form-control" placeholder="Juan" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="middleName" class="col-form-label">Middle Name</label>
                                    <input name="middleName" type="text" class="form-control" placeholder="A.">
                                </div>
                            </div>
                            <!-- Email Input -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="email" class="col-sm-6 col-form-label">Email Address:</label>
                                    <input name="email" type="email" class="form-control" placeholder="juandelacruz@gmail.com" required>
                                </div>
                                <!-- Contact Number Input -->
                                <div class="col-6">
                                    <label for="contactNumber" class="col-sm-6 col-form-label">Contact Number: </label>
                                    <input type="text" id="contactNumber" name="contactNumber" class="form-control"
                                        placeholder="096********"
                                        pattern="^[0-9]{10,15}$"
                                        title="Please enter a positive integer."
                                        required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>
                        </div>

                        <!-- Applicant form File Upload -->
                        <p style="font-size: 16px; margin-bottom:0;"><strong>Application form </strong></p>
                        <p style="color: grey; font-size:12px; width:75%;">Note: Please upload the completed application form provided by SEDP. If you haven't downloaded it yet, click this
                            <a href="../Files/SEDP-Employee Form.docx"
                                class=""
                                data-bs-toggle="tooltip"
                                title="Download the sedp job applicant form">link</a>.
                        </p>
                        <div class="row mb-3 ms-2">
                            <div class="col-sm-6">
                                <input type="file" id="file" name="formFileName" class="form-control" accept=".pdf,.doc,.docx" required onchange="checkFileSize(this)">
                                <p style="color: grey; font-size:12px;">Accepted file types: .doc, .docx, .pdf (2MB limit).</p>
                            </div>
                        </div>
                        <!-- Cover letter -->
                        <p style="font-size: 16px;" class="mb-0"><strong>Cover Letter</strong></p>
                        <div class="form-check mb-2 ms-3">
                            <input class="form-check-input" type="radio" name="coverLetterOption" id="coverLetter1" value="upload" checked onclick="toggleCoverLetterOption()" aria-controls="fileUpload">
                            <label class="form-check-label" for="coverLetter1">
                                Upload Cover Letter
                            </label>
                            <div class="col-sm-6" id="fileUpload" style="display: none;">
                                <input type="file" id="coverLetterFile" name="letter" class="form-control" accept=".pdf,.doc,.docx" onchange="checkFileSize(this)">
                                <p style="color: grey; font-size:12px;">Accepted file types: .pdf, .doc, .docx (2MB limit).</p>
                            </div>
                        </div>
                        <div class="form-check mb-2 ms-3">
                            <input class="form-check-input" type="radio" name="coverLetterOption" id="coverLetter2" value="write" onclick="toggleCoverLetterOption()" aria-controls="textAreaCoverLetter">
                            <label class="form-check-label" for="coverLetter2">
                                Write Cover Letter
                            </label>
                            <div class="col-sm-8" id="textAreaCoverLetter" style="display: none;">
                                <p style="color: grey; font-size:14px; width:75%;">Introduce yourself and briefly explain why you are suitable for this role. Consider your relevant skills, qualifications, and related experience.</p>
                                <textarea name="coverLetterText" id="coverletter" placeholder="..." rows="4" style="color: grey; font-size:16px; width:75%;"></textarea>
                            </div>
                        </div>
                        <div class="form-check mb-5 ms-3 ">
                            <input class="form-check-input" type="radio" name="coverLetterOption" id="coverLetter3" value="none" onclick="toggleCoverLetterOption()">
                            <label class="form-check-label" for="coverLetter3">
                                Don't include Cover Letter
                            </label>
                        </div>


                        <div class="text-end mb-4">
                            <button type="submit" name="submit" class="btn btn-primary">Submit Application</button>
                        </div>
                </form>
            </div>
            <!-- Off Canvas -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="viewDescription" aria-labelledby="viewDescriptionLabel" style="width: 650px;">
                <div class="offcanvas-header">
                    <h5 id="viewDescriptionLabel"><?php echo ($jobTitle); ?></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="mb-4">
                        <p>Job Title: <?php echo ($jobTitle); ?></p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-1">Benefits:</p>
                        <ul class='card-text mb-1' style="font-size: 16px;">
                            <?php
                            // Split the benefits string into an array
                            $jobBenefits = $getJobPostDetails['benefits'];
                            $benefitsArray = explode(', ', ($jobBenefits));

                            // Loop through each benefit and create a list item
                            foreach ($benefitsArray as $benefit):
                            ?>
                                <li><?= htmlspecialchars($benefit) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="mb-4">
                        <p class="mb-1">Description:</p>
                        <div><?php echo htmlspecialchars_decode($jobPostDescription); ?></div>
                    </div>
                    <div class="mb-4">
                        <p class="mb-1">Qualification:</p>
                        <div><?php echo htmlspecialchars_decode($jobPostQualification); ?></div>
                    </div>
                    <div class="mb-4">
                        <p class="mb-1">Key Responsibilities:</p>
                        <div><?php echo htmlspecialchars_decode($jobPostKeyResponsibilities); ?></div>
                    </div>
                </div>
            </div>
 
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                <i class="bi bi-check-circle-fill"></i> Application Submitted Successfully!
                            </h1>
                        </div>
                        <div class="modal-body text-center">
                            <img src="../../Assets/Images/application_submitted.png" alt="Success Icon" class="img-fluid mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                            <p><strong>Please save your unique ID!</strong><i class="bi bi-exclamation-circle" data-bs-toggle="tooltip" title="
                            It’s recommended to save your unique identifier, as it serves as your job application ID and is used to display your status."></i></p>
                            <h4 style="color: #333;">Your Unique ID is: <strong id="uniqueIdText"><?php echo $uniqueIdentifier; ?></strong></h4>
                            <!-- Pass the PHP unique ID directly into the onclick event -->
                            <button class="btn btn-outline-secondary mt-3" onclick="copyToClipboard('<?php echo htmlspecialchars($uniqueIdentifier); ?>')">
                                <i class="bi bi-clipboard"></i> Copy to Clipboard
                            </button>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-outline-primary" onclick="saveAsText('<?php echo htmlspecialchars($uniqueIdentifier); ?>')">
                                <i class="bi bi-download"></i> Save Text
                            </button>
                            <button type="button" class="btn btn-primary" onclick="window.location.href='JobApplicantStatus.php?uniqueId=<?php echo htmlspecialchars($uniqueIdentifier); ?>'">
                                <i class="bi bi-check-circle"></i> Okay
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

            <script>
                // Check if PHP variable $showModal is true and then show the modal
                <?php if ($showModal): ?>
                    var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                    myModal.show();
                <?php endif; ?>
            </script>

        </div>
    </section>


    <script>
        // Function to save the UniqueIdentifier as a .txt file
        function saveAsText(uniqueIdentifier) {
            const blob = new Blob([uniqueIdentifier], {
                type: 'text/plain'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'UniqueIdentifier.txt';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <script>
        function toggleCoverLetterOption() {
            // Get the radio buttons and elements to toggle
            const uploadRadio = document.getElementById("coverLetter1");
            const writeRadio = document.getElementById("coverLetter2");
            const noneRadio = document.getElementById("coverLetter3");

            const fileUpload = document.getElementById("fileUpload");
            const textAreaCoverLetter = document.getElementById("textAreaCoverLetter");

            // Toggle visibility based on the selected radio button
            if (uploadRadio.checked) {
                fileUpload.style.display = "block";
                textAreaCoverLetter.style.display = "none";
            } else if (writeRadio.checked) {
                fileUpload.style.display = "none";
                textAreaCoverLetter.style.display = "block";
            } else if (noneRadio.checked) {
                fileUpload.style.display = "none";
                textAreaCoverLetter.style.display = "none";
            }
        }

        function checkFileSize(input) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (input.files[0] && input.files[0].size > maxSize) {
                alert("File size should not exceed 2MB.");
                input.value = '';
            }
        }

        // Initialize with the first radio button checked
        window.onload = () => {
            const firstRadio = document.getElementById("coverLetter1");
            firstRadio.checked = true; // Ensure first radio is checked
            toggleCoverLetterOption(); // Ensure correct fields are shown
        };
    </script>

    <script>
        // JavaScript function to display the selected image in the container
        function previewImage(event) {
            const preview = document.getElementById('profilePhotoPreview');
            const file = event.target.files[0];

            // Check file size
            checkFileSize(event.target);
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

    <script>
        function copyToClipboard(uniqueId) {
            // Use the modern Clipboard API to copy the unique ID
            navigator.clipboard.writeText(uniqueId).then(() => {
                // Notify the user that the ID was copied successfully
                alert("Unique ID copied to clipboard!");
            }).catch((error) => {
                // Handle any errors in copying
                console.error("Failed to copy unique ID:", error);
                alert("Failed to copy unique ID.");
            });
        }
    </script>
    <script src="../../Assets/Js/tooltip.js" defer></script>


</html>