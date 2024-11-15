<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

// Handle department filter
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="../../public/assets/css/AdminStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap CSS -->
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include_once('../../core/includes/sidebar.php'); ?>

        <main class="main">
            <!-- Header -->
            <?php include '../../core/includes/navBar.php'; ?>

            <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4">
                <h3 class="fw-bold fs-3">List Of Employees</h3>
                <hr style="padding-bottom: 1.5rem;">

                <!-- Search and Filter -->
                <div class="d-flex mt">
                    <form action="" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>"
                                class="form-control" placeholder="Search here!">
                            <button type="submit" class="btn btn-primary btn-md">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="mx-3 mt-0">
                        <form method="GET">
                            <div class="form-group d-flex">
                                <select class="form-select" name="department" onchange="this.form.submit()" required>
                                    <option value="" disabled <?php echo empty($selectedDepartment) ? 'selected' : ''; ?>>Select a Department</option>
                                    <?php
                                    include("../../../Database/db.php");
                                    $sql = "SELECT * FROM departments ORDER BY name ASC";
                                    $result = $connection->query($sql);

                                    if (!$result) {
                                        die("Invalid Query: " . $connection->error);
                                    }

                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['name'] == $selectedDepartment) ? 'selected' : '';
                                        echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="ms-auto me-3">
                        <a href="../View/Employee.php" class="btn btn-dark btn-md">Back</a>
                    </div>
                </div>

                <br>
                <div class="table-responsive-md">
                    <table class="table table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>CONTACT</th>
                                <th>OPERATIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM employees WHERE 1";

                            if (!empty($searchTerm)) {
                                $searchTermEscaped = $connection->real_escape_string($searchTerm);
                                $query .= " AND username LIKE '%$searchTermEscaped%'";
                            }

                            if (!empty($selectedDepartment)) {
                                $selectedDepartmentEscaped = $connection->real_escape_string($selectedDepartment);
                                $query .= " AND department = '$selectedDepartmentEscaped'";
                            }

                            $query .= " ORDER BY employee_id DESC";
                            $result = $connection->query($query);

                            if (!$result) {
                                die("Invalid Query: " . $connection->error);
                            }

                            while ($row = $result->fetch_assoc()) {
                                $modalId = "editEmployeeModal" . $row['employee_id'];
                                $passwordFieldId = "password" . $row['employee_id'];
                                $toggleIconId = "togglePasswordIcon" . $row['employee_id'];
                                $viewId = "viewEmployeeModal" . $row['employee_id'];

                                echo "
                                <tr>
                                    <td>{$row['employee_id']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['ContactNumber']}</td>
                                    <td>
                                        <a href='../Employee/sample.php?id={$row['employee_id']}' class='btn btn-warning btn-sm'>
                                            <i class='bi bi-eye'></i>
                                        </a>
                                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' 
                                                data-bs-target='#$modalId'>
                                            <i class='bi bi-pencil-square'></i>
                                        </button>
                                        <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' 
                                                data-bs-target='#DeleteEmployee' 
                                                onclick='setEmployeeIdForDelete({$row['employee_id']})'>
                                            <i class='bi bi-trash'></i>
                                        </button>

                                        <div class='modal fade' id='$modalId' tabindex='-1' 
                                             aria-labelledby='editEmployeeLabel' aria-hidden='true'>
                                            <div class='modal-dialog modal-dialog-centered'>
                                                <div class='modal-content' style='width:550px;'>
                                                    <div class='modal-header'>
                                                        <h2 class='modal-title fs-5 fw-bold'>Edit Employee</h2>
                                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                    </div>
                                                    <form action='../Dao/Employee-db/EditEmployee_db.php' method='POST'>
                                                        <div class='modal-body'>
                                                            <input type='hidden' name='employee_id' value='{$row['employee_id']}'>
                                                            
                                                            <div class='mb-2'>
                                                                <label class='form-label'>Name</label>
                                                                <input type='text' class='form-control' name='username' value='{$row['username']}' required>
                                                            </div>

                                                            <div class='mb-2'>
                                                                <label class='form-label'>Email</label>
                                                                <input type='email' class='form-control' name='email' value='{$row['email']}' required>
                                                            </div>

                                                            <div class='mb-2'>
                                                                <label class='form-label'>Department</label>
                                                                <select class='form-select' name='department' required>
                                                                    <option value='' disabled>Select a Department</option>";

                                $sql_dept = "SELECT * FROM departments ORDER BY name ASC";
                                $result_dept = $connection->query($sql_dept);

                                if ($result_dept) {
                                    while ($dept_row = $result_dept->fetch_assoc()) {
                                        $selected = ($dept_row['name'] == $row['department']) ? 'selected' : '';
                                        echo "<option value='{$dept_row['name']}' $selected>{$dept_row['name']}</option>";
                                    }
                                } else {
                                    echo "<option>Error loading departments</option>";
                                }

                                echo "
                                                                </select>
                                                            </div>

                                                            <div class='mb-2'>
                                                                <label class='form-label'>Contact Number</label>
                                                                <input type='tel' class='form-control' name='ContactNumber' 
                                                                       pattern='[0-9]{11}' value='{$row['ContactNumber']}' maxlength='11' required>
                                                            </div>

                                                            <div class='mb-2'>
                                                                <label class='form-label'>Password</label>
                                                                <div class='input-group'>
                                                                    <input type='password' id='$passwordFieldId' class='form-control' 
                                                                           name='password' pattern='^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$' 
                                                                           title='Password must be at least 8 characters long, include one uppercase letter, one number, and one special character.' 
                                                                           value='{$row['password']}' required>
                                                                    <span class='input-group-text' onclick=\"togglePasswordVisibility('$passwordFieldId', '$toggleIconId')\" style='cursor: pointer;'>
                                                                        <i id='$toggleIconId' class='fa fa-eye'></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-outline-secondary me-2' data-bs-dismiss='modal'>Close</button>
                                                            <button type='submit' class='btn btn-primary'>Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php include("../Employee/AddEmployee.php"); ?>
    <?php include("../../App/Employee/DeleteEmployee.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Public/Assets/Js/AdminPage.js"></script>

    <script>
        function resetFilter() {
            window.location.href = window.location.pathname;
        }

        function togglePasswordVisibility(passwordFieldId, toggleIconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleIcon = document.getElementById(toggleIconId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>

</html>