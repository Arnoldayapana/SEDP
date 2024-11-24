<?php
$title = "Dashboard | SEDP HRMS";
$page = "admindashboard";
include('../../Core/Includes/header.php');
include('../../../Database/db.php');
?>
<div class="wrapper">
    <?php
    include_once('../../Core/Includes/sidebar.php');
    ?>

    <div class="main overflow-y-scroll">

        <!--Toasts Message--><!--headers-->
        <?php
        include('../../Core/Includes/Toasts.php');
        include('../../Core/Includes/navBar.php');
        ?>
        <!--Cards-->
        <div class="section" id="dashboard-content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <!--Employee Card-->
                    <?php
                    include('../Dashboard/EmployeeCard.php');
                    ?>

                    <!--Scholar Card-->
                    <?php
                    include('../Dashboard/ScholarCard.php');
                    ?>

                    <!--Job Applicant Card-->
                    <?php
                    include('../Dashboard/JobApplicantCard.php');
                    ?>

                    <!--Scholar Applicant Card-->
                    <?php
                    include('../Dashboard/ScholarApplicantCard.php');
                    ?>

                </div>
            </div>

            <!--Donut-->
            <div class="section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 mb-3 overflow-hidden shadow-lg " style="border-radius: 5px;">
                            <div id="donutchart" style="width: 680px; height: 360px;"></div>
                        </div>
                        <?php
                        include('../Dashboard/chart.php');
                        ?>
                    </div>
                </div>
            </div>

            <!--Applicant Cards-->
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <?php
                        include '../Dashboard/Anouncement.php';
                        ?>
                        <div class="col-lg-6 col-md-6 col-sm-6 mb-1 mt-2">
                            <div class="card border-0 shadow">
                                <div class="card-body">
                                    <h1 class="card-title fw-bold ms-2" style="font-weight: bold;">Upcoming Interview</h1>
                                    <!--Scholar-->
                                    <p class="ms-2 mb-1 text-secondary" style="font-size:15px;">Scholar Applicants:</p>
                                    <ul class="list-group list-group-flush">
                                        <?php
                                        include('../../../Database/db.php');
                                        $sql = "SELECT 
                                            sa.scholar_id,
                                            sa.name,
                                            sa.application_status,
                                            sa.email, 
                                            i.interview_date 
                                        FROM 
                                            interviews i
                                        JOIN 
                                            scholar_applicant sa 
                                        ON 
                                            i.scholar_id = sa.scholar_id 
                                        WHERE 
                                            i.interview_date >= CURDATE() -- Ensure future or current dates
                                        ORDER BY 
                                            i.interview_date ASC -- Closest dates first
                                        LIMIT 2";
                                        $result = $connection->query($sql);

                                        if (!$result) {
                                            die("Invalid Query: " . $connection->error);
                                        }

                                        while ($row = $result->fetch_assoc()) {
                                            $OffcanvaId = "editOffcanvas" . $row['scholar_id'];
                                            echo "
                                                <a href='?id={$row['interview_date']}' 
                                                    class='card text-decoration-none shadow-sm mb-2' 
                                                    style='border: none; border-radius: 5px;' 
                                                    data-bs-toggle='offcanvas' 
                                                    data-bs-target='#$OffcanvaId' 
                                                    aria-controls='offcanvasRight'>
                                                    <div class='card-body d-flex align-items-center'>
                                                        <div class='d-flex align-items-center me-2'>
                                                            <small class='text-muted me-3'>{$row['interview_date']}</small>
                                                        </div>
                                                        <div class='d-flex align-items-center flex-grow-1'>
                                                            <img src='../../Public/Assets/Images/profile.jpg' 
                                                                alt='Applicant Photo' 
                                                                class='rounded-circle me-3' 
                                                                style='width: 35px; height: 35px;'>
                                                            <div>
                                                                <strong class='d-block text-truncate' style='max-width: 130px;font-size:12px;'>{$row['name']}</strong>
                                                                <small class='text-muted' style='font-size:12px;'>{$row['email']}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                ";
                                        }
                                        ?>
                                    </ul>
                                    <div class="mt-1 text-end">
                                        <a href="#" class="text-primary"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasScrolling"
                                            aria-controls="offcanvasScrolling"
                                            style="font-size :12px;">View more.</a>
                                    </div>

                                    <div class="offcanvas offcanvas-end" style="width:500px;" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                                        <div class="offcanvas-header mt-3 mb-2">
                                            <h5 class="offcanvas-title fw-bold fs-4 ms-2 " id="offcanvasScrollingLabel">Upcoming Interviews</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body" style="max-height: 400px; overflow-y: auto;">
                                            <?php
                                            include('../../../Database/db.php');
                                            $sql = "SELECT 
                                            sa.scholar_id,
                                            sa.name, 
                                            sa.email, 
                                            i.interview_date 
                                        FROM 
                                            interviews i
                                        JOIN 
                                            scholar_applicant sa 
                                        ON 
                                            i.scholar_id = sa.scholar_id 
                                        WHERE 
                                            i.interview_date >= CURDATE() -- Ensure future or current dates
                                        ORDER BY 
                                            i.interview_date ASC";
                                            $result = $connection->query($sql);

                                            if (!$result) {
                                                die("Invalid Query: " . $connection->error);
                                            }

                                            while ($row = $result->fetch_assoc()) {
                                                $OffcanvaId = "editOffcanvas" . $row['scholar_id'];
                                                echo "
                                                <a href='?id={$row['interview_date']}' 
                                                    class='card text-decoration-none shadow-sm mb-2' 
                                                    style='border: none; border-radius: 5px;' 
                                                    data-bs-toggle='offcanvas' 
                                                    data-bs-target='#$OffcanvaId' 
                                                    aria-controls='offcanvasRight'>
                                                    <div class='card-body d-flex align-items-center'>
                                                        <div class='d-flex align-items-center me-4'>
                                                            <small class='text-muted me-3'>{$row['interview_date']}</small>
                                                        </div>
                                                        <div class='d-flex align-items-center flex-grow-1'>
                                                            <img src='../../Public/Assets/Images/profile.jpg' 
                                                                alt='Applicant Photo' 
                                                                class='rounded-circle me-3' 
                                                                style='width: 40px; height: 40px;'>
                                                            <div>
                                                                <strong class='d-block text-truncate' style='max-width: 150px;'>{$row['name']}</strong>
                                                                <small class='text-muted'>{$row['email']}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                ";
                                            }
                                            ?>
                                        </div>
                                    </div>


                                    <!-APPLICANT--->
                                        <p class="ms-2 mb-1 text-secondary" style="font-size:15px;">Job Applicants:</p>
                                        <ul class="list-group list-group-flush">
                                            <?php
                                            include('../../../Database/db.php');
                                            $sql = "SELECT 
                                            sa.scholar_id,
                                            sa.name, 
                                            sa.email, 
                                            i.interview_date 
                                        FROM 
                                            interviews i
                                        JOIN 
                                            scholar_applicant sa 
                                        ON 
                                            i.scholar_id = sa.scholar_id 
                                        WHERE 
                                            i.interview_date >= CURDATE() -- Ensure future or current dates
                                        ORDER BY 
                                            i.interview_date ASC -- Closest dates first
                                        LIMIT 2";
                                            $result = $connection->query($sql);

                                            if (!$result) {
                                                die("Invalid Query: " . $connection->error);
                                            }

                                            while ($row = $result->fetch_assoc()) {
                                                $OffcanvaId = "editOffcanvas" . $row['scholar_id'];
                                                echo "
                                                <a href='?id={$row['interview_date']}' 
                                                    class='card text-decoration-none shadow-sm mb-2' 
                                                    style='border: none; border-radius: 5px;' 
                                                    data-bs-toggle='offcanvas' 
                                                    data-bs-target='#$OffcanvaId' 
                                                    aria-controls='offcanvasRight'>
                                                    <div class='card-body d-flex align-items-center'>
                                                        <div class='d-flex align-items-center me-2'>
                                                            <small class='text-muted me-3'>{$row['interview_date']}</small>
                                                        </div>
                                                        <div class='d-flex align-items-center flex-grow-1'>
                                                            <img src='../../Public/Assets/Images/profile.jpg' 
                                                                alt='Applicant Photo' 
                                                                class='rounded-circle me-3' 
                                                                style='width: 35px; height: 35px;'>
                                                            <div>
                                                                <strong class='d-block text-truncate' style='max-width: 130px;font-size:12px;'>{$row['name']}</strong>
                                                                <small class='text-muted' style='font-size:12px;'>{$row['email']}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                ";
                                            }
                                            ?>
                                        </ul>
                                        <div class="mt-1 text-end">
                                            <a href="#" class="text-primary"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvasScrolling"
                                                aria-controls="offcanvasScrolling"
                                                style="font-size :12px;">View more.</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!---->


        </div>
    </div>

    <style>
        #dashboard-content {
            max-height: 650px;
            /* Set the maximum height */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }
    </style>
    <?php
    include('../../../Database/db.php');

    // Query to fetch the number of job applicants
    $sql_job = "SELECT COUNT(*) AS job_count FROM applicants";
    $result_job = $connection->query($sql_job);
    $job_row = $result_job->fetch_assoc();
    $job_count = $job_row['job_count'];

    // Query to fetch the number of scholar applicants
    $sql_scholar = "SELECT COUNT(*) AS scholar_count FROM scholar_applicant";
    $result_scholar = $connection->query($sql_scholar);
    $scholar_row = $result_scholar->fetch_assoc();
    $scholar_count = $scholar_row['scholar_count'];

    // Close the database connection
    $connection->close();
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Job Applicants', 11],
                ['Scholar Applicant', 9],
                ['Interviews', 2],
                ['Compliance', 2],
            ]);

            var options = {
                title: 'Reports',
                pieHole: 0.5,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>

    <!--toasts-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastElement = document.getElementById('successToast');
            if (toastElement) {
                var toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="../../Public/Assets/Js/AdminPage.js"></script>
    <script src="../../Public/Assets/JssideBarScript.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    </body>

    </html>