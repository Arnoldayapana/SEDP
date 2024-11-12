<?php

$title = 'Department | SEDP HRMS';
$page = 'Department';

include('../../Core/Includes/header.php');

$errorMessage = "";
$successMessage = "";


require_once(__DIR__ . '/../Controller/DepartmentController.php');
require_once(__DIR__ . '/../Controller/BranchController.php');

$departmentController = new DepartmentController();
$branchController = new BranchController();

// Get filter and search parameters from the request
//TODO:connect to controller
$search = isset($_GET['search']) ? $_GET['search'] : '';

$departments = $departmentController->getFilteredDepartment($search);
$branchs = $branchController->getFilteredBranch($search);

?>
<div class="wrapper">
    <!--sidebar-->
    <?php
    include_once('../../core/includes/sidebar.php');
    ?>

    <!--add employee-->
    <main class="main">
        <!--header-->
        <?php
        include '../../core/includes/navBar.php';
        ?>


        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4" my-4>
            <!--Alert Message for error and successMessage-->
            <?php
            include('../../Core/Includes/alertMessages.php');
            ?>
            <h3>List Of Departments</h3>
            <hr>
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
                            <!-- Reset Button -->
                            <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <!--Add department btn-->
                <div class="ms-auto me-3">
                    <button type='button' class='btn btn-primary btn-md' data-bs-toggle="modal" data-bs-target="#CreateDepartment">
                        New Department
                    </button>
                </div>
            </div>
            <br>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>DEPARTMENT NAME</th>
                        <th>BRANCH NAME</th>
                        <th>BRANCH LOCATION</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['DepartmentName']) ?></td>
                            <td><?= htmlspecialchars($row['BranchName']) ?></td>
                            <td><?= htmlspecialchars($row['country']) ?>, <?= htmlspecialchars($row['region']) ?>, <?= htmlspecialchars($row['province']) ?>, <?= htmlspecialchars($row['city']) ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['departmentId'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['departmentId'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <!-- view Button -->
                                <a href='../Departments/ViewDepartment.php?department_id={$row[' department_id']}' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-eye'></i>
                                </a>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?= $row['departmentId'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['departmentId'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl"> <!-- Change to modal-xl for a wider modal -->
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="editModalLabel<?= $row['departmentId'] ?>">Edit Department</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="../Controller/DepartmentController.php?action=update" method="POST">
                                                <div class="modal-body">
                                                    <!-- Hidden field for JobOfferID -->
                                                    <input type="hidden" name="departmentId" value="<?= $row['departmentId'] ?>">

                                                    <!-- Editable fields -->
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="name" class="form-label">Department Name</label>
                                                            <input type="text" class="form-control" name="DepartmentName" id="name<?= $row['departmentId'] ?>" value="<?= htmlspecialchars($row['DepartmentName']) ?>" required>
                                                        </div>

                                                        <div class="col-md-12 mb-3">
                                                            <label for="branchId" class="form-label">Branch Name</label>
                                                            <select class="form-select" name="branchId" id="branchId<?= $row['departmentId'] ?>" required onchange="updateFields(this)">
                                                                <option value="" disabled>Select Branch</option>
                                                                <?php foreach ($branchs as $branch): ?>
                                                                    <option value="<?= htmlspecialchars($branch['branchId']) ?>"
                                                                        data-id="<?= htmlspecialchars($branch['branchId']) ?>"
                                                                        data-location="<?= htmlspecialchars($branch['country']) ?>,<?= htmlspecialchars($branch['region']) ?>,<?= htmlspecialchars($branch['province']) ?>,<?= htmlspecialchars($branch['city']) ?>"
                                                                        <?= $branch['branchId'] == $row['branchId'] ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($branch['name']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12 mb-3">
                                                            <label for="BranchLocation" class="form-label">Branch Location</label>
                                                            <input type="text" class="form-control" name="location" id="BranchLocation<?= $row['departmentId'] ?>" value=<?= htmlspecialchars($branch['country']) ?>,<?= htmlspecialchars($branch['region']) ?>,<?= htmlspecialchars($branch['province']) ?>,<?= htmlspecialchars($branch['city']) ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?= $row['departmentId'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['departmentId'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $row['departmentId'] ?>">Delete Department</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="../Controller/DepartmentController.php?action=delete" method="POST">
                                                <div class="modal-body p-5">
                                                    <!-- Hidden field for JobOfferID -->
                                                    <input type="hidden" name="departmentId" value="<?= $row['departmentId'] ?>">

                                                    <p>Are you sure you want to delete the branch<strong>"<?= htmlspecialchars($row['DepartmentName']) ?>"</strong> in the branch <strong>"<?= htmlspecialchars($row['BranchName']) ?>"</strong>? This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete Branch</button>
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

        <!-- Create Department Modal -->
        <div class="modal fade" id="CreateDepartment" tabindex="-1" aria-labelledby="createDepartmenLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createDepartmentLabel">Create Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../Controller/DepartmentController.php?action=create" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="DepartmentName" class="form-label">Department Name</label>
                                    <input type="text" class="form-control" name="DepartmentName" id="DepartmentName">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="branchId" class="form-label">Branch Name</label>
                                    <select class="form-select" name="BranchId" id="branchId" required onchange="updateFields(this)">
                                        <option value="" disabled selected>Select Branch</option>
                                        <?php foreach ($branchs as $branch): ?>
                                            <option value="<?= htmlspecialchars($branch['branchId']) ?>"
                                                data-id="<?= htmlspecialchars($branch['branchId']) ?>"
                                                data-location="<?= htmlspecialchars($branch['country']) ?>,<?= htmlspecialchars($branch['region']) ?>,<?= htmlspecialchars($branch['province']) ?>,<?= htmlspecialchars($branch['city']) ?>">

                                                <?= htmlspecialchars($branch['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="BranchLocation" class="form-label">Branch Location</label>
                                    <input type="text" class="form-control" name="BranchLocation" id="BranchLocation" disabled>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function resetFilter() {
        // Reload the page without the 'filter' and 'search' query parameters
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        window.location.href = url.href; // Navigate to the reset URL
    }

    function updateFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the corresponding values from data attributes
        const branchid = selectedOption.getAttribute('data-id');
        const location = selectedOption.getAttribute('data-location');

        // Get departmentId to update specific fields
        const departmentId = selectElement.id.replace('branchId', '');

        // Update the input fields with the selected option's data
        document.getElementById('BranchLocation' + departmentId).value = location;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
<script src="../../Public/Assets/Js/AdminPage.js"></script>
</body>

</html>