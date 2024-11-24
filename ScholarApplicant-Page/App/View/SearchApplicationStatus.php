<?php
include("../../../Database/db.php");

// Initialize variables to hold search input and result
$email = "";
$statusMessage = "";
$applicantData = null;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email entered by the user
    $email = $_POST['email'];

    // Validate the email input
    if (!empty($email)) {
        // Query the database to find the applicant with the given email
        $sql = "SELECT * FROM scholar_applicant WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $email); // 's' means the parameter is a string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a result was found
        if ($result->num_rows > 0) {
            // Fetch the applicant data
            $applicantData = $result->fetch_assoc();
            $statusMessage = "Application found!";
        } else {
            // If no applicant found
            $statusMessage = "No application found for this email!";
        }
    } else {
        $statusMessage = "Please enter an email to search.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Application Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body style="font-family:sans-serif">
    <!--Nav-->
    <nav class="navbar navbar-expand-lg navbar-light bg-gradient bg-opacity-75" style="background-color: #003c3c;">
        <div class="container d-flex mb-1">
            <a class="navbar-brand text-white align-text-center fw-bolder fs-5" href="../../../index.php">
                SEDP Simbag Sa Pag-Asenso Inc.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <!--Nav ends-->
    <section id="criteria" class="my-2 m-3">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-flex">
                <ol class="breadcrumb" style="text-decoration:none;">
                    <li class="breadcrumb-item"><a href="../../../index.php">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Scholarship Landing Pages</li>
                    <li class="breadcrumb-item active" aria-current="page">Scholarship Status</li>
                </ol>
            </nav>
        </div>
    </section>

    <!--Main-->
    <section>
        <div class="container">
            <div class="card w-75 mb-3">
                <div class="card-body ms-5">
                    <h3 class="card-title">Search Application Status</h3>
                    <div class="col-auto mt-3">
                        <p>Enter the provided email account that was used to apply for the scholarship.</p>
                    </div>
                    <form class="row g-3" action="" method="POST">
                        <div class="col-md-6">
                            <label for="email" class="visually-hidden">email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email here!" value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-search"></i></button>
                        </div>
                        <div class="col-auto">
                            <!-- Reset button with type reset -->
                            <button type="reset" class="btn btn-secondary mb-3"><i class="bi bi-x-circle"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Show the search status message -->
            <div>
                <p class="fw-bold fs-6"><strong>Your Application Status:</strong></p>
            </div>

            <?php if ($applicantData): ?>
                <?php
                // Determine the badge class based on the application status
                $badgeClass = '';
                $statusText = $applicantData['application_status'];

                if ($statusText === 'On-Interview') {
                    $badgeClass = 'bg-info text-dark';
                } elseif ($statusText === 'Pending') {
                    $badgeClass = 'bg-secondary';
                } elseif ($statusText === 'Accepted') {
                    $badgeClass = 'bg-primary';
                } elseif ($statusText === 'Rejected') {
                    $badgeClass = 'bg-danger';
                } else {
                    $badgeClass = 'bg-warning text-dark'; // Fallback for other statuses
                }
                ?>

                <div class="card w-75 mb-2" id="application_result">
                    <div class="card-body ms-5">
                        <p>Status: <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span></p>
                        <p>Applied Date: <span><?php echo $applicantData['applied_date']; ?></span></p>
                    </div>
                </div>
            <?php elseif (empty($applicantData)): ?>
                <div class="card w-75 mb-2" id="no_result">
                    <div class="card-body ms-5">
                        <p class="text-center">No Application Found!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>