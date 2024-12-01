<?php
include('Database/login.php');
?>
<style>
    .swal2-popup {
        background-color: #003c3c !important;
        color: #fff !important;
    }

    .swal2-title,
    .swal2-html-container,
    .swal2-confirm {
        color: #fff !important;
    }

    .swal2-confirm {
        background-color: #003c3c !important;
    }

    .swal2-popup .swal2-success-line,
    .swal2-popup .swal2-error-line {
        background-color: #003c3c !important;
    }

    .swal2-popup .swal2-icon.swal2-success .swal2-success-ring {
        border-color: #fff !important;
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login into the site | SEDP HRMS</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="shortcut icon" href="Assets/Images/SEDPFavicon.png" type="image/x-icon">
    <!-- Custom CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="Assets/Css/index.css">
    <link rel="stylesheet" href="Assets/Css/loginStyle.css">
    <link rel="stylesheet" href="Assets/Css/inputLabel.css">


</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-opacity-75" style="background: linear-gradient(135deg, #001f1f, #003c3c, #004d4d);">
        <div class="container-fluid">
            <a class="navbar-brand text-white align-text-center fw-bolder fs-5" href="https://sedp.ph/" target="_blank">
                SEDP Simbag Sa Pag-Asenso Inc.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <i class="bi bi-list"></i>
                </span>
            </button>

            <!--navigation bar-->
            <div class="collapse navbar-collapse flex-row-reverse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" aria-current="page"
                            href="ScholarApplicant-Page/App/View/LandingPage.php">SCHOLAR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold"
                            href="./JobApplicantPage/">JOB</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold"
                            href="https://sedp.ph/about-us/">ABOUT</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Image Slider -->
    <div class="carousel-container">
        <div id="carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" style="display: none;" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" style="display: none;" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" style="display: none;" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" style="display: none;" data-bs-target="#carousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="4000">
                    <img src="Include/image/loginBg.jpg" class="d-block w-100 c-image" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                    <img src="Include/image/1.webp" class="d-block w-100 c-image" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                    <img src="Include/image/2.webp" class="d-block w-100 c-image" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                    <img src="Include/image/3.webp" class="d-block w-100 c-image" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                    <img src="Include/image/4.webp" class="d-block w-100 c-image" alt="...">
                </div>
            </div>
            <!-- next icon -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel"
                data-bs-slide="prev" style="display: none;">
                <span class="carousel-control-prev-icon"
                    aria-hidden="true" style="filter: invert(1);"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <!-- preview icon -->
            <button class="carousel-control-next" type="button" data-bs-target="#carousel"
                data-bs-slide="next" style="display: none;">
                <span class="carousel-control-next-icon"
                    aria-hidden="true" style="filter: invert(1);"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <?php
    include_once(__DIR__ . '/Assets/Php/login.php');
    ?>

    <?php include_once("Assets/Php/sweetAlert.php"); ?>
    <script src="Assets/Js/login.js"></script>
    <script src="Assets/Js/toggleEye.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>