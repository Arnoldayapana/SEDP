<?php
require_once(__DIR__ . '/../../../Admin Page/App/Controller/JobOfferController.php');

$controller = new JobOfferController();

// Get filter and search parameters from the request
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get filtered job offers
$jobOffers = $controller->getFilteredJobOffers($filter, $search);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Landing Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../public/assets/css/employee/emInfo.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'poppins', sans-serif;
        }
    </style>
    <!--Nav-->
    <nav class="navbar navbar-expand-lg navbar-light bg-gradient bg-opacity-75" style="background-color: #003c3c;">
        <div class="container d-flex mb-1">
            <a class="navbar-brand text-white align-text-center fw-bolder fs-5" href="../index.php">
                SEDP Simbag Sa Pag-Asenso Inc.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Job Listings -->
    <section>
        <div class="container mb-5 mt-2 bg-light">

            <div class="col mt-3 p-4">
                <nav aria-label="breadcrumb" class="my-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../../../">Home</a></li>
                        <li class="breadcrumb-item"><a href="../../../JobApplicantPage/">Job Lists</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Jobs Lists</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <h1 class="text-center fw-bold fs-3 my-5">Our Company is Currently Looking for the Following:</h1>
            </div>
            <!-- Search Bar and Filter Dropdown -->

            <form method="GET" action="" class="mb-5">
                <div class="row  align-items-center justify-content-center g-3">
                    <div class="col-md-4 mr-1">
                        <select class="form-select" name="filter" onchange="this.form.submit()">
                            <option value="" <?= empty($filter) ? 'selected' : '' ?>>All Jobs</option>
                            <option value="newest" <?= $filter == 'newest' ? 'selected' : '' ?>>Newest</option>
                            <option value="oldest" <?= $filter == 'oldest' ? 'selected' : '' ?>>Oldest</option>
                        </select>
                    </div>
                    <div class="col-md-5 ml-1">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search here!" name="search" value="<?= htmlspecialchars($search) ?>">
                            <button class="btn text-white" style="background-color: #003c3c;" type="submit">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row align-items-center justify-content-center">
                <?php foreach ($jobOffers as $row): ?>
                    <?php $ViewJobId = "viewApplicantModal" . $row['jobOfferId']; ?>
                    <div class='col-lg-5 mx-2 mb-3'>
                        <div class='card mb-3 shadow'>
                            <div class='card-body'>
                                <h5 class='card-title'><?= htmlspecialchars($row['title']) ?></h5>
                                <p class='card-text mx-2'>Responsibilities: <?= htmlspecialchars($row['JobDescription']) ?></p>
                                <p class='card-text mx-2'>Requirements: <?= htmlspecialchars($row['qualification']) ?></p>
                                <button type='button' class='btn btn-md text-white' style='background-color: #003c3c;'
                                    onclick="window.location.href='../../../JobApplicantPage/View/JobApplication.php?job_id=<?= $row['jobOfferId'] ?>'">
                                    Apply
                                </button>
                                <button type='button' class='btn btn-info btn-md' data-bs-toggle='modal'
                                    data-bs-target='#<?= $ViewJobId ?>'>
                                    View
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for viewing job details -->
                    <div class='modal fade' id='<?= $ViewJobId ?>' tabindex='-1' aria-labelledby='viewApplicantLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-lg modal-dialog-centered'> <!-- Large size modal -->
                            <div class='modal-content rounded'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='viewApplicantLabel'>Job Information</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <h6 class='fw-semi-bold fs-5'>Job Title:</h6>
                                            <p class='mx-3'><?= htmlspecialchars($row['title']) ?></p>
                                        </div>
                                        <div class='col-md-6'>
                                            <h6 class='fw-semi-bold fs-5'>Job Description:</h6>
                                            <p class='mx-3'><?= htmlspecialchars($row['JobDescription']) ?></p>
                                        </div>
                                        <div class='col-md-6'>
                                            <h6 class='fw-semi-bold fs-5'>Job Qualification:</h6>
                                            <p class='mx-3'><?= htmlspecialchars($row['qualification']) ?></p>
                                        </div>
                                        <div class='col-md-6'>
                                            <h6 class='fw-semi-bold fs-5'>Job Salary Range:</h6>
                                            <p class='mx-3'><?= htmlspecialchars($row['min_salary']) ?> - <?= htmlspecialchars($row['max_salary']) ?></p>
                                        </div>
                                        <div class='col-md-6'>
                                            <h6 class='fw-semi-bold fs-5'>Job Location:</h6>
                                            <p class='mx-3'><?= htmlspecialchars($row['location']) ?></p>
                                        </div>
                                        <div class='col-md-6'>
                                            <h6 class='fw-semi-bold fs-5'>Job Employment Type:</h6>
                                            <p class='mx-3'><?= htmlspecialchars($row['EmployeeType']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>