<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = 'Scholar Certificate of Registration | SEDP HRMS';
$page = 'Scholar Certificate of Registration';
include('../../Core/Includes/header.php');
include('../../../Database/db.php');

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch report status data from 'scholar_narrative_reports' table
$statusData = [];
$query = "SELECT report_status, report_month FROM scholar_certificate_of_registration"; 

$result = $connection->query($query);

// Check if query execution was successful
if (!$result) {
    die("Query failed: " . $connection->error); 
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusData[strtolower($row['report_month'])] = $row['report_status'];
    }
} else {
    echo "No data found";
}

// Check if today's date is January 1st
if (date('m-d') == '01-01') {
    $resetQuery = "UPDATE scholar_certificate_of_registration SET report_status = 'Not Submitted'";
    
    if ($connection->query($resetQuery) === TRUE) {
        echo "Report statuses reset successfully.";
    } else {
        echo "Error resetting report statuses: " . $connection->error;
    }
}
?>

<div class="container-fluid" style="margin-top: 5.5rem;">
    <h1 class="text-center text-uppercase text-dark" style="color: #003c3c; font-weight: 700; letter-spacing: 1px; font-size: 2.5rem;"><?php echo $page; ?></h1>
    <div class="row mt-5">
        <?php 
        // List of months for which reports can be submitted
        $months = [
            'January', 'February', 'March', 'April', 'May', 
            'June', 'July', 'August', 'September', 'October', 
            'November', 'December'
        ];

        foreach ($months as $month) {
            $monthKey = strtolower($month);

            // Check the actual status in the database
            $status = $statusData[$monthKey] ?? 'Not Submitted'; 

            echo '
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 h-100" style="transition: transform 0.3s ease; border-radius: 12px;">
                    <div class="card-body d-flex py-2 shadow-sm" style="border-radius: 10px;">
                        <div class="col-5 d-flex justify-content-center align-items-center" style="padding-right: 10px; width: 40%;">
                            <img src="../../Public/Assets/Gif/' . $monthKey . '.gif" alt="' . $month . '" style="width: 100%; border-radius: 8px;">
                        </div>
                        <div class="col-7" style="width: 60%;">
                            <h5 class="card-title text-uppercase" style="color: #003c3c; font-weight: 600; letter-spacing: 0.5px; font-size: 1.7rem;">' . $month . '</h5>
                            <p class="card-text text-muted" style="font-size: 0.80rem;">Click below to view or submit files.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="registration.php?month=' . $monthKey . '" class="btn btn-lg" style="background-color: #003c3c; color: #fff; border-radius: 5px; padding: 7px 23px; font-size: 0.7rem; font-weight: 700;">View Forms</a>
                                <span class="badge text-white" style="width: 50%;padding: 10px 0;background-color: ' . ($status === 'Submitted' ? '#28a745' : ($status === 'Pending' ? '#ffc107' : '#dc3545')) . ';">' . $status . '</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</div>

<!-- Add the necessary JS files -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
