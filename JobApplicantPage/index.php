<?php
require_once(__DIR__ . '/../Admin Page/App/Controller/JobPostController.php');
$jobPostController = new JobPostController();

// Initialize variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_time = isset($_GET['filter_time']) ? $_GET['filter_time'] : '';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$filter_minSalary = isset($_GET['filter_minSalary']) ? $_GET['filter_minSalary'] : '';

$jobPosts = $jobPostController->handleSearchAndFilters($search, $filter_time, $filter_type, $filter_minSalary);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Landing Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../public/assets/css/employee/emInfo.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .scrollable-cards,
        #job-details-container {
            overflow-y: auto;
            /* Enable vertical scrolling */
            max-height: 80vh;
            /* Set a maximum height to enable scrolling */
            padding: 10px;
            /* Adds space around the cards */
        }

        .scrollable-cards::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-cards::-webkit-scrollbar-thumb {
            background-color: #cccccc;
            border-radius: 10px;
        }

        .card {
            max-width: 100%;
            width: auto;
            box-sizing: border-box;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid transparent;
            margin-bottom: 10px;
            padding: 20px;
            font-size: 1.1rem;

        }

        .card:hover {
            transform: scale(1.02);
            /* Slightly increase size on hover */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            /* Add shadow on hover */
        }

        .card.active {
            border: 2px solid #007bff;
            /* Border color when active */
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.5);
            /* Change shadow color when active */
        }

        .spinner-border {
            display: none;
            margin-top: 200px;
            margin-bottom: 200px;
        }

        .no-results {
            width: 100vw;
            /* Full viewport width */
            text-align: center;
            font-size: 1.5rem;
            color: #ff0000;
            display: none;
            padding: 20px;
            /* Add padding for better appearance */
            margin-top: 200px;
            margin-bottom: 200px;
            /* Center the message */
            background-color: white;
            /* Optional: light background color for visibility */
            position: relative;
            /* Positioning context for centering */
            left: 50%;
            /* Start from the center */
            transform: translateX(-50%);
            /* Move back to center */
        }

        .clear-search {
            border: 1 px solid grey;
        }

        .clear-search:hover {
            border: 1 px solid black;
        }

        .qualifications-header {
            padding: 15px;
            font-weight: bold;
            text-align: left;
            width: 250px;
            border-radius: 12px;
        }

        .qualification-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .qualification-item i {
            color: #28a745;
            font-size: 1.3rem;
            margin-right: 10px;
            margin-left: 10px;
            padding: 3px;
        }

        #filter-form {
            margin-top: 50px;
        }

        #qualification {
            background-image: url("View/a.jpg");
            background-size: cover;
            backdrop-filter: blur(10px);
            filter: brightness(90%);


        }

        #jobCards {
            background-image: url("View/2.jpg");
            background-size: cover;
            background-position: center;
            filter: brightness(100%);
            backdrop-filter: blur(20px);
            color: #E0E0E0;
            /* Adjust percentage as needed */
        }


        #card {
            background: rgba(0, 0, 0, 0.3);
            /* background: rgba(255, 255, 255, 0.2);*/
            border-radius: 10px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(4.4px);
            -webkit-backdrop-filter: blur(4.4px);
            border: 1px solid rgba(255, 255, 255, 0.65);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-gradient bg-opacity-75" style="background-color: #003c3c;">
        <div class="container d-flex mb-1">
            <a class="navbar-brand text-white align-text-center fw-bolder fs-5" href="../index.php">
                SEDP Simbag Sa Pag-Asenso Inc.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-1 d-flex justify-content-between mx-5 mt-3">
        <div class="container d-flex">

            <ol class="breadcrumb display-flex">
                <li class="breadcrumb-item"><a class="text-decoration-none text-white" style="background: #003c3c; border-radius: 5px; padding: 10px 20px;" href="../index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Job Posts</li>
            </ol>
            <a href="./View/JobApplicantStatus.php" class="text-decoration-none ms-auto text-white m-0" style="background: #003c3c; border-radius: 5px; padding: 10px 20px;">Application Status</a>
        </div>
    </nav>
    <hr class="mx-5 my-0">
    <!-- Image -->
    <div class="container">
        <div class="text-center pt-2">
            <img src="../Assets/Images/job-poster.png" alt="Hiring Image" class="img-fluid rounded shadow my-4" style="height: auto; width: 800px;">
            <p class="lead text-muted p-2 m-2 my-3 mx-5" style="font-size: 18px;">
                At <span class="fs-5 fw-bold">SEDP Simbag Sa Pag-Asenso Inc</span>, we foster talent, embrace innovation, and drive success.
                Join us today and be part <br>
                of a company that values and invests in your future.
            </p>
        </div>
    </div>


    <!-- qualification -->
    <div class="container-fluid bg-light my-3 shadow rounded mt-5" id="qualification">
        <div class="row">
            <div class="col-md-12 m-4">
                <div class="qualifications-header">
                    <h4 class="fw-bold">QUALIFICATIONS:</h4>
                </div>
                <div class="row qualifications-container">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5 fw-bold">Undergraduate/graduate of any business-related courses</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5 fw-bold">Physically fit to do field work</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5 fw-bold">Willing to be assigned in any SEDP area of Operations</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5">Work experience as an Account Officer is an advantage</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5">Computer literate</span>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5">Good communication skills</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5">Driving skills with driver's license is an advantage</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5">Has positive work attitude, adaptable, and able to work well under pressure</span>
                        </div>
                        <div class="qualification-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span class="text-dark fs-5">Preferably from Camarines Sur, Sorsogon, and Masbate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Search Bar and Filter Dropdown -->
    <form id="filter-form" method="GET" action="" class="my-5">
        <div class="row justify-content-center mb-2">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search here!" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="button" class="btn text-black clear-search" onclick="resetFilter()">
                        <i class="bi bi-x"></i>
                    </button>
                    <button class="btn text-white" style="background-color: #003c3c;" type="submit">Search</button>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-2">
                <select class="form-select" name="filter_time" style="width: 100%; border-radius: 35px;">
                    <option value="" <?= empty($filter_time) ? 'selected' : '' ?>>All time</option>
                    <option value="3d" <?= $filter_time == '3d' ? 'selected' : '' ?>>Last 3 days</option>
                    <option value="7d" <?= $filter_time == '7d' ? 'selected' : '' ?>>Last 7 days</option>
                    <option value="14d" <?= $filter_time == '14d' ? 'selected' : '' ?>>Last 14 days</option>
                    <option value="30d" <?= $filter_time == '30d' ? 'selected' : '' ?>>Last 30 days</option>
                </select>
            </div>
            <div class="col-md-2 mx-1">
                <select class="form-select" name="filter_type" style="width: 100%; border-radius: 35px;">
                    <option value="" <?= empty($filter_type) ? 'selected' : '' ?>>All work types</option>
                    <option value="Part-time" <?= $filter_type == 'Part-time' ? 'selected' : '' ?>>Part time</option>
                    <option value="Full-time" <?= $filter_type == 'Full-time' ? 'selected' : '' ?>>Full time</option>
                    <option value="Contract" <?= $filter_type == 'Contract' ? 'selected' : '' ?>>Contract</option>
                    <option value="Intern" <?= $filter_type == 'Intern' ? 'selected' : '' ?>>Intern</option>
                    <option value="Freelance" <?= $filter_type == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="filter_minSalary" style="width: 100%; border-radius: 35px;">
                    <optgroup label="Salary (PHP)" style="color: grey; margin-bottom: 30px;">
                        <option value="" <?= empty($filter_minSalary) ? 'selected' : '' ?>>Paying ₱0</option>
                        <option value="10000" <?= $filter_minSalary == '10000' ? 'selected' : '' ?>>10K</option>
                        <option value="20000" <?= $filter_minSalary == '20000' ? 'selected' : '' ?>>20K</option>
                        <option value="30000" <?= $filter_minSalary == '30000' ? 'selected' : '' ?>>30K</option>
                        <option value="40000" <?= $filter_minSalary == '40000' ? 'selected' : '' ?>>40K</option>
                        <option value="50000" <?= $filter_minSalary == '50000' ? 'selected' : '' ?>>50K</option>
                        <option value="60000" <?= $filter_minSalary == '60000' ? 'selected' : '' ?>>60K</option>
                        <option value="70000" <?= $filter_minSalary == '70000' ? 'selected' : '' ?>>70K</option>
                        <option value="80000" <?= $filter_minSalary == '80000' ? 'selected' : '' ?>>80K</option>
                        <option value="90000" <?= $filter_minSalary == '90000' ? 'selected' : '' ?>>90K</option>
                        <option value="100000" <?= $filter_minSalary == '100000' ? 'selected' : '' ?>>100K</option>
                        <option value="120000" <?= $filter_minSalary == '120000' ? 'selected' : '' ?>>1200K</option>
                        <option value="150000" <?= $filter_minSalary == '150000' ? 'selected' : '' ?>>150K</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Spinner -->
    <div class="row justify-content-center">
        <div class="spinner-border text-primary" id="loading-spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

    </div>

    <!-- No results message -->
    <div class="container no-results" id="no-results-message">
        <div class="container">
            <img src="view/not found.png" alt="img" style="height: 300px; width:270px;">
            <h1>No Job Available</h1>
        </div>
    </div>

    <div class="row" id="jobCards">
        <!-- First Column: Cards -->
        <div class="col-md-5 mt-2 my-2" id="job-cards-container">
            <div class="scrollable-cards">
                <?php foreach ($jobPosts as $row): ?>
                    <div class='card mb-3 shadow' id="card" onclick="showJobDetails(<?= $row['jobPostId'] ?>, this)">
                        <div class='card-body text-white'>
                            <div style="flex: 1;">
                                <div class="text-end">
                                    <p class='card-text' style="font-size: 14px; margin-bottom: 0%; color:fff;"><span class="time-ago" data-time="<?= ($row['datePosted']) ?>"></span></p>
                                </div>
                                <h4 class="mb-1 ms-3 fw-bold"><?= ($row['jobTitle']) ?></h4>
                                <div class="row text-start ms-1 mb-4">
                                    <div class="col-auto pe-1">
                                        <p style="font-size: 12px; margin-bottom: 0;"><i class='bi bi-people-fill'></i> <?= $row['applicantSize'] ?></p>
                                    </div>
                                    <div class="col-auto pe-1">
                                        <p style="font-size: 12px; margin-bottom: 0;"><i class='bi bi-clock'></i> <?= ($row['employmentType']) ?></p>
                                    </div>
                                </div>
                                <div class="row ms-1">
                                    <p class='card-text mb-1' style="font-size: 15px;"> PHP <?= ($row['minimumSalary']) ?> - <?= ($row['maximumSalary']) ?></p>
                                    <p class='card-text mb-1' style="font-size: 15px;"></i> <?= ($row['country']) ?>, <?= ($row['region']) ?>, <?= ($row['province']) ?>, <?= ($row['city']) ?></p>
                                    <p class='card-text mb-1' style="font-size: 15px;">
                                        <?php
                                        // Split the benefits string into an array
                                        $benefitsArray = explode(', ', $row['benefits']);

                                        // Loop through each benefit and create a badge
                                        foreach ($benefitsArray as $benefit):
                                        ?>
                                            <span class="badge bg-success me-1"><?= htmlspecialchars($benefit) ?></span>
                                        <?php endforeach; ?>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <!-- Second Column: Full Job Details -->
        <div class="col-md-7 mt-0" style="display: none;" id="job-details-container"> <!-- Initially hidden -->
            <div class="border rounded p-4 shadow">
                <h3>Select a job to view details</h3>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                function timeAgo(date) {
                    const seconds = Math.floor((new Date() - new Date(date)) / 1000);
                    const intervals = [{
                            label: 'year',
                            seconds: 31536000
                        },
                        {
                            label: 'month',
                            seconds: 2592000
                        },
                        {
                            label: 'day',
                            seconds: 86400
                        },
                        {
                            label: 'hour',
                            seconds: 3600
                        },
                        {
                            label: 'minute',
                            seconds: 60
                        },
                        {
                            label: 'second',
                            seconds: 1
                        }
                    ];

                    for (const interval of intervals) {
                        const count = Math.floor(seconds / interval.seconds);
                        if (count > 0) {
                            return `${count} ${interval.label}${count > 1 ? 's' : ''} ago`;
                        }
                    }
                    return 'just now';
                }

                $('.time-ago').each(function() {
                    const datePosted = new Date($(this).data('time'));
                    $(this).text(timeAgo(datePosted));
                });

                $('#filter-form').on('submit change', function(e) {
                    e.preventDefault();
                    $('#loading-spinner').show();
                    $('#job-details-container').hide();
                    $('#job-cards-container').html('');
                    $('#no-results-message').hide();

                    $.ajax({
                        url: '',
                        type: 'GET',
                        data: $('#filter-form').serialize(),
                        success: function(response) {
                            var newjobPosts = $(response).find('#job-cards-container .scrollable-cards').html();
                            var noJobsFound = newjobPosts.trim() === '';

                            setTimeout(function() {
                                $('#loading-spinner').hide();

                                if (noJobsFound) {
                                    $('#job-cards-container').html('');
                                    $('#no-results-message').css('display', 'block');
                                    $('#job-details-container').hide();
                                } else {
                                    $('#job-cards-container').html('<div class="scrollable-cards"></div>');
                                    $('.scrollable-cards').html(newjobPosts).css({
                                        'max-height': '80vh',
                                        'overflow-y': 'auto'
                                    });

                                    $('#no-results-message').hide();
                                    $('#job-details-container').hide();

                                    $('.time-ago').each(function() {
                                        const datePosted = new Date($(this).data('time'));
                                        $(this).text(timeAgo(datePosted));
                                    });
                                }
                            }, 1500);
                        },
                        error: function() {
                            $('#loading-spinner').hide();
                            console.log('Failed to fetch job posts');
                        }
                    });
                });

                function decodeHtml(html) {
                    const txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                }

                window.showJobDetails = function(jobPostId, cardElement) {
                    const cards = document.querySelectorAll('.scrollable-cards .card');
                    cards.forEach(card => card.classList.remove('active'));
                    cardElement.classList.add('active');

                    const jobPost = <?= json_encode($jobPosts) ?>.find(post => post.jobPostId == jobPostId);

                    if (jobPost) {
                        const postedTimeAgo = timeAgo(jobPost.datePosted);

                        const jobDescription = decodeHtml(jobPost.jobDescription);
                        const jobQualification = decodeHtml(jobPost.jobQualification);
                        const jobKeyResponsibilities = decodeHtml(jobPost.jobKeyResponsibilities);

                        const applicantSize = jobPost.applicantSize || 'N/A'; // Example default value
                        const employmentType = jobPost.employmentType || 'N/A'; // Example default value

                        // Split the benefits string into an array
                        const benefitsArray = jobPost.benefits.split(', '); // Assuming benefits are stored as a comma-separated string

                        // Create HTML for benefits
                        const benefitsHtml = benefitsArray.map(benefit => `<li>${benefit.trim()}</li>`).join('');

                        const jobDetailsHtml = `
            <div class="card mt-2 mx-2 text-white" style="flex: 1; padding:20px;" id="card">
                <h2 class="mb-2 fw-bold">${jobPost.jobTitle}</h2>
                <div class="row text-start ms-1">
                    <div class="col-auto pe-1">
                        <p style="font-size: 13px; margin-bottom: 0;"><i class='bi bi-people-fill'></i> ${applicantSize}</p>
                    </div>
                    <div class="col-auto pe-1">
                        <p style="font-size: 13px; margin-bottom: 0;"><i class='bi bi-clock'></i> ${employmentType}</p>
                    </div>
                </div>
                <br>
                <div class="row ms-1 mb-2">
                    <p class='card-text mb-1' style="font-size: 16px;">PHP ${jobPost.minimumSalary} - ${jobPost.maximumSalary}</p>
                    <p class='card-text mb-1' style="font-size: 16px;">${jobPost.country}, ${jobPost.region}, ${jobPost.province}, ${jobPost.city}</p>
                </div>
                <div class="row mb-4 ms-1">
                    <p class='card-text' style="font-size: 16px;">Posted : ${postedTimeAgo}</p>
                </div>
                <div>
                <button type='button' class='btn btn-md text-white mb-3 px-5 py-2' style='background-color: #003c3c;'
                    onclick="window.location.href='./View/JobApplication.php?job_id=${jobPostId}'">
                    Apply
                </button>
                </div>

                <p class="my-2 "><strong>Benefits:</strong></p>
                <ul class='card-text mb-2' style="font-size: 16px;">
                    ${benefitsHtml}
                </ul>87
                
                <p><strong class="">Description: </strong>${jobDescription}</p>
                <p><strong>Qualification:</strong> ${jobQualification}</p>
                <p><strong>Key Responsibilities:</strong> ${jobKeyResponsibilities}</p>
            </div>
        `;

                        $('#job-details-container').html(jobDetailsHtml).show();
                    } else {
                        console.log('Job post not found');
                    }
                };

            });
        </script>

        <script>
            function resetFilter() {
                // Reload the page without the 'filter' and 'search' query parameters
                const url = new URL(window.location.href);
                url.searchParams.delete('filter_type');
                url.searchParams.delete('filter_minSalary');
                url.searchParams.delete('filter_time');
                url.searchParams.delete('search');
                window.location.href = url.href; // Navigate to the reset URL
            }
        </script>
    </div>

