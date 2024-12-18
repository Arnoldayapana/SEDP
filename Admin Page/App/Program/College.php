<?php
// Connection
$title = 'Scholar Recipient | SEDP HRMS';
$page = 'Recipient';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Initialize variables
$name = $email = $school = $contact = $GradeLevel = "";
$errorMessage = $successMessage = "";

$status = $_GET['applicant_status'] ?? '';

// Pagination logic
$limit = 7; // Number of results per page
$pageNum = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($pageNum - 1) * $limit;

// Fetch total number of applicants
$totalCountSql = "SELECT COUNT(*) as total FROM recipient WHERE GradeLevel = 'College' ";
$totalResult = $connection->query($totalCountSql);
$totalRow = $totalResult->fetch_assoc();
$totalApplicants = $totalRow['total'];
$totalPages = ceil($totalApplicants / $limit);
?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include_once('../../core/includes/sidebar.php'); ?>

    <!-- Main Content -->
    <main class="main">
        <!-- Header -->
        <?php include '../../core/includes/navBar.php'; ?>

        <div class="container-fluid shadow p-3 bg-body-tertiary rounded-3">
            <!-- Alert Messages -->
            <?php include('../../Core/Includes/alertMessages.php'); ?>
            <h3 class="fw-bold fs-5">List Of College Recipients</h3>
            <hr>
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                    <form action="../Scholar/SearchRecipient.php" method="GET">
                        <div class="input-group mb-2">
                            <input type="text" name="search" class="form-control" placeholder="Search Recipient">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    <div class="ms-auto me-3">
                        <button type='button' class='btn btn-primary btn-md' data-bs-toggle='modal' data-bs-target='#AddRecipient'>
                            Add Recipient
                        </button>
                    </div>
                </div>
            </div>
            <br>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Read all rows from the database table
                    $sql = "SELECT * FROM recipient WHERE GradeLevel = 'College' LIMIT $limit OFFSET $offset";
                    $result = $connection->query($sql);

                    if (!$result) {
                        die("Invalid Query: " . $connection->error);
                    }

                    // Read data of each row
                    while ($row = $result->fetch_assoc()) {
                        $modalId = "editRecipient" . $row['recipient_id'];
                        $ViewId = "viewRecipient" . $row['recipient_id'];

                        echo "
                        <tr>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                    <a href='viewCollegeRecipient.php?id=$row[recipient_id]'
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
                                            <form action='../Dao/programs/editCollegeRecipient-db.php' method='POST'>
                                                <div class='modal-body'>
                                                    <input type='hidden' name='recipient_id' value='{$row['recipient_id']}'>
                                                    <div class='mb-3'>
                                                        <label for='name' class='form-label'>Name</label>
                                                        <input type='text' class='form-control' name='name' value='{$row['name']}' required>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='email' class='form-label'>Email</label>
                                                        <input type='email' class='form-control' name='email' value='{$row['email']}' required>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='school' class='form-label'>School</label>
                                                        <input type='text' class='form-control' name='school' value='{$row['school']}' required>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='contact' class='form-label'>Contact</label>
                                                        <input type='text' class='form-control' name='contact' value='{$row['contact']}' required>
                                                    </div>
                                                    <div class='mb-2'>
                                                            <label for='branch' class='form-label'>Branch</label>
                                                             <select class='form-select' name='branch' required>
                                                                <option value='' disabled>Select a branch</option>";

                        // Fetch branchess from the database
                        $sql_dept = "SELECT * FROM tblbranch  ORDER BY name ASC";
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
                                                    <div class='mb-3'>
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
                                       data-bs-target='#DeleteRecipient' onclick='setRecipientIdForDelete({$row['recipient_id']})'>
                                    <i class='bi bi-trash'></i>
                                </button>
                            </td>
                        </tr>";

                        // View Modal for each applicant
                        include('../Scholar/ViewRecipientsModal.php');
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

<!-- Modal Add Recipient -->
<?php
include("AddCollegeRecipient.php");
include("DeleteCollegeRecipient.php");
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
<script src="../../Public/Assets/Js/AdminPage.js"></script>
</body>

</html>