<?php
$title = 'View Narrative | SEDP HRMS';
$page = 'View Narrative';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the recipient_id from the URL
$recipient_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch recipient data based on recipient_id
$sql = "SELECT 
            r.recipient_id,
            r.name,
            r.email,
            r.school,
            r.contact,
            r.branch,
            r.GradeLevel,
            ri.profile_image,
            snr.report_title,
            snr.report_content,
            snr.report_status,
            snr.report_month,
            snr.file AS COG,
            snr.submission_date
        FROM 
            recipient r
        JOIN 
            scholar_certificate_of_grade snr ON r.recipient_id = snr.recipient_id
        LEFT JOIN 
            recipientinfo ri ON r.recipient_id = ri.recipient_id
        WHERE 
            r.recipient_id = ?";

$stmt = $connection->prepare($sql);
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

// Function to convert Word document to PDF
function convertWordToPDF($filePath)
{
    $pathInfo = pathinfo($filePath);
    if ($pathInfo['extension'] === 'docx') {
        $pdfPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.pdf';
        $command = "unoconv -f pdf -o $pdfPath $filePath";
        exec($command);
        if (file_exists($pdfPath)) {
            return $pdfPath;  // Return the path to the generated PDF
        }
    }
    return $filePath;  // Return the original file path if no conversion is needed
}

?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>
        <div class="header d-flex">
            <h3 class="fw-bold fs-5 p-2 ms-3">Recipient COG Report</h3>
            <div class="ms-auto me-2">
                <a href="../../App/Compliances/NarativeReport.php" class="btn btn-dark">Return</a>
            </div>
        </div>

        <hr>
        <section class="m-2 p-2 me-3">
            <div class="container m-2 ">
                <H1>Basic Info</H1>
                <div class="row align-item-center justify-content-center">
                    <div class="col-md-6 m-0">
                        <h5 class="mt-3">Profile Picture</h5>
                    </div>
                    <div class="col-md-6">
                        <img src="<?php echo htmlspecialchars($recipient['profile_image']) ? '../../../Scholar Page/Public/Assets/Images/' . htmlspecialchars($recipient['profile_image']) : 'N/A'; ?>" alt="" style="width:60px; border-radius: 50%;">
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">Name</p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo htmlspecialchars($recipient['name']); ?></p>
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p>Email</p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo htmlspecialchars($recipient['email']); ?></p>
                    </div>
                    <hr>
                </div>

                <h1 class="mt-4">Submitted</h1>
                <div class="row">
                    <div class="col-md-6">
                        <p>Report Title</p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo htmlspecialchars($recipient['report_title']); ?></p>
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p>Report Content</p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo htmlspecialchars($recipient['report_content']); ?></p>
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p>Report Month</p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo htmlspecialchars($recipient['submission_date']); ?></p>
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p>Uploaded File</p>
                    </div>
                    <div class="col-md-3">
                        <!-- Download link with 'download' attribute -->
                        <a href="download.php?file=<?php echo htmlspecialchars($recipient['COG']); ?>">
                            Download Report file
                        </a>
                    </div>
                    <hr>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Scripts -->
<?php include("../../Core/Includes/script.php"); ?>

</body>

</html>