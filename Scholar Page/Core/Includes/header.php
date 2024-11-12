<?php
include("../../../Database/database.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Default Title'; ?></title>

    <link rel="shortcut icon" href="../../../Assets/Images/SEDPfavicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Public/Assets/Css/header.css">
    <link rel="stylesheet" href="../../Public/Assets/Css/dashboard.css">
    <link rel="stylesheet" href="../../Public/Assets/Css/scholar_home.css">
    <link rel="stylesheet" href="../../Public/Assets/Css/compliance.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../Public/Assets/Css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: "Josefin Sans", sans-serif;
            background: linear-gradient(-135deg, #006666 0%, #009999 30%, #66cccc 100%);

        }
        .notification-container {
            display: none;
            position: fixed;
            right: 20px;
            top: 60px;
            width: 300px;
            height: 200px;
            background-image: linear-gradient(rgba(0, 60,60,0.9), rgba(0, 60, 60, 0.7)), url('../../Public/Assets/Images/notification.png');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            padding: 15px;
            color: #fff;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 1000;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header>
        <div class="hamburger" id="hamburger">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="../../App/View/scholar_home.php" class="sedp-link" style="display: flex; align-items: center;">
                        <?php include("svg.php"); ?>
                        <h4 style="padding-right: 20px; margin: 0; font-size: 16px; font-weight: 700;">SEDP HRMS</h4>
                    </a>
                </li>
                <li class="home <?php echo ($page === 'scholar home') ? 'active' : ''; ?>">
                    <a href="../../App/View/scholar_home.php">Home</a>
                </li>
                <li class="scholar-compliance <?php echo ($page === 'scholarcompliance') ? 'active' : ''; ?>">
                    <a href="../../App/View/scholar_compliance.php">Scholar Compliance</a>
                </li>
            </ul>
            <div class="profile">
                <a href="#" id="notification-link"><i class="fa-solid fa-bell"></i></a>
                <img src="../../Public/Assets/Images/profile.jpg" alt="Profile" id="profile-img">
                <i class="fa-solid fa-angle-down" id="dropdown-toggle" style="color: #fff;"></i>
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="../../App/View/scholar_profile.php"><i class="fa-solid fa-user"></i>&nbsp;Profile</a>
                    <a href="#"><i class="fa-solid fa-gear"></i>&nbsp;Settings</a>
                    <a href="../../../Assets/logout.php"><i class="fa-solid fa-sign-out-alt"></i>&nbsp;Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="notification-container" id="notificationContainer">
        <div class="notification-header text-center fw-bolder text-white">SEDP Notifications</div>
        <div class="text-white text-center">No new notifications at this time.</div>
    </div>

    <script>
        const notificationIcon = document.getElementById('notification-link');
        const notificationContainer = document.getElementById('notificationContainer');

        notificationIcon.addEventListener('click', function() {
            if (notificationContainer.style.display === 'none' || notificationContainer.style.display === '') {
                notificationContainer.style.display = 'block';
                setTimeout(() => {
                    notificationContainer.style.opacity = 1;
                }, 10);
            } else {
                notificationContainer.style.opacity = 0;
                setTimeout(() => {
                    notificationContainer.style.display = 'none';
                }, 300);
            }
        });

        window.addEventListener('click', function(event) {
            if (!notificationIcon.contains(event.target) && !notificationContainer.contains(event.target)) {
                notificationContainer.style.opacity = 0;
                setTimeout(() => {
                    notificationContainer.style.display = 'none';
                }, 300);
            }
        });

        const dropdownToggle = document.getElementById('dropdown-toggle');
        const profileImg = document.getElementById('profile-img');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const hamburger = document.getElementById('hamburger');

        function toggleDropdown() {
            dropdownToggle.classList.toggle('active');
            dropdownMenu.classList.toggle('active');
        }

        dropdownToggle.addEventListener('click', toggleDropdown);
        profileImg.addEventListener('click', toggleDropdown);

        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
