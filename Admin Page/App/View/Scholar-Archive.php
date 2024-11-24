<?php
$title = 'Employee | SEDP HRMS';
$page = 'Employee';

include("../../../Database/db.php");
include('../../Core/Includes/header.php');

// Handle department filters
$selectedDate = $_GET['archived_at'] ?? '';  // Capture the date sorting selection

// Fetch archived employees from the database using mysqli
$query = "SELECT * FROM recipient_archive ORDER BY archived_at DESC";
$result = $connection->query($query);

// Check if the query was successful and fetch the data
$archivedRecipient = [];
if ($result && $result->num_rows > 0) {
    while ($recipient = $result->fetch_assoc()) {
        $archivedRecipient[] = $recipient;
    }
}

?>
<div class="wrapper">
    <!-- Sidebar -->
    <?php include("../../Core/Includes/sidebar.php"); ?>

    <div class="main p-3">
        <?php include('../../Core/Includes/navBar.php'); ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4">
            <?php include('../../Core/Includes/alertMessages.php'); ?>

            <h3 class="fw-bold fs-4">List Of Archived Employees</h3>
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
                <form id="searchForm" action="../Employee/SearchEmployee.php" method="GET" onsubmit="return validateSearch()">
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search here!">
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
                            <th>ARCHIVE_DATE</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($archivedRecipient)): ?>
                            <?php foreach ($archivedRecipient as $recipient): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($recipient['name']); ?></td>
                                    <td><?php echo htmlspecialchars($recipient['email']); ?></td>
                                    <td><?php echo htmlspecialchars($recipient['contact']); ?></td>
                                    <td><?php echo htmlspecialchars($recipient['archived_at']); ?></td>
                                    <td>
                                        <a href="../Archive/ViewRecipient.php?id=<?php echo $recipient['re_archive_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No archived Schoolasr found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include("../../Core/Includes/script.php"); ?>
    </div>
</div>
</body>

</html>