<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Default Title'; ?></title>

    <link rel="shortcut icon" href="../../Public/Assets/Images/SEDPfavicon.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Public/Assets/Css/sidebar.css">
    <link rel="shortcut icon" href="../../Public/Images/SEDPfavicon.png" type="image/x-icon">

    <!-- CKEditor Initialization Scripts -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 600px;
        }

        .modal-content h5 {
            margin-bottom: 20px;
        }

        .modal-content button {
            margin: 10px 0;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex" id="sideheader">
                <button class="toggle-btn" type="button">
                    <img class="logo" src="../../../Assets/Images/SEDPlogo.jpg" alt="">
                </button>
                <div class="sidebar-logo">
                    <a href="#">SEDP HRMS</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../../App/View/AdminDashboard.php" class="sidebar-link">
                        <i class="lni lni-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-link">
                    <p>Human Resource MS</p>
                </li>
                <li class="sidebar-item">
                    <a href="../../App/View/Employee.php" class="sidebar-link">
                        <i class="lni lni-users"></i>
                        <span>Employees</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth1" aria-expanded="false" aria-controls="auth1">
                        <i class="bi bi-search"></i>
                        <span>Recruitment</span>
                    </a>
                    <ul id="auth1" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="../../App/View/RecruitmentSetup.php" class="sidebar-link mx-4">Recruitment Setup</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/JobPosting.php" class="sidebar-link mx-4">Job Posting</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/JobApplicants.php" class="sidebar-link mx-4">Applicants</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/JobApplicants2.php" class="sidebar-link mx-4">Applicants2</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-protection"></i>
                        <span>Branches</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="../../App/View/Branch.php" class="sidebar-link mx-4">Branch</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/Department.php" class="sidebar-link mx-4">Department</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-link">
                    <p>Scholarship MS</p>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="lni lni-graduation"></i>
                        <span>Scholar</span>
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="../../App/View/recipients.php" class="sidebar-link mx-4">Scholars</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/Program.php" class="sidebar-link mx-4">Programs</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/Compliance.php" class="sidebar-link mx-4">Compliance</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/ScholarApplicant.php" class="sidebar-link mx-4">Scholar Applicants</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-link">
                    <p>Archives</p>
                </li>
                <li class="sidebar-item">
                    <a href="#"
                        class="sidebar-link has-dropdown"
                        data-bs-toggle="collapse"
                        data-bs-target="#archives"
                        aria-controls="archives"
                        aria-expanded="false">
                        <i class="bi bi-archive"></i>
                        <span>Archive</span>
                    </a>
                    <ul id="archives" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="../../App/View/Employee-Archive.php" class="sidebar-link mx-4">Employee</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../App/View/Scholar-Archive.php" class="sidebar-link mx-4">Scholar</a>
                        </li>
                    </ul>

            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link" onclick="showModal(event)">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
    </div>

    <div id="confirmationModal" class="modal-overlay" style="display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;">
        <div class="modal-content" style="width: 400px;">
            <h5>Are you sure you want to logout?</h5>
            <button class="btn btn-secondary" onclick="hideModal()">Cancel</button>
            <a href="../../../index.php" class="btn" style="background: #003c3c; color: #fff; font-weight: 600;" id=" confirmLogout">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


    <script>
        const hamBurger = document.querySelector(".toggle-btn");
        const sidebar = document.querySelector("#sidebar");

        if (localStorage.getItem("sidebarState") === "expanded") {
            sidebar.classList.add("expand");
        }

        hamBurger.addEventListener("click", function() {
            sidebar.classList.toggle("expand");

            if (sidebar.classList.contains("expand")) {
                localStorage.setItem("sidebarState", "expanded");
            } else {
                localStorage.setItem("sidebarState", "collapsed");
            }
        });
    </script>
</body>

</html>