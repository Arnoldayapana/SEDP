<?php
//connection
$title = 'Scholar Applicant | SEDP HRMS ';
$page = 'Recipient';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

$name = "";
$email = "";
$school = "";
$contact = "";
$GradeLevel = "";

$errorMessage = "";
$successMessage = "";
?>

<div class="wrapper">
    <!--sidebar-->
    <?php
    include("../../Core/Includes/sidebar.php");
    ?>
    <div class="main p-3">
        <?php
        include('../../Core/Includes/navBar.php');
        ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4" my-4>
            <h3 class="fw-bold fs-5">List Of Scholar Applicants</h3>
            <hr>
            <div class="row">
                <div class="col-4 ms-auto  me-2">
                    <form action="../ScholarApplicant/SearchScholarApplicant.php" method="GET">
                        <div class="input-group mb-2">
                            <input type="text" name="search" value="" class="form-control" placeholder="Search Recipient">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>STATUS</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //connection
                    include("../../../Database/db.php");
                    //read all row from database table
                    $sql = "SELECT * FROM scholar_applicant ";
                    $result = $connection->query($sql);

                    if (!$result) {
                        die("Invalid Query" . $connection->error);
                    }
                    //read data of each row
                    while ($row = $result->fetch_assoc()) {
                        // create a unique modal ID for each employee
                        $modalId = "editScholarApplicant" . $row['scholar_id'];

                        echo "
                        <tr>
                            <td>$row[scholar_id]</td>
                            <td>$row[name]</td>
                            <td>$row[email]</td>
                            <td></td>
                            <td>
                                    <!-- view Button -->
                                    <a href='../ScholarApplicant/ViewScholarApplicant.php?id={$row['scholar_id']}' class='btn btn-warning btn-sm'>
                                        <i class='bi bi-eye'></i>
                                    </a> 
                                    <!-- Delete Button -->
                                     <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' 
                                            data-bs-target='#DeleteScholarApplicant' onclick='setScholarApplicantIdForDelete($row[scholar_id])'>
                                           <i class='bi bi-trash'></i>
                                    </button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        </main>
    </div>
    <!-- Modal Add Employee-->
    <?php
    include("../../App/ScholarApplicant/DeleteScholarApplicant.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="../../Public/Assets/Js/AdminPage.js"></script>
    </body>

    </html>