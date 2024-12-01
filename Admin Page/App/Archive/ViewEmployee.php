<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the employee_id from the URL
$employee_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


// Fetch scholar data using LEFT JOIN
$sql = "
    SELECT 
        e.employee_id,
        e.email,
        e.name,
        e.ContactNumber,
        e.Department,
        e.Branch,
        e.password,
        e.hire_date,
        ei.firstName,
        ei.lastName,
        ei.middleName,
        ei.street,
        ei.municipality,
        ei.zipCode,
        ei.barangay,
        ei.city,
        ei.age,
        ei.dateOfBirth,
        ei.gender,
        ei.civilStatus,
        ei.religion,
        ei.hobbies
    FROM employee_archive e
    LEFT JOIN employeesinfo ei ON e.employee_id = ei.employee_id
    WHERE e.employee_id = ?
";

// Prepare the statement
$stmt = $connection->prepare($sql);

// Use the correct number of parameters
$stmt->bind_param("i", $employee_id); // Bind a single integer parameter

$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Check if the scholar exists

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
                <a href="../View/Employee-Archive.php" class="btn btn-dark"> return</a>
            </div>
        </div>

        <hr style="padding-bottom: 1.5rem;">

        <div class="container-fluid shadow mb-2 rounded m-2 p-2" id="container">
            <div class="row mb-4 m-2 p-2 ">
                <!-- Column 1: Profile Photo -->
                <div class="col-md-2 mb-3 ">
                    <p class="mb-0" style="font-size: 16px;"><strong>Profile Photo</strong></p>
                    <div class="photo-container">
                        <img id="profilePhotoPreview" src="../../../Assets/Images/applicant.jpg" alt="Profile Photo" class="photo-preview">
                    </div>
                </div>

                <!-- Column 2: Form Fields -->
                <div class="col-md-8  mt-3 ms-sm-5">
                    <!-- Name Input -->
                    <div class="row mt-9 mb-1">
                        <div class="col-sm-9">
                            <h2 class="fw-bold mt-2"> <?php echo htmlspecialchars($employee['name'] ?? 'No Name available'); ?> </h2>
                        </div>
                    </div>
                    <!-- Email Input -->
                    <div class="row mb-1">
                        <p><i class="bi bi-envelope-at-fill"></i> <?php echo htmlspecialchars($employee['email'] ?? 'No Email available'); ?></p>
                        <p><i class="bi bi-telephone-fill"></i> <?php echo htmlspecialchars($employee['ContactNumber'] ?? 'No Contact available'); ?></p>
                        <p><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($employee['Address'] ?? 'No Address available'); ?> </p>
                    </div>
                </div>


                <h1 class="fw-bold fs-5 text-white p-2" style="background-color: #003c3c;">PERSONAL INFORMATION</h1>
                <hr>
                <div class="d-flex mb-2">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">First Name :</span> <?php echo htmlspecialchars($employee['firstName']  ?? 'No First Name available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Last Name :</span> <?php echo htmlspecialchars($employee['lastName']  ?? 'No Last Name available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Middle Name :</span> <?php echo htmlspecialchars($employee['middleName']  ?? 'No Middle Name available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 co-md-6 d-flex">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Date Of Birth :</span>
                                <?php echo htmlspecialchars($employee['dateOfBirth']
                                    ?? 'No dateOfBirth available'); ?> </p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Age :</span>
                                <?php echo htmlspecialchars($employee['age']
                                    ?? 'No age Level available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex mb-4">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Gender :</span>
                                <?php echo htmlspecialchars($employee['gender'] ?? 'No gender available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Civil Status :</span>
                                <?php echo htmlspecialchars($employee['civilStatus'] ?? 'No civilStatus available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Religion :</span>
                                <?php echo htmlspecialchars($employee['religion'] ?? 'No religion available'); ?></p>
                        </div>
                    </div>
                </div>
                <div class=" d-flex">
                    <div class="row col-12">
                        <div class="col-4">
                            <p>Present Address : </p>
                        </div>
                        <div class="col-4">
                            <p><span class="fw-bold">City :</span>
                                <?php echo htmlspecialchars($employee['city'] ?? 'No city available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-5">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex">
                        <div class="col-md-4 col-6">
                            <p><span class="fw-bold">Street :</span>
                                <?php echo htmlspecialchars($employee['street']  ?? 'No Street available'); ?></p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p><span class="fw-bold">Barangay :</span>
                                <?php echo htmlspecialchars($employee['barangay'] ?? 'No barangay available'); ?></p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p><span class="fw-bold">Municipality :</span>
                                <?php echo htmlspecialchars($employee['municipality'] ?? 'No municipality available'); ?></p>
                        </div>
                    </div>
                </div>
                <!--Company Info-->
                <h1 class="fw-bold fs-5 text-white p-2" style="background-color: #003c3c;">COMPANY RELATED INFORMATION</h1>
                <hr>
                <div class="">
                    <!-- Email Input -->
                    <div class="row col-12 mb-1">
                        <div class="col-6">
                            <p>
                                <span class="fw-bold">Email :</span>
                                <?php echo htmlspecialchars($employee['email'] ?? 'No Email available'); ?>
                            </p>

                        </div>
                        <div class="col-6">
                            <p><span class="fw-bold">Contact Number :</span>
                                <?php echo htmlspecialchars($employee['ContactNumber'] ?? 'No Contact available'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="row col-12 mb-1">
                        <div class="col-6">
                            <p><span class="fw-bold">Department :</span>
                                <?php echo htmlspecialchars($employee['Department'] ?? 'No Department available'); ?>
                            </p>
                        </div>
                        <div class="col-6">
                            <p><span class="fw-bold">Branch :</span>
                                <?php echo htmlspecialchars($employee['Branch'] ?? 'No Branch available'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="row col-12 mb-5">
                        <div class="col-6">
                            <p><span class="fw-bold">Account Password :</span>
                                <?php echo htmlspecialchars($employee['password'] ?? 'No password available'); ?>
                            </p>
                        </div>
                        <div class="col-6">
                            <p><span class="fw-bold">Hired Date :</span>
                                <?php echo htmlspecialchars($employee['hire_date'] ?? 'No Hired Date available'); ?>
                            </p>
                        </div>

                    </div>
                </div>

                <!--Family Background-->
                <h1 class="fw-bold fs-5 text-white p-2" style="background-color: #003c3c;">FAMILY BACKGROUND</h1>
                <hr>

                <div class="d-flex mb-2">
                    <!-- Father info -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-6">
                            <p><span class="fw-bold">Father's Name :</span> <?php echo htmlspecialchars($employee['FatherName'] ?? 'No Name available'); ?></p>
                        </div>
                        <div class="col-sm-3">
                            <p><span class="fw-bold">Age :</span> <?php echo htmlspecialchars($employee['FaAge'] ?? 'No Age available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Date Of Birth :</span> <?php echo htmlspecialchars($employee['FaDateOfBirth'] ?? 'No Date Available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Father’s Occupation/Kind of Business : </span> <?php echo htmlspecialchars($employee['FaOccupation'] ?? 'No Occupation available'); ?> </p>
                    </div>
                </div>


                <div class="d-flex mb-2">
                    <!-- Father info -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-6">
                            <p><span class="fw-bold">Mother's Name :</span> <?php echo htmlspecialchars($employee['MoName'] ?? 'No Name available'); ?></p>
                        </div>
                        <div class="col-sm-3">
                            <p><span class="fw-bold">Age :</span> <?php echo htmlspecialchars($employee['MoAge'] ?? 'No Age available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Date Of Birth :</span> <?php echo htmlspecialchars($employee['MoDateOfBirth'] ?? 'No Date Available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Mother's Occupation/Kind of Business : </span> <?php echo htmlspecialchars($employee['MaOccupation'] ?? 'No Occupation available'); ?></p>
                    </div>
                </div>
                <div class="d-flex mb-5">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Number of Siblings : </span> <?php echo htmlspecialchars($employee['NumberOfSiblings'] ?? 'No Siblings available'); ?></p>
                    </div>
                </div>

                <h1 class="fw-bold fs-5 text-white p-2" style="background-color: #003c3c;">HOBBIES</h1>
                <hr>

                <div class="d-flex mb-2">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Hobbies : </span> <?php echo htmlspecialchars($employee['hobbies']) ?? 'No Hobbies available'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<?php include("../../Core/Includes/script.php"); ?>
<style>
    #container {
        max-height: 550px;
        /* Set the maximum height */
        overflow-y: auto;
        /* Enable vertical scrolling */
        overflow-x: hidden;
    }
</style>
</body>

</html>