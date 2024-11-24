<?php
require_once(__DIR__ . '/../Controller/JobPostController.php');
require_once(__DIR__ . '/../Controller/DepartmentController.php');
require_once(__DIR__ . '/../Controller/JobController.php');
require_once(__DIR__ . '/../Controller/JobApplicantController.php');
require_once(__DIR__ . '/../Controller/InterviewJobApplicantController.php');

$jobPostController = new JobPostController();
$jobApplicantController = new JobApplicantController();
$interviewJobApplicantController = new InterviewJobApplicantController();

$departmentController = new DepartmentController();
$jobController = new JobController();

// Get filter and search parameters from the request
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get filtered job offers
$jobPosts = $jobPostController->getFilteredjobPosts($filter, $search);
$jobApplicants =  $jobApplicantController->getFilteredJobApplicants($filter, $search);
$interviewJobApplicants =  $interviewJobApplicantController->getFilteredJobApplicants($filter, $search);

$jobSelects = $jobController->getAllJobs();

//$branchSelects = $branchController->getAllBranch();
$departmentSelects = $departmentController->getAllDepartmentsWithBranchLocations();

$title = "JobApplicants1 | SEDP HRMS";
$page = "JobApplicants1";
include('../../Core/Includes/header.php');


?>
<div class="wrapper">
    <!--sidebar-->
    <?php include_once('../../core/includes/sidebar.php'); ?>

    <!--add employee-->
    <main class="main">
        <!--header-->
        <?php include '../../core/includes/navBar.php'; ?>

        <div class="container-fluid shadow p-4 mb-5 bg-body-tertiary rounded-1 my-4">
            <!-- Alert Message for error and successMessage -->
            <?php include('../../Core/Includes/alertMessages.php'); ?>

            <div class="row">
                <!-- Sidebar for Job Position List -->
                <div class="p-2 bg-white shadow-sm rounded-2 sidebar-modern" style="width: 253px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold">Job Positions</h5>
                        <span
                            class="all-applicants-icon"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="View All Applicants"
                            onclick="filterApplicants(this)"
                            data-id="all"
                            style="cursor: pointer;">
                            <i class="fa fa-users" style="color: #336868 ;"></i>
                        </span>
                    </div>

                    <!-- "All Button -->
                    <ul class="list-group list-group-flush job-list">
                        <!-- Job List -->
                        <?php foreach ($jobPosts as $jobPost): ?>
                            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                data-id="<?= htmlspecialchars($jobPost['jobPostId']); ?>"
                                onclick="filterApplicants(this)">
                                <span>
                                    <?= htmlspecialchars($jobPost['jobTitle']); ?> (<?= htmlspecialchars($jobPost['branchName']); ?>)
                                </span>
                                <p class="mb-0"><?= htmlspecialchars($jobPost['applicantCount']); ?></p>
                            </li>
                    </ul>
                <?php endforeach; ?>
                </div>

                <!-- Main Content Area -->
                <div class="col-md-9">
                    <div class="header mb-3">
                        <ul id="menu-list" class="menu-list-column">
                            <li onclick="showContent('applicants')" class="list-item active">Applicants</li>
                            <li onclick="showContent('interviews')" class="list-item">Interview</li>
                            <li onclick="showContent('hire')" class="list-item">Hire</li>
                            <li onclick="showContent('archive')" class="list-item archive">Archive</li>
                        </ul>
                    </div>
                    <!-- Search and Filter -->
                    <div class="search-bar d-flex align-items-center mb-3">
                        <input
                            type="text"
                            id="search"
                            placeholder="Search Applicants"
                            class="form-control form-control-sm me-2"
                            oninput="filterApplicants()">
                        <button
                            class="btn btn-outline-secondary btn-sm clear-btn me-2"
                            title="Clear Search and Filters"
                            onclick="clearFilters()">
                            <i class="bi bi-x"></i>
                        </button>
                        <select
                            id="filter"
                            class="form-select form-select-sm me-2"
                            onchange="filterApplicants()"
                            style="width: auto;">
                            <option value="">All</option>
                            <option value="Pending">Pending</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                        <div class="dropdown">
                            <button
                                class="btn btn-outline-primary btn-sm dropdown-toggle"
                                type="button"
                                id="advancedFilterButton"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-funnel"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="advancedFilterButton">
                                <li>
                                    <button class="dropdown-item" onclick="filterBy('name')">
                                        Filter by Name
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" onclick="filterBy('date')">
                                        Filter by Date Applied
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Content for Applicants -->
                    <div id="applicants" class="content-div active">
                        <p class="text-muted mb-0 mt-4" style="font-size: 0.75rem">New Applicants <span>#</span></p>

                        <!-- Customized Table -->
                        <div class="table-responsive-md">
                            <table class="table align-middle">
                                <!-- 
                                <thead class="table-light border-bottom border-secondary">
                                    <tr class="text-muted text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                                        <th class="py-2 px-3">Name</th>
                                        <th class="py-2 px-3">Position</th>
                                        <th class="py-2 px-3">Date Applied</th>
                                        <th class="py-2 px-3">Status</th>
                                    </tr>
                                </thead>
                                -->

                                <tbody style="font-size: 0.9rem; color: #495057;">
                                    <?php foreach ($jobApplicants as $row): ?>
                                        <tr
                                            class="table-row <?= $row['isViewed'] ? '' : 'font-weight-bold'; ?>"
                                            data-id="<?= htmlspecialchars($row['applicantId']) ?>"
                                            onclick="viewApplicant(this)">
                                            <td class="px-3"><?= htmlspecialchars($row['applicantName']) ?></td>
                                            <td class="px-3"><?= htmlspecialchars($row['jobTitle']) ?></td>
                                            <td class="px-3"><?= htmlspecialchars($row['appliedDate']) ?></td>
                                            <td class="px-3">
                                                <?php
                                                $badgeColors = [
                                                    'Schedule Interview' => '#b3e5fc',
                                                    'Pending' => '#ffe082',
                                                    'Accepted' => '#64b5f6',
                                                    'Rejected' => '#ef9a9a',
                                                    'Default' => '#b0bec5',
                                                ];
                                                $color = $badgeColors[$row['status']] ?? $badgeColors['Default'];
                                                echo "<span class='badge text-light' style='background-color: {$color};'>{$row['status']}</span>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Content for Interviews -->
                    <div id="interviews" class="content-div">
                        <p class="text-muted mb-0 mt-4" style="font-size: 0.75rem"> On interview Applicants <span>#</span></p>

                        <!-- Customized Table -->
                        <div class="table-responsive-md">
                            <table class="table align-middle">
                                <tbody style="font-size: 0.9rem; color: #495057;">
                                    <?php foreach ($interviewJobApplicants as $row): ?>
                                        <tr
                                            class="table-row <?= $row['isViewed'] ? '' : 'font-weight-bold'; ?>"
                                            data-interview-id="<?= htmlspecialchars($row['interviewApplicantId']) ?>"
                                            onclick="viewInterviewApplicant(this)">
                                            <td class="px-3"><?= htmlspecialchars($row['applicantName']) ?></td>
                                            <td class="px-3"><?= htmlspecialchars($row['jobTitle']) ?></td>
                                            <td class="px-3"><?= htmlspecialchars($row['appliedDate']) ?></td>
                                            <td class="px-3">
                                                <?php
                                                $badgeColors = [
                                                    'Schedule Interview' => '#b3e5fc',
                                                    'Pending' => '#ffe082',
                                                    'Accepted' => '#64b5f6',
                                                    'Rejected' => '#ef9a9a',
                                                    'Default' => '#b0bec5',
                                                ];
                                                $color = $badgeColors[$row['status']] ?? $badgeColors['Default'];
                                                echo "<span class='badge text-light' style='background-color: {$color};'>{$row['status']}</span>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Content for Hire -->
                    <div id="hire" class="content-div">
                        <p>Hire content will go here.</p>
                    </div>
                    <!-- Content for Archive -->
                    <div id="archive" class="content-div">
                        <p>Archive content will go here.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Off-canvas for applicant -->
