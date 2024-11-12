<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Handle department filter
$selectedDepartment = $_GET['department'] ?? '';
$selectedDate = $_GET['hire_date'] ?? '';  // Capture the date sorting selection

?>
<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4">
            <?php include('../../Core/Includes/alertMessages.php'); ?>

            <h3 class="fw-bold fs-4">List Of Employees</h3>
            <hr style="padding-bottom: 1.5rem;">

            <div class="d-flex">
                <!-- Search Form -->
                <form id="searchForm" action="../Employee/SearchEmployee.php" method="GET" onsubmit="return validateSearch()">
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search here!">
                        <button type="submit" class="btn btn-primary btn-md">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Dropdown for sorting filter -->
                <div class="col-3 mx-3">
                    <form action="" method="GET">
                        <div class="form-group d-flex">
                            <select class="form-select" name="hire_date" onchange="this.form.submit()">
                                <option value="" disabled <?= empty($selectedDate) ? 'selected' : ''; ?>>
                                    Sort by date
                                </option>
                                <option value="newest" <?= $selectedDate === 'newest' ? 'selected' : ''; ?>>Newest</option>
                                <option value="oldest" <?= $selectedDate === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Department Filter -->
                <div class="col-3 mx-3">
                    <form action="" method="GET">
                        <div class="form-group d-flex">
                            <select class="form-select" name="department" onchange="this.form.submit()">
                                <option value="" disabled <?= empty($selectedDepartment) ? 'selected' : ''; ?>>
                                    Select Department
                                </option>
                                <?php
                                $sql = "SELECT * FROM departments ORDER BY name ASC";
                                $result = $connection->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($row['name'] === $selectedDepartment) ? 'selected' : '';
                                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="ms-auto me-3">
                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#AddEmployee">
                        Add Employee
                    </button>
                </div>
            </div>

            <div class="table-responsive-md">
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Base SQL query
                        $sql = "SELECT * FROM employees";

                        // Apply department filter if selected
                        if (!empty($selectedDepartment)) {
                            $sql .= " WHERE department = ?";
                        }

                        // Apply sorting based on hire date selection
                        if ($selectedDate === 'newest') {
                            $sql .= " ORDER BY hire_date DESC";
                        } elseif ($selectedDate === 'oldest') {
                            $sql .= " ORDER BY hire_date ASC";
                        }

                        $sql .= " LIMIT 8";  // Limit results to 8 per page

                        // Prepare and execute the query
                        $stmt = $connection->prepare($sql);
                        if (!empty($selectedDepartment)) {
                            $stmt->bind_param("s", $selectedDepartment);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $modalId = "editEmployeeModal" . $row['employee_id'];
                            $passwordFieldId = "password" . $row['employee_id'];
                            $toggleIconId = "togglePasswordIcon" . $row['employee_id'];
                        ?>
                            <tr>
                                <td><?= $row['employee_id'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['ContactNumber'] ?></td>
                                <td>
                                    <a href="../Employee/sample.php?id=<?= $row['employee_id'] ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DeleteEmployee" onclick="setEmployeeIdForDelete(<?= $row['employee_id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="editEmployeeLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" style="width:550px;">
                                                <div class="modal-header">
                                                    <h2 class="modal-title fs-5 fw-bold">Edit Employee</h2>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="../Dao/Employee-db/EditEmployee_db.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="employee_id" value="<?= $row['employee_id'] ?>">

                                                        <div class="mb-2">
                                                            <label for="username">Name</label>
                                                            <input type="text" class="form-control" name="username" value="<?= $row['username'] ?>" required>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="email">Email</label>
                                                            <input type="email" class="form-control" name="email" value="<?= $row['email'] ?>" required>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="department">Department</label>
                                                            <select class="form-select" name="department" required>
                                                                <option value="" disabled>Select a Department</option>
                                                                <?php
                                                                $deptResult = $connection->query("SELECT * FROM departments ORDER BY name ASC");
                                                                while ($dept = $deptResult->fetch_assoc()) {
                                                                    $selected = ($dept['name'] === $row['department']) ? 'selected' : '';
                                                                    echo "<option value='{$dept['name']}' $selected>{$dept['name']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="branch">Branch</label>
                                                            <select class="form-select" name="branch" required>
                                                                <?php
                                                                $branchResult = $connection->query("SELECT * FROM tblbranch ORDER BY name ASC");
                                                                while ($branch = $branchResult->fetch_assoc()) {
                                                                    $selected = ($branch['name'] === $row['branch']) ? 'selected' : '';
                                                                    echo "<option value='{$branch['name']}' $selected>{$branch['name']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="ContactNumber">Contact Number</label>
                                                            <input type="tel" class="form-control" name="ContactNumber" pattern="[0-9]{11}" value="<?= $row['ContactNumber'] ?>" maxlength="11" required>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label>Password</label>
                                                            <div class="input-group">
                                                                <input type="password" id="<?= $passwordFieldId ?>" class="form-control" name="password" value="<?= $row['password'] ?>" required>
                                                                <span class="input-group-text" onclick="togglePasswordVisibility('<?= $passwordFieldId ?>', '<?= $toggleIconId ?>')" style="cursor: pointer;">
                                                                    <i id="<?= $toggleIconId ?>" class="fa fa-eye"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        include("../Employee/AddEmployee.php");
        include("../../App/Employee/DeleteEmployee.php");
        include("../../Core/Includes/script.php");
        ?>
    </div>
</div>
</body>

</html>