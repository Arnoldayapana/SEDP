<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("location:../../index.php");
    exit();
}

$username = $_SESSION['username'];
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}


$possibleNames = preg_split('/[._]/', $username);

if (preg_match('/([a-z]+)([A-Z][a-z]+)/', $username)) {

    $firstName = preg_replace('/([a-z])([A-Z])/', '$1 $2', $username);
    $firstName = explode(' ', $firstName)[0];
} elseif (count($possibleNames) > 1) {

    $firstName = $possibleNames[0];
} else {

    $firstName = $username;
}


$firstName = ucfirst(strtolower($firstName));



$title = 'Employee Home | SEDP HRMS';
$page = 'employee home';
include('../../Core/Includes/header.php');
?>

<style>
    .custom-list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .progress-bar {
        background-color: #003c3c;
    }

    .announcement-image {
        width: 100%;
        /* Make the container span the full width */
        height: 300px;
        /* Set a height for the container (adjust as needed) */
        background-size: cover;
        /* Ensures the background image covers the full container */
        background-position: center;
        /* Centers the image inside the container */
        background-repeat: no-repeat;
        /* Prevents tiling of the image */
        border-radius: 4px;
        margin-top: 3rem;
        /* Rounded corners */
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .announcement-image {
            height: 250px;
            /* Reduce the height for smaller screens */
        }
    }

    @media (max-width: 480px) {
        .announcement-image {
            height: 200px;
            /* Further reduce the height for mobile screens */
        }
    }

    .announcement-content {
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
    }

    .announcement-image {
        width: 100%;
        height: 300px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 4px;
    }
</style>
</head>

