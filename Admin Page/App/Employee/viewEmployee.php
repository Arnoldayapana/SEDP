<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

$username = "";
$email = "";
$ContactNumber = "";
$department = "";
$brach = "";
$password = "";
$confirm_password = "";
$usertype = "";

?>
<div class="wrapper">
    <!-- Sidebar -->
    <?php
    include("../../Core/Includes/sidebar.php");
    ?>
    <div class="main p-3 ">
        <?php
        include('../../Core/Includes/navBar.php');
        ?>
        <div class="container-fluid shadow p-3 mb-5 rounded" style="background: #99cccc;">

            <!-- Employee Table -->
            <div class="text-center">
                <img src="../../Public/Assets/Images/employee-info.png" class="rounded" alt="image" style="width:300px">
                <h3 class=" fw-semi-bold fs-4 mb-4">EMPLOYEE INFORMATIONS</h3>
            </div>

            <?php
            'SELECT * FROM employees WHERE employee_id = ?';
            ?>
            <div class="row m-3">
                <div class="col-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control" id="email">
                </div>
            </div>

            <div class="row m-3">
                <div class="col-6">
                    <label for="name" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Account Password</label>
                    <input type="text" class="form-control" id="email">
                </div>
            </div>
            <div class="row m-3">
                <div class="col-6">
                    <label for="name" class="form-label">Department</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Branch</label>
                    <input type="text" class="form-control" id="email">
                </div>
            </div>

            <div class="row m-3">
                <div class="col-6">
                    <label for="name" class="form-label">Hire Date</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Employee Type</label>
                    <input type="text" class="form-control" id="email">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end me-4 mb-2">
                <button class="btn btn-outline-secondary me-md-2" type="button">Close</button>
                <button class="btn" type="button" style="background: #003c3c;color:white;">Schedule Interview</button>
            </div>


        </div>
    </div>
</div>

<!-- Modal Add Employee -->
<?php
include("../Employee/AddEmployee.php");
include("../../App/Employee/DeleteEmployee.php");
?>

<!-- Scripts -->
<?php include("../../Core/Includes/script.php"); ?>
</body>

</html>