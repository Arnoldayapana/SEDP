<?php

$page = 'applicants';
require_once(__DIR__ . '/../Controller/JobApplicantController.php');
$ApplicantController = new JobApplicantController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['applicantId'])) {
    $applicant = $ApplicantController->ViewApplicantById($_GET['applicantId']);
}

include('../../Core/Includes/header.php');

$errorMessage = "";
$successMessage = "";
?>
<style>

</style>
<div class="wrapper">
    <style>
        .file-container {
            background-color: white;
            /* Default background color */
            transition: background-color 0.3s ease;
            /* Smooth transition */
            padding: 5px;
            /* Add padding */
            border-radius: 5px;
            /* Optional: for rounded corners */
            margin-bottom: 10px;
            /* Space between file containers */
        }

        .file-container:hover {
            background-color: #e0f7fa;
            /* Light blue on hover */
        }

        .file-container:active {
            background-color: #b2ebf2;
            /* Slightly darker blue on active */
        }

        .file-container a {
            text-decoration: none;
            /* Remove underline */
            color: inherit;
            /* Use the container's text color */
        }

        .custom-dropdown-menu {
            background-color: rgba(255, 255, 255, 0.7);
            /* White with transparency */
            backdrop-filter: blur(8px);
            /* Blur effect */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Optional: light border */
            left: auto !important;
            top: auto !important;
            /* Force the dropdown to always align to the top left */
            bottom: 0 !important;
            right: 0 !important;
            /* Prevent the dropdown from aligning to the bottom right */
        }

        .btn-group .dropdown-menu {
            position: absolute;
            top: auto;
            /* Allow dropdown to go up or down based on the available space */
            bottom: auto;
            transform: none;
            /* Remove any transformation that could affect positioning */
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
            /* Allows resizing the textarea vertically */
        }

        /* For flex layout */
        .btn-group {
            display: flex;
            gap: 10px;
        }
    </style>

    <!--sidebar-->
    <?php
    include_once('../../core/includes/sidebar.php');
    ?>

    <!--add employee-->
    <main class="main">
        <!--header-->
        <?php
        include '../../core/includes/navBar.php';
        ?>
        <div class="container-fluid shadow p-4 mb-5 rounded-4 my-4">
            <div class="row">
                <!-- First Column (5) -->
                <div class="col-12 col-md-5 mb-4 mb-md-0">
                    <div class="d-flex flex-column align-items-center text-center position-relative">
                        <!-- Applicant Picture -->
                        <?php
                        $photoFileName = htmlspecialchars($applicant['photoFileName']);
                        $photoFilePath = "../../../JobApplicantPage/Files/uploads/profilePictures/" . $photoFileName;
                        $photoSrc = (!empty($photoFileName) && file_exists($photoFilePath)) ? $photoFilePath : "../../../Assets/Images/resizeuserimg.png";
                        ?>
                        <div class="picture-container rounded-circle border border-light shadow-sm" style="width: 180px; height: 180px; overflow: hidden;">
                            <img src="<?= $photoSrc ?>" alt="Applicant Photo" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>

                        <!-- Status Badge -->
                        <span class="badge position-absolute bg-success" style="font-size: 0.8rem; bottom: 185px; right: 113px; padding: 0.5rem 0.75rem; transform: translate(0%, 0%);">
                            <?= htmlspecialchars($applicant['status']) ?>
                        </span>

                        <!-- Applicant Details -->
                        <div class="mt-3 text-start w-100">
                            <p><strong>Name:</strong> <?= htmlspecialchars($applicant['applicantName']) ?> </p>
                            <p><strong>Unique ID:</strong> <?= htmlspecialchars($applicant['uniqueId']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($applicant['email']) ?></p>
                            <p><strong>Contact Number:</strong> <?= htmlspecialchars($applicant['contactNumber']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Second Column (7) -->
                <div class="col-12 col-md-7">
                    <div class="d-flex flex-column">
                        <!-- Applied Date -->
                        <div class="col-12 text-end">

                            <p>Applied <span id="timeAgo"></span></p>
                        </div>


                        <!-- Job Details -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <p><strong>Title:</strong> <?= htmlspecialchars($applicant['jobTitle']) ?></p>
                                <p><strong>Department:</strong> <?= htmlspecialchars($applicant['departmentName']) ?></p>
                                <p><strong>Branch:</strong> <?= htmlspecialchars($applicant['branchName']) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($applicant['country']) ?>, <?= htmlspecialchars($applicant['region']) ?>, <?= htmlspecialchars($applicant['province']) ?>, <?= htmlspecialchars($applicant['city']) ?></p>
                            </div>
                        </div>

                        <!-- File Attachments -->
                        <?php
                        $formFileName = htmlspecialchars($applicant['formFileName']);
                        $formFilePath = "../../../JobApplicantPage/Files/uploads/applicationForms/" . $formFileName;
                        $letterFileName = htmlspecialchars($applicant['letterFileName']);
                        $letterFilePath = "../../../JobApplicantPage/Files/uploads/coverletters/" . $letterFileName;
                        ?>

                        <!-- Applicant Form File -->
                        <?php if (!empty($formFileName)) : ?>
                            <div class="file-container mt-3">
                                <a href="<?= $formFilePath ?>" target="_blank" class="d-flex align-items-center text-decoration-none">
                                    <img src="../../../Assets/Images/applicant_file.png" alt="File Icon" class="img-fluid" style="max-width: 50px;">
                                    <p class="ms-3" style="font-size: 14px; color: #333; word-wrap: break-word;">
                                        <strong>Applicant Form:</strong> <?= $formFileName ?>
                                    </p>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Cover Letter File -->
                        <?php if (!empty($letterFileName)) : ?>
                            <div class="file-container mt-3">
                                <a href="<?= $letterFilePath ?>" target="_blank" class="d-flex align-items-center text-decoration-none">
                                    <img src="../../../Assets/Images/cover_letter.png" alt="File Icon" class="img-fluid" style="max-width: 50px;">
                                    <p class="ms-3" style="font-size: 14px; color: #333; word-wrap: break-word;">
                                        <strong>Cover Letter:</strong> <?= $letterFileName ?>
                                    </p>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <!-- Left side: Back button -->
                <button type="button" class="btn btn-secondary btn-sm py-2 px-3 me-2" onclick="location.href='./JobApplicants1.php'">
                    <i class="fa fa-arrow-left"></i> Back
                </button>

                <!-- Right side: Reject and Next Steps buttons -->
                <div class="d-flex">
                    <button type="button" class="btn btn-danger btn-sm py-2 px-3 me-2" data-bs-toggle="modal" data-bs-target="#RejectModal">
                        <i class="bi bi-x-circle me-2"></i>Reject
                    </button>

                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle py-2 px-3" data-bs-toggle="dropdown" aria-expanded="false">
                            Next Steps
                        </button>
                        <ul class="dropdown-menu custom-dropdown-menu">
                            <p class="text-muted ps-3 mb-1" style="font-size: 13px;">Change applicant status</p>
                            <li>
                                <a class="dropdown-item <?= $schedule_disabled; ?>" href="#" data-bs-toggle="modal" data-bs-target="#scheduleInterview">
                                    <i class="bi bi-calendar me-2"></i>Schedule Interview
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $accept_disabled; ?>" href="#" data-bs-toggle="modal" data-bs-target="#HireModal">
                                    <i class="bi bi-check-circle me-2"></i>Hire
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $reject_disabled; ?>" href="#" data-bs-toggle="modal" data-bs-target="#RejectModal">
                                    <i class="bi bi-x-circle me-2"></i>Reject
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Hire modal -->
<div class="modal fade" id="HireModal" tabindex='-1' aria-labelledby='HireModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered modal-md'>
        <div class='modal-content shadow-lg border-0 rounded-3'>
            <div class='modal-header bg-light border-0'>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <form action='../Applicants/sample.php' method='POST'>
                <div class="modal-body">
                    <p>hire invitaion message</p>
                </div>
                <div class='modal-footer border-0'>
                    <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>Close</button>
                    <button type='submit' class='btn btn-primary'>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Reject modal-->
<div class="modal fade" id="RejectModal" tabindex='-1' aria-labelledby='RejectModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered modal-md'>
        <div class='modal-content shadow-lg border-0 rounded-3'>
            <div class='modal-header bg-light border-0'>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <form action='../Applicants/sample.php' method='POST'>
                <div class="modal-body">
                    <p>reject message</p>
                </div>
                <div class='modal-footer border-0'>
                    <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>Close</button>
                    <button type='submit' class='btn btn-primary'>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Interview -->
<div class='modal fade' id='scheduleInterview' tabindex='-1' aria-labelledby='scheduleInterviewLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered modal-md'>
        <div class='modal-content shadow-lg border-0 rounded-3'>
            <div class='modal-header bg-light border-0'>
                <div class='modal-title fw-bold' id='scheduleModalLabel'>
                    <h5 class="mb-1" style="color: #333;">Schedule Interview</h5>
                    <p class="text-muted small mb-0">To: <?= htmlspecialchars($applicant['applicantName']) ?></p>
                </div>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>

            <form action='../Applicants/sample.php' method='POST'>
                <div class='modal-body' style="max-height: 390px; overflow-y: auto;">

                    <div class="interview-history-container mb-5 p-3 bg-light rounded-3 text-primary" style="border: 1px solid #ccc;">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock me-2" style="font-size: 14px;"></i>
                            <h6 class="fw-bold mb-0" style="font-size: 14px;">Interview History</h6>
                        </div>

                        <div class="interview-history-content" style="max-height: 200px; overflow-y: auto; padding-right: 5px;">

                            <!-- History Entry 1 -->
                            <div class="history-entry p-2 mb-2" style="background-color: #f9f9f9;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="fw-semibold mb-0" style="margin: 0; font-size: 12px;">2nd Round: HR Interview with Applicant</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small" style="font-size: 10px;">Completed</span>
                                        <button class="btn btn-link p-0" type="button" style="font-size: 10px;" onclick="toggleNotes(this)">
                                            <i class="fas fa-caret-down"></i> Notes
                                        </button>
                                    </div>
                                </div>
                                <!-- Date and In-office in a -->
                                <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 10px;">
                                    <p class="mb-0 me-3">21 March 2023, 2:00 pm</p>
                                    <p class="mb-0">In-office</p>
                                </div>

                                <!-- Notes Section (Initially Hidden) -->
                                <div class="notes-section mt-2 p-2" style="display: none; border:solid 1px grey;">
                                    <p class="note-text text-muted small mb-1" style="font-size: 10px;">
                                        Interview went well. Applicant showed strong communication skills and relevant experience.
                                    </p>
                                    <textarea class="note-edit text-muted small mb-1" style="display: none; font-size: 10px; width: 100%; height: 80px;" rows="4"></textarea>
                                    <button class="btn btn-sm btn-primary edit-button" type="button" style="font-size: 10px;" onclick="toggleEdit(this)">Edit</button>
                                </div>
                            </div>

                            <!-- History Entry 2 -->
                            <div class="history-entry p-2 mb-2" style="background-color: #f9f9f9;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="fw-semibold mb-0" style="margin: 0; font-size: 12px;">1st Round: Technical Interview</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small" style="font-size: 10px;">Pending</span>
                                        <button class="btn btn-link p-0" type="button" style="font-size: 10px;" onclick="toggleNotes(this)">
                                            <i class="fas fa-caret-down"></i> Notes
                                        </button>
                                    </div>
                                </div>
                                <!-- Date and In-office in a -->
                                <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 10px;">
                                    <p class="mb-0 me-3">21 March 2023, 2:00 pm</p>
                                    <p class="mb-0">Video call</p>
                                </div>
                                <!-- Notes Section (Initially Hidden) -->
                                <div class="notes-section mt-2" style="display: none;">
                                    <p class="note-text text-muted small mb-1" style="font-size: 10px;">
                                        Awaiting feedback from the interview panel.
                                    </p>
                                    <textarea class="note-edit text-muted small mb-1" style="display: none; font-size: 10px; width: 100%; height: 80px;" rows="4"></textarea>
                                    <button class="btn btn-sm btn-primary edit-button" type="button" style="font-size: 10px;" onclick="toggleEdit(this)">Edit</button>
                                </div>
                            </div>

                            <!-- History Entry 3 -->
                            <div class="history-entry p-2 mb-2" style="background-color: #f9f9f9;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="fw-semibold mb-0" style="margin: 0; font-size: 12px;">Follow-up Call with Applicant</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small" style="font-size: 10px;">Rescheduled</span>
                                        <button class="btn btn-link p-0" type="button" style="font-size: 10px;" onclick="toggleNotes(this)">
                                            <i class="fas fa-caret-down"></i> Notes
                                        </button>
                                    </div>
                                </div>
                                <!-- Date and In-office in a -->
                                <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 10px;">
                                    <p class="mb-0 me-3">21 March 2023, 2:00 pm</p>
                                    <p class="mb-0">Phone</p>
                                </div>
                                <!-- Notes Section (Initially Hidden) -->
                                <div class="notes-section mt-2" style="display: none;">
                                    <p class="note-text text-muted small mb-1" style="font-size: 10px;">
                                        The applicant requested a new interview time.
                                    </p>
                                    <textarea class="note-edit text-muted small mb-1" style="display: none; font-size: 10px; width: 100%; height: 80px;" rows="4"></textarea>
                                    <button class="btn btn-sm btn-primary edit-button" type="button" style="font-size: 10px;" onclick="toggleEdit(this)">Edit</button>
                                </div>
                            </div>

                            <!-- History Entry 4 -->
                            <div class="history-entry p-2 mb-2" style="background-color: #f9f9f9;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="fw-semibold mb-0" style="margin: 0; font-size: 12px;">Initial Screening Interview</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small" style="font-size: 10px;">Completed</span>
                                        <button class="btn btn-link p-0" style="font-size: 10px;" onclick="toggleNotes(this)">
                                            <i class="fas fa-caret-down"></i> Notes
                                        </button>
                                    </div>
                                </div>
                                <!-- Date and In-office in a -->
                                <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 10px;">
                                    <p class="mb-0 me-3">21 March 2023, 2:00 pm</p>
                                    <p class="mb-0">In-office</p>
                                </div>
                                <!-- Notes Section (Initially Hidden) -->
                                <div class="notes-section mt-2" style="display: none;">
                                    <p class="note-text text-muted small mb-1" style="font-size: 10px;">
                                        Applicant demonstrated strong problem-solving skills.
                                    </p>
                                    <textarea class="note-edit text-muted small mb-1" style="display: none; font-size: 10px; width: 100%; height: 80px;" rows="4"></textarea>
                                    <button class="btn btn-sm btn-primary edit-button" style="font-size: 10px;" onclick="toggleEdit(this)">Edit</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="bg-primary text-white px-2 py-1 mb-2">
                        <h5>New Schedule Interview</h5>
                    </div>

                    <input type='hidden' id='applicant_id' name='applicant_id' value="">

                    <div class="mb-3">
                        <label for='title' class='form-label fw-semibold'>Title</label>
                        <input type='text' class='form-control' id='title' name='title' placeholder="e.g. First round interview" required>
                    </div>

                    <div class="mb-4">
                        <label for='date' class='form-label fw-semibold'>Interview Date and Time</label>
                        <input type='datetime-local' class='form-control' id='date' name='date' required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Interview Type</label>
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
                        <label for="videocalllink" class="form-label fw-semibold">Video Call Link</label>
                        <input type="text" class="form-control mb-3" id="videocalllink" name="videocalllink" placeholder="e.g. https://meet.google.com/abc-defg-hij" required>

                        <label for="video-description" class="form-label fw-semibold">Description</label>
                        <textarea name="interviewdescription" id="video-description" class="form-control" placeholder="Describe the video call." required></textarea>
                    </div>

                    <!-- Phone Section -->
                    <div id="phone-section" class="interview-details" style="display: none;">
                        <label for="phone-number" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control mb-3" id="phone-number" name="phone" placeholder="e.g. +123456789" required>

                        <label for="phone-description" class="form-label fw-semibold">Description</label>
                        <textarea name="interviewdescription" id="phone-description" class="form-control" placeholder="Describe the phone interview." required></textarea>
                    </div>

                    <!-- In-office Section -->
                    <div id="in-office-section" class="interview-details" style="display: none;">
                        <label for="office-address" class="form-label fw-semibold">Office Address</label>
                        <input type="text" class="form-control mb-3" id="office-address" name="office" placeholder="e.g. 123 Main St, City" required>

                        <label for="office-description" class="form-label fw-semibold">Description</label>
                        <textarea name="interviewdescription" id="office-description" class="form-control" placeholder="Describe the office interview." required></textarea>
                    </div>
                </div>

                <div class='modal-footer border-0'>
                    <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>Close</button>
                    <button type='submit' class='btn btn-primary'>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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

    // Assuming $applicant['appliedDate'] is output from PHP and looks like '2024-11-13 23:56:29'
    const appliedDate = "<?php echo $applicant['appliedDate']; ?>"; // Date from PHP

    // Apply the time ago function and display the result
    document.getElementById('timeAgo').innerText = timeAgo(appliedDate);
</script>
<script>
    document.querySelectorAll('input[name="interviewType"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const videoSection = document.getElementById('video-call-section');
            const phoneSection = document.getElementById('phone-section');
            const inOfficeSection = document.getElementById('in-office-section');

            if (document.getElementById('video-call').checked) {
                videoSection.style.display = 'block';
                phoneSection.style.display = 'none';
                inOfficeSection.style.display = 'none';
            } else if (document.getElementById('phone').checked) {
                videoSection.style.display = 'none';
                phoneSection.style.display = 'block';
                inOfficeSection.style.display = 'none';
            } else if (document.getElementById('in-office').checked) {
                videoSection.style.display = 'none';
                phoneSection.style.display = 'none';
                inOfficeSection.style.display = 'block';
            }
        });
    });

    // Trigger change event to initialize the view based on the default checked radio button
    document.querySelector('input[name="interviewType"]:checked').dispatchEvent(new Event('change'));
</script>


<script>
    function toggleNotes(button) {
        const notesSection = button.closest('.history-entry').querySelector('.notes-section');
        const icon = button.querySelector('i');

        // Toggle visibility of the notes section
        notesSection.style.display = notesSection.style.display === 'none' ? 'block' : 'none';

        // Toggle caret icon direction
        icon.classList.toggle('fa-caret-down');
        icon.classList.toggle('fa-caret-up');
    }
</script>
<script>
    function toggleNotes(button) {
        const notesSection = button.closest('.history-entry').querySelector('.notes-section');
        notesSection.style.display = notesSection.style.display === 'none' ? 'block' : 'none';
    }

    function toggleEdit(button) {
        const notesSection = button.closest('.notes-section');
        const noteText = notesSection.querySelector('.note-text');
        const noteEdit = notesSection.querySelector('.note-edit');

        if (button.textContent.trim() === "Edit") {
            noteEdit.value = noteText.textContent.trim();
            noteText.style.display = 'none';
            noteEdit.style.display = 'block';
            button.textContent = "Save";
        } else {
            noteText.textContent = noteEdit.value;
            noteText.style.display = 'block';
            noteEdit.style.display = 'none';
            button.textContent = "Edit";
        }
    }
</script>

</body>

</html>