<body>

    <div class="container">
        <div class="row" style="margin-top: 5.5rem;">
            <div class="col-md-4">
                <div class="dashboard-card" style="background-color: #f0f9f9;">
                    <div class="icon-section-title">
                        <i class="fa-solid fa-user"></i>
                        <span style="font-weight: 700; font-size: 25px;">Good Day, <?php echo $firstName; ?>!</span>
                    </div>
                    <span style="padding-left: 2.3rem; font-weight: 600; font-size: 16px;">Welcome Back!</span>
                </div>

                <div class="dashboard-card" style="background-color: #f0f9f9;">
                    <h5 class="section-title">Calendar</h5>
                    <div id="calendar" class="event-calendar" style="background-color: #003c3c; color: #fff;"></div>
                </div>

                <div class="dashboard-card" style="background-color: #f0f9f9;">
                    <h6 style="font-weight: 700;">You can now change your email and and other personal details.</h6>
                    <p style="font-weigth: 700; font-size: 13px;">If you want to recover your password or receive a personalized notification, change your email address or other personal details.</p>
                    <a href="employee_profile.php"><button style="color: #fff; background: #003c3c; border: none; font-size: 13px; font-weight: 700; padding: 10px 25px; border-radius: 5px;">Go to Profile</button></a>
                </div>
            </div>

            <div class="col-md-8">
                <div class="dashboard-card pb-5" style="height: 96%; position: relative; background-color: #f0f9f9;">
                    <div class="custom-card-header bg-white shadow rounded border-dark">
                        <div class="admin-profile">
                            <img src="../../Public/Assets/Images/profile.jpg" alt="Admin Profile">
                            <div>
                                <strong style="font-size: 20px;">System Administrator</strong><br>
                                <?php
                                include("../../../Database/db.php");

                                $sql = "SELECT announcement_id, title, content, image, created_at 
                                FROM announcements  WHERE audience = 'employee' OR audience = 'both'
                                ORDER BY created_at DESC 
                                LIMIT 1";

                                $result = $connection->query($sql);


                                if ($result->num_rows > 0) {

                                    $row = $result->fetch_assoc();
                                    $created_at = $row['created_at'];

                                    echo "
                                    <strong class='text-muted' id='postedTime' style='font-size: 10px;' data-time='" . htmlspecialchars($created_at) . "'>Posted: <span id='timeAgo'></span></strong>
                                    <script>
                                        function timeAgo() {
                                            const postedDate = new Date(document.getElementById('postedTime').getAttribute('data-time'));
                                            const now = new Date();
                                            const interval = Math.floor((now - postedDate) / 1000); // Get time in seconds

                                            let timeAgo = '';
                                            if (interval < 60) {
                                                timeAgo = 'just now';
                                            } else if (interval < 3600) { // Less than an hour
                                                const minutes = Math.floor(interval / 60);
                                                timeAgo = minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
                                            } else if (interval < 86400) { // Less than a day
                                                const hours = Math.floor(interval / 3600);
                                                timeAgo = hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
                                            } else { // More than a day
                                                const days = Math.floor(interval / 86400);
                                                timeAgo = days + ' day' + (days > 1 ? 's' : '') + ' ago';
                                            }
                                            
                                            document.getElementById('timeAgo').innerText = timeAgo;
                                        }

                                        // Update the timeAgo on load and then every minute
                                        timeAgo();
                                        setInterval(timeAgo, 60000);
                                    </script>
                                    ";
                                } else {
                                    echo "<p>No announcements found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                        <span class="floating-announcement">Announcement</span>
                    </div>
                    <div class="announcement shadow rounded p-4 mt-3 bg-white">
                        <?php
                        $sql = "SELECT announcement_id, title, content, image, created_at 
                        FROM announcements WHERE audience = 'employee' OR audience = 'both'
                        ORDER BY created_at DESC 
                        LIMIT 1";

                        $result = $connection->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $title = $row['title'];
                            $content = $row['content'];
                            $image = $row['image'];

                            if (!empty($image)) {
                                $imagePath = '../Uploads/images/' . $image;
                            } else {
                                $imagePath = '../Uploads/images/announcement.png';
                            }

                            echo "
                            <h1 class='fs-4 p-2 m-1 fw-bold'>" . htmlspecialchars($title) . "</h1>
                            <p class='announcement-content m-2'>" . htmlspecialchars($content) . "</p>
                            <div class='announcement-image' style='background-image: url(" . htmlspecialchars($imagePath) . ");'>
                            </div>
                            ";
                        } else {
                            echo "<p>No announcements found.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 shadow rounded p-4 mb-4" style="background-color: #f0f9f9;">
            <h5 class="text-center" style="color: #003c3c; font-weight: bold; letter-spacing: 1px;">User Activity Over Time</h5>
            <canvas id="userActivityChart"></canvas>
        </div>

    </div>
    <div class="container-fluid bg-dark text-center text-light" style="padding: 10px 0;">
        <div class="footer-content" style="min-height: 100px; line-height: 30px;">
            <p class="mb-2">&copy; 2024 Your Organization. All Rights Reserved.</p>

            <ul class="list-inline mb-2">
                <li class="list-inline-item"><a href="https://sedp.ph/about-us/" class="text-light">About Us</a></li>
                <li class="list-inline-item"><a href="https://sedp.ph/services/" class="text-light">Services</a></li>
                <li class="list-inline-item"><a href="/privacy-policy" class="text-light">Privacy Policy</a></li>
                <li class="list-inline-item"><a href="/terms-of-service" class="text-light">Terms of Service</a></li>
            </ul>

            <p class="mb-2">Contact Us: <a href="mailto:mfi@sedp.ph " class="text-light">simbag_sedp@yahoo.com</a></p>

            <div class="social-media-links mb-2">
                <a href="https://web.facebook.com/sedp.ph" target="_blank" class="mx-2 text-light"><i class="fa fa-facebook"></i></a>
                <a href="https://twitter.com/yourprofile" class="mx-2 text-light"><i class="fa fa-twitter"></i></a>
                <a href="https://linkedin.com/in/yourprofile" class="mx-2 text-light"><i class="fa fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Public/Assets/Js/lineGraph.js"></script>
    <script src="../../Public/Assets/Js/calendar.js"></script>

    </script>
</body>

</html>