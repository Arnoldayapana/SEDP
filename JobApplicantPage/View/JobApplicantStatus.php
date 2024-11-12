<?php
require_once(__DIR__ . '/../../Admin Page/App/Controller/JobApplicantController.php');
$applicantController = new JobApplicantController();
$status = ''; // Initialize empty status

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['uniqueId'])) {
    $status = $applicantController->ViewApplicantStatus(); // Get the status directly
    $applicantDatetime = $applicantController->ViewApplicantInterviewDatebyUniqId($_GET['uniqueId']);
    if ($applicantDatetime) {
        $interviewDatetime = $applicantDatetime['interviewDatetime'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: #003c3c;
        }

        .navbar-brand {
            font-size: 1.25rem;
        }

        .breadcrumb-item a {
            text-decoration: none;

        }

        .breadcrumb-item.active {
            color: #003c3c;
        }

        .status-container {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }

        .status-label {
            font-size: 1rem;
            font-weight: 600;
            color: #003c3c;
        }

        .status-text {
            color: #00695c;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .status-date {
            font-size: 0.9rem;
            color: #777;
        }

        .btn-custom {
            width: 48%;
            margin-right: 2%;
            height: calc(2.25rem + 2px);
            /* Make the height same as input field */
            padding: 0.375rem 0.75rem;
            /* Adjust padding to fit the text better */
            text-align: center;
            /* Ensure text is centered */
            font-size: 1rem;
            /* Adjust the font size if needed */
            display: inline-flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
            /* Prevent text from overflowing */
        }

        .btn-custom:last-child {
            margin-right: 0;
        }

        .form-control {
            height: calc(2.25rem + 2px);
            /* Ensure the text field matches button height */
        }
    </style>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex mb-1">
            <a class="navbar-brand text-white fw-bold" href="../index.php">
                SEDP Simbag Sa Pag-Asenso Inc.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Breadcrumb and Status Section -->
    <section class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../">Home</a></li>
                <li class="breadcrumb-item"><a href="../../JobApplicantPage/">Job Posts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Application Status</li>
            </ol>
        </nav>

        <div class="status-container mt-3">
            <h4 class="mb-4 text-center text-primary">Check Your Application Status</h4>
            <form action="" method="GET">
                <div class="row mb-3">
                    <label for="uniqueId" class="col-sm-4 col-form-label text-end">Enter Your Applicant ID:</label>
                    <div class="col-sm-5">
                        <input type="text" id="uniqueId" name="uniqueId" class="form-control" placeholder="e.g., applicant_1234" required
                            value="<?= isset($_GET['uniqueId']) ? htmlspecialchars($_GET['uniqueId']) : '' ?>">
                    </div>
                    <div class="col-sm-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary btn-custom" onclick="clearField()">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                        <button type="submit" name="submit" class="btn btn-primary btn-custom">
                            <i class="bi bi-search"></i> Show Status
                        </button>
                    </div>
                </div>
            </form>

            <div class="row mt-4">
                <label class="col-sm-4 col-form-label text-end status-label">Your Application Status:</label>
                <div class="col-sm-8" id="status-text">
                    <span class="status-text">
                        <?php echo htmlspecialchars($status); ?>
                    </span>
                    <?php if ($status === "Schedule Interview" && isset($interviewDatetime)): ?>
                        <br>
                        <small class="status-date">Scheduled for: <?= htmlspecialchars($interviewDatetime); ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        function clearField() {
            document.getElementById('uniqueId').value = '';
            document.getElementById('status-text').textContent = '';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>