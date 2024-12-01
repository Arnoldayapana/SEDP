<?php
$title = 'Scholar | SEDP HRMS';
$page = 'Scholar';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Get the re_archive_id from the URL
$re_archive_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch scholar data using LEFT JOIN
$sql = "
    SELECT 
        r.re_archive_id,
        r.email,
        r.name,
        r.contact,
        r.school,
        ri.FirstName,
        ri.LastName,
        ri.MiddleName,
        ri.Address,
        ri.Course,
        ri.Year,
        ri.DateOfBirth,
        ri.Age,
        ri.Gender,
        ri.Campus,
        ri.Religion,
        ri.Hobbies,
        rfbg.FatherName,
        rfbg.FaAge,
        rfbg.FaDateOfBirth,
        rfbg.FaOccupation,
        rfbg.MotherName,
        rfbg.MoAge,
        rfbg.MoDateOfBirth,
        rfbg.MoOccupation,
        rfbg.NumberOfSiblings
    FROM recipient_archive r
    LEFT JOIN recipientinfo ri ON r.re_archive_id = ri.re_archive_id
    LEFT JOIN recipientfamilybg rfbg ON r.re_archive_id = rfbg.re_archive_id
    WHERE r.re_archive_id = ?
";

// Prepare the statement
$stmt = $connection->prepare($sql);

// Use the correct number of parameters
$stmt->bind_param("i", $re_archive_id); // Bind a single integer parameter

$stmt->execute();
$result = $stmt->get_result();
$scholar = $result->fetch_assoc();

// Check if the scholar exists

?>


<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>
        <div class="header d-flex">
            <h3 class="fw-bold fs-5 ms-3">SCHOLAR INFORMATION</h3>
            <div class="ms-auto me-2">
                <a href="../View/Scholar-Archive.php" class="btn text-white" style="background-color: #003c3c;"> return</a>
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
                <div class="col-md-8 mt-3 ms-5">
                    <!-- Name Input -->
                    <div class="row mt-9 mb-1">
                        <div class="col-sm-9">
                            <h2 class="fw-bold mt-2"> <?php echo htmlspecialchars($scholar['name'] ?? 'No Name available'); ?> </h2>
                        </div>
                    </div>
                    <!-- Email Input -->
                    <div class="row mb-1">
                        <p><i class="bi bi-envelope-at-fill"></i> <?php echo htmlspecialchars($scholar['email'] ?? 'No Email available'); ?></p>
                        <p><i class="bi bi-telephone-fill"></i> <?php echo htmlspecialchars($scholar['contact'] ?? 'No Contact available'); ?></p>
                        <p><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($scholar['Address'] ?? 'No Address available'); ?> </p>
                    </div>
                </div>


                <h1 class="fw-bold fs-5 text-white p-2" style="background-color: #003c3c;">PERSONAL INFORMATION</h1>
                <hr>
                <div class="d-flex mb-2">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">First Name :</span> <?php echo htmlspecialchars($scholar['FirstName']  ?? 'No email available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Last Name :</span> <?php echo htmlspecialchars($scholar['LastName']  ?? 'No email available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Middle Name :</span> <?php echo htmlspecialchars($scholar['MiddleName']  ?? 'No email available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Course :</span> <?php echo htmlspecialchars($scholar['Course'] ?? 'No Course available'); ?> </p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Year Level :</span> <?php echo htmlspecialchars($scholar['Year'] ?? 'No Year Level available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Study at :</span> <?php echo htmlspecialchars($scholar['School'] ?? 'No School available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Campus :</span> <?php echo htmlspecialchars($scholar['Campus'] ?? 'No Campus available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Religion :</span> <?php echo htmlspecialchars($scholar['Religion'] ?? 'No Religion available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-5">
                    <!-- Applicant form File Upload -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Date Of Birth :</span> <?php echo htmlspecialchars($scholar['DateOfBirth']  ?? 'No Date Of Birth available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Age :</span> <?php echo htmlspecialchars($scholar['Age'] ?? 'No Age available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Gender :</span> <?php echo htmlspecialchars($scholar['Gender'] ?? 'No Gender available'); ?></p>
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
                            <p><span class="fw-bold">Father's Name :</span> <?php echo htmlspecialchars($scholar['FatherName'] ?? 'No Name available'); ?></p>
                        </div>
                        <div class="col-sm-3">
                            <p><span class="fw-bold">Age :</span> <?php echo htmlspecialchars($scholar['FaAge'] ?? 'No Age available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Date Of Birth :</span> <?php echo htmlspecialchars($scholar['FaDateOfBirth'] ?? 'No Date Available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Father’s Occupation/Kind of Business : </span> <?php echo htmlspecialchars($scholar['FaOccupation'] ?? 'No Occupation available'); ?> </p>
                    </div>
                </div>


                <div class="d-flex mb-2">
                    <!-- Father info -->
                    <div class="col-12 d-flex">
                        <div class="col-sm-6">
                            <p><span class="fw-bold">Mother's Name :</span> Ella Dela Cruz</p>
                        </div>
                        <div class="col-sm-3">
                            <p><span class="fw-bold">Age :</span> <?php echo htmlspecialchars($scholar['MoAge'] ?? 'No Age available'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="fw-bold">Date Of Birth :</span> <?php echo htmlspecialchars($scholar['MoDateOfBirth'] ?? 'No Date Available'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Mother's Occupation/Kind of Business : </span> <?php echo htmlspecialchars($scholar['MaOccupation'] ?? 'No Occupation available'); ?></p>
                    </div>
                </div>
                <div class="d-flex mb-5">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Number of Siblings : </span> <?php echo htmlspecialchars($scholar['NumberOfSiblings'] ?? 'No Siblings available'); ?></p>
                    </div>
                </div>

                <h1 class="fw-bold fs-5 text-white p-2" style="background-color: #003c3c;">HOBBIES</h1>
                <hr>

                <div class="d-flex mb-2">
                    <div class="col-6 d-flex">
                        <p><span class="fw-bold">Hobbies : </span> <?php echo htmlspecialchars($scholar['Hobbies']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #container {
        max-height: 550px;
        /* Set the maximum height */
        overflow-y: auto;
        /* Enable vertical scrolling */
        overflow-x: hidden;
    }
</style>

<!-- Scripts -->
<?php include("../../Core/Includes/script.php"); ?>
</body>

</html>