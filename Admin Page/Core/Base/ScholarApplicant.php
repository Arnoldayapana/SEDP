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
            <h3 class="fw-bold fs-5">List Of Scholar Applicants</h3>
            <hr>
            <div class="row">
                <div class="col-4 me-2">
                    <!-- Search Form -->
                    <form id="searchForm" action="" method="GET" onsubmit="return validateSearch()">
                        <div class="input-group mb-3">
                            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search here!" value="<?= htmlspecialchars($searchTerm); ?>">
                            <button type="submit" class="btn btn-primary btn-md">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Status Filter -->
                <!-- Status Filter -->
                <div class="col-3">
                    <form action="" method="GET">
                        <div class="form-group d-flex">
                            <select class="form-select" name="applicant_status" onchange="this.form.submit()">
                                <option value="" <?= empty($status) ? 'selected' : ''; ?>>Select Status</option>
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
                            <button type="button" class="btn btn-danger ms-2" onclick="window.location.href='your_page_url_here';">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
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
                            $badgeClass = '';
                            $scholarId = "editEmployeeModal" . $row['scholar_id'];

                            // Generate badge class based on application status
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

                            echo "
                        <tr>
                            <td>{$row['scholar_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td><span class='badge {$badgeClass}'>{$row['application_status']}</span></td>
                            <td>
                                <a href='?id={$row['scholar_id']}' class='btn btn-warning btn-sm'><i class='bi bi-eye'></i></a>

                                <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal'
                                    data-bs-target='#{$scholarId}' onclick='setScholarForInterview({$row['scholar_id']})'><i class='bi bi-calendar'></i></button>

                                <div class='btn-group'>
                                    <button class='btn btn-info btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='bi bi-three-dots-vertical'></i>
                                    </button>
                                    <ul class='dropdown-menu'>
                                        <li><button class='dropdown-item' onclick='updateScholarStatus({$row['scholar_id']}, \"accepted\")'><i class='bi bi-check-circle'></i> Accept</button></li>
                                        <li><button class='dropdown-item' onclick='updateScholarStatus({$row['scholar_id']}, \"rejected\")'><i class='bi bi-x-circle'></i> Reject</button></li>
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
    <script>
        function updateScholarStatus(scholarId, action) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", `../ScholarApplicant/${action}Scholar.php`, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("scholar_id=" + scholarId);

            xhr.onload = function() {
                if (xhr.status === 200 && xhr.responseText === "success") {
                    alert(`Scholar ${action.charAt(0).toUpperCase() + action.slice(1)}ed!`);
                    location.reload();
                } else {
                    alert("Error: " + xhr.responseText);
                }
            };

            xhr.onerror = function() {
                alert("Request failed due to a network error.");
            };
        }
    </script>
    </body>

    </html>
    <?php ob_end_flush(); ?>