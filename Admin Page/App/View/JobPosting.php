<?php
require_once(__DIR__ . '/../Controller/JobPostController.php');
require_once(__DIR__ . '/../Controller/DepartmentController.php');
require_once(__DIR__ . '/../Controller/JobController.php');
require_once(__DIR__ . '/../Controller/EmploymentTypeController.php');
require_once(__DIR__ . '/../Controller/BenefitController.php');

$JobPostController = new JobPostController();
$departmentController = new DepartmentController();
$jobController = new JobController();
$employmentController = new EmploymentTypeController();
$benefitController = new BenefitController();

// Get filter and search parameters from the request
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
// Get filtered 
$jobPosts = $JobPostController->getFilteredJobPosts($filter, $search);
$jobSelects = $jobController->getAllJobs();
$employmentTypes = $employmentController->getFilteredEmploymentType($search);
$benefits = $benefitController->getFilteredBenefit($search);

//$branchSelects = $branchController->getAllBranch();
$departmentSelects = $departmentController->getAllDepartmentsWithBranchLocations();

$title = "Job Posting | SEDP HRMS";
$page = "Job Posting";
include('../../Core/Includes/header.php');
include('../../../Database/db.php');

?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include '../../Core/Includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main">
        <!-- Header -->
        <?php include '../../Core/Includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="container-fluid">
            <!--Alert Message for error and successMessage-->
            <?php
            include('../../Core/Includes/alertMessages.php');
            ?>

            <!-- Job Offers Section -->
            <section>
                <div class="container my-3 bg-light">
                    <div class="row align-items-center justify-content-center">

                        <h3 class="fw-bold fs-4">List Of Posted Jobs</h3>
                        <hr style="padding-bottom: 1.5rem;">

                        <!-- Search Bar , Filter Dropdown , New Button-->
                        <div class="d-flex mt">
                            <form action="" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" value="<?= ($search) ?>" class="form-control" placeholder="Search here!">
                                    <button type="submit" class="btn btn-primary btn-md"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                            <div class="mx-3 mt-0">
                                <form action="" method="GET">
                                    <div class="form-group d-flex">
                                        <select class="form-select" name="filter" onchange="this.form.submit()">
                                            <option value="" <?= empty($filter) ? 'selected' : '' ?>>All Jobs</option>
                                            <option value="newest" <?= $filter == 'newest' ? 'selected' : '' ?>>Newest</option>
                                            <option value="oldest" <?= $filter == 'oldest' ? 'selected' : '' ?>>Oldest</option>
                                        </select>

                                        <!-- Reset Button -->
                                        <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!--Post Job btn-->
                            <div class="ms-auto me-3">
                                <button type='button' class='btn btn-primary btn-md' data-bs-toggle="modal" data-bs-target="#CreateJobPost">
                                    New Job Post
                                </button>
                            </div>
                        </div>

                        <!-- JOb post table here-->
                        <div class="table-responsive-md">
                            <table class="table table-striped">
                                <thead class="table-primary" style=" font-size: 19px;font-weight: bold, sans-serif;color: #333;">
                                    <tr>
                                        <th>TITLE</th>
                                        <th>SALARY</th>
                                        <th>TYPE</th>
                                        <th>DEPARTMENT</th>
                                        <th>LOCATION</th>
                                        <th>SLOTS</th>
                                        <th>EXPIRYDATE</th>
                                        <th>OPERATIONS</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 16px;font-weight: normal, sans-serif;color: #555; ">
                                    <?php foreach ($jobPosts as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['jobTitle']) ?></td>
                                            <td><?= "PHP " . htmlspecialchars($row['minimumSalary']) ?> - <?= htmlspecialchars($row['maximumSalary']) ?></td>
                                            <td><?= htmlspecialchars($row['employmentType']) ?></td>
                                            <td><?= htmlspecialchars($row['departmentName']) ?></td>
                                            <td><?= htmlspecialchars($row['country']) ?>, <?= htmlspecialchars($row['region']) ?>, <?= htmlspecialchars($row['province']) ?>, <?= htmlspecialchars($row['city']) ?></td>
                                            <td><?= htmlspecialchars($row['applicantSize']) ?></td>
                                            <td><?= htmlspecialchars($row['expiryDate']) ?></td>

                                            <td>
                                                <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['jobPostId'] ?>" data-bs-toggle="tooltip" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['jobPostId'] ?>" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['jobPostId'] ?>" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- View JobPost Modal -->
                                                <div class="modal fade" id="viewModal<?= $row['jobPostId'] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $row['jobPostId'] ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content" style="font-size: 15px; font-family: Arial;">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel">Job Post Details</h5>
                                                            </div>
                                                            <!-- Modal Body -->
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <p>Edit modal</p>
                                                                </div>
                                                            </div>
                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Edit Job Post Modal -->
                                                <div class="modal fade" id="editModal<?= $row['jobPostId'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['jobPostId'] ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title" id="editJobPostLabel">Edit Job Post</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <form id="editJobForm" action="../Controller/JobPostController.php?action=update" method="POST" enctype="multipart/form-data">
                                                                <div class="modal-body" style="height: 400px; overflow-y: auto;">

                                                                    <div class="bg-white m-1 p-1">
                                                                        <!-- Job Title -->
                                                                        <div class="col-md-12 mb-4">
                                                                            <label for="jobId" class="form-label">Job Title</label>
                                                                            <select class="form-select" name="jobId" id="jobId<?= $row['jobPostId'] ?>" required onchange="updateEditJobTitleFields(this, <?= $row['jobPostId'] ?>)">
                                                                                <option value="" disabled selected>Select Job Title</option>
                                                                                <?php foreach ($jobSelects as $job): ?>
                                                                                    <option value="<?= htmlspecialchars($job['jobId']) ?>"
                                                                                        edit-data-description="<?= htmlspecialchars_decode($job['jobDescription']) ?>"
                                                                                        edit-data-qualification="<?= htmlspecialchars_decode($job['jobQualification']) ?>"
                                                                                        edit-data-key-responsibilities="<?= htmlspecialchars_decode($job['jobKeyResponsibilities']) ?>"
                                                                                        <?= $job['jobId'] == $row['jobId'] ? 'selected' : '' ?>>
                                                                                        <?= htmlspecialchars($job['jobTitle']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <!-- Salary Input -->
                                                                        <div class="row mb-4">
                                                                            <div class="col-md-6">
                                                                                <label for="minimumSalary" class="form-label">Minimum Salary</label>
                                                                                <input name="minimumSalary" id="minimumSalary<?= $row['jobPostId'] ?>" type="text" value="<?= htmlspecialchars($row['minimumSalary']) ?>" class="form-control" placeholder="Minimum Salary" required oninput="formatNumber(this)" onkeypress="return isNumberKey(event)">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="maximumSalary" class="form-label">Maximum Salary</label>
                                                                                <input name="maximumSalary" id="maximumSalary<?= $row['jobPostId'] ?>" type="text" value="<?= htmlspecialchars($row['maximumSalary']) ?>" class="form-control" placeholder="Maximum Salary" required oninput="formatNumber(this)" onkeypress="return isNumberKey(event)">
                                                                            </div>
                                                                        </div>
                                                                        <!-- Applicant Size Input -->
                                                                        <div class="row mb-4">
                                                                            <div class="col-md-12">
                                                                                <label for="applicantSize" class="form-label">Number of Slots Available</label>
                                                                                <input name="applicantSize" id="applicantSize<?= $row['jobPostId'] ?>" value="<?= htmlspecialchars($row['applicantSize']) ?>" type="text" class="form-control" placeholder="# of Slots Available" required oninput="formatNumber(this)" onkeypress="return isNumberKey(event)">
                                                                            </div>
                                                                        </div>
                                                                        <!--  form input for expiryDate -->
                                                                        <div class="col-md-12 mb-4">
                                                                            <label for="expiryDate" class="form-label">Expiry Date of Job Post</label>
                                                                            <input type="date" id="expiryDate<?= $row['jobPostId'] ?>" value="<?= htmlspecialchars($row['expiryDate']) ?>" name="expiryDate">
                                                                        </div>
                                                                        <!-- Employment Type Select Input -->
                                                                        <div class="col-md-12 mb-4">
                                                                            <label for="employeeTypeId" class="form-label">Type of Employment</label>
                                                                            <select class="form-select" name="employeeTypeId" id="employeeTypeId" required>
                                                                                <option value="" disabled selected>Select Employment Type</option>
                                                                                <?php foreach ($employmentTypes as $employmentType): ?>
                                                                                    <option value="<?= $employmentType['employmentTypeId'] ?>"
                                                                                        <?= $employmentType['employmentTypeId'] == $row['employmentTypeId'] ? 'selected' : '' ?>>
                                                                                        <?= htmlspecialchars($employmentType['employmentType']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <!-- Job Description Input -->
                                                                        <div class="container my-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="jobDescriptionEdit">Job Description</label>
                                                                                <textarea class="form-control scrollable-textarea" name="jobDescription" id="jobDescriptionEdit<?= $row['jobPostId'] ?>" rows="5"></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Key Responsibilities Input -->
                                                                        <div class="container my-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="keyResponsibilitiesEdit">Key Responsibilities</label>
                                                                                <textarea class="form-control scrollable-textarea" name="jobKeyResponsibilities" id="keyResponsibilitiesEdit<?= $row['jobPostId'] ?>" rows="5"></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Qualifications Input -->
                                                                        <div class="container my-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="qualificationEdit">Qualifications</label>
                                                                                <textarea class="form-control scrollable-textarea" name="jobQualification" id="qualificationEdit<?= $row['jobPostId'] ?>" rows="5"></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Benefits Multi-Select Input -->
                                                                        <div class="col-md-12 mb-4">
                                                                            <label for="benefitIdEdit" class="form-label">Benefits</label>
                                                                            <select class="form-select" name="benefits" id="benefitsEdit">
                                                                                <option value="" disabled selected>Select Benefits</option>
                                                                                <?php foreach ($benefits as $benefit): ?>
                                                                                    <option value="<?= htmlspecialchars($benefit['benefitId']) ?>">
                                                                                        <?= htmlspecialchars($benefit['benefit']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <!-- Display Selected Benefits -->
                                                                        <div class="col-md-12 mb-4">
                                                                            <label class="form-label" for="selectedBenefitsEdit">Selected Benefits</label>
                                                                            <div id="selectedBenefitsEdit" class="benefit-container" style="height: auto; padding: 15px; border: 1px solid grey; border-radius: 4px;">
                                                                                <!-- Selected benefits will appear here as badges -->
                                                                            </div>
                                                                        </div>
                                                                        <!-- Department Select Input -->
                                                                        <div class="col-md-12 mb-3">
                                                                            <label for="departmentIdEdit" class="form-label">Department Name</label>
                                                                            <select class="form-select" name="departmentId" id="departmentIdEdit" required onchange="updateEditDepartmentFields(this)">
                                                                                <option value="" disabled>Select Department</option>
                                                                                <?php foreach ($departmentSelects as $department): ?>
                                                                                    <option value="<?= htmlspecialchars($department['departmentId']) ?>"
                                                                                        edit-data-branch-id="<?= htmlspecialchars($department['branchId']) ?>"
                                                                                        edit-data-country="<?= htmlspecialchars($department['country']) ?>"
                                                                                        edit-data-region="<?= htmlspecialchars($department['region']) ?>"
                                                                                        edit-data-province="<?= htmlspecialchars($department['province']) ?>"
                                                                                        edit-data-city="<?= htmlspecialchars($department['city']) ?>">
                                                                                        <?= $department['departmentId'] == $row['departmentId'] ? 'selected' : '' ?>>
                                                                                        <?= htmlspecialchars($department['DepartmentName']) . ' (' . htmlspecialchars($department['BranchName']) . ')' ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <!-- Display Selected Department -->
                                                                        <div class="card md-12">
                                                                            <input type="hidden" name="" id="" value="<?= htmlspecialchars($row['country']) ?>" />
                                                                            <input type="hidden" name="" id="" value="<?= htmlspecialchars($row['region']) ?>" />
                                                                            <input type="hidden" name="" id="" value="<?= htmlspecialchars($row['province']) ?>" />
                                                                            <input type="hidden" name="" id="" value="<?= htmlspecialchars($row['city']) ?>" />
                                                                            <div class="card-body">
                                                                                <h5 class="card-title">Branch Location</h5>
                                                                                <div class="col-md-12 mb-1">
                                                                                    <label for="countryEdit" class="form-label">Country:</label>
                                                                                    <label for="countryEdit" id="countryEdit" class="form-label">____</label>
                                                                                </div>
                                                                                <div class="col-md-12 mb-1">
                                                                                    <label for="regionEdit" class="form-label">Region:</label>
                                                                                    <label for="regionEdit" id="regionEdit" class="form-label">____</label>
                                                                                </div>
                                                                                <div class="col-md-12 mb-1">
                                                                                    <label for="provinceEdit" class="form-label">Province:</label>
                                                                                    <label for="provinceEdit" id="provinceEdit" class="form-label">____</label>
                                                                                </div>
                                                                                <div class="col-md-12 mb-1">
                                                                                    <label for="cityOrTownEdit" class="form-label">City/Town:</label>
                                                                                    <label for="cityOrTownEdit" id="cityOrTownEdit" class="form-label">____</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Hidden input to hold selected benefit IDs -->
                                                                        <input type="hidden" name="benefitId" id="selectedBenefitIdsEdit" />
                                                                    </div>
                                                                </div>
                                                                <!-- Modal Footer with Next/Back/Submit buttons -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary" id="editSubmitJobPostBtn">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?= $row['jobPostId'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['jobPostId'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $row['jobPostId'] ?>">Delete Job Offer</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="../Controller/JobPostController.php?action=delete" method="POST">
                                                                <div class="modal-body">
                                                                    <!-- Hidden field for jobPostId -->
                                                                    <input type="hidden" name="jobPostId" value="<?= $row['jobPostId'] ?>">

                                                                    <p>Are you sure you want to delete the job post <strong>"<?= htmlspecialchars($row['jobTitle']) ?>"</strong> in the department <strong>"<?= htmlspecialchars($department['DepartmentName']) . ' (' . htmlspecialchars($department['BranchName']) . ')' ?>"</strong>? This action cannot be undone.</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete Job Offer</button>
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
                </div>
            </section>
        </div>
        <!-- Create Job Post Modal with Multi-Step Form -->
        <div class="modal fade" id="CreateJobPost" tabindex="-1" aria-labelledby="createJobPostLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createJobPostLabel">New Job Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="createstep-indicator">
                        <span class="createstep-dot" id="createdot1"></span>
                        <p>Basic Information</p>
                        <hr class="createseparator" id="createseparator1">
                        <span class="createstep-dot" id="createdot2"></span>
                        <p>Details</p>
                        <hr class="createseparator" id="createseparator2">
                        <span class="createstep-dot" id="createdot3"></span>
                        <p>Location</p>
                        <hr class="createseparator" id="createseparator3">
                        <span class="createstep-dot" id="createdot4"></span>
                        <p>Review</p>
                    </div>
                    <form id="createJobForm" action="../Controller/JobPostController.php?action=create" method="POST" enctype="multipart/form-data">
                        <div class="modal-body" style="height: 400px; overflow-y: auto;">
                            <!-- Step 1: Basic Information -->
                            <div class="createStep" id="createStep1">
                                <div class="bg-white">
                                    <!-- Job Title Select -->
                                    <div class="col-md-12 mb-4">
                                        <label for="jobId" class="form-label">Job Title</label>
                                        <select class="form-select" name="jobId" id="jobId" required onchange="updateCreateJobTitleFields(this)">
                                            <option value="" disabled selected>Select Job Title</option>
                                            <?php foreach ($jobSelects as $job): ?>
                                                <option value="<?= htmlspecialchars($job['jobId']) ?>"
                                                    create-data-description="<?= ($job['jobDescription']) ?>"
                                                    create-data-qualification="<?= ($job['jobQualification']) ?>"
                                                    create-data-key-responsibilities="<?= ($job['jobKeyResponsibilities']) ?>">
                                                    <?= htmlspecialchars($job['jobTitle']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- Salary Input -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="minimumSalary" class="form-label">Minimum Salary</label>
                                            <input name="minimumSalary" id="minimumSalary" type="text" class="form-control" placeholder="Minimum Salary" required oninput="formatNumber(this)" onkeypress="return isNumberKey(event)">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="maximumSalary" class="form-label">Maximum Salary</label>
                                            <input name="maximumSalary" id="maximumSalary" type="text" class="form-control" placeholder="Maximum Salary" required oninput="formatNumber(this)" onkeypress="return isNumberKey(event)">
                                        </div>
                                    </div>
                                    <!-- Applicant Size Input -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label for="applicantSize" class="form-label">Number of Slots Available</label>
                                            <input name="applicantSize" id="applicantSize" type="text" class="form-control" placeholder="# of Slots Available" required oninput="formatNumber(this)" onkeypress="return isNumberKey(event)">
                                        </div>
                                    </div>
                                    <!--  form input for expiryDate -->
                                    <div class="col-md-12 mb-4">
                                        <label for="expiryDate" class="form-label">Expiry Date of Job Post</label>
                                        <input type="date" id="expiryDate" name="expiryDate">
                                    </div>
                                    <!-- Employment Type Input -->
                                    <div class="col-md-12 mb-4">
                                        <label for="employeeTypeId" class="form-label">Type of Employment</label>
                                        <select class="form-select" name="employeeTypeId" id="employeeTypeId" required>
                                            <option value="" disabled selected>Select Employment Type</option>
                                            <?php foreach ($employmentTypes as $employmentType): ?>
                                                <option value="<?= $employmentType['employmentTypeId'] ?>">
                                                    <?= htmlspecialchars($employmentType['employmentType']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- Benefits Multi-Select Input -->
                                    <div class="col-md-12 mb-4">
                                        <label for="benefitIdCreate" class="form-label">Benefits</label>
                                        <select class="form-select" name="benefits" id="benefitsCreate">
                                            <option value="" disabled selected>Select Benefits</option>
                                            <?php foreach ($benefits as $benefit): ?>
                                                <option value="<?= htmlspecialchars($benefit['benefitId']) ?>">
                                                    <?= htmlspecialchars($benefit['benefit']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- Display Selected Benefits -->
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label" for="selectedBenefitsCreate">Selected Benefits</label>
                                        <div id="selectedBenefitsCreate" class="benefit-container" style="height: auto; padding: 15px; border: 1px solid grey; border-radius: 4px;">
                                            <!-- Selected benefits will appear here as badges -->
                                        </div>
                                    </div>
                                    <!-- Hidden input to hold selected benefit IDs -->
                                    <input type="hidden" name="benefitId" id="selectedBenefitIdsCreate" />
                                </div>
                            </div>
                            <!-- Step 2: Details -->
                            <div class="createStep d-none" id="createStep2">
                                <!-- Job Description Input -->
                                <div class="container my-4 col-md-12">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="jobDescriptionCreateSwitch" checked onchange="toggleContent('jobDescriptionCreateSwitch', jobDescriptionCreateEditor, jobDescriptionCreateContent)">
                                        <label class="form-check-label" for="jobDescriptionCreateSwitch"><span style="font-size: 13px;">Use Job Title Description</span></label>
                                    </div>
                                    <div class="form-group">
                                        <label for="jobDescriptionCreate">Job Description</label>
                                        <textarea class="form-control scrollable-textarea" name="jobPostDescription" id="jobDescriptionCreate" rows="5"></textarea>
                                    </div>
                                </div>

                                <!-- Key Responsibilities Input -->
                                <div class="container my-4 col-md-12">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="keyResponsibilitiesCreateSwitch" checked onchange="toggleContent('keyResponsibilitiesCreateSwitch', keyResponsibilitiesCreateEditor, keyResponsibilitiesCreateContent)">
                                        <label class="form-check-label" for="keyResponsibilitiesCreateSwitch"><span style="font-size: 13px;">Use Job Title Key Responsibilities</span></label>
                                    </div>
                                    <div class="form-group">
                                        <label for="keyResponsibilitiesCreate">Key Responsibilities</label>
                                        <textarea class="form-control scrollable-textarea" name="jobPostKeyResponsibilities" id="keyResponsibilitiesCreate" rows="5"></textarea>
                                    </div>
                                </div>

                                <!-- Qualifications Input -->
                                <div class="container my-4 col-md-12">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="qualificationCreateSwitch" checked onchange="toggleContent('qualificationCreateSwitch', qualificationCreateEditor, qualificationCreateContent)">
                                        <label class="form-check-label" for="qualificationCreateSwitch"><span style="font-size: 13px;">Use Job Title Qualification</span></label>
                                    </div>
                                    <div class="form-group">
                                        <label for="qualificationCreate">Qualifications</label>
                                        <textarea class="form-control scrollable-textarea" name="jobPostQualification" id="qualificationCreate" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Location -->
                            <div class="createStep d-none" id="createStep3">
                                <div class="col-md-12 mb-3">
                                    <label for="departmentIdCreate" class="form-label">Department Name</label>
                                    <select class="form-select" name="departmentId" id="departmentIdCreate" required onchange="updateCreateDepartmentFields(this)">
                                        <option value="" disabled selected>Select Department</option>
                                        <?php foreach ($departmentSelects as $department): ?>
                                            <option value="<?= htmlspecialchars($department['departmentId']) ?>"
                                                create-data-branch-id="<?= htmlspecialchars($department['branchId']) ?>"
                                                create-data-country="<?= htmlspecialchars($department['country']) ?>"
                                                create-data-region="<?= htmlspecialchars($department['region']) ?>"
                                                create-data-province="<?= htmlspecialchars($department['province']) ?>"
                                                create-data-city="<?= htmlspecialchars($department['city']) ?>">
                                                <?= htmlspecialchars($department['DepartmentName']) . ' (' . htmlspecialchars($department['BranchName']) . ')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="card md-12 ">
                                    <div class="card-body">
                                        <h5 class="card-title">Branch Location</h5>
                                        <div class="col-md-12 mb-1">
                                            <label for="countryCreate" class="form-label ">Contry:</label>
                                            <label for="countryCreate" id="countryCreate" class="form-label ">____</label>
                                        </div>
                                        <div class="col-md-12 mb-1">
                                            <label for="regionCreate" class="form-label ">Region:</label>
                                            <label for="regionCreate" id="regionCreate" class="form-label ">____</label>
                                        </div>
                                        <div class="col-md-12 mb-1">
                                            <label for="provinceCreate" class="form-label ">Province:</label>
                                            <label for="provinceCreate" id="provinceCreate" class="form-label ">____</label>
                                        </div>
                                        <div class="col-md-12 mb-1">
                                            <label for="cityOrTownCreate" class="form-label ">City/Town:</label>
                                            <label for="cityOrTownCreate" id="cityOrTownCreate" class="form-label ">____</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Review Inputs -->
                            <div class="createStep d-none" id="createStep4">
                                <!-- Review Content Here -->
                            </div>
                        </div>

                        <!-- Modal Footer with Next/Back/Submit buttons -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" id="prevCreateStepBtn" onclick="prevCreateStep()">Previous</button>
                            <button type="button" class="btn btn-primary" id="nextCreateStepBtn" onclick="nextCreateStep()">Next</button>
                            <button type="submit" class="btn btn-success" id="createSubmitJobPostBtn">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <style>
            .scrollable-textarea {
                height: 200px;
                /* Set the height */
                overflow-y: auto;
                /* Enable vertical scrolling */
            }

            .createstep-indicator {
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 10px 0;
            }

            .createstep-indicator p {
                font-size: 15px;
                margin: 0 2px;
            }

            .createstep-dot {
                height: 20px;
                width: 20px;
                margin: 0 5px;
                border-radius: 50%;
                background-color: #ccc;
                /* Inactive dot color */
                transition: background-color 0.3s;
            }

            .createstep-dot.active {
                background-color: #007bff;
                /* Active dot color */
            }

            .createseparator {
                flex: 1;
                height: 4px;
                /* Thickness of the line */
                background-color: #ccc;
                /* Inactive line color */
                margin: 0 5px;
                /* Space around the line */
            }

            .benefit-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                /* 3 equal columns */
                gap: 10px;
                /* Add spacing between badges */
                margin-top: 10px;
            }

            .benefit-container .badge {
                display: flex;
                align-items: center;
                justify-content: space-between;
                white-space: nowrap;
                /* Prevents text wrapping in the badge */
            }

            .ck-content ul {
                list-style-type: disc !important;
                /* Ensure bullets show */
            }

            .ck-content ol {
                list-style-type: decimal !important;
                /* Ensure numbers show */
            }

            .ck-content .image {
                max-width: 80%;
                margin: 20px auto;
            }
        </style>

        <script>
            // Handle multi-step form navigation for Create Job Post Modal
            let currentCreateStep = 1;
            const totalCreateSteps = 4; // Adjusted total steps

            function showCreateStep(createstep) {
                // Hide all steps
                document.querySelectorAll('.createStep').forEach(stepElement => stepElement.classList.add('d-none'));
                // Show the current step
                document.getElementById(`createStep${createstep}`).classList.remove('d-none');

                // Adjust button visibility
                document.getElementById('prevCreateStepBtn').style.display = createstep === 1 ? 'none' : 'inline-block';
                document.getElementById('nextCreateStepBtn').style.display = createstep === totalCreateSteps ? 'none' : 'inline-block';
                document.getElementById('createSubmitJobPostBtn').style.display = createstep === totalCreateSteps ? 'inline-block' : 'none';

                // Update step indicators
                updateCreateStepIndicators(createstep);
            }

            function updateCreateStepIndicators(createstep) {
                for (let i = 1; i <= totalCreateSteps; i++) {
                    const createdot = document.getElementById(`createdot${i}`);
                    createdot.classList.remove('active');

                    if (i <= createstep) {
                        createdot.classList.add('active');
                    }
                }
            }

            function nextCreateStep() {
                if (currentCreateStep < totalCreateSteps) {
                    currentCreateStep++;
                    showCreateStep(currentCreateStep);
                }
            }

            function prevCreateStep() {
                if (currentCreateStep > 1) {
                    currentCreateStep--;
                    showCreateStep(currentCreateStep);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                $('#CreateJobPost').on('shown.bs.modal', function() {
                    currentCreateStep = 1; // Reset to the first step
                    showCreateStep(currentCreateStep);
                });
            });
        </script>
        <!--function for benefit in edit modal -->
        <script>
            const benefitsSelectEdit = document.getElementById('benefitsEdit');
            const selectedBenefitsContainerEdit = document.getElementById('selectedBenefitsEdit');
            const selectedBenefitIdsInputEdit = document.getElementById('selectedBenefitIdsEdit');
            const selectedBenefitsEdit = [];

            // Add benefit on selection
            benefitsSelectEdit.addEventListener('change', function() {
                const selectedOptions = Array.from(benefitsSelectEdit.selectedOptions);

                selectedOptions.forEach(option => {
                    if (!selectedBenefitsEdit.includes(option.value)) {
                        selectedBenefitsEdit.push(option.value);
                        const benefitChip = document.createElement('span');
                        benefitChip.className = 'badge bg-primary m-1';
                        benefitChip.innerText = option.text;
                        benefitChip.setAttribute('data-id', option.value);

                        // Adjust the size of the badge
                        benefitChip.style.fontSize = '11px';
                        benefitChip.style.padding = '2px';
                        benefitChip.style.borderRadius = '8px';

                        // Create 'x' button for removing the benefit
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn-close btn-close-white ms-2';
                        removeBtn.style.fontSize = '9px';
                        removeBtn.addEventListener('click', function() {
                            removeBenefitEdit(option.value, benefitChip);
                        });

                        benefitChip.appendChild(removeBtn);
                        selectedBenefitsContainerEdit.appendChild(benefitChip);
                        updateHiddenInput(); // Update hidden input after adding
                    }
                });
            });

            // Function to remove a benefit
            function removeBenefitEdit(benefitId, benefitChip) {
                selectedBenefitsEdit.splice(selectedBenefitsEdit.indexOf(benefitId), 1);
                selectedBenefitsContainerEdit.removeChild(benefitChip);
                updateHiddenInput(); // Update hidden input after removal

                // Deselect the option in the select box
                const options = benefitsSelectEdit.options;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value == benefitId) {
                        options[i].selected = false;
                        break;
                    }
                }
            }

            // Function to update hidden input with selected benefit IDs
            function updateHiddenInputEdit() {
                selectedBenefitIdsInputEdit.value = selectedBenefitsEdit.join(',');
            }
        </script>


        <!--function for benefit in create modal -->
        <script>
            const benefitsSelectCreate = document.getElementById('benefitsCreate');
            const selectedBenefitsContainerCreate = document.getElementById('selectedBenefitsCreate');
            const selectedBenefitIdsInputCreate = document.getElementById('selectedBenefitIdsCreate');
            const selectedBenefitsCreate = [];

            // Add benefit on selection
            benefitsSelectCreate.addEventListener('change', function() {
                const selectedOptions = Array.from(benefitsSelectCreate.selectedOptions);

                selectedOptions.forEach(option => {
                    if (!selectedBenefitsCreate.includes(option.value)) {
                        selectedBenefitsCreate.push(option.value);
                        const benefitChip = document.createElement('span');
                        benefitChip.className = 'badge bg-primary m-1';
                        benefitChip.innerText = option.text;
                        benefitChip.setAttribute('data-id', option.value);

                        // Adjust the size of the badge
                        benefitChip.style.fontSize = '11px';
                        benefitChip.style.padding = '2px';
                        benefitChip.style.borderRadius = '8px';

                        // Create 'x' button for removing the benefit
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn-close btn-close-white ms-2';
                        removeBtn.style.fontSize = '9px';
                        removeBtn.addEventListener('click', function() {
                            removeBenefitCreate(option.value, benefitChip);
                        });

                        benefitChip.appendChild(removeBtn);
                        selectedBenefitsContainerCreate.appendChild(benefitChip);
                        updateHiddenInputCreate(); // Update hidden input after adding
                    }
                });
            });

            // Function to remove a benefit
            function removeBenefitCreate(benefitId, benefitChip) {
                selectedBenefitsCreate.splice(selectedBenefitsCreate.indexOf(benefitId), 1);
                selectedBenefitsContainerCreate.removeChild(benefitChip);
                updateHiddenInputCreate(); // Update hidden input after removal

                // Deselect the option in the select box
                const options = benefitsSelectCreate.options;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value == benefitId) {
                        options[i].selected = false;
                        break;
                    }
                }
            }

            // Function to update hidden input with selected benefit IDs
            function updateHiddenInputCreate() {
                selectedBenefitIdsInputCreate.value = selectedBenefitsCreate.join(',');
            }
        </script>

    </div>
</div>

<script>
    function resetFilter() {
        // Reload the page without the 'filter' and 'search' query parameters
        const url = new URL(window.location.href);
        url.searchParams.delete('filter');
        url.searchParams.delete('search');
        window.location.href = url.href; // Navigate to the reset URL
    }

    let jobDescriptionCreateContent = '';
    let qualificationCreateContent = '';
    let keyResponsibilitiesCreateContent = '';



    // Function to update CKEditor fields when a job title is selected for create modal
    function updateCreateJobTitleFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        jobDescriptionCreateContent = selectedOption.getAttribute('create-data-description');
        qualificationCreateContent = selectedOption.getAttribute('create-data-qualification');
        keyResponsibilitiesCreateContent = selectedOption.getAttribute('create-data-key-responsibilities');

        if (document.getElementById('jobDescriptionCreateSwitch').checked) {
            jobDescriptionCreateEditor.setData(jobDescriptionCreateContent); // Use unique instance
        }
        if (document.getElementById('qualificationCreateSwitch').checked) {
            qualificationCreateEditor.setData(qualificationCreateContent); // Use unique instance
        }
        if (document.getElementById('keyResponsibilitiesCreateSwitch').checked) {
            keyResponsibilitiesCreateEditor.setData(keyResponsibilitiesCreateContent); // Use unique instance
        }
    }


    // Function to toggle content based on checkbox status
    function toggleContent(checkboxId, editorInstance, content) {
        const checkbox = document.getElementById(checkboxId);

        if (checkbox.checked) {
            // Restore content to the editor if checked
            editorInstance.setData(content);
        } else {
            // Clear the editor content if unchecked
            editorInstance.setData('');
        }
    }

    let jobDescriptionCreateEditor, keyResponsibilitiesCreateEditor, qualificationCreateEditor;

    // CKEditor for Create Modal
    ClassicEditor
        .create(document.querySelector('#jobDescriptionCreate'))
        .then(editor => {
            jobDescriptionCreateEditor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#keyResponsibilitiesCreate'))
        .then(editor => {
            keyResponsibilitiesCreateEditor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#qualificationCreate'))
        .then(editor => {
            qualificationCreateEditor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    function updateCreateDepartmentFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the location from the selected option's data attribute
        const branchCountry = selectedOption.getAttribute('create-data-country');
        const branchRegion = selectedOption.getAttribute('create-data-region');
        const branchProvince = selectedOption.getAttribute('create-data-province');
        const branchCity = selectedOption.getAttribute('create-data-city');

        if (branchCountry) {

            // Update each label with the corresponding part of the location
            document.getElementById('countryCreate').textContent = branchCountry || "N/A";
            document.getElementById('regionCreate').textContent = branchRegion || "N/A";
            document.getElementById('provinceCreate').textContent = branchProvince || "N/A";
            document.getElementById('cityOrTownCreate').textContent = branchCity || "N/A";
        } else {
            // Reset fields if no location is found
            document.getElementById('countryCreate').textContent = "____";
            document.getElementById('regionCreate').textContent = "____";
            document.getElementById('provinceCreate').textContent = "____";
            document.getElementById('cityOrTownCreate').textContent = "____";
        }
    }

    function updateDepartmentDetails(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const jobPostId = selectElement.id.replace('department', ''); // extraction of jobPostId

        // Update the hidden departmentId input field
        const departmentIdInput = document.getElementById('departmentId' + jobPostId);
        departmentIdInput.value = selectedOption.getAttribute('data-id');

        // Update the location input value based on the selected option's data-location attribute
        const locationInput = document.getElementById('location' + jobPostId);
        locationInput.value = selectedOption.getAttribute('data-location');
    }

    function updateDepartmentDetailsForCreate(selectElement) {
        // Get the selected option
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get department ID and branch location from data attributes
        const departmentId = selectedOption.getAttribute('data-id');
        const branchLocation = selectedOption.getAttribute('data-location');

        // Update the hidden input field with the department ID
        document.getElementById('departmentId').value = departmentId;

        // Update the location input field with the branch location
        document.getElementById('location').value = branchLocation;
    }

    function updateDepartmentDetailsForCreate(selectElement) {
        // Get the selected option
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get department ID and branch location from data attributes
        const departmentId = selectedOption.getAttribute('data-id');
        const branchLocation = selectedOption.getAttribute('data-location');

        // Update the hidden input field with the department ID
        document.getElementById('departmentId').value = departmentId;

        // Update the location input field with the branch location
        document.getElementById('location').value = branchLocation;
    }
</script>

<script>
    // Object to store initialized CKEditor instances by jobPostId
    const editors = {};

    document.addEventListener('DOMContentLoaded', function() {
        // Listen for the modal show event
        document.querySelectorAll('[id^="editModal"]').forEach(modalElement => {
            modalElement.addEventListener('shown.bs.modal', function() {
                const jobPostId = modalElement.getAttribute('id').replace('editModal', '');
                if (!editors[jobPostId]) { // Only initialize if editor doesn't already exist
                    initializeModalEditors(jobPostId);
                }
            });

            // Destroy CKEditor instances on modal hide to avoid duplicates
            modalElement.addEventListener('hidden.bs.modal', function() {
                const jobPostId = modalElement.getAttribute('id').replace('editModal', '');
                destroyModalEditors(jobPostId);
            });
        });
    });

    function initializeModalEditors(jobPostId) {
        const descriptionId = `jobDescriptionEdit${jobPostId}`;
        const qualificationId = `qualificationEdit${jobPostId}`;
        const keyResponsibilitiesId = `keyResponsibilitiesEdit${jobPostId}`;

        // Get the preselected option in the job title select field
        const selectElement = document.getElementById(`jobId`);
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get pre-filled values from the selected option's attributes
        const jobDescription = selectedOption.getAttribute('edit-data-description') || "";
        const keyResponsibilities = selectedOption.getAttribute('edit-data-key-responsibilities') || "";
        const qualification = selectedOption.getAttribute('edit-data-qualification') || "";

        editors[jobPostId] = {}; // Initialize object to store editor instances for this jobPostId

        // Initialize CKEditor and set default data for each field
        if (!editors[jobPostId][descriptionId] && document.querySelector(`#${descriptionId}`)) {
            ClassicEditor.create(document.querySelector(`#${descriptionId}`))
                .then(editor => {
                    editors[jobPostId][descriptionId] = editor;
                    editor.setData(jobDescription); // Set initial data
                })
                .catch(error => console.error(error));
        }

        if (!editors[jobPostId][qualificationId] && document.querySelector(`#${qualificationId}`)) {
            ClassicEditor.create(document.querySelector(`#${qualificationId}`))
                .then(editor => {
                    editors[jobPostId][qualificationId] = editor;
                    editor.setData(qualification); // Set initial data
                })
                .catch(error => console.error(error));
        }

        if (!editors[jobPostId][keyResponsibilitiesId] && document.querySelector(`#${keyResponsibilitiesId}`)) {
            ClassicEditor.create(document.querySelector(`#${keyResponsibilitiesId}`))
                .then(editor => {
                    editors[jobPostId][keyResponsibilitiesId] = editor;
                    editor.setData(keyResponsibilities); // Set initial data
                })
                .catch(error => console.error(error));
        }
    }


    function destroyModalEditors(jobPostId) {
        const descriptionId = `jobDescriptionEdit${jobPostId}`;
        const qualificationId = `qualificationEdit${jobPostId}`;
        const keyResponsibilitiesId = `keyResponsibilitiesEdit${jobPostId}`;

        // Destroy each editor instance and remove it from the editors object
        if (editors[jobPostId]) {
            if (editors[jobPostId][descriptionId]) {
                editors[jobPostId][descriptionId].destroy();
                delete editors[jobPostId][descriptionId];
            }
            if (editors[jobPostId][qualificationId]) {
                editors[jobPostId][qualificationId].destroy();
                delete editors[jobPostId][qualificationId];
            }
            if (editors[jobPostId][keyResponsibilitiesId]) {
                editors[jobPostId][keyResponsibilitiesId].destroy();
                delete editors[jobPostId][keyResponsibilitiesId];
            }
            delete editors[jobPostId]; // Clean up the jobPostId entry
        }
    }

    // Function to update textareas based on selected job title
    function updateEditJobTitleFields(selectElement, jobPostId) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Retrieve job description, qualifications, and responsibilities from the selected option
        const jobDescription = selectedOption.getAttribute('edit-data-description') || "";
        const keyResponsibilities = selectedOption.getAttribute('edit-data-key-responsibilities') || "";
        const qualification = selectedOption.getAttribute('edit-data-qualification') || "";

        // Set data in each CKEditor instance for the specific jobPostId
        if (editors) {
            if (editors[jobPostId][`jobDescriptionEdit${jobPostId}`]) {
                editors[jobPostId][`jobDescriptionEdit${jobPostId}`].setData(jobDescription);
            }
            if (editors[jobPostId][`keyResponsibilitiesEdit${jobPostId}`]) {
                editors[jobPostId][`keyResponsibilitiesEdit${jobPostId}`].setData(keyResponsibilities);
            }
            if (editors[jobPostId][`qualificationEdit${jobPostId}`]) {
                editors[jobPostId][`qualificationEdit${jobPostId}`].setData(qualification);
            }
        }
    }


    function updateEditDepartmentFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the location from the selected option's data attribute
        const branchCountry = selectedOption.getAttribute('edit-data-country');
        const branchRegion = selectedOption.getAttribute('edit-data-region');
        const branchProvince = selectedOption.getAttribute('edit-data-province');
        const branchCity = selectedOption.getAttribute('edit-data-city');

        if (branchLocationEdit) {
            // Update each label with the corresponding part of the location
            document.getElementById('countryEdit').textContent = branchCountry || "N/A";
            document.getElementById('regionEdit').textContent = branchRegion || "N/A";
            document.getElementById('provinceEdit').textContent = branchProvince || "N/A";
            document.getElementById('cityOrTownEdit').textContent = branchCity || "N/A";
        } else {
            // Reset fields if no location is found
            document.getElementById('countryEdit').textContent = "____";
            document.getElementById('regionEdit').textContent = "____";
            document.getElementById('provinceEdit').textContent = "____";
            document.getElementById('cityOrTownEdit').textContent = "____";
        }
    }
</script>

<script>
    // Function to allow only numeric input
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // Allow only numbers (48-57) and backspace (8)
        if (charCode != 8 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    // Function to format the number with commas
    function formatNumber(input) {
        // Remove non-numeric characters except commas
        let value = input.value.replace(/[^\d]/g, '');

        // Format the number with commas
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    //function for operations tooltip
    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    // Call the function when the page loads
    document.addEventListener('DOMContentLoaded', initializeTooltips);
</script>


<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../../public/assets/javascript/AdminPage.js"></script>
</body>

</html>