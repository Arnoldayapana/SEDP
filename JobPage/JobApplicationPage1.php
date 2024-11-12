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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'poppins', sans-serif;
        }
    </style>

    <!--Nav-->
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

    <section>
        <div class="container mb-5 mt-4 bg-light p-3">
            <nav aria-label="breadcrumb" class="my-0.5">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="Jobpage.php">Job Lists</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Job Application</li>
                </ol>
            </nav>

            <?php
            // Initialize variables to avoid undefined variable warnings
            $name = "";
            $email = "";
            $contact = "";
            $message = "";
            ?>
            <div class="bg-white m-2 p-3">
                <form action="../Admin Page/App/Dao/JobApplicants-db/JobApplicantController.php" method="POST">
                    <input type="hidden" id="job_id" name="job_id" value="<?php echo $_GET['job_id']; ?>"> <!-- Hidden field for job_id -->

                    <!-- Step 1: Personal Information -->

                    <div id="step1" class="form-step">
                        step 1
                        <!-- Instruction -->
                        <div>
                            <p>INSTRUCTION: Please print all information requested except signature </p>
                        </div>

                        <!-- Position Applied -->
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Position applied for</label>
                            <div class="col-sm-5">
                                <input type="email" class="form-control" id="inputEmail3">
                            </div>
                        </div>
                        <!-- Name Input -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-1 col-form-label" class="form-label">Name</label>
                            <div class="col-sm-4">
                                <input name="name" type="text" class="form-control" placeholder="Last Name" aria-label="Last name">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="First Name" aria-label="First name">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="Middle Name" aria-label="Middle name">
                            </div>
                        </div>
                        <!-- Permanent Address Input -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-1 col-form-label" class="form-label">Permanent Address</label>
                            <div class="col-sm ">
                                <input type="text" class="form-control" placeholder="Street" aria-label="City">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Barangay" aria-label="City">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Municipality" aria-label="State">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="City" aria-label="Zip">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Zip Code" aria-label="Zip">
                            </div>
                        </div>

                        <!-- Present Address Input -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-1 col-form-label" class="form-label">Present Address</label>
                            <div class="col-sm ">
                                <input type="text" class="form-control" placeholder="Street" aria-label="City">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Barangay" aria-label="City">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Municipality" aria-label="State">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="City" aria-label="Zip">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Zip Code" aria-label="Zip">
                            </div>
                        </div>

                        <!-- DateOfBirth, Age, Gender, Religion Input -->
                        <div class="row mb-3">
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Date Of Birth" aria-label="City">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Age" aria-label="State">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Gender" aria-label="Zip">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Religion" aria-label="Zip">
                            </div>
                        </div>

                        <!-- Civil Status, Single Parent, Live in Input -->
                        <div class="row mb-3">
                            <div class="col-sm">
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Civil Status</option>
                                    <option value="1">Married</option>
                                    <option value="2">Separated</option>
                                    <option value="3">Single</option>
                                </select>
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Single Parent" aria-label="State">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Live in" aria-label="Zip">
                            </div>
                        </div>
                        <!-- IF Married: Civil ,Church input -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label" class="form-label">If Married:</label>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Civil" aria-label="State">
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" placeholder="Church" aria-label="Zip">
                            </div>
                        </div>

                        <!-- Sacrament of Baptism input -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-5 col-form-label" class="form-label">Have you recieved the Sacrament of Baptism:</label>
                            <div class="col-sm">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- contact number,email inputs -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="name" class="col-sm col-form-label" class="form-label">Telephone/Cellular Number</label>
                                <div class="col-sm">
                                    <input name="name" type="text" class="form-control" placeholder="" aria-label="Last name">
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="name" class="col-sm col-form-label" class="form-label">Email Address</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" aria-label="Middle name">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
                    </div>

                    <!-- Step 2: about organization  -->
                    <div id="step2" class="form-step" style="display:none;">
                        Step 2
                        <!-- knowledge about the position -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">How did you learn about the position</label>
                            <textarea class="form-control" name="message" placeholder="..." rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <!-- Referred input -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="name" class="col-sm col-form-label" class="form-label">Referred by</label>
                                <div class="col-sm">
                                    <input name="name" type="text" class="form-control" placeholder="name" aria-label="Last name">
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="name" class="col-sm col-form-label" class="form-label">Agency</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="name" aria-label="Middle name">
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="name" class="col-sm col-form-label" class="form-label">RadioAd</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="Station" aria-label="Middle name">
                                </div>
                            </div>
                        </div>
                        <!-- Applied Previously input -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-7">
                                <label for="name" class="col-sm-6 col-form-label" class="form-label">Have you applied previously to SEDP</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <label for="dob" class="col-sm col-form-label">If yes, date</label>
                                <div class="col-sm">
                                    <input type="date" class="form-control" id="dob" name="dob">
                                </div>
                            </div>
                        </div>
                        <!--  input -->
                        <div class="row mb-3 align-items-center">
                            Family members/relatives working with Social Action Center(SAC) or SIMBAG SA PAG-ASENSO INC. (if any)
                        </div>

                        <!-- names-relationship 1-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm-6 col-form-label" class="form-label">Names</label>
                                <div class="col-sm mb-2">
                                    <input type="email" class="form-control" placeholder="Name1" id="inputEmail3">
                                </div>
                                <div class="col-sm">
                                    <input type="email" class="form-control" placeholder="Name2" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm-6 col-form-label " class="form-label">Relationship</label>
                                <div class="col-sm mb-2">
                                    <input type="email" class="form-control" placeholder="Relationship1" id="inputEmail3">
                                </div>
                                <div class="col-sm">
                                    <input type="email" class="form-control" placeholder="Relationship2" id="inputEmail3">
                                </div>
                            </div>
                        </div>

                        <!--  input -->
                        <div class="row mb-3 align-items-center">
                            Family members/relatives working with Bank/Microfinance institution or other lending institution
                        </div>

                        <!-- names-relationship 1-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm-6 col-form-label" class="form-label">Names</label>
                                <div class="col-sm mb-2">
                                    <input type="email" class="form-control" placeholder="Name1" id="inputEmail3">
                                </div>
                                <div class="col-sm">
                                    <input type="email" class="form-control" placeholder="Name2" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm-6 col-form-label " class="form-label">Company/Institution</label>
                                <div class="col-sm mb-2">
                                    <input type="email" class="form-control" placeholder="Company/Institution1" id="inputEmail3">
                                </div>
                                <div class="col-sm">
                                    <input type="email" class="form-control" placeholder="Company/Institution2" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm-6 col-form-label " class="form-label">Relationship</label>
                                <div class="col-sm mb-2">
                                    <input type="email" class="form-control" placeholder="Relationship1" id="inputEmail3">
                                </div>
                                <div class="col-sm">
                                    <input type="email" class="form-control" placeholder="Relationship2" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- drivers license input -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-7">
                                <label for="name" class="col-sm-6 col-form-label" class="form-label">Do you have a valid driver's license</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <label for="dob" class="col-sm col-form-label">If yes, date</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">Professional</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Non-Professional</label>
                                </div>
                            </div>
                        </div>

                        <!-- position applied input -->
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">What is your desired salary</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="inputEmail3">
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">Next</button>
                    </div>

                    <!-- Step 3: Education -->
                    <div id="step3" class="form-step" style="display:none;">
                        Step 3:Education
                        <!-- post graduate - year graduate-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Post Graduate:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="post graduate" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Year Graduate:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="year graduate" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- bachelors degree - year graduate-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Bachelor's degree:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="bachelors degree" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Year Graduate:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="year graduate" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- vocational/non-formal - year graduate-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Vocational/Non-Formal</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="vocational/non-formal" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Year Graduate:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="year graduate" id="inputEmail3">
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">Next</button>
                    </div>

                    <!--  step 4 Employment History-->
                    <div id="step4" class="form-step" style="display:none;">

                        step 4

                        <!-- employer - position held-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Employer(most recent):</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="employer" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Position held:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="position held" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- address-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Address:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="address" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!--supervisor name - contact number-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Supervisor's Name/Position Title:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Tel/CP No:</label>
                                <div class="col-sm">
                                    <input type="number" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!--date employed,from - salary -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Date Employed: From:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Salary:</label>
                                <div class="col-sm">
                                    <input type="number" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- Job summary Input -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Job Summary</label>
                            <textarea class="form-control" name="message" placeholder="job summary" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <!-- Reason for leaving Input -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Reason for Leaving</label>
                            <textarea class="form-control" name="message" placeholder="reason" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <br><br>
                        <hr>
                        <br>
                        <!-- employer - position held-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Employer:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="employer" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Position held:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="position held" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- address-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Address:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="address" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!--supervisor name - contact number-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Supervisor's Name/Position Title:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Tel/CP No:</label>
                                <div class="col-sm">
                                    <input type="number" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!--date employed,from - salary -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Date Employed: From:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Salary:</label>
                                <div class="col-sm">
                                    <input type="number" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- Job summary Input -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Job Summary</label>
                            <textarea class="form-control" name="message" placeholder="job summary" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <!-- Reason for leaving Input -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Reason for Leaving</label>
                            <textarea class="form-control" name="message" placeholder="reason" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>

                        <br><br>
                        <hr>
                        <br>
                        <!-- employer - position held-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Employer:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="employer" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Position held:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="position held" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- address-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Address:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="address" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!--supervisor name - contact number-->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Supervisor's Name/Position Title:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Tel/CP No:</label>
                                <div class="col-sm">
                                    <input type="number" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!--date employed,from - salary -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label" class="form-label">Date Employed: From:</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                            <div class="col-sm align-items-center">
                                <label for="name" class="col-sm col-form-label " class="form-label">Salary:</label>
                                <div class="col-sm">
                                    <input type="number" class="form-control" placeholder="" id="inputEmail3">
                                </div>
                            </div>
                        </div>
                        <!-- Job summary Input -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Job Summary</label>
                            <textarea class="form-control" name="message" placeholder="job summary" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <!-- Reason for leaving Input -->
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Reason for Leaving</label>
                            <textarea class="form-control" name="message" placeholder="reason" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="prevStep(3)">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(5)">Next</button>
                    </div>

                    <!--  step 5 Medical History-->
                    <div id="step5" class="form-step" style="display:none;">

                        step 5
                        <!-- state of health -->
                        <div class="row mb-3 align-items-center">
                            <div class="col">
                                <label for="name" class="col-sm-4 col-form-label" class="form-label">Describe your current state of health: </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">Very Good</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Good</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Average</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Poor</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Pregnant(female only)</label>
                                </div>
                            </div>
                        </div>
                        <!--list if ilnesses -->
                        <div class="row mb-3 align-items-center">
                            <?php
                            $illnesses = [
                                ["name" => "Allergy", "hasIllness" => !empty($_POST['allergy']) ? true : false],
                                ["name" => "ThyroidDisease", "hasIllness" => !empty($_POST['thyroidDisease']) ? true : false],
                                ["name" => "ChestOrHeartProblem", "hasIllness" => !empty($_POST['chestOrHeartProblem']) ? true : false],
                                ["name" => "FrequentHeadache", "hasIllness" => !empty($_POST['frequentHeadache']) ? true : false],
                                ["name" => "EyeTrouble", "hasIllness" => !empty($_POST['eyeTrouble']) ? true : false],
                                ["name" => "HeadOrNeckInjury", "hasIllness" => !empty($_POST['headOrNeckInjury']) ? true : false],
                                ["name" => "AbdominalTrouble", "hasIllness" => !empty($_POST['abdominalTrouble']) ? true : false],
                                ["name" => "AnyRepartriation", "hasIllness" => !empty($_POST['anyRepartriation']) ? true : false],
                                ["name" => "Arthritis", "hasIllness" => !empty($_POST['arthritis']) ? true : false],
                                ["name" => "DiabetesMellitus", "hasIllness" => !empty($_POST['diabetesMellitus']) ? true : false],
                                ["name" => "BloodDisorder", "hasIllness" => !empty($_POST['bloodDisorder']) ? true : false],
                                ["name" => "GeneticDisorder", "hasIllness" => !empty($_POST['geneticDisorder']) ? true : false],
                                ["name" => "TyphoidFever", "hasIllness" => !empty($_POST['typhoidFever']) ? true : false],
                                ["name" => "FaintingOrSeizure", "hasIllness" => !empty($_POST['faintingOrSeizure']) ? true : false],
                                ["name" => "UrinaryTrouble", "hasIllness" => !empty($_POST['urinaryTrouble']) ? true : false],
                                ["name" => "Asthma", "hasIllness" => !empty($_POST['asthma']) ? true : false],
                                ["name" => "PulmonaryTuberculosis", "hasIllness" => !empty($_POST['pulmonaryTuberculosis']) ? true : false],
                                ["name" => "LiverOrGallBladderDisease", "hasIllness" => !empty($_POST['liverOrGallBladderDisease']) ? true : false],
                                ["name" => "PsychiatricDisorder", "hasIllness" => !empty($_POST['psychiatricDisorder']) ? true : false],
                                ["name" => "EarTrouble", "hasIllness" => !empty($_POST['earTrouble']) ? true : false],
                                ["name" => "EndocrineDisorder", "hasIllness" => !empty($_POST['endocrineDisorder']) ? true : false],
                                ["name" => "ChronicCough", "hasIllness" => !empty($_POST['chronicCough']) ? true : false],
                                ["name" => "STD", "hasIllness" => !empty($_POST['std']) ? true : false]
                            ];

                            foreach ($illnesses as $illness) {
                                $checked = $illness['hasIllness'] ? 'checked' : '';
                                $illnessId = strtolower($illness['name']); // Generate lowercase id
                                echo "
                                    <div class='col-sm-4'>
                                        <div class='form-check'>
                                            <input class='form-check-input' type='checkbox' name='{$illnessId}' id='{$illnessId}' {$checked}>
                                            <label class='form-check-label' for='{$illnessId}'>
                                                {$illness['name']}
                                            </label>
                                        </div>
                                    </div>
                                ";
                            }
                            ?>
                        </div>
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Have you undergone surgeries/operation/s? Please specify:</label>
                            <textarea class="form-control" name="message" placeholder="" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="message" class="form-label">Medical illness taking maintenance medication</label>
                            <textarea class="form-control" name="message" placeholder="" rows="2" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="prevStep(4)">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(6)">Next</button>
                    </div>

                    <!-- last step -->
                    <div id="step6" class="form-step" style="display:none;">
                        <!-- Form Submit -->
                        <div class="form-group mb-3">
                            <button type="button" class="btn btn-secondary" onclick="prevStep(5)">Previous</button>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>
    <!-- Include the multistepform.js script -->
    <script src="../Assets//Js//multistepform.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>