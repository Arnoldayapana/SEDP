<?php
$title = 'Scholar | SEDP HRMS';
$page = 'Scholar';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the recipient_id from the URL
$recipient_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch recipient data
$sql = "SELECT * FROM recipient_archive WHERE recipient_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $recipient_id);
$stmt->execute();
$result = $stmt->get_result();
$recipient = $result->fetch_assoc();

// Check if the recipient exists
if (!$recipient) {
    echo "<div class='alert alert-danger' role='alert'>Scholar not found.</div>";
    exit;
}
?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>
        <div class="header d-flex">
            <h3 class="fw-bold fs-5 p-2 ms-3">SCHOLAR INFORMATION</h3>
            <div class="ms-auto me-2">
                <a href="../View/Scholar-Archive.php" class="btn btn-dark">return</a>
            </div>
        </div>

        <hr style="padding-bottom: 1.5rem;">

        <div class="container-fluid shadow mb-5 rounded">
            <table class="table table-info table-striped table-hover">
                <tbody>
                    <tr>
                        <td>Full Name</td>
                        <td><?php echo htmlspecialchars($recipient['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td><?php echo htmlspecialchars($recipient['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td><?php echo htmlspecialchars($recipient['contact']); ?></td>
                    </tr>
                    <tr>
                        <td>Account Password</td>
                        <td><?php echo htmlspecialchars($recipient['password']); ?></td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td><?php echo htmlspecialchars($recipient['branch']); ?></td>
                    </tr>
                    <tr>
                        <td>Hire Date</td>
                        <td><?php echo htmlspecialchars($recipient['admission_date']); ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>NA</td>
                    </tr>
                    <tr>
                        <td>Employment Type</td>
                        <td>NA</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>NA</td>
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