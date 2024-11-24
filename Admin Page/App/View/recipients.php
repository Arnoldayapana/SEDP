<?php
//connection
$title = 'Scholar Recipient | SEDP HRMS';
$page = 'Recipient';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

$name = "";
$email = "";
$school = "";
$contact = "";
$GradeLevel = "";
$branch = "";

$errorMessage = "";
$successMessage = "";

$selectedBranch = $_GET['branch'] ?? '';
$searchTerm = $_GET['search'] ?? '';
$status = $_GET['applicant_status'] ?? '';

// Pagination logic
$limit = 7; // Number of results per page
$pageNum = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($pageNum - 1) * $limit;

// Fetch total number of applicants
$totalCountSql = "SELECT COUNT(*) as total FROM recipient";
$totalResult = $connection->query($totalCountSql);
$totalRow = $totalResult->fetch_assoc();
$totalApplicants = $totalRow['total'];
$totalPages = ceil($totalApplicants / $limit);


?>

<div class="wrapper">
    <!--sidebar-->
    <?php
    include("../../Core/Includes/sidebar.php");
    ?>
    <div class="main p-3">
        <?php
        include('../../Core/Includes/Toasts.php');
        include('../../Core/Includes/navBar.php');
        ?>

        <div class="container-fluid shadow p-3 mb-0 bg-body-tertiary rounded-4" my-4>
            <h3 class="fw-bold fs-4">List Of Recipient</h3>
            <hr>
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <!-- Search Form -->
                    <form id="searchForm" action="" method="GET" onsubmit="return validateSearch()">
                        <div class="input-group mb-1">
                            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search here!" value="<?= htmlspecialchars($searchTerm); ?>">
                            <button type="submit" class="btn btn-primary btn-md">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <!-- Department Filter -->
                    <div class="col-3 mx-3">
                        <form action="" method="GET">
                            <div class="form-group d-flex">
                                <select class="form-select" name="branch" onchange="this.form.submit()">
                                    <option value="" disabled <?= empty($selectedBranch) ? 'selected' : ''; ?>>
                                        Select Branch
                                    </option>
                                    <?php
                                    $sql = "SELECT * FROM tblbranch ORDER BY name ASC";
                                    $result = $connection->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['name'] === $selectedBranch) ? 'selected' : '';
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
                        <button type='button' class='btn btn-primary btn-md' data-bs-toggle='modal' data-bs-target='#AddRecipient'>
                            Add Recipient
                        </button>
                    </div>
                </div>
            </div>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>BRANCH</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Initialize SQL query
                    $sql = "SELECT * FROM recipient";

                    // Store parameters and their types
                    $params = [];
                    $types = "";

                    // Apply search filter if a search term is provided
                    if (!empty($searchTerm)) {
                        $sql .= " WHERE name LIKE ? OR email LIKE ?";
                        $searchTermWithWildcards = "%" . $searchTerm . "%";
                        $params = [$searchTermWithWildcards, $searchTermWithWildcards];
                        $types = "ss"; // "s" for string
                    }

                    // Apply department filter if selected
                    if (!empty($selectedBranch)) {
                        if (strpos($sql, 'WHERE') !== false) {
                            $sql .= " AND branch = ?";
                        } else {
                            $sql .= " WHERE branch = ?";
                        }
                        $params[] = $selectedBranch;
                        $types .= "s"; // Add string parameter for branch
                    }

                    // Add pagination limits
                    $sql .= " LIMIT $limit OFFSET $offset";

                    // Prepare statement
                    if ($stmt = $connection->prepare($sql)) {
                        // Bind parameters if there are any
                        if ($params) {
                            $stmt->bind_param($types, ...$params);
                        }

                        // Execute the query
                        $stmt->execute();

                        // Get result
                        $result = $stmt->get_result();

                        // Loop through the results and display them
                        while ($row = $result->fetch_assoc()) {
                            // create a unique modal ID for each employee
                            $modalId = "editRecipient" . $row['recipient_id'];
                            $ViewId = "viewRecipient" . $row['recipient_id'];
                            $passwordFieldId = "password" . $row['recipient_id'];
                            $toggleIconId = "togglePasswordIcon" . $row['recipient_id'];

                            echo "
                            <tr>
                                <td>$row[recipient_id]</td>
                                <td>$row[name]</td>
                                <td>$row[email]</td>
                                <td>$row[branch]</td>
                                <td>
                                    <a href='../Scholar/ViewScholarInfo.php?id=$row[recipient_id]'
                                        class='btn btn-warning btn-sm'>
                                        <i class='bi bi-eye'></i>
                                    </a>

                                    <!-- Edit Button (Opens Modal) -->
                                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#$modalId'>
                                            <i class='bi bi-pencil-square'></i>
                                        </button>

                                    <div class='modal fade' id='$modalId' tabindex='-1' aria-labelledby='editRecipientLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content' style='width:550px;'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='editRecipientLabel'>Edit Recipient</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <form action='../Scholar/EditRecipient.php' method='POST'>
                                                    <div class='modal-body'>
                                                        <input type='hidden' name='recipient_id' value='{$row['recipient_id']}'>
                                                        <div class='mb-2'>
                                                            <label for='name' class='form-label'>Name</label>
                                                            <input type='text' class='form-control' name='name' value='{$row['name']}' required>
                                                        </div>
                                                        <div class='mb-2'>
                                                            <label for='email' class='form-label'>Email</label>
                                                            <input type='email' class='form-control' name='email' value='{$row['email']}' required>
                                                        </div>
                                                        <div class='mb-2'>
                                                            <label for='school' class='form-label'>School</label>
                                                            <input type='text' class='form-control' name='school' value='{$row['school']}' required>
                                                        </div>
                                                        <div class='mb-2'>
                                                            <label for='contact' class='form-label'>Contact</label>
                                                            <input type='text' class='form-control' name='contact' value='{$row['contact']}' required>
                                                        </div>
                                                        <div class='mb-2'>
                                                                <label for='branch' class='form-label'>Branch</label>
                                                                 <select class='form-select' name='branch' required>
                                                                <option value='' disabled>Select a branch</option>";

                            // Fetch branches from the database
                            $sql_dept = "SELECT * FROM tblbranch ORDER BY name ASC";
                            $result_dept = $connection->query($sql_dept);

                            if ($result_dept) {
                                while ($dept_row = $result_dept->fetch_assoc()) {
                                    $selected = ($dept_row['name'] == $row['branches']) ? 'selected' : '';
                                    echo "<option value='{$dept_row['name']}' $selected>{$dept_row['name']}</option>";
                                }
                            } else {
                                echo "<option value=''>Error loading branches</option>";
                            }

                            echo "
                                                                </select> 
                                                            </div>
                                                        <div class='mb-1'>
                                                            <label for='GradeLevel' class='form-label'>Grade Level</label>
                                                            <select class='form-select' name='GradeLevel' required>
                                                                <option value=''>Select</option>";

                            // Fetch grade levels
                            $gradeLevelQuery = 'SELECT * FROM grade_level';
                            $gradeResult = $connection->query($gradeLevelQuery);

                            if (!$gradeResult) {
                                die('Invalid Query: ' . $connection->error);
                            }
                            while ($gradeRow = $gradeResult->fetch_assoc()) {
                                $selected = ($row['GradeLevel'] == $gradeRow['name']) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($gradeRow['name']) . "' $selected>" . htmlspecialchars($gradeRow['name']) . "</option>";
                            }

                            echo "
                                                    </select>
                                                        </div>
                                                
                                                    <div class='form-group mb-1'>
                                                        <label>Password</label>
                                                        <div class='input-group'>
                                                            <input type='password' id='{$passwordFieldId}' class='form-control' name='password' value='" . htmlspecialchars($row['password']) . "' required>
                                                            <span class='input-group-text' onclick=\"togglePasswordVisibility('$passwordFieldId', '$toggleIconId')\" style='cursor: pointer;'>
                                                                <i id='{$toggleIconId}' class='fa fa-eye'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    </div>


                                                    <div class='modal-footer'>
                                                        <button type='button' class='btn btn-outline-secondary me-2' data-bs-dismiss='modal'>Cancel</button>
                                                        <button type='submit' class='btn btn-primary'>Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' 
                                           data-bs-target='#DeleteRecipient' onclick='setRecipientIdForDelete($row[recipient_id])'>
                                          <i class='bi bi-trash'></i>
                                   </button> 
                                </td>
                            </tr>";

                            // View Modal for each applicant
                            include('../Scholar/ViewRecipientsModal.php');
                        }

                        $stmt->close();
                    } else {
                        die("Query preparation failed: " . $connection->error);
                    }
                    ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- First Page Button -->
                        <li class="page-item <?= ($pageNum <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=1&search=<?= htmlspecialchars($searchTerm); ?>&applicant_status=<?= htmlspecialchars($status); ?>" aria-label="First">
                                <span aria-hidden="true">&laquo;&laquo;</span>
                            </a>
                        </li>

                        <!-- Previous Page Button -->
                        <li class="page-item <?= ($pageNum <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $pageNum - 1; ?>&search=<?= htmlspecialchars($searchTerm); ?>&applicant_status=<?= htmlspecialchars($status); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i === $pageNum) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&search=<?= htmlspecialchars($searchTerm); ?>&applicant_status=<?= htmlspecialchars($status); ?>">
                                    <?= $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page Button -->
                        <li class="page-item <?= ($pageNum >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $pageNum + 1; ?>&search=<?= htmlspecialchars($searchTerm); ?>&applicant_status=<?= htmlspecialchars($status); ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>

                        <!-- Last Page Button -->
                        <li class="page-item <?= ($pageNum >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $totalPages; ?>&search=<?= htmlspecialchars($searchTerm); ?>&applicant_status=<?= htmlspecialchars($status); ?>" aria-label="Last">
                                <span aria-hidden="true">&raquo;&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        </main>
    </div>
    <!-- Modal Add Employee-->
    <?php
    include("../../App/Scholar/AddRecipient.php");
    include("../../App/Scholar/DeleteRecipient.php");
    include("../../Core/Includes/script.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="../../Public/Assets/Js/AdminPage.js"></script>
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
    </body>

    </html>