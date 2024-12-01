<?php
$title = 'Load Expenses | SEDP HRMS';
$page = 'compliance Load Expenses';

include('../../Core/Includes/header.php');
include("../../../Database/db.php");
$recipient_id = isset($_GET['id']);

// Pagination logic
$limit = 7; // Number of results per page
$pageNum = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($pageNum - 1) * $limit;

// Fetch total number of applicants
$totalCountSql = "SELECT COUNT(*) as total FROM recipient";
$totalResult = $connection->query($totalCountSql);
$totalRow = $totalResult->fetch_assoc();
$totalApplicants = $totalRow['total'];
$totalPages = ceil($totalApplicants / $limit);
$counter = ($pageNum - 1) * $limit + 1;
?>
<div class="wrapper">
    <?php
    include("../../Core/Includes/sidebar.php");
    ?>

    <div class="main p-3">
        <?php
        include('../../Core/Includes/navBar.php');
        ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4">
            <h3 class="fw-bold fs-4">Load Expenses</h3>
            <hr>
            <div class="row">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Button aligned to the start -->
                    <div>
                        <a href="../View/Compliance.php" class="btn text-white" style="background-color: #003c3c;">
                            <i class="bi bi-list"></i>
                        </a>
                    </div>

                    <!-- Search form aligned to the end -->
                    <div>
                        <form action="#" method="GET" class="d-flex">
                            <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                class="form-control me-2" placeholder="Search Recipient">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>SUBMISSION STATUS</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connection
                    include("../../../Database/db.php");

                    // Search query logic
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                    $searchCondition = $search ? "AND (r.name LIKE '%$search%' OR r.email LIKE '%$search%')" : '';

                    // SQL query
                    $sql = "SELECT 
                        r.recipient_id,
                        r.name,
                        r.email,
                        snr.report_title,
                        snr.report_status
                    FROM 
                        recipient r
                    JOIN 
                        scholar_load_expenses snr
                    ON 
                        r.recipient_id = snr.recipient_id
                    WHERE 
                        snr.submission_date = (
                            SELECT MAX(submission_date)
                            FROM scholar_load_expenses snr_inner
                            WHERE snr_inner.recipient_id = r.recipient_id
                        )
                    $searchCondition";

                    // Execute the query
                    $result = $connection->query($sql);

                    // Check if the query is valid
                    if (!$result) {
                        die("Invalid Query: " . $connection->error);
                    }

                    // Read data from each row
                    while ($row = $result->fetch_assoc()) {
                        $modalId = "editRecipient" . $row['recipient_id'];  // Use recipient_id as it is the alias for r.id
                        $ViewId = "viewRecipient" . $row['recipient_id'];   // Use recipient_id as it is the alias for r.id

                        // Set the badge color based on the report status
                        $statusBadge = '';
                        if ($row['report_status'] === 'Pending') {
                            $statusBadge = "<span class='badge bg-warning text-dark'>{$row['report_status']}</span>";
                        } elseif ($row['report_status'] === 'Submitted') {
                            $statusBadge = "<span class='badge bg-primary'>{$row['report_status']}</span>";
                        } else {
                            $statusBadge = "<span class='badge bg-secondary'>{$row['report_status']}</span>";
                        }

                        echo "
                        <tr>
                            <td>" . $counter++ . "</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$statusBadge}</td>
                            <td>
                                <!-- View Button -->
                                <a href='../LoadExpenses/ViewLoadExpenses.php?id={$row['recipient_id']}' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-eye'></i>
                                </a>   
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <?php
            include('../../Core/Includes/Pagination.php');
            ?>

        </div>

        <?php
        include('../../../Assets/Js/bootstrap.js');
        ?>
    </div>
</div>

</body>

</html>