<div class="offcanvas offcanvas-end" id="applicantDetailsOffcanvas" tabindex="-1" aria-labelledby="applicantDetailsLabel" style="width: 750px;">
    <!-- Off-canvas Header -->
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <h5 class="offcanvas-title" id="applicantDetailsLabel">Applicant Details</h5>
        <div class="d-flex align-items-center">
            <!-- Send SMS Icon -->
            <button class="btn btn-outline-secondary me-2" title="Send SMS" data-bs-toggle="modal" data-bs-target="#SMSModal">
                <i class="bi bi-chat-dots"></i>
            </button>

            <!-- Stages Dropdown -->
            <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Stages
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#" style="pointer-events: none; color: gray;">Applicant Section</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="transferApplicantToInterview(currentApplicantId)">On Interview Section</a>
                    </li>
                    <li><a class="dropdown-item" href="#">Hire Applicant Section</a></li>
                    <li><a class="dropdown-item" href="#" title="Archive" data-bs-toggle="modal" data-bs-target="#ArchiveModal">Archive Applicant</a></li>
                </ul>
            </div>

            <!-- Archive Icon -->
            <button class="btn btn-outline-secondary me-2" title="Archive" data-bs-toggle="modal" data-bs-target="#ArchiveModal">
                <i class="bi bi-archive"></i>
            </button>
            <!-- Close Button -->
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <!-- Off-canvas Body -->
    <div class="offcanvas-body bg-light">
        <div class="container-fluid position-relative">
            <div class="row">
                <!-- Applicant Details Container -->
                <div class="col-12 col-md-7">
                    <div class="d-flex flex-column h-100">
                        <!-- Photo Container-->
                        <div class="d-flex flex-row mb-4 h-40 position-relative">
                            <!-- Picture -->
                            <div class="picture-container rounded-circle border border-primary shadow-lg me-3" style="width: 100px; height: 100px; overflow: hidden;">
                                <img class="intervewApplicantPhoto img-fluid w-100 h-100" id="intervewApplicantPhoto" src="../../../Assets/Images/resizeuserimg.png" alt="Applicant Photo" style="object-fit: cover;">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <p class="fw-bold mb-0 text-dark applicantName"></p>
                                <span id="applicantStatusBadge" class="badge bg-success applicantStatusBadge"></span>
                            </div>
                            <!-- Time Ago -->
                            <p class="text-muted small timeAgo position-relative" style="top: 0px; right: 0px;">Just now</p>
                        </div>

                        <!-- Information Container  -->
                        <div class="pt-3">
                            <p class="mb-2"><strong class="text-dark">Unique ID:</strong> <span class="uniqueId"></span></p>
                            <p class="mb-2"><strong class="text-dark">Email:</strong> <span class="email"></span></p>
                            <p class="mb-2"><strong class="text-dark">Contact Number:</strong> <span class="contactNumber"></span></p>
                            <p class="mb-2"><strong class="text-dark">Title:</strong> <span class="jobTitle"></span></p>
                            <p class="mb-2"><strong class="text-dark">Department:</strong> <span class="departmentName"></span></p>
                            <p class="mb-2"><strong class="text-dark">Branch:</strong> <span class="branchName"></span></p>
                            <p class="mb-2"><strong class="text-dark">Location:</strong> <span class="location"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Applicant Files Container-->
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <!-- Application Form Container -->
                        <div id="formFileContainer" class="file-container mb-3" style="display: none;">
                            <h6 class="text-muted">Application Form</h6>
                            <iframe
                                id="formFileIframe"
                                class="w-100 rounded shadow-sm"
                                style="border: 1px solid #ddd; height: 400px; overflow: auto;"
                                src=""
                                title="Application Form">
                                Your browser does not support iframes.
                            </iframe>
                        </div>
                        <br>
                        <!-- Cover Letter Container -->
                        <div id="coverLetterContainer" class="file-container" style="display: none;">
                            <h6 class="text-muted">Cover Letter</h6>
                            <iframe
                                id="coverLetterIframe"
                                class="w-100 rounded shadow-sm"
                                style="border: 1px solid #ddd; height: 400px; overflow: auto;"
                                src=""
                                title="Cover Letter">
                                Your browser does not support iframes.
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Off-canvas for interview applicant -->
<div class="offcanvas offcanvas-end" id="applicantDetailsInterviewOffcanvas" tabindex="-1" aria-labelledby="applicantDetailsInterviewLabel" style="width: 750px;">
    <!-- Off-canvas Header -->
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <h5 class="offcanvas-title" id="applicantDetailsInterviewLabel">Applicant Details</h5>
        <div class="d-flex align-items-center">
            <!-- Send SMS Icon -->
            <button class="btn btn-outline-secondary me-2" title="Send SMS" data-bs-toggle="modal" data-bs-target="#SMSModal">
                <i class="bi bi-chat-dots"></i>
            </button>

            <!-- Stages Dropdown -->
            <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Stages
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#" style="pointer-events: none; color: gray;">Applicant Section</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" style="pointer-events: none; color: gray;">On Interview Section</a>
                    </li>
                    <li><a class="dropdown-item" href="#">Hire Applicant Section</a></li>
                    <li><a class="dropdown-item" href="#" title="Archive" data-bs-toggle="modal" data-bs-target="#ArchiveModal">Archive Applicant</a></li>
                    <hr class="mb-1 mt-1">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#scheduleInterview">Schedule an Interview</a></li>
                </ul>
            </div>

            <!-- Archive Icon -->
            <button class="btn btn-outline-secondary me-2" title="Archive" data-bs-toggle="modal" data-bs-target="#ArchiveModal">
                <i class="bi bi-archive"></i>
            </button>
            <!-- Close Button -->
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <!-- Off-canvas Body -->
    <div class="offcanvas-body bg-light">
        <div class="container-fluid position-relative">
            <div class="row">
                <!-- Applicant Details Container  -->
                <div class="col-12 col-md-7">
                    <div class="d-flex flex-column h-100">
                        <!-- Photo Container -->
                        <div class="d-flex flex-row mb-4 h-40 position-relative">
                            <!-- Picture -->
                            <div class="picture-container rounded-circle border border-primary shadow-lg me-3" style="width: 100px; height: 100px; overflow: hidden;">
                                <img class="intervewApplicantPhoto img-fluid w-100 h-100" id="intervewApplicantPhoto" src="../../../Assets/Images/resizeuserimg.png" alt="Applicant Photo" style="object-fit: cover;">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <p class="fw-bold mb-0 text-dark interviewApplicantName"></p>
                                <span id="interviewapplicantStatusBadge" class="badge bg-success interviewapplicantStatusBadge"></span>
                            </div>
                            <!-- Time Ago -->
                            <p class="text-muted small timeAgo position-relative" style="top: 0px; right: 0px;">Just now</p>
                        </div>

                        <!-- Information Container-->
                        <div class="pt-3">
                            <p class="mb-2"><strong class="text-dark">Unique ID:</strong> <span class="interviewUniqueId"></span></p>
                            <p class="mb-2"><strong class="text-dark">Email:</strong> <span class="interviewEmail"></span></p>
                            <p class="mb-2"><strong class="text-dark">Contact Number:</strong> <span class="interviewContactNumber"></span></p>
                            <p class="mb-2"><strong class="text-dark">Title:</strong> <span class="interviewJobTitle"></span></p>
                            <p class="mb-2"><strong class="text-dark">Department:</strong> <span class="interviewDepartmentName"></span></p>
                            <p class="mb-2"><strong class="text-dark">Branch:</strong> <span class="interviewBranchName"></span></p>
                            <p class="mb-2"><strong class="text-dark">Location:</strong> <span class="interviewLocation"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Applicant Files Container-->
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <!-- Application Form Container -->
                        <div id="interviewFormFileContainer" class="file-container mb-3" style="display: none;">
                            <h6 class="text-muted">Application Form</h6>
                            <iframe
                                id="interviewFormFileIframe"
                                class="w-100 rounded shadow-sm"
                                style="border: 1px solid #ddd; height: 400px; overflow: auto;"
                                src=""
                                title="Application Form">
                                Your browser does not support iframes.
                            </iframe>
                        </div>
                        <br>
                        <!-- Cover Letter Container -->
                        <div id="interviewCoverLetterContainer" class="file-container" style="display: none;">
                            <h6 class="text-muted">Cover Letter</h6>
                            <iframe
                                id="interviewCoverLetterIframe"
                                class="w-100 rounded shadow-sm"
                                style="border: 1px solid #ddd; height: 400px; overflow: auto;"
                                src=""
                                title="Cover Letter">
                                Your browser does not support iframes.
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="SMSModal" tabindex="-1" aria-labelledby="SMSModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-2">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="SMSModalLabel">Send SMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="smsTo" class="form-label">To</label>
                        <input type="text" class="form-control" id="smsTo" name="smsTo" placeholder="Enter recipient's number">
                    </div>
                    <div class="mb-3">
                        <label for="smsFrom" class="form-label">From</label>
                        <input type="text" class="form-control" id="smsFrom" name="smsFrom" placeholder="Enter sender's name or number">
                    </div>
                    <div class="mb-3">
                        <label for="smsMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="smsMessage" name="smsMessage" rows="3" placeholder="Type your message"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background-color: #195252;">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Archive Modal -->
