<?php
ob_start(); // Start output buffering
session_start(); //

// Connection
$title = 'Scholar Applicant | SEDP HRMS';
$page = 'Recipient';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

$errorMessage = "";
$successMessage = "";

$status = $_GET['applicant_status'] ?? ''; // Corrected variable name
$searchTerm = $_GET['search'] ?? '';
?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4" my-4>
            <div class="d-flex justify-content:center">
                <h3 class="fw-bold fs-5">List Of Scholar Applicants</h3>
                <!-- Status Filter -->
                <div class="col-3 ms-auto">
                    <form action="" method="GET">
                        <div class="form-group d-flex">
                            <select class="form-select" name="applicant_status" onchange="this.form.submit()">
                                <option value="" <?= empty($status) ? 'selected' : ''; ?>>All Status</option>
                                <option value="Pending" <?= $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <?php
                                // Fetch distinct statuses from the database
                                $sql = "SELECT DISTINCT application_status FROM scholar_applicant ORDER BY application_status ASC";
                                $result = $connection->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    if ($row['application_status'] !== 'Pending') { // Avoid duplicating "Pending"
                                        $selected = ($row['application_status'] === $status) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($row['application_status']) . "' $selected>" . htmlspecialchars($row['application_status']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <button type="button" class="btn btn-danger mx-2 " onclick="window.location.href='your_page_url_here';">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-3 me-2">
                    <!-- Search Form -->
                    <form id="searchForm" action="" method="GET" onsubmit="return validateSearch()">
                        <div class="input-group mb-1">
                            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search here!" value="<?= htmlspecialchars($searchTerm); ?>">
                            <button type="submit" class="btn btn-primary btn-md">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row">
                <ul class="nav nav-pills ms-3 gap-2">
                    <li class="nav-item">
                        <a class="btn btn-primary" href="#">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary " aria-current="page" href="#">On Interview</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="#"> Accepted</a>
                    </li>
                    <li class="nav-item ms-auto me-3">
                        <a class="btn btn-primary" href="#" tabindex="-1">Archive</a>
                    </li>
                </ul>

                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>STATUS</th>
                            <th>OPERATIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if the "id" parameter is passed for status update
                        if (isset($_GET['id'])) {
                            $scholar_id = $_GET['id'];

                            // Check if the scholar with this ID exists in the database
                            $checkExistSql = "SELECT * FROM scholar_applicant WHERE scholar_id = $scholar_id";
                            $checkResult = $connection->query($checkExistSql);

                            if ($checkResult && $checkResult->num_rows > 0) {
                                // Scholar exists, get the current status
                                $row = $checkResult->fetch_assoc();
                                $currentStatus = $row['application_status'];

                                // If current status is 'Pending', update to 'Reviewed'
                                if ($currentStatus === 'Pending') {
                                    $updateStatusSql = "UPDATE scholar_applicant SET application_status = 'Reviewed' WHERE scholar_id = $scholar_id";
                                    if ($connection->query($updateStatusSql) === TRUE) {
                                        header("Location: ../ScholarApplicant/ViewScholarApplicant.php?id={$scholar_id}");
                                        exit;
                                    } else {
                                        $errorMessage = "Error updating status: " . $connection->error;
                                    }
                                } else {
                                    header("Location: ../ScholarApplicant/ViewScholarApplicant.php?id={$scholar_id}");
                                    exit;
                                }
                            } else {
                                $errorMessage = "Scholar not found.";
                            }
                        }
                        // Construct search condition dynamically
                        $conditions = [];
                        if (!empty($searchTerm)) {
                            $searchTermEscaped = $connection->real_escape_string($searchTerm);
                            $conditions[] = "(name LIKE '%$searchTermEscaped%' OR email LIKE '%$searchTermEscaped%')";
                        }
                        if (!empty($status)) {
                            $statusEscaped = $connection->real_escape_string($status);
                            $conditions[] = "application_status = '$statusEscaped'";
                        }
                        $searchCondition = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

                        // Retrieve all rows from the database table
                        $sql = "SELECT * FROM scholar_applicant $searchCondition";
                        $result = $connection->query($sql);

                        if (!$result) {
                            die("Invalid Query: " . $connection->error);
                        }

                        // Iterate through each row
                        while ($row = $result->fetch_assoc()) {
                            // Generate badge class based on application status
                            $badgeClass = '';
                            $statusText = '';
                            $scholarId = "editEmployeeModal" . $row['scholar_id'];


                            switch ($row['application_status']) {
                                case 'On-Interview':
                                    $badgeClass = 'bg-info text-dark';
                                    break;
                                case 'Pending':
                                    $badgeClass = 'bg-secondary';
                                    break;
                                case 'Accepted':
                                    $badgeClass = 'bg-primary';
                                    break;
                                case 'Rejected':
                                    $badgeClass = 'bg-danger';
                                    break;
                                default:
                                    $badgeClass = 'bg-warning text-dark';
                            }
                            $statusText = $row['application_status'];
                            // Check if button should be disabled
                            $disabled = ($row['application_status'] === 'Pending' || $row['application_status'] === 'Reviewed') ? '' : 'disabled';

                            echo "
                        <tr>
                            <td>{$row['scholar_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td><span class='badge {$badgeClass}'>{$statusText}</span></td>
                            <td>
                                <a href='?id={$row['scholar_id']}' class='btn btn-warning btn-sm'><i class='bi bi-eye'></i></a>

                                <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal'
                                 data-bs-target='#{$scholarId}' onclick='setScholarForInterview({$row['scholar_id']})' {$disabled}>
                                 <i class='bi bi-calendar'></i></button>

                                    <!-- Modal for Interview -->
                                    <div class='modal fade' id='{$scholarId}' tabindex='-1' aria-labelledby='scheduleInterviewLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered modal-xl'>
                                            <div class='modal-content shadow-lg border-0 rounded-3'>
                                                <div class='modal-header bg-primary border-0'>
                                                    <div class='modal-title fw-bold text-white' id='scheduleModalLabel'>
                                                        <h5 class='mb-1'>Schedule Interview</h5>
                                                    </div>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>

                                                <div class='modal-body' style='max-height: 590px; overflow-y: auto;'>
                                                    <form id='interviewForm' action='../Dao/Scholar-db/schedule_interview.php' method='POST'>
                                                        <!-- Hidden input for scholar_id -->
                                                        <input type='hidden' class='scholar_id' name='scholar_id' id='scholar_id' value='{$row['scholar_id']}'>
                                                        <div class='mb-3'>
                                                            <label for='title' class='form-label fw-semibold'>Title</label>
                                                            <input type='text' class='form-control' id='title' name='title' placeholder='e.g. First round interview' required>
                                                        </div>
                                                        <div class='mb-4'>
                                                            <label for='date' class='form-label fw-semibold'>Interview Date and Time</label>
                                                            <input type='datetime-local' class='form-control' id='date' name='date' required>
                                                        </div>

                                                        <div class='mb-3'>
                                                            <label for='interviewType' class='form-label fw-semibold'>Interview Type</label>
                                                            <div class='btn-group w-100' role='group' aria-label='Interview Type'>
                                                                <input type='radio' class='btn-check' name='interviewType' id='video-call' value='video' autocomplete='off' checked>
                                                                <label class='btn btn-outline-primary w-100' for='video-call'>Video Call</label>

                                                                <input type='radio' class='btn-check' name='interviewType' id='phone' value='phone' autocomplete='off'>
                                                                <label class='btn btn-outline-primary w-100' for='phone'>Phone</label>

                                                                <input type='radio' class='btn-check' name='interviewType' id='in-office' value='in-office' autocomplete='off'>
                                                                <label class='btn btn-outline-primary w-100' for='in-office'>In-office</label>
                                                            </div>
                                                        </div>

                                                        <div id='video-call-section' class='interview-details' style='display: none;'>
                                                            <label for='videocallLink' class='form-label fw-semibold'>Video Call Link</label>
                                                            <input type='text' class='form-control mb-3' id='videocallLink' name='videocallLink' placeholder='e.g. https://meet.google.com/abc-defg-hij'>
                                                            <label for='videoDescription' class='form-label fw-semibold'>Description</label>
                                                            <textarea name='interviewDescription_video' id='videoDescription' class='form-control' placeholder='Describe the video call.'></textarea>
                                                        </div>

                                                        <div id='phone-section' class='interview-details' style='display: none;'>
                                                            <label for='phoneNumber' class='form-label fw-semibold'>Phone Number</label>
                                                            <input type='text' class='form-control mb-3' id='phoneNumber' name='phoneNumber' placeholder='e.g. +123456789'>
                                                            <label for='phoneDescription' class='form-label fw-semibold'>Description</label>
                                                            <textarea name='interviewDescription_phone' id='phoneDescription' class='form-control' placeholder='Describe the phone interview.'></textarea>
                                                        </div>

                                                        <div id='in-office-section' class='interview-details' style='display: none;'>
                                                            <label for='officeAddress' class='form-label fw-semibold'>Office Address</label>
                                                            <input type='text' class='form-control mb-3' id='officeAddress' name='officeAddress' placeholder='e.g. 123 Main St, City'>
                                                            <label for='officeDescription' class='form-label fw-semibold'>Description</label>
                                                            <textarea name='interviewDescription_office' id='officeDescription' class='form-control' placeholder='Describe the office interview.'></textarea>
                                                        </div>

                                                        <button type='submit' class='btn btn-primary mt-3'>Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class='btn-group'>
                                    <button class='btn btn-info btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='bi bi-three-dots-vertical'></i>
                                    </button>
                                    <ul class='dropdown-menu'>
                                        <li>
                                            <button class='dropdown-item' onclick='updateScholarStatus({$row['scholar_id']}, \"accepted\")' 
                                                " . ($row['application_status'] === 'Accepted' ? 'disabled' : '') . ">
                                                <i class='bi bi-check-circle'></i> Accept
                                            </button>
                                        </li>
                                        <li>
                                            <button class='dropdown-item' onclick='updateScholarStatus({$row['scholar_id']}, \"rejected\")' 
                                                " . ($row['application_status'] === 'Rejected' ? 'disabled' : '') . ">
                                                <i class='bi bi-x-circle'></i> Reject
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Employee-->
<?php include("../../App/ScholarApplicant/DeleteScholarApplicant.php"); ?>
<?php include("../../App/ScholarApplicant/SchedInterview.php"); ?>
<script>
    // function to switch between interview types
    document.addEventListener('DOMContentLoaded', function() {
        function updateInterviewType() {
            // Get all sections
            const videoSection = document.getElementById('video-call-section');
            const phoneSection = document.getElementById('phone-section');
            const inOfficeSection = document.getElementById('in-office-section');

            // Ensure all sections are hidden initially
            [videoSection, phoneSection, inOfficeSection].forEach(section => {
                section.style.display = 'none';
            });

            // Determine which section to display based on the checked radio button
            const selectedType = document.querySelector('input[name="interviewType"]:checked');
            if (selectedType) {
                switch (selectedType.id) {
                    case 'video-call':
                        videoSection.style.display = 'block';
                        break;
                    case 'phone':
                        phoneSection.style.display = 'block';
                        break;
                    case 'in-office':
                        inOfficeSection.style.display = 'block';
                        break;
                    default:
                        console.error("Unexpected radio button selected!");
                }
            }
        }

        // Initialize on page load
        updateInterviewType();

        // Add event listeners for radio buttons
        document.querySelectorAll('input[name="interviewType"]').forEach(function(radio) {
            radio.addEventListener('change', updateInterviewType);
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="../../Public/Assets/Js/AdminPage.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Common function to update Scholar's status (Accept/Reject)
    function updateScholarStatus(scholarId, action) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../ScholarApplicant/" + action + "Scholar.php", true); // Dynamically call the appropriate script
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("scholar_id=" + scholarId); // Send the scholar_id in the request body

        xhr.onload = function() {
            if (xhr.status == 200) {
                var response = xhr.responseText;

                if (response === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: `Scholar has been ${action.charAt(0).toUpperCase() + action.slice(1)}ed successfully!`,
                        confirmButtonText: "OK",
                    }).then(() => {
                        location.reload(); // Reload the page to reflect the changes
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response, // Show server-side error message
                        confirmButtonText: "OK",
                    });
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Request Failed",
                    text: `Request failed with status: ${xhr.status}`,
                    confirmButtonText: "OK",
                });
            }
        };

        xhr.onerror = function() {
            Swal.fire({
                icon: "error",
                title: "Network Error",
                text: "Request failed due to a network error.",
                confirmButtonText: "OK",
            });
        };
    }
</script>

</body>

</html>

<?php
ob_end_flush(); // Send buffered output to the browser
?>