<?php
$title = 'View Narrative | SEDP HRMS';
$page = 'View Narrative';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the recipient_id from the URL (not recipient_id)
$recipient_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch recipient data based on recipient_id
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
            snr.file AS narrative_report,  -- Assuming 'file' is the column for the uploaded file
            snr.submission_date  -- Assuming you want the submission date from the 'scholar_narrative_reports' table
        FROM 
            recipient r
        JOIN 
            scholar_narrative_reports snr
        ON 
            r.recipient_id = snr.id
        WHERE
            r.recipient_id = ?";  // Use recipient_id for the WHERE clause

$stmt = $connection->prepare($sql);

// Bind the recipient_id as an integer parameter
$stmt->bind_param("i", $recipient_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the recipient data
$recipient = $result->fetch_assoc();

// Check if the recipient exists
if (!$recipient) {
    echo "<div class='alert alert-danger' role='alert'>Recipient not found.</div>";
    exit;
}
?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>
        <div class="header d-flex">
            <h3 class="fw-bold fs-5 p-2 ms-3">RECIPIENT INFORMATION</h3>
            <div class="ms-auto me-2">
                <a href="../../App/Compliances/NarativeReport.php" class="btn btn-dark">Return</a>
            </div>
        </div>

        <hr style="padding-bottom: 1.5rem;">

        <div class="container-fluid shadow mb-5 rounded">
            <table class="table table-info table-striped table-hover">
                <tbody>
                    <tr>
                        <td>Full Name</td>
                        <td><?php echo htmlspecialchars($recipient['recipient_name']); ?></td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td><?php echo htmlspecialchars($recipient['recipient_email']); ?></td>
                    </tr>
                    <tr>
                        <td>School</td>
                        <td><?php echo htmlspecialchars($recipient['recipient_school']); ?></td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td><?php echo htmlspecialchars($recipient['recipient_contact']); ?></td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td><?php echo htmlspecialchars($recipient['recipient_branch']); ?></td>
                    </tr>
                    <tr>
                        <td>Grade Level</td>
                        <td><?php echo htmlspecialchars($recipient['recipient_GradeLevel']); ?></td>
                    </tr>
                    <tr>
                        <td>Report Title</td>
                        <td><?php echo htmlspecialchars($recipient['report_title']); ?></td>
                    </tr>
                    <tr>
                        <td>Report Content</td>
                        <td><?php echo htmlspecialchars($recipient['report_content']); ?></td>
                    </tr>
                    <tr>
                        <td>Report Status</td>
                        <td>
                            <?php
                            // Ensure the $recipient variable is defined and contains valid data
                            if (isset($recipient) && is_array($recipient)) {
                                // Initialize the $statusBadge variable
                                $statusBadge = '';

                                // Determine the status badge based on the report status
                                if ($recipient['report_status'] === 'Pending') {
                                    $statusBadge = "<span class='badge bg-warning text-dark'>{$recipient['report_status']}</span>";
                                } elseif ($recipient['report_status'] === 'Submitted') {
                                    $statusBadge = "<span class='badge bg-primary'>{$recipient['report_status']}</span>";
                                } else {
                                    $statusBadge = "<span class='badge bg-secondary'>{$recipient['report_status']}</span>";
                                }

                                // Echo the status badge safely
                                echo $statusBadge;
                            } else {
                                // Handle cases where $recipient is not set or empty
                                echo "<span class='badge bg-secondary'>No Status</span>";
                            }
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td>Submission Date</td>
                        <td><?php echo htmlspecialchars($recipient['submission_date']); ?></td>
                    </tr>
                    <tr>
                        <td>Uploaded File</td>
                        <td><a href="../../../Assets/Reports/<?php echo htmlspecialchars($recipient['narrative_report']); ?>" target="_blank">Download Report</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<?php include("../../Core/Includes/script.php"); ?>
</body>

</html>