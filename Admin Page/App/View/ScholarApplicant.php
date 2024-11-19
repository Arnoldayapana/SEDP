<?php
ob_start(); // Start output buffering

// Connection
$title = 'Scholar Applicant | SEDP HRMS ';
$page = 'Recipient';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

$name = "";
$email = "";
$school = "";
$contact = "";
$GradeLevel = "";
$resume = "";

$errorMessage = "";
$successMessage = "";
?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4" my-4>
            <h3 class="fw-bold fs-5">List Of Scholar Applicants</h3>
            <hr>
            <div class="row">
                <div class="col-4 ms-auto me-2">
                    <form action="../ScholarApplicant/SearchScholarApplicant.php" method="GET">
                        <div class="input-group mb-2">
                            <input type="text" name="search" value="" class="form-control" placeholder="Search Recipient">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>

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
                        $scholarId = $_GET['id'];

                        // Check if the scholar with this ID exists in the database
                        $checkExistSql = "SELECT * FROM scholar_applicant WHERE scholar_id = $scholarId";
                        $checkResult = $connection->query($checkExistSql);

                        if ($checkResult && $checkResult->num_rows > 0) {
                            // Scholar exists, get the current status
                            $row = $checkResult->fetch_assoc();
                            $currentStatus = $row['application_status'];

                            // If current status is 'Pending', update to 'Reviewed'
                            if ($currentStatus === 'Pending') {
                                $updateStatusSql = "UPDATE scholar_applicant SET application_status = 'Reviewed' WHERE scholar_id = $scholarId";
                                if ($connection->query($updateStatusSql) === TRUE) {
                                    // After updating, redirect to the Scholar Applicants list page
                                    header("Location: ../ScholarApplicant/ViewScholarApplicant.php?id={$scholarId}"); // Redirect to the view page of the updated scholar
                                    exit; // Ensure no further code is executed after the redirect
                                } else {
                                    $errorMessage = "Error updating status: " . $connection->error;
                                }
                            } else {
                                // If status is not 'Pending', just redirect to the scholar's view page
                                header("Location: ../ScholarApplicant/ViewScholarApplicant.php?id={$scholarId}");
                                exit;
                            }
                        } else {
                            // Scholar not found, set an error message
                            $errorMessage = "Scholar not found.";
                        }
                    }

                    // Retrieve all rows from the database table
                    $sql = "SELECT * FROM scholar_applicant";
                    $result = $connection->query($sql);

                    if (!$result) {
                        die("Invalid Query: " . $connection->error);
                    }

                    // Iterate through each row
                    while ($row = $result->fetch_assoc()) {
                        // Generate badge class based on application status
                        $badgeClass = '';
                        $statusText = '';

                        if ($row['application_status'] === 'On-Interview') {
                            $badgeClass = 'bg-info text-dark';
                            $statusText = $row['application_status'];
                        } elseif ($row['application_status'] === 'Pending') {
                            $badgeClass = 'bg-secondary';
                            $statusText = $row['application_status'];
                        } elseif ($row['application_status'] === 'Accepted') {
                            $badgeClass = 'bg-primary';
                            $statusText = $row['application_status'];
                        } elseif ($row['application_status'] === 'Rejected') {
                            $badgeClass = 'bg-danger';
                            $statusText = $row['application_status'];
                        } else {
                            $badgeClass = 'bg-warning text-dark';
                            $statusText = $row['application_status']; // Fallback for other statuses
                        }

                        echo "
                        <tr>
                            <td>{$row['scholar_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td><span class='badge {$badgeClass}'>{$statusText}</span></td>
                            <td>
                                <!-- View Button -->
                                <a href='?id={$row['scholar_id']}' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-eye'></i>
                                </a>
                                <!-- schedule Button -->
                                <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' 
                                        data-bs-target='#ScheduleScholarApplicant' onclick='setScholarApplicantIdForSchedule({$row['scholar_id']})'>
                                    <i class='bi bi-calendar'></i>
                                </button>
                                <!-- Delete Button -->
                                <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' 
                                        data-bs-target='#DeleteScholarApplicant' onclick='setScholarApplicantIdForDelete({$row['scholar_id']})'>
                                    <i class='bi bi-trash'></i>
                                </button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Employee-->
<?php include("../../App/ScholarApplicant/DeleteScholarApplicant.php"); ?>
<?php include("../../App/ScholarApplicant/SchedInterview.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="../../Public/Assets/Js/AdminPage.js"></script>

</body>

</html>

<?php
ob_end_flush(); // Send buffered output to the browser
?>