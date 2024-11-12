<?php
$title = 'RecruitmentSetup | SEDP HRMS';
$page = 'RecruitmentSetup';

include('../../Core/Includes/header.php');

$errorMessage = "";
$successMessage = "";


require_once(__DIR__ . '/../Controller/JobController.php');
$jobController = new JobController();
// Get filter and search parameters from the request
$search = isset($_GET['search']) ? $_GET['search'] : '';
$jobs = $jobController->getFilteredJob($search);

// Get filter and search parameters from the request
$search = isset($_GET['search']) ? $_GET['search'] : '';


require_once(__DIR__ . '/../Controller/EmploymentTypeController.php');
$employmentTypeController = new EmploymentTypeController();
// Get filter and search parameters from the request
$search = isset($_GET['search']) ? $_GET['search'] : '';
$employmentTypes = $employmentTypeController->getFilteredEmploymentType($search);

require_once(__DIR__ . '/../Controller/BenefitController.php');
$benefitController = new BenefitController();
// Get filter and search parameters from the request
$search = isset($_GET['search']) ? $_GET['search'] : '';
$benefits = $benefitController->getFilteredBenefit($search);

?>
<div class="wrapper">
    <!--sidebar-->
    <?php include_once('../../core/includes/sidebar.php'); ?>

    <!--add employee-->
    <main class="main">
        <!--header-->
        <?php include '../../core/includes/navBar.php'; ?>


        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4" my-4>
            <!--Alert Message for error and successMessage-->
            <?php include('../../Core/Includes/alertMessages.php'); ?>

            <!-- List with clickable items -->
            <div class="header">
                <ul id="menu-list" class="menu-list-column">
                    <li onclick="showContent('jobs')" class="list-item active">Jobs</li>
                    <li onclick="showContent('employmentTypes')" class="list-item">Employment Types</li>
                    <li onclick="showContent('benefits')" class="list-item">Benefits</li>
                </ul>
            </div>
            <hr class=" mb-4 ">

            <!-- Content for Jobs -->
            <div id="jobs" class="content-div active">
                <!-- Search Bar , Filter Dropdown , New Button-->
                <div class="d-flex mb-1">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search here!">
                            <button type="submit" class="btn btn-primary btn-md"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    <div class="mx-3 mt-0">
                        <form action="" method="GET">
                            <div class="form-group d-flex">
                                <!-- Reset Button -->
                                <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!--Add Job btn-->
                    <div class="ms-auto me-3">
                        <button type='button' class='btn btn-primary btn-md' data-bs-toggle="modal" data-bs-target="#CreateJob">
                            New Job
                        </button>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>JOB TITLE</th>
                            <th>DESCRIPTION</th>
                            <th>QUALIFICATION</th>
                            <th>KEY RESPONSIBILITIES</th>
                            <th>OPERATIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['jobTitle']) ?></td>
                                <td>
                                    <?= strlen($row['jobDescription']) > 50 ? substr(strip_tags(($row['jobDescription'])), 0, 50) . '...' : strip_tags(($row['jobDescription'])) ?>
                                </td>
                                <td>
                                    <?= strlen($row['jobQualification']) > 50 ? substr(($row['jobQualification']), 0, 50) . '...' : ($row['jobQualification']) ?>
                                </td>
                                <td>
                                    <?= strlen($row['jobKeyResponsibilities']) > 50 ? substr(($row['jobKeyResponsibilities']), 0, 50) . '...' : ($row['jobKeyResponsibilities']) ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['jobId'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['jobId'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editBenefitModal<?= $row['benefitId'] ?>" tabindex="-1" aria-labelledby="editBenefitModalLabel<?= $row['benefitId'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold" id="editBenefitModalLabel<?= $row['benefitId'] ?>">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit Benefit
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../Controller/BenefitController.php?action=update" method="POST">
                                                    <div class="modal-body">
                                                        <!-- Hidden field for BenefitID -->
                                                        <input type="hidden" name="benefitId" value="<?= $row['benefitId'] ?>">

                                                        <!-- Editable fields -->
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="name" class="form-label">Benefit</label>
                                                                <input type="text" class="form-control" name="benefit" id="name<?= $row['benefitId'] ?>" value="<?= htmlspecialchars($row['benefit']) ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                            <i class="bi bi-save-fill me-1"></i>Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Job Modal -->
                                    <div class="modal fade" id="editModal<?= $row['jobId'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['jobId'] ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold" id="createJobPostLabel">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit Job
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="editJob" action="../Controller/JobController.php?action=update" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body" style="height: 400px; overflow-y: auto;">

                                                        <!-- Hidden field for JobOfferID -->
                                                        <input type="hidden" name="jobId" value="<?= $row['jobId'] ?>">


                                                        <!-- Job Title -->
                                                        <div class="col-md-12 mb-2">
                                                            <label for="title" class="form-label">Title</label>
                                                            <input type="text" class="form-control" name="title" id="title<?= $row['jobId'] ?>" value="<?= htmlspecialchars_decode($row['jobTitle']) ?>" required>
                                                        </div>
                                                        <br>

                                                        <!-- Job Description -->
                                                        <div class="container my-2 col-md-12">
                                                            <div class="form-group">
                                                                <label for="description">Job Description</label>
                                                                <textarea class="form-control scrollable-textarea" name="description" id="descriptionEdit<?= $row['jobId'] ?>" rows="5" required><?= htmlspecialchars_decode($row['jobDescription']) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <hr>
                                                        <!-- Job Qualification -->
                                                        <div class="container my-2 col-md-12">
                                                            <div class="form-group">
                                                                <label for="qualification">Qualification</label>
                                                                <textarea class="form-control scrollable-textarea" name="qualification" id="qualificationEdit<?= $row['jobId'] ?>" rows="5" required><?= htmlspecialchars_decode($row['jobQualification']) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <hr>
                                                        <!-- Job Key Responsibilities -->
                                                        <div class="container my-2 col-md-12">
                                                            <div class="form-group">
                                                                <label for="keyResponsibilities">Key Responsibilities</label>
                                                                <textarea class="form-control scrollable-textarea" name="keyResponsibilities" id="keyResponsibilitiesEdit<?= $row['jobId'] ?>" rows="5" required><?= htmlspecialchars_decode($row['jobKeyResponsibilities']) ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer-->
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                            <i class="bi bi-save-fill me-1" id="editJobBtn"></i>Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?= $row['jobId'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['jobId'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fw-bold" id="deleteModalLabel<?= $row['jobId'] ?>">
                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Job
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../Controller/JobController.php?action=delete" method="POST">
                                                    <div class="modal-body">
                                                        <!-- Hidden field for JobOfferID -->
                                                        <input type="hidden" name="jobId" value="<?= $row['jobId'] ?>">


                                                        <p class="fs-5 text-muted mb-4">
                                                            Are you sure you want to delete the job
                                                            <strong class="text-dark">"<?= htmlspecialchars($row['jobTitle']) ?>"</strong>?<br>
                                                            <span class="text-danger">This action cannot be undone.</span>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                            <i class="bi bi-trash-fill me-1"></i>Delete
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Content for Employment Types -->
            <div id="employmentTypes" class="content-div">

                <!-- Search Bar , Filter Dropdown , New Button-->
                <div class="d-flex mb-1">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search here!">
                            <button type="submit" class="btn btn-primary btn-md"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    <div class="mx-3 mt-0">
                        <form action="" method="GET">
                            <div class="form-group d-flex">
                                <!-- Reset Button -->
                                <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!--Add education btn-->
                    <div class="ms-auto me-3">
                        <button type='button' class='btn btn-primary btn-md' data-bs-toggle="modal" data-bs-target="#CreateEmploymentType">
                            New Employment Type
                        </button>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr class="justify-content: space-between;">
                            <th>EMPLOMENT TYPE</th>
                            <th>OPERATIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employmentTypes as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['employmentType']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editEmploymentTypeModal<?= $row['employmentTypeId'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteEmploymentTypeModal<?= $row['employmentTypeId'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editEmploymentTypeModal<?= $row['employmentTypeId'] ?>" tabindex="-1" aria-labelledby="editEmploymentTypeModalLabel<?= $row['employmentTypeId'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold" id="editEmploymentTypeModalLabel<?= $row['employmentTypeId'] ?>">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit Employment Type
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../Controller/EmploymentTypeController.php?action=update" method="POST">
                                                    <div class="modal-body">
                                                        <!-- Hidden field for Employment Type ID -->
                                                        <input type="hidden" name="employmentTypeId" value="<?= $row['employmentTypeId'] ?>">

                                                        <!-- Editable field -->
                                                        <div class="mb-4">
                                                            <label for="name<?= $row['employmentTypeId'] ?>" class="form-label fw-semibold">Employment Type</label>
                                                            <input type="text" class="form-control rounded-pill px-3 py-2" name="employmentType" id="name<?= $row['employmentTypeId'] ?>" value="<?= htmlspecialchars($row['employmentType']) ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                            <i class="bi bi-save-fill me-1"></i>Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteEmploymentTypeModal<?= $row['employmentTypeId'] ?>" tabindex="-1" aria-labelledby="deleteEmploymentTypeModalLabel<?= $row['employmentTypeId'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fw-bold" id="deleteEmploymentTypeModalLabel<?= $row['employmentTypeId'] ?>">
                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Employment Type
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../Controller/EmploymentTypeController.php?action=delete" method="POST">
                                                    <div class="modal-body">
                                                        <!-- Hidden field for Employment Type ID -->
                                                        <input type="hidden" name="employmentTypeId" value="<?= $row['employmentTypeId'] ?>">

                                                        <p class="fs-5 text-muted mb-4">
                                                            Are you sure you want to delete the Employment Type
                                                            <strong class="text-dark">"<?= htmlspecialchars($row['employmentType']) ?>"</strong>?<br>
                                                            <span class="text-danger">This action cannot be undone.</span>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                            <i class="bi bi-trash-fill me-1"></i>Delete
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Content for Benefits -->
            <div id="benefits" class="content-div">
                <!-- Search Bar , Filter Dropdown , New Button-->
                <div class="d-flex mb-1">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search here!">
                            <button type="submit" class="btn btn-primary btn-md"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    <div class="mx-3 mt-0">
                        <form action="" method="GET">
                            <div class="form-group d-flex">
                                <!-- Reset Button -->
                                <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!--Add benefit btn-->
                    <div class="ms-auto me-3">
                        <button type='button' class='btn btn-primary btn-md' data-bs-toggle="modal" data-bs-target="#CreateBenefit">
                            New Benefit
                        </button>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr class="justify-content: space-between;">
                            <th>BENEFIT</th>
                            <th>OPERATIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($benefits as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['benefit']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editBenefitModal<?= $row['benefitId'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteBenefitModal<?= $row['benefitId'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editBenefitModal<?= $row['benefitId'] ?>" tabindex="-1" aria-labelledby="editBenefitModalLabel<?= $row['benefitId'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold" id="editBenefitModalLabel<?= $row['benefitId'] ?>">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit Benefit
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../Controller/BenefitController.php?action=update" method="POST">
                                                    <div class="modal-body">
                                                        <!-- Hidden field for BenefitID -->
                                                        <input type="hidden" name="benefitId" value="<?= $row['benefitId'] ?>">

                                                        <!-- Editable fields -->
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="name" class="form-label">Benefit</label>
                                                                <input type="text" class="form-control" name="benefit" id="name<?= $row['benefitId'] ?>" value="<?= htmlspecialchars($row['benefit']) ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                            <i class="bi bi-save-fill me-1"></i>Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteBenefitModal<?= $row['benefitId'] ?>" tabindex="-1" aria-labelledby="deleteBenefitModalLabel<?= $row['benefitId'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg border-0">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fw-bold" id="deleteBenefitModalLabel<?= $row['benefitId'] ?>">
                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Benefit
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../Controller/BenefitController.php?action=delete" method="POST">
                                                    <div class="modal-body">
                                                        <!-- Hidden field for JobOfferID -->
                                                        <input type="hidden" name="benefitId" value="<?= $row['benefitId'] ?>">

                                                        <p class="fs-5 text-muted mb-4">
                                                            Are you sure you want to delete the Benefit
                                                            <strong class="text-dark">"<?= htmlspecialchars($row['benefit']) ?>"</strong>?<br>
                                                            <span class="text-danger">This action cannot be undone.</span>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                            <i class="bi bi-trash-fill me-1"></i>Delete
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Job Modal -->
        <div class="modal fade" id="CreateJob" tabindex="-1" aria-labelledby="createJobLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content rounded-3 shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="createJobLabel">
                            <i class="bi bi-plus-circle-fill me-2"></i>Create Job
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="createJobForm" action="../Controller/JobController.php?action=create" method="POST" enctype="multipart/form-data">
                        <div class="modal-body" style="height: 400px; overflow-y: auto;">
                            <div class="bg-white m-1 p-1">
                                <!-- Job Title -->
                                <div class="col-md-12 mb-2">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" name="jobTitle" id="title" required>
                                </div>
                                <br>
                                <!-- Job Description -->
                                <div class="container my-2 col-md-12">
                                    <div class="form-group">
                                        <label for="description">Job Description</label>
                                        <textarea class="form-control scrollable-textarea" name="jobDescription" id="descriptionCreate" rows="5"></textarea>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <!--  Qualification -->
                                <div class="container my-2 col-md-12">
                                    <div class="form-group">
                                        <label for="qualification">Qualification</label>
                                        <textarea class="form-control scrollable-textarea" name="jobQualification" id="qualificationCreate" rows="5"></textarea>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <!-- Key Responsibilities -->
                                <div class="container my-2 col-md-12">
                                    <div class="form-group">
                                        <label for="keyResponsibilities">Key Responsibilities</label>
                                        <textarea class="form-control scrollable-textarea" name="jobKeyResponsibilities" id="keyResponsibilitiesCreate" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer with Cancel/Submit buttons -->
                        <div class="modal-footer border-0 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Close
                            </button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4" id="createJobBtn">
                                <i class="bi bi-check-circle-fill me-1"></i>Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Employment Type Modal -->
        <div class="modal fade" id="CreateEmploymentType" tabindex="-1" aria-labelledby="createEmploymentTypeLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-3 shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="createEmploymentTypeLabel">
                            <i class="bi bi-plus-circle-fill me-2"></i>Create Employment Type
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../Controller/EmploymentTypeController.php?action=create" method="POST">
                        <div class="modal-body">
                            <div class="mb-4">
                                <label for="employmentType" class="form-label fw-semibold">Employment Type</label>
                                <input type="text" class="form-control rounded-pill px-3 py-2" name="employmentType" id="employmentType" placeholder="Enter Employment Type" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Close
                            </button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-check-circle-fill me-1"></i>Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Benefit Modal -->
        <div class="modal fade" id="CreateBenefit" tabindex="-1" aria-labelledby="createBenefitLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-3 shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="createBenefitLabel">
                            <i class="bi bi-plus-circle-fill me-2"></i>Create Benefit
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../Controller/BenefitController.php?action=create" method="POST">
                        <div class="modal-body">
                            <div class="mb-4">
                                <label for="benefit" class="form-label fw-semibold">Benefit</label>
                                <input type="text" class="form-control rounded-pill px-3 py-2" name="benefit" id="benefit" placeholder="Enter Benefit" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary rounded-pill me-2 px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Close
                            </button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-check-circle-fill me-1"></i>Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- JavaScript for tab behavior and highlighting -->
<script>
    function showContent(contentId) {
        // Hide all content divs
        var contents = document.getElementsByClassName('content-div');
        for (var i = 0; i < contents.length; i++) {
            contents[i].style.display = 'none';
        }

        // Show the selected content div
        document.getElementById(contentId).style.display = 'block';

        // Remove active class from all list items
        var listItems = document.getElementsByClassName('list-item');
        for (var i = 0; i < listItems.length; i++) {
            listItems[i].classList.remove('active');
        }

        // Add active class to the clicked list item
        event.target.classList.add('active');
    }

    // Set initial active tab and content
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('jobs').style.display = 'block';
    });

    function resetFilter() {
        // Reload the page without the 'filter' and 'search' query parameters
        const url = new URL(window.location.href);
        url.searchParams.delete('filter');
        url.searchParams.delete('search');
        window.location.href = url.href; // Navigate to the reset URL
    }
</script>

<script>
    // Object to store initialized CKEditor instances by job ID
    const editors = {};

    document.addEventListener('DOMContentLoaded', function() {
        // Listen for the modal show event
        document.querySelectorAll('[id^="editModal"]').forEach(modalElement => {
            modalElement.addEventListener('shown.bs.modal', function() {
                const jobId = modalElement.getAttribute('id').replace('editModal', '');
                initializeModalEditors(jobId); // Initialize CKEditor for this modal's textareas
            });

            // Destroy CKEditor instances on modal hide to avoid duplicates
            modalElement.addEventListener('hidden.bs.modal', function() {
                const jobId = modalElement.getAttribute('id').replace('editModal', '');
                destroyModalEditors(jobId);
            });
        });
    });

    function initializeModalEditors(jobId) {
        const descriptionId = `descriptionEdit${jobId}`;
        const qualificationId = `qualificationEdit${jobId}`;
        const keyResponsibilitiesId = `keyResponsibilitiesEdit${jobId}`;

        // Check if editors already exist for the current job ID before creating new instances
        if (!editors[descriptionId]) {
            ClassicEditor.create(document.querySelector(`#${descriptionId}`))
                .then(editor => {
                    editors[descriptionId] = editor;
                    editor.setData(document.querySelector(`#${descriptionId}`).value); // Initialize with existing value
                })
                .catch(error => console.error(error));
        }

        if (!editors[qualificationId]) {
            ClassicEditor.create(document.querySelector(`#${qualificationId}`))
                .then(editor => {
                    editors[qualificationId] = editor;
                    editor.setData(document.querySelector(`#${qualificationId}`).value); // Initialize with existing value
                })
                .catch(error => console.error(error));
        }

        if (!editors[keyResponsibilitiesId]) {
            ClassicEditor.create(document.querySelector(`#${keyResponsibilitiesId}`))
                .then(editor => {
                    editors[keyResponsibilitiesId] = editor;
                    editor.setData(document.querySelector(`#${keyResponsibilitiesId}`).value); // Initialize with existing value
                })
                .catch(error => console.error(error));
        }
    }

    function destroyModalEditors(jobId) {
        const descriptionId = `descriptionEdit${jobId}`;
        const qualificationId = `qualificationEdit${jobId}`;
        const keyResponsibilitiesId = `keyResponsibilitiesEdit${jobId}`;

        if (editors[descriptionId]) {
            editors[descriptionId].destroy();
            delete editors[descriptionId];
        }

        if (editors[qualificationId]) {
            editors[qualificationId].destroy();
            delete editors[qualificationId];
        }

        if (editors[keyResponsibilitiesId]) {
            editors[keyResponsibilitiesId].destroy();
            delete editors[keyResponsibilitiesId];
        }
    }
</script>

<script>
    ClassicEditor.create(document.querySelector('#descriptionCreate')).catch(error => {
        console.error(error);
    });
    ClassicEditor.create(document.querySelector('#qualificationCreate')).catch(error => {
        console.error(error);
    });
    ClassicEditor.create(document.querySelector('#keyResponsibilitiesCreate')).catch(error => {
        console.error(error);
    });
</script>

<style>
    .modal-body {
        overflow-y: auto;
    }

    .scrollable-textarea {
        overflow-y: auto;
        /* Enable vertical scrolling */
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 0;
    }

    .step-indicator p {
        font-size: 15px;
        margin: 0 2px;
    }

    .step-dot {
        height: 20px;
        width: 20px;
        margin: 0 5px;
        border-radius: 50%;
        background-color: #ccc;
        /* Inactive dot color */
        transition: background-color 0.3s;
    }

    .step-dot.active {
        background-color: #007bff;
        /* Active dot color */
    }

    .separator {
        flex: 1;
        height: 4px;
        /* Thickness of the line */
        background-color: #ccc;
        /* Inactive line color */
        margin: 0 5px;
        /* Space around the line */
    }

    .content-div {
        display: none;
    }

    .content-div.active {
        display: block;
    }

    .list-item {
        cursor: pointer;
        padding: 7px 20px;
        margin-right: 10px;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    .list-item.active {
        background-color: #e9ecef;
        color: black;
    }

    .menu-list-column {
        display: flex;
        flex-direction: row;
        /* Arrange items in a row */
        list-style: none;
        padding: 0;
        margin: 0;
        justify-content: flex-start;
        /* Adjust spacing between items */
    }

    .menu-list-column .list-item {
        margin-right: 10px;
    }

    .menu-list-column .list-item:hover {
        background-color: #e9ecef;
    }
</style>