</body>
<!--Footer-->
<div class="container-fluid bg-dark text-center text-light mt-2" style="padding: 10px 0;">
    <div class="footer-content" style="min-height: 100px; line-height: 30px;">
        <p class="mb-2">&copy; 2024 Your Organization. All Rights Reserved.</p>

        <ul class="list-inline mb-2">
            <li class="list-inline-item"><a href="https://sedp.ph/about-us/" class="text-light">About Us</a></li>
            <li class="list-inline-item"><a href="https://sedp.ph/services/" class="text-light">Services</a></li>
            <li class="list-inline-item"><a href="/privacy-policy" class="text-light">Privacy Policy</a></li>
            <li class="list-inline-item"><a href="/terms-of-service" class="text-light">Terms of Service</a></li>
        </ul>

        <p class="mb-2">Contact Us: <a href="mailto:simbag_sedp@yahoo.com" class="text-light">simbag_sedp@yahoo.com</a></p>

        <div class="social-media-links mb-2">
            <a href="https://web.facebook.com/sedp.ph" target="_blank" class="mx-2 text-light"><i class="fa fa-facebook"></i></a>
            <a href="https://twitter.com/yourprofile" class="mx-2 text-light"><i class="fa fa-twitter"></i></a>
            <a href="https://linkedin.com/in/yourprofile" class="mx-2 text-light"><i class="fa fa-linkedin"></i></a>
        </div>
    </div>
</div>

</html>