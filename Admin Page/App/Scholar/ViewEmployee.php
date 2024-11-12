<?php
$title = 'Scholar | SEDP HRMS';
$page = 'Scholar';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the recipient_id from the URL
$recipient_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch employee data
$sql = "SELECT * FROM recipient WHERE recipient_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $recipient_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Check if the employee exists
if (!$employee) {
    echo "<div class='alert alert-danger' role='alert'>Employee not found.</div>";
    exit;
}
?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>
        <div class="header d-flex">
            <h3 class="fw-bold fs-5 ms-3">SCHOLAR INFORMATION</h3>
            <div class="ms-auto me-2">
                <a href="../View/recipients.php" class="btn btn-dark"> return</a>
            </div>
        </div>

        <hr style="padding-bottom: 1.5rem;">

        <div class="container-fluid shadow mb-3 rounded">
            <table class="table table-info table-striped table-hover">
                <tbody>
                    <tr>
                        <td class="fw-bold">Full Name :</td>
                        <td><?php echo htmlspecialchars($employee['name']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email Address :</td>
                        <td><?php echo htmlspecialchars($employee['email']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Contact :</td>
                        <td><?php echo htmlspecialchars($employee['contact']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Branch :</td>
                        <td><?php echo htmlspecialchars($employee['branch']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Account Password :</td>
                        <td><?php echo htmlspecialchars($employee['password']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Hire Date :</td>
                        <td><?php echo htmlspecialchars($employee['admission_date']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Gender :</td>
                        <td>NA</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Address :</td>
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