<div class="modal fade" id="ArchiveModal" tabindex="-1" aria-labelledby="ArchiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-2">
            <div class="modal-header border-0">
                <h6 class="modal-title text-muted mb-0" id="ArchiveModalLabel">
                    Archive Applicant: <span class="applicantName text-muted" style="font-size: 13px;"></span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="archiveReason" class="form-label">Reason for Archiving</label>
                        <textarea class="form-control" id="archiveReason" name="archiveReason" rows="4" placeholder="Provide a reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background-color: #195252;">Archive</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Schedule Interview -->
<div class='modal fade' id='scheduleInterview' tabindex='-1' aria-labelledby='scheduleInterviewLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content shadow-lg border-0 rounded-3'>
            <div class='modal-header bg-light border-0'>
                <div class='modal-title fw-bold' id='scheduleModalLabel'>
                    <h5 class=" mb-1" style="color: #333;">Schedule Interview</h5>
                    <p class="text-muted small mb-0 ">To: <span class="interviewApplicantName"></span></p>
                </div>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>

            <div class="modal-body" style="max-height: 590px; overflow-y: auto;">
                <div class="interview-history-container mb-5 p-3 bg-light rounded-3 text-primary" style="border: 1px solid #ccc;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock me-2" style="font-size: 14px;"></i>
                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Interview History</h6>
                        <!-- Refresh icon -->
                        <i class="fas fa-sync-alt ms-auto refresh-icon" style="font-size: 14px; cursor: pointer; padding: 9px 11px;" onclick="refreshInterviewHistory()"></i>
                    </div>

                    <div class="interview-history-content" style="max-height: 200px; overflow-y: auto; padding-right: 5px;">
                        <!-- Each row of interview history will be appended here dynamically -->
                    </div>
                </div>
                <form id="interviewForm">
                    <div class="bg-primary text-white px-2 py-1 mb-2">
                        <h5>New Schedule Interview</h5>
                    </div>

                    <input type="hidden" class="interviewId" name="interviewId">
                    <input type="hidden" class="InterviewApplicantId" name="InterviewApplicantId">

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="e.g. First round interview" required>
                    </div>
                    <div class="mb-4">
                        <label for="date" class="form-label fw-semibold">Interview Date and Time</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="interviewType" class="form-label fw-semibold">Interview Type</label>
                        <div class="btn-group w-100" role="group" aria-label="Interview Type">
                            <input type="radio" class="btn-check" name="interviewType" id="video-call" value="video" autocomplete="off" checked>
                            <label class="btn btn-outline-primary w-100" for="video-call">Video Call</label>

                            <input type="radio" class="btn-check" name="interviewType" id="phone" value="phone" autocomplete="off">
                            <label class="btn btn-outline-primary w-100" for="phone">Phone</label>

                            <input type="radio" class="btn-check" name="interviewType" id="in-office" value="in-office" autocomplete="off">
                            <label class="btn btn-outline-primary w-100" for="in-office">In-office</label>
                        </div>
                    </div>

                    <!-- Video Call Section -->
                    <div id="video-call-section" class="interview-details">
                        <label for="videocallLink" class="form-label fw-semibold">Video Call Link</label>
                        <input type="text" class="form-control mb-3" id="videocallLink" name="videocallLink" placeholder="e.g. https://meet.google.com/abc-defg-hij">

                        <label for="videoDescription" class="form-label fw-semibold">Description</label>
                        <textarea name="interviewDescription_video" id="videoDescription" class="form-control" placeholder="Describe the video call."></textarea>
                    </div>

                    <!-- Phone Section -->
                    <div id="phone-section" class="interview-details" style="display: none;">
                        <label for="phoneNumber" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control mb-3" id="phoneNumber" name="phoneNumber" placeholder="e.g. +123456789">

                        <label for="phoneDescription" class="form-label fw-semibold">Description</label>
                        <textarea name="interviewDescription_phone" id="phoneDescription" class="form-control" placeholder="Describe the phone interview."></textarea>
                    </div>

                    <!-- In-office Section -->
                    <div id="in-office-section" class="interview-details" style="display: none;">
                        <label for="officeAddress" class="form-label fw-semibold">Office Address</label>
                        <input type="text" class="form-control mb-3" id="officeAddress" name="officeAddress" placeholder="e.g. 123 Main St, City">

                        <label for="officeDescription" class="form-label fw-semibold">Description</label>
                        <textarea name="interviewDescription_office" id="officeDescription" class="form-control" placeholder="Describe the office interview."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript for tab behavior and highlighting -->
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (including Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<script>
    //function to show the content(tab)
    function showContent(contentId) {
        var contents = document.getElementsByClassName('content-div');
        for (var i = 0; i < contents.length; i++) {
            contents[i].style.display = 'none';
        }
        document.getElementById(contentId).style.display = 'block';

        var listItems = document.getElementsByClassName('list-item');
        for (var i = 0; i < listItems.length; i++) {
            listItems[i].classList.remove('active');
        }
        event.target.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('applicants').style.display = 'block';
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //function to view an applicant in off canvas for applicant tab
    function viewApplicant(row) {
        const applicantId = row.getAttribute('data-id');

        // AJAX request to update the "isViewed" status
        fetch('/sedp-hrms/Admin%20Page/App/Controller/JobApplicantController.php?action=markViewed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `applicantId=${encodeURIComponent(applicantId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    row.classList.remove('font-weight-bold');


                    loadApplicantDetails(applicantId);


                    const offCanvas = new bootstrap.Offcanvas(document.getElementById('applicantDetailsOffcanvas'));
                    offCanvas.show();
                } else {
                    console.error('Failed to update applicant status:', data.message);
                    alert('Failed to update applicant status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the request.');
            });
    }

    function viewInterviewApplicant(row) {
        const interviewApplicantId = row.getAttribute('data-interview-id');

        // AJAX request to update the "isViewed" status
        fetch('/sedp-hrms/Admin%20Page/App/Controller/InterviewJobApplicantController.php?action=markViewed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `interviewApplicantId=${encodeURIComponent(interviewApplicantId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    row.classList.remove('font-weight-bold');


                    loadInterviewApplicantDetails(interviewApplicantId);


                    const offCanvas = new bootstrap.Offcanvas(document.getElementById('applicantDetailsInterviewOffcanvas'));
                    offCanvas.show();
                } else {
                    console.error('Failed to update applicant status:', data.message);
                    alert('Failed to update applicant status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the request.');
            });
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    let currentApplicantId = null;

    //function to fetch and display applicant details
    function loadApplicantDetails(applicantId) {
        fetch(`/sedp-hrms/Admin%20Page/App/Controller/JobApplicantController.php?action=ViewApplicantById&applicantId=${encodeURIComponent(applicantId)}`)
            .then(response => response.json())
            .then(details => {
                if (details.success) {
                    const data = details.data;
                    // Store the applicantId in the global variable
                    currentApplicantId = data.applicantId;

                    // Update content dynamically
                    document.querySelectorAll('.applicantId').forEach(el => el.value = data.applicantId || 'N/A');
                    document.querySelectorAll('.applicantId').forEach(el => el.textContent = data.applicantId || 'N/A');
                    document.querySelectorAll('.applicantName').forEach(el => el.textContent = data.applicantName || 'N/A');
                    document.querySelectorAll('.uniqueId').forEach(el => el.textContent = data.uniqueId || 'N/A');
                    document.querySelectorAll('.email').forEach(el => el.textContent = data.email || 'N/A');
                    document.querySelectorAll('.contactNumber').forEach(el => el.textContent = data.contactNumber || 'N/A');
                    // Use timeAgo function for appliedDate
                    const timeAgoText = timeAgo(data.appliedDate);
                    document.querySelectorAll('.timeAgo').forEach(el => el.textContent = timeAgoText);

                    document.querySelectorAll('.jobTitle').forEach(el => el.textContent = data.jobTitle || 'N/A');
                    document.querySelectorAll('.departmentName').forEach(el => el.textContent = data.departmentName || 'N/A');
                    document.querySelectorAll('.branchName').forEach(el => el.textContent = data.branchName || 'N/A');
                    document.querySelectorAll('.location').forEach(el => el.textContent = `${data.city || ''}, ${data.province || ''}, ${data.country || ''}`);

                    // Update files
                    if (data.formFileName) {
                        document.querySelectorAll('#formFileContainer').forEach(el => el.style.display = 'block');
                        const formFileExtension = data.formFileName.split('.').pop().toLowerCase();
                        if (formFileExtension === 'docx' || formFileExtension === 'doc') {
                            // Use Google Docs Viewer for DOC and DOCX files
                            document.querySelectorAll('#formFileIframe').forEach(el => {
                                el.src = `https://docs.google.com/viewer?url=../../../JobApplicantPage/Files/uploads/applicationForms/${data.formFileName}&embedded=true`;
                            });
                        } else if (formFileExtension === 'pdf') {
                            // Directly embed PDF files
                            document.querySelectorAll('#formFileIframe').forEach(el => {
                                el.src = `../../../JobApplicantPage/Files/uploads/applicationForms/${data.formFileName}`;
                            });
                        }
                        document.querySelectorAll('.formFileName').forEach(el => el.textContent = data.formFileName);
                    } else {
                        document.querySelectorAll('#formFileContainer').forEach(el => el.style.display = 'none');
                        document.querySelectorAll('#formFileIframe').forEach(el => el.src = '');
                    }

                    if (data.letterFileName) {
                        document.querySelectorAll('#coverLetterContainer').forEach(el => el.style.display = 'block');
                        const letterFileExtension = data.letterFileName.split('.').pop().toLowerCase();
                        if (letterFileExtension === 'docx' || letterFileExtension === 'doc') {
                            // Use Google Docs Viewer for DOC and DOCX files
                            document.querySelectorAll('#coverLetterIframe').forEach(el => {
                                el.src = `https://docs.google.com/viewer?url=../../../JobApplicantPage/Files/uploads/coverletters/${data.letterFileName}&embedded=true`;
                            });
                        } else if (letterFileExtension === 'pdf') {
                            // Directly embed PDF files
                            document.querySelectorAll('#coverLetterIframe').forEach(el => {
                                el.src = `../../../JobApplicantPage/Files/uploads/coverletters/${data.letterFileName}`;
                            });
                        }
                        document.querySelectorAll('.coverLetterFileName').forEach(el => el.textContent = data.letterFileName);
                    } else {
                        document.querySelectorAll('#coverLetterContainer').forEach(el => el.style.display = 'none');
                        document.querySelectorAll('#coverLetterIframe').forEach(el => el.src = '');
                    }

                    // Update photo and status
                    document.querySelectorAll('.applicantPhoto').forEach(el => {
                        el.src = data.photoFileName ?
                            `../../../JobApplicantPage/Files/uploads/profilePictures/${data.photoFileName}` :
                            '../../../Assets/Images/resizeuserimg.png';

                        el.onerror = () => {
                            el.src = '../../../Assets/Images/resizeuserimg.png';
                        };
                    });

                    document.querySelectorAll('.applicantStatusBadge').forEach(el => el.textContent = data.status || 'N/A');
                } else {
                    alert(details.message || 'Failed to fetch applicant details.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading applicant details.');
            });
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    let currentInterviewApplicantId = null;

    //function to fetch and display applicant details
    function loadInterviewApplicantDetails(interviewApplicantId) {
        fetch(`/sedp-hrms/Admin%20Page/App/Controller/InterviewJobApplicantController.php?action=ViewApplicantById&interviewApplicantId=${encodeURIComponent(interviewApplicantId)}`)
            .then(response => response.json())
            .then(details => {
                if (details.success) {
                    const data = details.data;

                    currentInterviewApplicantId = data.interviewApplicantId;


                    document.querySelectorAll('.interviewApplicantId').forEach(el => el.value = data.interviewApplicantId || 'N/A');
                    document.querySelectorAll('.interviewApplicantId').forEach(el => el.textContent = data.interviewApplicantId || 'N/A');
                    document.querySelectorAll('.interviewApplicantName').forEach(el => el.textContent = data.applicantName || 'N/A');
                    document.querySelectorAll('.interviewUniqueId').forEach(el => el.textContent = data.uniqueId || 'N/A');
                    document.querySelectorAll('.interviewEmail').forEach(el => el.textContent = data.email || 'N/A');
                    document.querySelectorAll('.interviewContactNumber').forEach(el => el.textContent = data.contactNumber || 'N/A');
                    // Use timeAgo function for appliedDate
                    const timeAgoText = timeAgo(data.appliedDate);
                    document.querySelectorAll('.timeAgo').forEach(el => el.textContent = timeAgoText);

                    document.querySelectorAll('.interviewJobTitle').forEach(el => el.textContent = data.jobTitle || 'N/A');
                    document.querySelectorAll('.interviewDepartmentName').forEach(el => el.textContent = data.departmentName || 'N/A');
                    document.querySelectorAll('.interviewBranchName').forEach(el => el.textContent = data.branchName || 'N/A');
                    document.querySelectorAll('.interviewLocation').forEach(el => el.textContent = `${data.city || ''}, ${data.province || ''}, ${data.country || ''}`);

                    // Update files
                    if (data.formFileName){
                        document.querySelectorAll('#interviewFormFileContainer').forEach(el => el.style.display = 'block');
                        const formFileExtension = data.formFileName.split('.').pop().toLowerCase();
                        if (formFileExtension === 'docx' || formFileExtension === 'doc') {
                            // Use Google Docs Viewer for DOC and DOCX files
                            document.querySelectorAll('#interviewFormFileIframe').forEach(el => {
                                el.src = `https://docs.google.com/viewer?url=../../../JobApplicantPage/Files/uploads/applicationForms/${data.formFileName}&embedded=true`;
                            });
                        } else if (formFileExtension === 'pdf') {
                            // Directly embed PDF files
                            document.querySelectorAll('#interviewFormFileIframe').forEach(el => {
                                el.src = `../../../JobApplicantPage/Files/uploads/applicationForms/${data.formFileName}`;
                            });
                        }
                        document.querySelectorAll('.interviewFormFileName').forEach(el => el.textContent = data.formFileName);
                    } else {
                        document.querySelectorAll('#interviewFormFileContainer').forEach(el => el.style.display = 'none');
                        document.querySelectorAll('#interviewFormFileIframe').forEach(el => el.src = '');
                    }

                    if (data.letterFileName) {
                        document.querySelectorAll('#interviewCoverLetterContainer').forEach(el => el.style.display = 'block');
                        const letterFileExtension = data.letterFileName.split('.').pop().toLowerCase();
                        if (letterFileExtension === 'docx' || letterFileExtension === 'doc') {
                            // Use Google Docs Viewer for DOC and DOCX files
                            document.querySelectorAll('#interviewCoverLetterIframe').forEach(el => {
                                el.src = `https://docs.google.com/viewer?url=../../../JobApplicantPage/Files/uploads/coverletters/${data.letterFileName}&embedded=true`;
                            });
                        } else if (letterFileExtension === 'pdf') {
                            // Directly embed PDF files
                            document.querySelectorAll('#interviewCoverLetterIframe').forEach(el => {
                                el.src = `../../../JobApplicantPage/Files/uploads/coverletters/${data.letterFileName}`;
                            });
                        }
                        document.querySelectorAll('.interviewCoverLetterFileName').forEach(el => el.textContent = data.letterFileName);
                    } else {
                        document.querySelectorAll('#interviewCoverLetterContainer').forEach(el => el.style.display = 'none');
                        document.querySelectorAll('#interviewCoverLetterIframe').forEach(el => el.src = '');
                    }

                    // Update photo and status
                    document.querySelectorAll('.interviewapplicantPhoto').forEach(el => {
                        el.src = data.photoFileName ?
                            `../../../JobApplicantPage/Files/uploads/profilePictures/${data.photoFileName}` :
                            '../../../Assets/Images/resizeuserimg.png';

                        el.onerror = () => {
                            el.src = '../../../Assets/Images/resizeuserimg.png';
                        };
                    });

                    document.querySelectorAll('.interviewApplicantStatusBadge').forEach(el => el.textContent = data.status || 'N/A');
                } else {
                    alert(details.message || 'Failed to fetch applicant details.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading applicant details.');
            });
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Track the last opened off-canvas
    let lastOffcanvas = null;

    // Track the last opened off-canvas when it's shown
    document.querySelectorAll('.offcanvas').forEach(offcanvas => {
        offcanvas.addEventListener('shown.bs.offcanvas', () => {
            lastOffcanvas = offcanvas;
        });
    });

    // Automatically close the off-canvas when opening a modal
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', () => {
            const offcanvas = document.querySelector('.offcanvas.show');
            if (offcanvas) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
                bsOffcanvas.hide();
                document.body.classList.add('modal-open');
            }
        });
    });

    // Reopen the last opened off-canvas when the modal is closed
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', () => {
            if (lastOffcanvas && !lastOffcanvas.classList.contains('show')) {
                const bsOffcanvas = new bootstrap.Offcanvas(lastOffcanvas);
                bsOffcanvas.show();
            }

            // Clear any blur effects after the modal closes
            clearOffcanvasBlurEffects();
        });
    });

    // Clear all blur layers when the off-canvas is closed
    document.querySelectorAll('.offcanvas').forEach(offcanvas => {
        offcanvas.addEventListener('hidden.bs.offcanvas', () => {
            // Clear all offcanvas related blur effects
            clearOffcanvasBlurEffects();
        });
    });

    // Function to clear all blur effects from the off-canvas and body (excluding modal blur)
    function clearOffcanvasBlurEffects() {

        const offcanvasBackdrop = document.querySelector('.offcanvas-backdrop');
        if (offcanvasBackdrop) {
            offcanvasBackdrop.remove();
        }

        // Reset body styles to ensure no blur or overflow is left
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        document.body.style.filter = '';
        document.body.style.backgroundColor = '';
    }

    // Prevent recursive event calls (stop propagation in off-canvas and modal)
    document.addEventListener('focusin', (event) => {
        if (event.target.closest('.offcanvas, .modal')) {
            event.stopPropagation();
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //function to filter applicant by job post
    function filterApplicants(element) {
        const jobPostId = element.getAttribute('data-id');
        const filter = document.getElementById('filter').value;
        const search = document.getElementById('search').value;

        const tableBody = document.querySelector('tbody');
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';

        fetch('/sedp-hrms/Admin%20Page/App/Controller/JobApplicantController.php?action=filterApplicants', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    jobPostId: jobPostId === "all" ? null : jobPostId,
                    filter,
                    search
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tableBody.innerHTML = '';
                    data.applicants.forEach(applicant => {
                        const row = `
                        <tr class="table-row ${applicant.isViewed ? '' : 'font-weight-bold'}" data-id="${applicant.applicantId}" onclick="viewApplicant(this)">
                            <td>${applicant.applicantName}</td>
                            <td>${applicant.jobTitle}</td>
                            <td>${applicant.appliedDate}</td>
                            <td>
                                <span class="badge text-light" style="background-color: ${getStatusColor(applicant.status)};">
                                    ${applicant.status}
                                </span>
                            </td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No applicants found.</td></tr>';
                    alert(data.message || 'Failed to fetch applicants.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Error occurred.</td></tr>';
                alert('An error occurred while processing the request.');
            });
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //function to apply color to status type
    function getStatusColor(status) {
        switch (status) {
            case 'Schedule Interview':
                return '#b3e5fc';
            case 'Pending':
                return '#ffe082';
            case 'Accepted':
                return '#64b5f6';
            case 'Rejected':
                return '#ef9a9a';
            default:
                return '#b0bec5';
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //function to clear all filters applied
    function clearFilters() {
        document.getElementById('search').value = '';
        document.getElementById('filter').selectedIndex = 0;
        filterApplicants(); // Call your filtering logic
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //FUNCTION TO FILTER APPLICANT NAMES
    function filterBy(type) {
        if (type === 'name') {
            // Implement filter logic for Name
            console.log('Filtering by Name...');
        } else if (type === 'date') {
            // Implement filter logic for Date Applied
            console.log('Filtering by Date...');
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Function to calculate "time ago" label
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
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //function to switch between interview types
    document.addEventListener('DOMContentLoaded', function() {
        function updateInterviewType() {
            // Get all sections
            const videoSection = document.getElementById('video-call-section');
            const phoneSection = document.getElementById('phone-section');
            const inOfficeSection = document.getElementById('in-office-section');

            // Ensure all sections are hidden initially
            [videoSection, phoneSection, inOfficeSection].forEach(section => {
                section.style.display = 'none';
            });

            // Determine which section to display based on the checked radio button
            const selectedType = document.querySelector('input[name="interviewType"]:checked');
            if (selectedType) {
                switch (selectedType.id) {
                    case 'video-call':
                        videoSection.style.display = 'block';
                        break;
                    case 'phone':
                        phoneSection.style.display = 'block';
                        break;
                    case 'in-office':
                        inOfficeSection.style.display = 'block';
                        break;
                    default:
                        console.error("Unexpected radio button selected!");
                }
            }
        }

        // Initialize on page load
        updateInterviewType();

        // Add event listeners for radio buttons
        document.querySelectorAll('input[name="interviewType"]').forEach(function(radio) {
            radio.addEventListener('change', updateInterviewType);
        });
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // This function will be triggered when the modal is about to open
    document.getElementById('scheduleInterviewLink').addEventListener('click', function(event) {
        // Make sure the global variable has the applicantId
        if (currentInterviewApplicantId) {
            console.log('Selected applicant ID:', currentInterviewApplicantId);
            loadInterviewHistory(currentInterviewApplicantId); // Pass the applicantId to the function that loads the interview history
        } else {
            console.error('Interview Applicant ID is not set.');
        }
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function loadInterviewHistory(interviewApplicantId) {
        console.log('Loading interview history for applicant:', interviewApplicantId);
        fetch(`../Controller/InterviewController.php?action=history&interviewApplicantId=${encodeURIComponent(interviewApplicantId)}`)
            .then(response => response.json())
            .then(data => {
                const container = document.querySelector('.interview-history-content');
                console.log('Function called');
                console.log('Container found:', container);
                console.log('Data:', data);
                container.innerHTML = ''; // Clear any existing content

                if (!Array.isArray(data) || data.length === 0) {
                    container.innerHTML = '<p class="text-muted">No interview history found.</p>';
                    return;
                }
                data.forEach(interview => {
                    // Variables for displaying interview type-specific data
                    let typeChannel = '';
                    let channel = '';
                    let description = '';

                    if (interview.interviewType === "video") {
                        typeChannel = "Video Link: ";
                        channel = interview.videocallLink || 'N/A';
                        description = interview.interviewDescription_video || 'No description available.';
                    } else if (interview.interviewType === "phone") {
                        typeChannel = "Phone Number: ";
                        channel = interview.phoneNumber || 'N/A';
                        description = interview.interviewDescription_phone || 'No description available.';
                    } else if (interview.interviewType === "in-office") {
                        typeChannel = "Office Address: ";
                        channel = interview.officeAddress || 'N/A';
                        description = interview.interviewDescription_office || 'No description available.';
                    }
                    // Create a new div for each interview entry
                    const entry = document.createElement('div');
                    entry.classList.add('history-entry', 'p-2', 'mb-2');
                    entry.style.backgroundColor = '#f9f9f9';

                    // interview ID as a data attribute
                    entry.dataset.interviewId = interview.interviewId;

                    // Add the content to the entry
                    entry.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
            <p class="title fw-semibold mb-0" style="margin: 0; font-size: 12px;">${interview.title || 'N/A'}</p>
            <div class="d-flex align-items-center gap-2">
                <span class="status text-muted small" style="font-size: 10px;">${interview.status || 'N/A'}</span>
                <button class="btn btn-link p-0 notes-toggle" type="button" style="font-size: 10px;" onclick="toggleNotes(this)">
                    <i class="fas fa-caret-down"></i> Notes
                </button>
            </div>
        </div>
        <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 10px;">
            <p class="date mb-0 me-3">${new Date(interview.interviewDate).toLocaleString() || 'N/A'}</p>
            <span class="badge text-white" style="background-color: lightgray; font-size: 10px;">${interview.interviewType || 'N/A'}</span>
        </div>
        <div class="type-description-container mt-2 p-2" style="border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9;">
            <div class="d-flex align-items-start mb-1">
                <span class="fw-semibold text-primary me-2" style="font-size: 11px;">${typeChannel}</span>
                <span class="text-dark" style="font-size: 11px;">${channel}</span>
            </div>
            <div class="d-flex align-items-start">
                <span class="fw-semibold text-secondary me-2" style="font-size: 11px;">Description:</span>
                <span class="text-muted" style="font-size: 11px;">${description}</span>
            </div>
        </div>
        <div class="notes-section mt-2 p-2" style="display: none; border: solid 1px grey;">
            <p class="notes-text text-muted small mb-1" style="font-size: 10px;">${interview.notes || 'No notes available.'}</p>
            <textarea class="note-edit text-muted small mb-1" style="display: none; font-size: 10px; width: 100%; height: 80px;" rows="4"></textarea>
            <button class="btn btn-sm btn-primary edit-button" type="button" style="font-size: 10px; margin-left:90%" onclick="toggleEdit(this)">Edit</button>
        </div>
        <hr>
                `;
                    // Append the entry to the container
                    container.appendChild(entry);
                });
            })
            .catch(error => console.error('Error fetching interview history:', error));
    }

    // Function to refresh the interview history
    function refreshInterviewHistory() {
        if (currentInterviewApplicantId) {
            loadInterviewHistory(currentInterviewApplicantId); // Call the loadInterviewHistory function with the dynamic applicantId
        } else {
            console.log('Applicant ID is not available to refresh interview history.');
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function toggleNotes(button) {
        const entry = button.closest('.history-entry');
        const notesSection = entry.querySelector('.notes-section');

        if (!notesSection) {
            console.error('Notes section not found.');
            return;
        }

        // Toggle the display property
        if (notesSection.style.display === 'none' || notesSection.style.display === '') {
            notesSection.style.display = 'block';
            button.innerHTML = `<i class="fas fa-caret-up"></i> Notes`; // Change icon to "up"
        } else {
            notesSection.style.display = 'none';
            button.innerHTML = `<i class="fas fa-caret-down"></i> Notes`; // Change icon to "down"
        }
    }

    function toggleEdit(button) {
        const notesSection = button.closest('.notes-section');
        const noteText = notesSection.querySelector('.notes-text');
        const noteEdit = notesSection.querySelector('.note-edit');
        const interviewId = button.closest('.history-entry').dataset.interviewId;

        if (!interviewId) {
            console.error('Interview ID not found.');
            return;
        }

        if (button.textContent.trim() === "Edit") {
            // Switch to edit mode
            enterEditMode(noteText, noteEdit, button);
        } else {
            // Save changes
            const updatedNotes = noteEdit.value.trim();
            if (!updatedNotes) {
                alert('Notes cannot be empty.');
                return;
            }

            saveNotes(interviewId, updatedNotes, noteText, noteEdit, button);
        }
    }

    function enterEditMode(noteText, noteEdit, button) {
        noteEdit.value = noteText.textContent.trim();
        noteText.style.display = 'none';
        noteEdit.style.display = 'block';
        button.textContent = "Save";
    }

    function saveNotes(interviewId, updatedNotes, noteText, noteEdit, button) {
        button.disabled = true; // Prevent multiple clicks
        fetch('../Controller/InterviewController.php?action=updateNote', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    interviewId: interviewId,
                    notes: updatedNotes,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    noteText.textContent = updatedNotes;
                    noteText.style.display = 'block';
                    noteEdit.style.display = 'none';
                    button.textContent = "Edit";
                } else {
                    alert('Failed to update notes: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error updating notes:', error);
                alert('An error occurred while saving notes.');
            })
            .finally(() => {
                button.disabled = false; // Re-enable the button
            });
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("interviewForm");

        form.addEventListener("submit", (event) => {
            event.preventDefault(); // Prevent the default form submission

            // Collect form data
            const formData = new FormData(form);
            const formDataObject = {};
            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            console.log(formDataObject);

            // Check if necessary fields are filled
            if (!formDataObject.title || !formDataObject.date || !formDataObject.interviewType) {
                alert("Please fill in all required fields.");
                return;
            }

            // Send data using Fetch API
            fetch("../Controller/InterviewController.php?action=create", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(formDataObject),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert(data.message);
                        form.reset(); // Reset the form fields            
                    } else {
                        alert("Error creating interview: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error submitting form:", error);
                    alert("An unexpected error occurred. Please try again.");
                });
        });
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function transferApplicantToInterview(applicantId) {
        console.log("Transferring Applicant ID:", applicantId);

        // Confirm action with the user
        if (!confirm("Are you sure you want to transfer this applicant to the interview section?")) {
            return; // Exit if the user cancels
        }

        // Make the AJAX request
        fetch(`../Controller/InterviewJobApplicantController.php?action=transfer&applicantId=${applicantId}`, {
                method: 'GET',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Applicant transferred to the interview section successfully.');

                    // Dynamically update the UI to remove the applicant from the current list
                    const row = document.getElementById(`applicant-row-${applicantId}`);
                    if (row) {
                        row.remove();
                    } else {
                        console.warn("Row for applicant not found in the UI.");
                    }

                    // Reload the page to reflect other potential changes
                    setTimeout(() => {
                        location.reload(); // Reload after a slight delay for better UX
                    }, 500);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred. Please try again later.');
            });
    }



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
</script>

<!-- Styles -->
<style>
    * {
        font-family: 'poppins', sans-serif;
    }

    /*refresh icon */
    .refresh-icon:hover {
        color: #003366;
        padding: 9px 11px;
        background-color: #e0e3e8;
        border-radius: 100%;
    }


    /* file */
    .file-container {
        background-color: white;

        transition: background-color 0.3s ease;

        padding: 5px;

        border-radius: 5px;

        margin-bottom: 10px;

    }

    .file-container:hover {
        background-color: #4d7e7e;

    }

    .file-container:active {
        background-color: #b2ebf2;

    }

    .file-container a {
        text-decoration: none;

        color: inherit;

    }

    /* step btn */
    .custom-dropdown-menu {
        background-color: rgba(255, 255, 255, 0.7);

        backdrop-filter: blur(8px);

        border: 1px solid rgba(255, 255, 255, 0.2);

        left: auto !important;
        top: auto !important;

        bottom: 0 !important;
        right: 0 !important;

    }

    .btn-group .dropdown-menu {
        position: absolute;
        top: auto;

        bottom: auto;
        transform: none;

    }

    /* Make the sections look clean and spaced */
    .interview-details {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    textarea {
        resize: vertical;

    }

    /* For flex layout */
    .btn-group {
        display: flex;
        gap: 10px;
    }


    /* Sidebar */
    .sidebar-modern {
        border: 1px solid #ddd;
        border-radius: 8px;
        min-height: 90vh;

        overflow-y: auto;

        padding-bottom: 10px;
    }

    /* Sidebar Header */
    .sidebar-modern h5 {
        color: #495057;
        font-size: 1rem;
        margin-bottom: 15px;
        margin-top: 5px;
    }

    /* Search Bar */
    .search-bar {
        display: flex;
        align-items: center;
        gap: 3px;
        margin-bottom: 1rem;
    }

    .search-bar input {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 7px 15px;
        font-size: 0.85rem;
        transition: border-color 0.3s ease;
        width: 100%;
    }

    .search-bar input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .search-bar select {
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 0.85rem;
        border: 1px solid #ddd;
        transition: border-color 0.3s ease;
        cursor: pointer;
    }

    .search-bar select:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .search-bar .clear-btn {
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 30px;
        padding: 0;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .search-bar .clear-btn:hover {
        background-color: #f8d7da;
        border-color: #dc3545;
        color: #dc3545;
    }

    .dropdown .dropdown-menu {
        min-width: auto;
        padding: 0.5rem;
    }

    .dropdown .dropdown-menu .dropdown-item {
        font-size: 0.85rem;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .dropdown .dropdown-menu .dropdown-item:hover {
        background-color: #195252;
        color: white;
    }


    /* Table Styles */
    .table-responsive-md {
        margin-top: 0rem;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 0.4rem;

    }

    .table thead {
        background-color: #f8f9fa;
        font-size: 0.85rem;
    }

    .table tbody tr {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        cursor: pointer;
    }

    .table-row.font-weight-bold {
        font-weight: bold;
    }


    /* Job List */
    .job-list {
        max-height: calc(100vh - 150px);

        overflow-y: auto;
    }

    .list-group-item {
        border: none;
        padding: 8px 10px;
        font-size: 0.9rem;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
        cursor: pointer;
    }

    .list-group-item:hover,
    .list-group-item:focus {
        background-color: #f0f2f5;
        color: #007bff;
    }

    /* Icon all to Job Positions */
    .all-applicants-icon {
        font-size: 1.2rem;
        padding: 3px 6px;
        margin-top: 5px;
        margin-bottom: 15px;

    }

    .all-applicants-icon:hover {
        color: #0056b3;
        padding: 3px 6px;
        background-color: #f0f2f5;
        border-radius: 65%;

    }

    .list-group-item[data-id="all"] {
        font-weight: bold;
        background-color: #f8f9fa;
        color: #007bff;
    }

    .list-group-item[data-id="all"]:hover {
        background-color: #e9ecef;
        color: #0056b3;
    }

    /* Tabs */
    .menu-list-column {
        display: flex;
        gap: 10px;
        padding: 0;
        margin-bottom: 15px;
        list-style: none;
        justify-content: flex-start;

    }

    .list-item {
        cursor: pointer;
        padding: 7px 15px;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 5px;
        background-color: #f0f2f5;
        transition: background-color 0.3s ease;
    }

    .list-item.active {
        background-color: #195252;
        color: white;
    }

    .list-item:hover {
        background-color: #669494;
        color: white;
    }

    /* Move Archive tab to the right */
    .menu-list-column .archive {
        margin-left: auto;

    }

    /* Content Divs */
    .content-div {
        display: none;
    }

    .content-div.active {
        display: block;
        padding: 0px;
        border-radius: 8px;
        background-color: #f7f9fc;
    }

    .modal-body {
        overflow-y: auto;
    }

    .modal-content {
        position: absolute;
    }

    .scrollable-textarea {
        overflow-y: auto;
    }

    /* Table Container */
    .table-responsive-md {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Table Header */
    .table thead {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    /* Row and Column Styles */
    .table tbody tr {
        transition: background-color 0.2s ease-in-out;
    }

    .table tbody tr:hover {
        background-color: #f1f3f5;
    }

    .font-weight-bold {
        font-weight: bold !important;
    }

    /* Badge Styling */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.6em;
        border-radius: 8px;
    }

    /* Button Styling */
    .btn-outline-secondary {
        border-color: #ced4da;
        color: #495057;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-secondary:hover {
        background-color: #e9ecef;
        color: #343a40;
    }
</style>