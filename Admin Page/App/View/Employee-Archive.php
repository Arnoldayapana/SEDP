<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Handle department filters
$selectedDate = $_GET['hire_date'] ?? ''; // Capture the date sorting selection
$searchTerm = $_GET['search'] ?? ''; // Capture search input

// Pagination logic
$limit = 7; // Number of results per page
$pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($pageNum - 1) * $limit;
$status = $_GET['applicant_status'] ?? '';

// Fetch total number of archived employees
$totalCountSql = "SELECT COUNT(*) as total FROM employee_archive";
$totalResult = $connection->query($totalCountSql);
$totalRow = $totalResult->fetch_assoc();
$totalEmployees = $totalRow['total'];
$totalPages = ceil($totalEmployees / $limit);

// Base query for fetching archived employees
$query = "SELECT * FROM employee_archive";
$conditions = [];
$params = [];
$types = "";

// Apply search filter if a search term is provided
if (!empty($searchTerm)) {
    $conditions[] = "(username LIKE ? OR email LIKE ?)";
    $searchTermWithWildcards = "%" . $searchTerm . "%";
    $params[] = $searchTermWithWildcards;
    $params[] = $searchTermWithWildcards;
    $types .= "ss";
}

// Apply date sorting if selected
if (!empty($selectedDate)) {
    if ($selectedDate === "newest") {
        $conditions[] = "1=1 ORDER BY archived_at DESC";
    } elseif ($selectedDate === "oldest") {
        $conditions[] = "1=1 ORDER BY archived_at ASC";
    }
}

// Build the final query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $connection->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$archivedEmployees = [];
if ($result && $result->num_rows > 0) {
    while ($employee = $result->fetch_assoc()) {
        $archivedEmployees[] = $employee;
    }
}

$stmt->close();
?>
<div class="wrapper">
    <!-- Sidebar -->
    <?php
    include('../../Core/Includes/Toasts.php');
    include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>

        <div class="container-fluid shadow p-3 bg-body-tertiary rounded-2">

            <h3 class="fw-bold fs-4">Archived Employees</h3>
            <hr>

            <div class="d-flex">
                <!-- Dropdown for sorting filter -->
                <div class="col-3 mx-3 ms-auto">
                    <form action="" method="GET">
                        <div class="form-group d-flex">
                            <select class="form-select" name="hire_date" onchange="this.form.submit()">
                                <option value="" disabled <?= empty($selectedDate) ? 'selected' : ''; ?>>
                                    Sort by date
                                </option>
                                <option value="newest" <?= $selectedDate === 'newest' ? 'selected' : ''; ?>>Newest</option>
                                <option value="oldest" <?= $selectedDate === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                            <button type="button" class="btn btn-danger ms-2" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search Form -->
                <form id="searchForm" action="" method="GET" onsubmit="return validateSearch()">
                    <div class="input-group mb-1">
                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search here!" value="<?= htmlspecialchars($searchTerm); ?>">
                        <button type="submit" class="btn btn-primary btn-md">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive-md">
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Archived Date</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($archivedEmployees)): ?>
                            <?php foreach ($archivedEmployees as $employee): ?>
                                <tr>
                                    <td><?= htmlspecialchars($employee['username']); ?></td>
                                    <td><?= htmlspecialchars($employee['email']); ?></td>
                                    <td><?= htmlspecialchars($employee['ContactNumber']); ?></td>
                                    <td><?= htmlspecialchars($employee['archived_at']); ?></td>
                                    <td>
                                        <a href="../Archive/ViewEmployee.php?id=<?= $employee['employee_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DeleteEmployeeArchive" onclick="setEmployeeIdForDelete(<?= $employee['employee_id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No archived employees found.</td>
                            </tr>
                        <?php endif; ?>
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
        </div>

        <?php
        include("../../App/Employee/DeleteEmployeeArchive.php");
        include("../../Core/Includes/script.php");
        include("../../Core/Includes/script.php"); ?>
        <!-- Toasts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastElement = document.getElementById('successToast');
                if (toastElement) {
                    var toast = new bootstrap.Toast(toastElement);
                    toast.show();
                }
            });
        </script>


    </div>
</div>
</body>

</html>