<?php

$title = 'Branch | SEDP HRMS';
$page = 'Branch';

include('../../Core/Includes/header.php');

$errorMessage = "";
$successMessage = "";


require_once(__DIR__ . '/../Controller/BranchController.php');

$branchController = new BranchController();

// Get filter and search parameters from the request
//TODO:connect to controller
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$branchs = $branchController->getFilteredBranch($filter, $search);

?>
<style>
    #suggestions {
        list-style-type: none;
        padding: 0;
        background-color: white;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
    }

    #suggestions li {
        padding: 8px;
        cursor: pointer;
    }

    #suggestions li:hover {
        background-color: #f0f0f0;
    }
</style>
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
            <h3 class="fw-bold fs-4">List Of Branches</h3>
            <hr>
            <!-- Search Bar , Filter Dropdown , New Button-->
            <!--TODO: connect to database -->
            <div class="d-flex mt">
                <form action="" method="GET">
                    <div class="input-group mb-1">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search here!">
                        <button type="submit" class="btn btn-primary btn-md"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                <div class="mx-2 mt-0">
                    <form action="" method="GET">
                        <div class="form-group d-flex">
                            <!-- Reset Button -->
                            <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <!--Add branch btn-->
                <div class="ms-auto me-3">
                    <button type='button' class='btn btn-primary btn-md' data-bs-toggle="modal" data-bs-target="#CreateBranch">
                        New Branch
                    </button>
                    <!--<button type='button' class='btn btn-info btn-md' data-bs-toggle='modal' data-bs-target='#Employee'>
                                Employee
                                </button>-->
                </div>
            </div>
            <br>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>BRANCH NAME</th>
                        <th>LOCATION</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($branchs as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['country']) ?>, <?= htmlspecialchars($row['region']) ?>, <?= htmlspecialchars($row['province']) ?>, <?= htmlspecialchars($row['city']) ?></td>
                            <td>
                                <!-- view Button -->
                                <a href="../Branches/ViewBranch.php?branch_id={$row['branch_id']}" class='btn btn-warning btn-sm'>
                                    <i class='bi bi-eye'></i>
                                </a>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['branchId'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['branchId'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?= $row['branchId'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['branchId'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content round-3 shadow-lg border-0">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title fw-bold" id="editModalLabel<?= $row['branchId'] ?>">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Branch
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="../Controller/BranchController.php?action=update" method="POST">
                                                <div class="modal-body">
                                                    <!-- Hidden field for Branch ID -->
                                                    <input type="hidden" name="branchId" value="<?= $row['branchId'] ?>">
                                                    <!-- Hidden fields for storing the location names -->
                                                    <input type="hidden" name="countryName" id="countryName<?= $row['branchId'] ?>" value="<?= htmlspecialchars($row['country']) ?>">
                                                    <input type="hidden" name="regionName" id="regionName<?= $row['branchId'] ?>" value="<?= htmlspecialchars($row['region']) ?>">
                                                    <input type="hidden" name="provinceName" id="provinceName<?= $row['branchId'] ?>" value="<?= htmlspecialchars($row['province']) ?>">
                                                    <input type="hidden" name="cityName" id="cityName<?= $row['branchId'] ?>" value="<?= htmlspecialchars($row['city']) ?>">


                                                    <!-- Editable fields -->
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="name" class="form-label">Branch Name</label>
                                                            <input type="text" class="form-control" name="name" id="name<?= $row['branchId'] ?>" value="<?= htmlspecialchars($row['name']) ?>" required>
                                                        </div>

                                                        <!-- Dropdowns for country, region, province, and city -->
                                                        <div class="col-md-12">
                                                            <label for="countrySelect<?= $row['branchId'] ?>" class="form-label">Country</label>
                                                            <select id="countrySelect<?= $row['branchId'] ?>" name="countrySelect" class="form-select">
                                                                <option value="">Select a Country</option>
                                                                <option value="<?= htmlspecialchars($row['country']) ?>" selected><?= htmlspecialchars($row['country']) ?></option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12 mt-1">
                                                            <label for="regionSelect<?= $row['branchId'] ?>" class="form-label">Region</label>
                                                            <select id="regionSelect<?= $row['branchId'] ?>" name="regionSelect" class="form-select" >
                                                                <option value="">Select a Region</option>
                                                                <option value="<?= htmlspecialchars($row['region']) ?>" selected><?= htmlspecialchars($row['region']) ?></option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-1">
                                                            <label for="provinceSelect<?= $row['branchId'] ?>" class="form-label">Province</label>
                                                            <select id="provinceSelect<?= $row['branchId'] ?>" name="provinceSelect" class="form-select" >
                                                                <option value="">Select a Province</option>
                                                                <option value="<?= htmlspecialchars($row['province']) ?>" selected><?= htmlspecialchars($row['province']) ?></option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-1">
                                                            <label for="citySelect<?= $row['branchId'] ?>" class="form-label">City/Town</label>
                                                            <select id="citySelect<?= $row['branchId'] ?>" name="citySelect" class="form-select" >
                                                                <option value="">Select a City/Town</option>
                                                                <option value="<?= htmlspecialchars($row['city']) ?>" selected><?= htmlspecialchars($row['city']) ?></option>
                                                            </select>
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
                                <div class="modal fade" id="deleteModal<?= $row['branchId'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['branchId'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content round-3 shadow-lg border-0">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $row['branchId'] ?>">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Branch
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="../Controller/BranchController.php?action=delete" method="POST">
                                                <div class="modal-body">
                                                    <!-- Hidden field for JobOfferID -->
                                                    <input type="hidden" name="branchId" value="<?= $row['branchId'] ?>">

                                                    <p>Are you sure you want to delete the branch<strong>"<?= htmlspecialchars($row['name']) ?>"</strong>? This action cannot be undone.</p>
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

        <!-- Create Branch Modal -->
        <div class="modal fade" id="CreateBranch" tabindex="-1" aria-labelledby="createBranchLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-3 shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="createBranchLabel">
                            <i class="bi bi-plus-circle-fill me-2"></i>Create Branch
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../Controller/BranchController.php?action=create" method="POST">
                        <div class="modal-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Branch Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter branch name">
                                </div>

                                <div class="col-12">
                                    <h6 class="mt-2 mb-1">Select a Location</h6>
                                </div>

                                <!-- Dropdowns for country, province, and city -->
                                <div class="col-md-12">
                                    <label for="countrySelect" class="form-label">Country</label>
                                    <select id="countrySelect" name="countrySelect" class="form-select">
                                        <option value="">Select a Country</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <label for="regionSelect" class="form-label">Region</label>
                                    <select id="regionSelect" name="regionSelect" class="form-select" disabled>
                                        <option value="">Select a Region</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <label for="provinceSelect" class="form-label">Province</label>
                                    <select id="provinceSelect" name="provinceSelect" class="form-select" disabled>
                                        <option value="">Select a Province</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <label for="citySelect" class="form-label">City/Town</label>
                                    <select id="citySelect" name="citySelect" class="form-select" disabled>
                                        <option value="">Select a City/Town</option>
                                    </select>
                                </div>

                                <!-- Hidden inputs to capture location names -->
                                <input type="hidden" id="countryName" name="country">
                                <input type="hidden" id="regionName" name="region">
                                <input type="hidden" id="provinceName" name="province">
                                <input type="hidden" id="cityName" name="city">
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
 
<script>
    function resetFilter() {
        // Reload the page without the 'filter' and 'search' query parameters
        const url = new URL(window.location.href);
        url.searchParams.delete('filter');
        url.searchParams.delete('search');
        window.location.href = url.href; // Navigate to the reset URL
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
<script src="../../../api/GeoNameAPI.js" defer></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>