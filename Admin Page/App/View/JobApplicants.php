<?php
require_once(__DIR__ . '/../Controller/JobPostController.php');
require_once(__DIR__ . '/../Controller/DepartmentController.php');
require_once(__DIR__ . '/../Controller/JobController.php');
require_once(__DIR__ . '/../Controller/JobApplicantController.php');

$jobPostController = new JobPostController();
$jobApplicantController = new JobApplicantController();

$departmentController = new DepartmentController();
$jobController = new JobController();

// Get filter and search parameters from the request
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get filtered job offers
$jobPosts = $jobPostController->getFilteredjobPosts($filter, $search);
$jobApplicants =  $jobApplicantController->getFilteredJobApplicants($filter, $search);

$jobSelects = $jobController->getAllJobs();

//$branchSelects = $branchController->getAllBranch();
$departmentSelects = $departmentController->getAllDepartmentsWithBranchLocations();

$title = "JobApplicants | SEDP HRMS";
$page = "reqcruitment";
include('../../Core/Includes/header.php');

?>

<div class="wrapper">
    <style>
        .file-container {
            background-color: white;
            /* Default background color */
            transition: background-color 0.3s ease;
            /* Smooth transition */
            padding: 5px;
            /* Add padding */
            border-radius: 5px;
            /* Optional: for rounded corners */
            margin-bottom: 10px;
            /* Space between file containers */
        }

        .file-container:hover {
            background-color: #e0f7fa;
            /* Light blue on hover */
        }

        .file-container:active {
            background-color: #b2ebf2;
            /* Slightly darker blue on active */
        }

        .file-container a {
            text-decoration: none;
            /* Remove underline */
            color: inherit;
            /* Use the container's text color */
        }

        .custom-dropdown-menu {
            background-color: rgba(255, 255, 255, 0.7);
            /* White with transparency */
            backdrop-filter: blur(8px);
            /* Blur effect */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Optional: light border */
            left: auto !important;
            /* Force the dropdown to always align to the left */
            right: 0 !important;
            /* Prevent the dropdown from aligning to the right */
        }

        .btn-group .dropdown-menu {
            position: absolute;
            top: auto;
            /* Allow dropdown to go up or down based on the available space */
            bottom: auto;
            transform: none;
            /* Remove any transformation that could affect positioning */
        }
    </style>

    <!-- Sidebar -->
    <?php include '../../Core/Includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main">
        <!-- Header -->
        <?php include '../../Core/Includes/navbar.php'; ?>
        <!-- Main Content -->
        <div class="container-fluid">
            <!--Alert Message for error and successMessage-->
            <?php
            include('../../Core/Includes/alertMessages.php');
            ?>
            <!-- Job Offers Section -->
            <section>
                <div class="container my-3 bg-light">
                    <div class="row align-items-center justify-content-center">

                        <h3 class="fw-bold fs-4">List Of Applicants</h3>
                        <hr style="padding-bottom: 1.5rem;">

                        <!-- Search Bar , Filter Dropdown , New Button-->
                        <!--TODO: connect to database -->
                        <div class="d-flex ">
                            <form action="" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search here!">
                                    <button type="submit" class="btn btn-primary btn-md"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                            <div class="col-3 mx-3 mt-0">
                                <form action="" method="GET">
                                    <div class="form-group d-flex">
                                        <select class="form-select" name="filter" onchange="this.form.submit()">
                                            <option value="" <?= empty($filter) ? 'selected' : '' ?>>All names</option>
                                            <option value="newest" <?= $filter == 'newest' ? 'selected' : '' ?>>Newest</option>
                                            <option value="oldest" <?= $filter == 'oldest' ? 'selected' : '' ?>>Oldest</option>
                                        </select>
                                        <!-- Reset Button -->
                                        <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>

                                    </div>
                                </form>
                            </div>
                            <div class="ms-auto me-3">
                            </div>
                        </div>

                        <!-- JOb offer table here-->
                        <div class="table-responsive-md">
                            <table class="table table-striped">
                                <thead class="table-primary">
                                    <tr>
                                        <th>NAME</th>
                                        <th>POSITION</th>
                                        <th>DATE APPLIED</th>
                                        <th>STATUS</th>
                                        <th>OPERATION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobApplicants as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['applicantName']) ?> </td>
                                            <td><?= htmlspecialchars($row['jobTitle']) ?> </td>
                                            <td><?= htmlspecialchars($row['appliedDate']) ?></td>
                                            <td>
                                                <?php
                                                // Status badge logic
                                                if ($row['status'] === 'Schedule Interview') {
                                                    echo "<span class='badge bg-info text-dark'>{$row['status']}</span>";
                                                } elseif ($row['status'] === 'Pending') {
                                                    echo "<span class='badge bg-warning text-dark'>{$row['status']}</span>";
                                                } elseif ($row['status'] === 'Accepted') {
                                                    echo "<span class='badge bg-primary'>{$row['status']}</span>";
                                                } elseif ($row['status'] === 'Rejected') {
                                                    echo "<span class='badge bg-danger'>{$row['status']}</span>";
                                                } else {
                                                    echo "<span class='badge bg-secondary'>{$row['status']}</span>"; // Fallback for other statuses
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#applicantViewModal<?= $row['applicantId'] ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu custom-dropdown-menu">
                                                        <p style="font-size: 13px; color:darkgrey; padding-left:4px; margin-bottom: 2px;">Change applicant status</p>

                                                        <?php
                                                        // Check the status and adjust the dropdown items accordingly
                                                        if ($row['status'] === 'Pending') {
                                                            // All options enabled
                                                            $review_disabled = '';
                                                            $schedule_disabled = '';
                                                            $accept_disabled = '';
                                                            $reject_disabled = '';
                                                        } elseif ($row['status'] === 'Reviewed') {
                                                            // All options enabled except for 'Schedule Interview'
                                                            $review_disabled = 'disabled';
                                                            $schedule_disabled = '';
                                                            $accept_disabled = '';
                                                            $reject_disabled = '';
                                                        } elseif ($row['status'] === 'Schedule Interview') {
                                                            // All options enabled except for 'Schedule Interview'
                                                            $review_disabled = 'disabled';
                                                            $schedule_disabled = 'disabled';
                                                            $accept_disabled = '';
                                                            $reject_disabled = '';
                                                        } elseif ($row['status'] === 'Approved' || $row['status'] === 'Rejected') {
                                                            // All options disabled
                                                            $review_disabled = 'disabled';
                                                            $schedule_disabled = 'disabled';
                                                            $accept_disabled = 'disabled';
                                                            $reject_disabled = 'disabled';
                                                        } else {
                                                            // Fallback if no status matches
                                                            $review_disabled = '';
                                                            $schedule_disabled = '';
                                                            $accept_disabled = '';
                                                            $reject_disabled = '';
                                                        }
                                                        ?>
                                                        <li>
                                                            <a class="dropdown-item <?php echo $review_disabled; ?>" href="#"
                                                                onclick="reviewedStatus(<?= htmlspecialchars($row['applicantId']) ?>); return false;">
                                                                <i class="bi bi-eye me-2"></i>Reviewed
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item <?php echo $schedule_disabled; ?>" href="#"
                                                                data-bs-toggle="modal" data-bs-target="#scheduleInterview<?= $row['applicantId'] ?>"
                                                                onclick="return false;">
                                                                <i class="bi bi-calendar me-2"></i>Schedule Interview
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item <?php echo $accept_disabled; ?>" href="#"
                                                                onclick="acceptStatus(<?= htmlspecialchars($row['applicantId']) ?>); return false;">
                                                                <i class="bi bi-check-circle me-2"></i>Accepted
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item <?php echo $reject_disabled; ?>" href="#"
                                                                onclick="rejectStatus(<?= htmlspecialchars($row['applicantId']) ?>); return false;">
                                                                <i class="bi bi-x-circle me-2"></i>Rejected
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>


                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['applicantId'] ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?= $row['applicantId'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['applicantId'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $row['applicantId'] ?>">Delete Applicant</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="../Controller/JobApplicantController.php?action=delete" method="POST">
                                                                <div class="modal-body">
                                                                    <!-- Hidden fields for JobApplicant ID and JobApplicant file -->
                                                                    <input type="hidden" name="applicantId" value="<?= $row['applicantId'] ?>">
                                                                    <input type="hidden" name="formFileName" value="<?= $row['formFileName'] ?>">
                                                                    <input type="hidden" name="letterFileName" value="<?= $row['letterFileName'] ?>">
                                                                    <input type="hidden" name="photoFileName" value="<?= $row['photoFileName'] ?>">

                                                                    <p>Are you sure you want to delete Applicant <strong>"
                                                                            <?= htmlspecialchars($row['applicantName']) ?>
                                                                            "</strong> with applied position of <strong>"<?= htmlspecialchars($row['jobTitle']) ?>"</strong>? This action cannot be undone.</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete Applicant</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- View Applicant Modal -->
                                                <div class="modal fade" id="applicantViewModal<?= $row['applicantId'] ?>" tabindex="-1" aria-labelledby="applicantViewModalLabel<?= $row['applicantId'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content w-100" style="font-size: 12px; font-family: Arial;">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="applicantViewModalLabel">Applicant Details</h5>
                                                            </div>
                                                            <!-- Modal Body -->
                                                            <div class="modal-body" style="padding: 20px; background-color: #f8f9fa;">
                                                                <div class="row">
                                                                    <!-- First Column (5) -->
                                                                    <div class="col-12 col-md-5 mb-4 mb-md-0">
                                                                        <div class="card shadow-sm border-0" style="border-radius: 12px; background-color: #fff;">
                                                                            <div class="card-body d-flex flex-column align-items-center text-center">
                                                                                <!-- Applicant Picture -->
                                                                                <?php
                                                                                $photoFileName = htmlspecialchars($row['photoFileName']);
                                                                                $photoFilePath = "../../../Database/uploads/" . $photoFileName;
                                                                                $photoSrc = (!empty($photoFileName) && file_exists($photoFilePath))
                                                                                    ? $photoFilePath
                                                                                    : "../../../Assets/Images/resizeuserimg.png";
                                                                                ?>
                                                                                <div class="picture-container rounded-circle border border-light shadow-sm" style="width: 180px; height: 180px; overflow: hidden;">
                                                                                    <img src="<?= $photoSrc ?>" alt="Applicant Photo" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                                                                </div>
                                                                                <!-- Status Badge -->
                                                                                <span class="badge position-absolute bg-success" style="font-size: 0.6rem;bottom: 210px; padding: 0.4rem 0.70rem; transform: translate(60%, 70%);">
                                                                                    <?= htmlspecialchars($row['status']) ?>
                                                                                </span>

                                                                                <!-- Applicant Details -->
                                                                                <div class="mt-3 text-start">
                                                                                    <p><strong>Name:</strong> <?= htmlspecialchars($row['applicantName']) ?></p>
                                                                                    <p><strong>Unique ID:</strong> <?= htmlspecialchars($row['uniqueId']) ?></p>
                                                                                    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                                                                                    <p><strong>Contact Number:</strong> <?= htmlspecialchars($row['contactNumber']) ?></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Second Column (6) -->
                                                                    <div class="col-12 col-md-7">
                                                                        <div class="card shadow-sm border-0" style="border-radius: 12px; background-color: #fff;">
                                                                            <div class="card-body">
                                                                                <!-- Applied Date -->
                                                                                <div class="row mb-3">
                                                                                    <div class="col-12 text-end">
                                                                                        <p><strong>Applied Date:</strong> <?= htmlspecialchars($row['appliedDate']) ?></p>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Job Details -->
                                                                                <div class="row mb-3">
                                                                                    <div class="col-12">
                                                                                        <p><strong>Title:</strong> <?= htmlspecialchars($row['jobTitle']) ?></p>
                                                                                        <p><strong>Department:</strong> <?= htmlspecialchars($row['departmentName']) ?></p>
                                                                                        <p><strong>Branch:</strong> <?= htmlspecialchars($row['branchName']) ?></p>
                                                                                        <p><strong>Location:</strong> <?= htmlspecialchars($row['country']) ?>, <?= htmlspecialchars($row['region']) ?>, <?= htmlspecialchars($row['province']) ?>, <?= htmlspecialchars($row['city']) ?></p>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- File Attachments -->
                                                                                <?php
                                                                                $formFileName = htmlspecialchars($row['formFileName']);
                                                                                $formFilePath = "../../../Database/uploads/" . $formFileName;

                                                                                $letterFileName = htmlspecialchars($row['letterFileName']);
                                                                                $letterFilePath = "../../../Database/uploads/" . $letterFileName;
                                                                                ?>

                                                                                <!-- Applicant Form File -->
                                                                                <?php if (!empty($formFileName)) : ?>
                                                                                    <div class="file-container mt-3">
                                                                                        <a href="<?= $formFilePath ?>" target="_blank" class="d-flex align-items-center text-decoration-none">
                                                                                            <img src="../../../Assets/Images/applicant_file.png" alt="File Icon" class="img-fluid" style="max-width: 50px;">
                                                                                            <p class="ms-3" style="font-size: 14px; color: #333; word-wrap: break-word;">
                                                                                                <strong>Applicant Form:</strong> <?= $formFileName ?>
                                                                                            </p>
                                                                                        </a>
                                                                                    </div>
                                                                                <?php endif; ?>

                                                                                <!-- Cover Letter File -->
                                                                                <?php if (!empty($letterFileName)) : ?>
                                                                                    <div class="file-container mt-3">
                                                                                        <a href="<?= $letterFilePath ?>" target="_blank" class="d-flex align-items-center text-decoration-none">
                                                                                            <img src="../../../Assets/Images/cover_letter.png" alt="File Icon" class="img-fluid" style="max-width: 50px;">
                                                                                            <p class="ms-3" style="font-size: 14px; color: #333; word-wrap: break-word;">
                                                                                                <strong>Cover Letter:</strong> <?= $letterFileName ?>
                                                                                            </p>
                                                                                        </a>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn text-white" data-bs-dismiss="modal" style="background: #003c3c;">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Schedule Interview -->
                                                <div class='modal fade' id='scheduleInterview<?= $row['applicantId'] ?>' tabindex='-1' aria-labelledby='scheduleInterviewLabel<?= $row['applicantId'] ?>' aria-hidden='true'>
                                                    <div class='modal-dialog modal-dialog-centered'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h5 class='modal-title fw-bold mb-0' id='scheduleModalLabel'>
                                                                    <p style="font-size: 12px; color:darkgrey; margin-bottom:0%;"> Interview for,</p> <?= htmlspecialchars($row['applicantName']) ?>
                                                                </h5>
                                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <form action='../Applicants/sample.php' method='POST'>
                                                                <div class='modal-body'>
                                                                    <input type='hidden' id='applicant_id' name='applicant_id' value="<?= htmlspecialchars($row['applicantId']) ?>">
                                                                    <label for='date' class='form-label'>Select the interview Date and Time</label>
                                                                    <input type='datetime-local' class='form-control' id='date' name='date' required>

                                                                </div>
                                                                <div class='modal-footer'>
                                                                    <button type='button' class='btn btn-secondary me-2' data-bs-dismiss='modal'>Close</button>
                                                                    <button type='submit' class='btn btn-primary' onclick="handleApplicantScheduleInterview(<?= htmlspecialchars($row['applicantId']) ?>);"> Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>


                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    function resetFilter() {
        // Reload the page without the 'filter' and 'search' query parameters
        const url = new URL(window.location.href);
        url.searchParams.delete('filter');
        url.searchParams.delete('search');
        window.location.href = url.href; // Navigate to the reset URL
    }

    function updateFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the corresponding values from data attributes
        const jobid = selectedOption.getAttribute('data-id');
        const description = selectedOption.getAttribute('data-description');
        const qualification = selectedOption.getAttribute('data-qualification');
        const minSalary = selectedOption.getAttribute('data-min-salary');
        const location = selectedOption.getAttribute('data-location');
        const maxSalary = selectedOption.getAttribute('data-max-salary');

        // Get job offer ID to update specific fields
        const jobOfferId = selectElement.id.replace('jobId', '');

        // Update the input fields with the selected option's data
        document.getElementById('JobDescription' + jobOfferId).value = description;
        document.getElementById('qualification' + jobOfferId).value = qualification;
        document.getElementById('min_salary' + jobOfferId).value = minSalary;
        document.getElementById('max_salary' + jobOfferId).value = maxSalary;

    }

    function updateDepartmentDetails(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const jobOfferId = selectElement.id.replace('department', ''); // extraction of jobOfferId

        // Update the hidden departmentId input field
        const departmentIdInput = document.getElementById('departmentId' + jobOfferId);
        departmentIdInput.value = selectedOption.getAttribute('data-id');

        // Update the location input value based on the selected option's data-location attribute
        const locationInput = document.getElementById('location' + jobOfferId);
        locationInput.value = selectedOption.getAttribute('data-location');
    }

    function updateDepartmentDetailsForCreate(selectElement) {
        // Get the selected option
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get department ID and branch location from data attributes
        const departmentId = selectedOption.getAttribute('data-id');
        const branchLocation = selectedOption.getAttribute('data-location');

        // Update the hidden input field with the department ID
        document.getElementById('departmentId').value = departmentId;

        // Update the location input field with the branch location
        document.getElementById('location').value = branchLocation;
    }
</script>
<script>
    function reviewedStatus(applicantId) {
        // Prevent default action
        event.preventDefault();

        // Get the status badge element
        const statusBadge = document.querySelector(`#applicantViewModal${applicantId} .badge`);

        // Check if the status is already "Reviewed"
        if (statusBadge && statusBadge.textContent.trim() === "Reviewed") {
            console.log("Status is already Reviewed. No further action needed.");
            return;
        }

        // Create an AJAX request to update the status to "Reviewed"
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../Controller/JobApplicantController.php?action=updateStatus", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Optionally close any modal or provide feedback
                    window.location.href = "JobApplicants.php?msg=reviewedSuccess";
                } else {
                    alert("Failed to update status to Reviewed. Please try again.");
                }
            }
        };

        // Send the applicant ID and status to the server
        xhr.send("applicantId=" + applicantId + "&status=Reviewed");
    }
</script>

<script>
    function handleApplicantScheduleInterview(applicantId) {
        // Prevent the form from submitting the default way
        event.preventDefault();

        // Get the interview date and time from the input field
        const interviewDateTimeInput = document.querySelector(`#scheduleInterview${applicantId} #date`);
        const interviewDateTime = interviewDateTimeInput.value;

        // Ensure a date and time is selected
        if (!interviewDateTime) {
            alert("Please select an interview date and time.");
            return;
        }

        // Create an AJAX request to update the status and interview datetime
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../Controller/JobApplicantController.php?action=scheduleInterview", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Close the modal and refresh the page to show the updated status
                    const modal = document.querySelector(`#scheduleInterview${applicantId}`);
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();

                    window.location.href = "JobApplicants.php?msg=scheduleSuccess";
                } else {
                    alert("Failed to schedule interview. Please try again.");
                }
            }
        };

        // Send the applicant ID, new status, and interview date & time to the server
        xhr.send("applicantId=" + applicantId + "&status=Schedule Interview" + "&interviewDatetime=" + encodeURIComponent(interviewDateTime));
    }
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../../public/assets/javascript/AdminPage.js"></script>
</body>

</html>