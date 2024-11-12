<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the employee_id from the URL
$employee_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch employee data
$sql = "SELECT * FROM employees WHERE employee_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $employee_id);
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
            <h3 class="fw-bold fs-5 p-2 ms-3">EMPLOYEE INFORMATION</h3>
            <div class="ms-auto me-2">
                <a href="../View/Employee.php" class="btn btn-dark"> return</a>
            </div>
        </div>

        <hr style="padding-bottom: 1.5rem;">

        <div class="container-fluid shadow mb-5 rounded">
            <table class="table table-info table-striped table-hover">
                <tbody>
                    <tr>
                        <td>Full Name</td>
                        <td><?php echo htmlspecialchars($employee['username']); ?></td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td><?php echo htmlspecialchars($employee['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td><?php echo htmlspecialchars($employee['ContactNumber']); ?></td>
                    </tr>
                    <tr>
                        <td>Account Password</td>
                        <td><?php echo htmlspecialchars($employee['password']); ?></td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td><?php echo htmlspecialchars($employee['department']); ?></td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td><?php echo htmlspecialchars($employee['branch']); ?></td>
                    </tr>
                    <tr>
                        <td>Hire Date</td>
                        <td><?php echo htmlspecialchars($employee['hire_date']); ?></td>
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