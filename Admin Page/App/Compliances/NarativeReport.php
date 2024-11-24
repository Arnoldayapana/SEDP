<?php
$title = 'Narrative Report | SEDP HRMS';
$page = 'compliance narrative report';

include('../../Core/Includes/header.php');
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
            <h3 class="fw-bold fs-4">Narrative Report</h3>
            <hr>
            <div class="row">
                <div class="d-grid d-md-flex justify-content-md-end px-6">
                    <form action="#" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" value="" class="form-control" placeholder="Search Recipient">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
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

                    // Read all rows from the database table
                    $sql = "SELECT 
                        r.recipient_id AS recipient_id,
                        r.name AS recipient_name,
                        r.email AS recipient_email,
                        r.school AS recipient_school,
                        r.contact AS recipient_contact,
                        r.branch AS recipient_branch,
                        r.GradeLevel AS recipient_GradeLevel,
                        snr.id AS snr_id,
                        snr.report_title AS report_title,
                        snr.report_content AS report_content,
                        snr.report_status AS report_status,
                        snr.report_month AS report_month,
                        snr.file AS narrative_report, 
                        snr.submission_date  
                    FROM 
                        recipient r
                    JOIN 
                        scholar_narrative_reports snr
                    ON 
                        r.recipient_id = snr.id;";

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
                            <td>{$row['recipient_id']}</td>
                            <td>{$row['recipient_name']}</td>
                            <td>{$row['recipient_email']}</td>
                            <td>{$statusBadge}</td>
                            <td>
                                <!-- View Button -->
                                <a href='../Narative/ViewNarative.php?id={$row['recipient_id']}' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-eye'></i>
                                </a>    
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>

        <?php
        include('../../../Assets/Js/bootstrap.js');
        ?>
    </div>
</div>

</body>

</html>