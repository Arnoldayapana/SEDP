<?php
require_once(__DIR__ . '/../Controller/JobOfferController.php');
require_once(__DIR__ . '/../Controller/DepartmentController.php');
require_once(__DIR__ . '/../Controller/JobController.php');

$jobOfferController = new JobOfferController();

$departmentController = new DepartmentController();
$jobController = new JobController();

// Get filter and search parameters from the request
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get filtered job offers
$jobOffers = $jobOfferController->getFilteredJobOffers($filter, $search);
$jobSelects = $jobController->getAllJobs();

//$branchSelects = $branchController->getAllBranch();
$departmentSelects = $departmentController->getAllDepartmentsWithBranchLocations();

$title = "Reqcruitment | SEDP HRMS";
$page = "reqcruitment";
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
                <div class="container my-5 bg-light">
                    <div class="row align-items-center justify-content-center">

                        <h3 class="fw-bold fs-4">List Of Job Offers</h3>
                        <hr style="padding-bottom: 1.5rem;">

                        <!-- Search Bar , Filter Dropdown , New Button-->
                        <!--TODO: connect to database -->
                        <div class="d-flex mt">
                            <form action="" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search here!">
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
                                    New Job Offer
                                </button>
                                <!--<button type='button' class='btn btn-info btn-md' data-bs-toggle='modal' data-bs-target='#Employee'>
                                Employee
                                </button>-->
                            </div>
                        </div>

                        <!-- JOb offer table here-->
                        <div class="table-responsive-md">
                            <table class="table table-striped">
                                <thead class="table-primary">
                                    <tr>
                                        <th>TITLE</th>
                                        <th>SALARY</th>
                                        <th>TYPE</th>
                                        <th>Department</th>
                                        <th>LOCATION</th>
                                        <th>DATE</th>
                                        <th>OPERATIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobOffers as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['title']) ?></td>
                                            <td><?= "PHP" . htmlspecialchars($row['min_salary']) ?> - <?= htmlspecialchars($row['max_salary']) ?></td>
                                            <td><?= htmlspecialchars($row['EmployeeType']) ?></td>
                                            <td><?= htmlspecialchars($row['DepartmentName']) ?></td>
                                            <td><?= htmlspecialchars($row['location']) ?></td>
                                            <td><?= htmlspecialchars($row['datePosted']) ?></td>

                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['jobOfferId'] ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['jobOfferId'] ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal<?= $row['jobOfferId'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['jobOfferId'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-xl"> <!-- Change to modal-xl for a wider modal -->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel<?= $row['jobOfferId'] ?>">Edit Job Offer</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="../Controller/JobOfferController.php?action=update" method="POST">
                                                                <div class="modal-body">
                                                                    <!-- Hidden field for JobOfferID -->
                                                                    <input type="hidden" name="jobOfferId" value="<?= $row['jobOfferId'] ?>">

                                                                    <!-- Editable fields -->
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="jobId" class="form-label">Job Title</label>
                                                                            <select class="form-select" name="jobId" id="jobId<?= $row['jobOfferId'] ?>" required onchange="updateFields(this)">
                                                                                <option value="" disabled>Select Job Title</option>
                                                                                <?php foreach ($jobSelects as $job): ?>
                                                                                    <option value="<?= $job['jobId'] ?>"
                                                                                        data-id="<?= htmlspecialchars($job['jobId']) ?>"
                                                                                        data-description="<?= htmlspecialchars($job['description']) ?>"
                                                                                        data-qualification="<?= htmlspecialchars($job['qualification']) ?>"
                                                                                        data-min-salary="<?= htmlspecialchars($job['minimumSalary']) ?>"
                                                                                        data-max-salary="<?= htmlspecialchars($job['maximumSalary']) ?>"
                                                                                        <?= $job['jobId'] == $row['jobId'] ? 'selected' : '' ?>>
                                                                                        <?= htmlspecialchars($job['title']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="JobDescription" class="form-label">Job Description</label>
                                                                            <textarea class="form-control" name="JobDescription" id="JobDescription<?= $row['jobOfferId'] ?>" rows="3" required><?= htmlspecialchars($row['JobDescription']) ?></textarea>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="qualification" class="form-label">Qualification</label>
                                                                            <input type="text" class="form-control" name="qualification" id="qualification<?= $row['jobOfferId'] ?>" value="<?= htmlspecialchars($row['qualification']) ?>" required>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="department<?= $row['jobOfferId'] ?>" class="form-label">Department Name</label>
                                                                            <select class="form-select" name="departmentName" id="department<?= $row['jobOfferId'] ?>" required onchange="updateDepartmentDetails(this)">
                                                                                <option value="" disabled selected>Select Department</option>
                                                                                <?php foreach ($departmentSelects as $department): ?>
                                                                                    <option value="<?= $department['DepartmentName'] ?>"
                                                                                        data-id="<?= $department['departmentId'] ?>"
                                                                                        data-location="<?= $department['BranchLocation'] ?>"
                                                                                        <?= $department['DepartmentName'] == $row['DepartmentName'] ? 'selected' : '' ?>>
                                                                                        <?= htmlspecialchars($department['DepartmentName']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>

                                                                        <!-- Hidden input to store the departmentId -->
                                                                        <input type="hidden" name="departmentId" id="departmentId<?= $row['jobOfferId'] ?>" value="<?= $row['departmentId'] ?>">


                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="min_salary" class="form-label">Minimum Salary</label>
                                                                            <input type="number" class="form-control" name="min_salary" id="min_salary<?= $row['jobOfferId'] ?>" value="<?= htmlspecialchars($row['min_salary']) ?>" required>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="location<?= $row['jobOfferId'] ?>" class="form-label">Location</label>
                                                                            <input type="text" class="form-control" name="location" id="location<?= $row['jobOfferId'] ?>" value="<?= htmlspecialchars($row['location']) ?>" required>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="max_salary" class="form-label">Maximum Salary</label>
                                                                            <input type="number" class="form-control" name="max_salary" id="max_salary<?= $row['jobOfferId'] ?>" value="<?= htmlspecialchars($row['max_salary']) ?>" required>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="EmployeeType" class="form-label">Employee Type</label>
                                                                            <select class="form-select" name="EmploymentType" id="EmployeeType" required>
                                                                                <option value="" disabled>Select Employee Type</option>
                                                                                <option value="Full-time" <?= $row['EmployeeType'] == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                                                                                <option value="Part-time" <?= $row['EmployeeType'] == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                                                                                <option value="Contract" <?= $row['EmployeeType'] == 'Contract' ? 'selected' : '' ?>>Contract</option>
                                                                                <option value="Intern" <?= $row['EmployeeType'] == 'Intern' ? 'selected' : '' ?>>Intern</option>
                                                                                <option value="Freelance" <?= $row['EmployeeType'] == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?= $row['jobOfferId'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['jobOfferId'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $row['jobOfferId'] ?>">Delete Job Offer</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="../Controller/JobOfferController.php?action=delete" method="POST">
                                                                <div class="modal-body">
                                                                    <!-- Hidden field for JobOfferID -->
                                                                    <input type="hidden" name="jobOfferId" value="<?= $row['jobOfferId'] ?>">

                                                                    <p>Are you sure you want to delete the job offer <strong>"<?= htmlspecialchars($row['title']) ?>"</strong> in the department <strong>"<?= htmlspecialchars($row['DepartmentName']) ?>"</strong>? This action cannot be undone.</p>
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

        <!-- Create Job Post Modal -->
        <div class="modal fade" id="CreateJobPost" tabindex="-1" aria-labelledby="createJobPostLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createJobPostLabel">Create Job Offer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../Controller/JobOfferController.php?action=create" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jobId" class="form-label">Job Title</label>
                                    <select class="form-select" name="jobId" id="jobId" required onchange="updateFields(this)">
                                        <option value="" disabled selected>Select Job Title</option>
                                        <?php foreach ($jobSelects as $job): ?>
                                            <option value="<?= $job['jobId'] ?>"
                                                data-id="<?= htmlspecialchars($job['jobId']) ?>"
                                                data-description="<?= htmlspecialchars($job['description']) ?>"
                                                data-qualification="<?= htmlspecialchars($job['qualification']) ?>"
                                                data-min-salary="<?= htmlspecialchars($job['minimumSalary']) ?>"
                                                data-max-salary="<?= htmlspecialchars($job['maximumSalary']) ?>">
                                                <?= htmlspecialchars($job['title']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="JobDescription" class="form-label">Job Description</label>
                                    <textarea class="form-control" name="JobDescription" id="JobDescription" rows="3" readonly></textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="qualification" class="form-label">Qualification</label>
                                    <input type="text" class="form-control" name="qualification" id="qualification" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Department Name</label>
                                    <select class="form-select" name="departmentName" id="department" required onchange="updateDepartmentDetailsForCreate(this)">
                                        <option value="" disabled selected>Select Department</option>
                                        <?php foreach ($departmentSelects as $department): ?>
                                            <option value="<?= htmlspecialchars($department['DepartmentName']) ?>"
                                                data-id="<?= htmlspecialchars($department['departmentId']) ?>"
                                                data-location="<?= htmlspecialchars($department['BranchLocation']) ?>">
                                                <?= htmlspecialchars($department['DepartmentName']) . ' (' . htmlspecialchars($department['BranchName']) . ')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Hidden input to store the departmentId -->
                                <input type="hidden" name="departmentId" id="departmentId" value="">


                                <div class="col-md-6 mb-3">
                                    <label for="min_salary" class="form-label">Minimum Salary</label>
                                    <input type="number" class="form-control" name="min_salary" id="min_salary" readonly>
                                </div>

                                <!-- Location input -->
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Branch Location</label>
                                    <input type="text" class="form-control" id="location" name="branchLocation" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_salary" class="form-label">Maximum Salary</label>
                                    <input type="number" class="form-control" name="max_salary" id="max_salary" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="EmploymentType" class="form-label">Employee Type</label>
                                    <select class="form-select" name="EmploymentType" id="EmployeeType" required>
                                        <option value="" disabled>Select Employee Type</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Intern">Intern</option>
                                        <option value="Freelance">Freelance</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Job Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

    function updateFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the corresponding values from data attributes
        const jobid = selectedOption.getAttribute('data-id');
        const description = selectedOption.getAttribute('data-description');
        const qualification = selectedOption.getAttribute('data-qualification');
        const minSalary = selectedOption.getAttribute('data-min-salary');
        const location = selectedOption.getAttribute('data-location');
        const maxSalary = selectedOption.getAttribute('data-max-salary');

        // Get job offer ID to update specific fields
        const jobOfferId = selectElement.id.replace('jobId', '');

        // Update the input fields with the selected option's data
        document.getElementById('JobDescription' + jobOfferId).value = description;
        document.getElementById('qualification' + jobOfferId).value = qualification;
        document.getElementById('min_salary' + jobOfferId).value = minSalary;
        document.getElementById('max_salary' + jobOfferId).value = maxSalary;

    }

    function updateDepartmentDetails(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const jobOfferId = selectElement.id.replace('department', ''); // extraction of jobOfferId

        // Update the hidden departmentId input field
        const departmentIdInput = document.getElementById('departmentId' + jobOfferId);
        departmentIdInput.value = selectedOption.getAttribute('data-id');

        // Update the location input value based on the selected option's data-location attribute
        const locationInput = document.getElementById('location' + jobOfferId);
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
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../../public/assets/javascript/AdminPage.js"></script>
</body>

</html>