<?php
$title = 'Scholar Compliance | SEDP HRMS';
$page = 'compliance';
include('../../Core/Includes/header.php');
?>
<div class="main-container p-4">

    <div class="container" style="margin-top: 4rem;">
            <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="compliance-card p-5 shadow-sm bg-white rounded position-relative" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 6px solid #00bfa5; background: #f8f9fa; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-container text-center me-4">
                            <a href="narrative_report_month.php"> <img src="../../Public/Assets/Images/narrative_report.png" alt="Narrative Report Folder" style="height: 110px;"></a>
                        </div>
                        <div>
                            <a href="narrative_report_month.php" style="text-decoration: none;">
                                <h5 style="color: #003c3c; font-weight: 700;">Narrative Reports</h5>
                            </a>
                            <p class="text-muted mb-1" style="font-size: 14px;">Monthly</p>
                            <p class="text-muted mb-0" style="font-size: 14px;">50% submitted</p>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 50%; background: linear-gradient(45deg, #00c853, #b2ff59);"></div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="compliance-card p-5 shadow-sm bg-white rounded position-relative" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 6px solid #00a892; background: #f8f9fa; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-container text-center me-4">
                            <a href="load_expenses_month.php"><img src="../../Public/Assets/Images/load_expenses.png" alt="Load Expenses Folder" style="height: 110px;"></a>
                        </div>
                        <div>
                            <a href="load_expenses_month.php" style="text-decoration: none;">
                                <h5 style="color: #003c3c;">Load Expenses</h5>
                            </a>
                            <p class="text-muted mb-1" style="font-size: 14px;">Monthly</p>
                            <p class="text-muted mb-0" style="font-size: 14px;">25% submitted</p>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%; background: linear-gradient(45deg, #00796b, #80cbc4);"></div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6">
                <div class="compliance-card p-5 shadow-sm bg-white rounded position-relative" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 6px solid #00907f; background: #f8f9fa; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-container text-center me-4">
                            <a href="book_expenses_month.php"><img src="../../Public/Assets/Images/book_expenses.png" alt="Books Expenses Folder" style="height: 110px;"></a>
                        </div>
                        <div>
                            <a href="book_expenses_month.php" style="text-decoration: none;">
                                <h5 style="color: #003c3c;">Book Expenses</h5>
                            </a>
                            <p class="text-muted mb-1" style="font-size: 14px;">Monthly</p>
                            <p class="text-muted mb-0" style="font-size: 14px;">25% submitted</p>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%; background: linear-gradient(45deg, #004d40, #4db6ac);"></div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6">
                <div class="compliance-card p-5 shadow-sm bg-white rounded position-relative" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 6px solid #00796b; background: #f8f9fa; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-container text-center me-4">
                            <a href="thesis_expenses_month.php"><img src="../../Public/Assets/Images/thesis_expenses.png" alt="Thesis Expenses Folder" style="height: 110px;"></a> <!-- Increased font size -->
                        </div>
                        <div>
                            <a href="thesis_expenses_month.php" style="text-decoration: none;">
                                <h5 style="color: #003c3c;">Thesis Expenses</h5>
                            </a>
                            <p class="text-muted mb-1" style="font-size: 14px;">Monthly</p>
                            <p class="text-muted mb-0" style="font-size: 14px;">25% submitted</p>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%; background: linear-gradient(45deg, #00251a, #26a69a);"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="compliance-card p-5 shadow-sm bg-white rounded position-relative" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 6px solid #006257; background: #f8f9fa; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-container text-center me-4">
                            <a href="registration_month.php"><img src="../../Public/Assets/Images/cert_registration.png" alt="Certificate of Registration Folder" style="height: 110px;"></a>
                        </div>
                        <div>
                            <a href="registration_month.php" style="text-decoration: none;">
                                <h5 style="color: #003c3c;">Certificate of Registration</h5>
                            </a>
                            <p class="text-muted mb-1" style="font-size: 14px;">Monthly</p>
                            <p class="text-muted mb-0" style="font-size: 14px;">25% submitted</p>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%; background: linear-gradient(45deg, #00251a, #26a69a);"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="compliance-card p-5 shadow-sm bg-white rounded position-relative" style="transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 6px solid #004b43; background: #f8f9fa; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-container text-center me-4">
                            <a href="grade_month.php"><img src="../../Public/Assets/Images/cert_grade.png" alt="Certificate of Grade Folder" style="height: 110px;"></a>
                        </div>
                        <div>
                            <a href="grade_month.php" style="text-decoration: none;">
                                <h5 style="color: #003c3c;">Certificate of Grade</h5>
                            </a>
                            <p class="text-muted mb-1" style="font-size: 14px;">Monthly</p>
                            <p class="text-muted mb-0" style="font-size: 14px;">25% submitted</p>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%; background: linear-gradient(45deg, #00251a, #26a69a);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../Core/Includes/footer.php'); ?>