Create database "sedp_hrmsDB";

***************************Table structure for table `EmploymentType`**********************************
CREATE TABLE tblEmploymentType (
    employmentTypeId INT AUTO_INCREMENT PRIMARY KEY,  -- Primary Key (auto-incremented ID)
    employmentType VARCHAR(255) NOT NULL               -- Type of employment (e.g., full-time, part-time, etc.)
);
*****************************************************************************************************

***************************Table structure for table `Benefit`**********************************
CREATE TABLE tblBenefit (
    benefitId INT AUTO_INCREMENT PRIMARY KEY,          -- Primary Key (auto-incremented ID)
    benefit VARCHAR(255) NOT NULL                       -- Description of the benefit (e.g., health insurance, retirement plan, etc.)
);
*****************************************************************************************************

***************************Table structure for table `Job`***************************
CREATE TABLE tblJob (
    jobId INT AUTO_INCREMENT PRIMARY KEY,               -- Primary Key (auto-incremented ID)
    jobTitle VARCHAR(255) NOT NULL,                        -- Job title
    jobDescription TEXT NOT NULL,                          -- Job description
    jobQualification TEXT NOT NULL,                        -- Job qualification requirements
    jobKeyResponsibilities TEXT NOT NULL                   -- Key responsibilities of the job
);
****************************************************************************************

***************************Table structure for table `Department`**********************************
CREATE TABLE tblDepartment (
    departmentId INT AUTO_INCREMENT PRIMARY KEY,        -- Primary Key (auto-incremented ID)
    name VARCHAR(255) NOT NULL,                         -- Department name

    -- Foreign keys
    branchId INT,                                       -- Foreign Key to Branch table

    -- Constraints for Foreign Key 
    CONSTRAINT fk_branch FOREIGN KEY (branchId) REFERENCES tblBranch(branchId)
        ON DELETE SET NULL ON UPDATE CASCADE
);
****************************************************************************************
 
***************************Table structure for table `Branch`**********************************
CREATE TABLE tblBranch (
    branchId INT AUTO_INCREMENT PRIMARY KEY,            -- Primary Key (auto-incremented ID)
    name VARCHAR(255) NOT NULL,                         -- Branch name
    country  VARCHAR(100) NOT NULL,      
    region  VARCHAR(100) NOT NULL,  
    province  VARCHAR(100) NOT NULL,  
    city VARCHAR(100) NOT NULL
);
****************************************************************************************

***************************Table structure for table `JobPost`**********************************
CREATE TABLE tblJobPost (
    jobPostId INT AUTO_INCREMENT PRIMARY KEY,           -- Primary Key (auto-incremented ID)
    applicantSize INT NOT NULL,                         -- Number of applicants needed
    minimumSalary INT NOT NULL,                         -- Minimum salary offered
    maximumSalary INT NOT NULL,                         -- Maximum salary offered
    datePosted DATE NOT NULL DEFAULT CURRENT_DATE,      -- Date of posting
    jobPostDescription TEXT NOT NULL,
    jobPostKeyResponsibilities TEXT NOT NULL,
    jobPostQualification TEXT NOT NULL,
    expiryDate DATE,                                    -- Expiry date of the job post
 
    -- Foreign keys
    jobId INT,                                         -- Foreign Key to Job table
    departmentId INT,                                  -- Foreign Key to Department table
    employeeTypeId INT,                                -- Foreign Key to EmploymentType table

    -- Constraints for Foreign Keys
    CONSTRAINT fk_job FOREIGN KEY (jobId) REFERENCES tblJob(jobId)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_department FOREIGN KEY (departmentId) REFERENCES tblDepartment(departmentId)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_employeeType FOREIGN KEY (employeeTypeId) REFERENCES tblEmploymentType(employmentTypeId)
        ON DELETE SET NULL ON UPDATE CASCADE
);
****************************************************************************************

*************************** Table structure for table `tblJobPostBenefit` ************************
CREATE TABLE tblJobPostBenefit (
    jobPostId INT,                     -- Foreign Key to tblJobPost
    benefitId INT,                     -- Foreign Key to tblBenefit
    PRIMARY KEY (jobPostId, benefitId),
    CONSTRAINT fk_jobPost FOREIGN KEY (jobPostId) REFERENCES tblJobPost(jobPostId)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_benefit FOREIGN KEY (benefitId) REFERENCES tblBenefit(benefitId)
        ON DELETE CASCADE ON UPDATE CASCADE
);
****************************************************************************************


*************************Table structure for table `JobApplicant`***************************

CREATE TABLE tbljobapplicant (
    applicantId INT AUTO_INCREMENT PRIMARY KEY,
    uniqueId VARCHAR(255) NOT NULL UNIQUE,          -- Unique ID for each applicant
    name VARCHAR(255) NOT NULL,                     -- Applicant's name (combined first, middle, last name)
    email VARCHAR(255) NOT NULL, 
    contactNumber VARCHAR(50) NOT NULL,             -- Contact number of the applicant
    appliedDate DATE NOT NULL,                      -- Date when the application was submitted
    formFileName VARCHAR(255),                      -- Name of the uploaded form file (stored on server)
    formfileSize INT,                               -- Size of the form file in bytes
    formfileType VARCHAR(50),                       -- MIME type of the form file (e.g., application/pdf)

    letterFileName VARCHAR(255),                    -- Name of the uploaded letter file (stored on server)
    letterFileSize INT,                         -- Size of the letter file in bytes
    letterFileType VARCHAR(50),                     -- MIME type of the letter file (e.g., application/pdf)
    
    photoFileName VARCHAR(255) DEFAULT NULL,        -- Name of the applicant's photo file (stored on server)
    photoFileSize INT DEFAULT NULL,                 -- Size of the photo file in bytes
    photoFileType VARCHAR(50) DEFAULT NULL,         -- MIME type of the photo file (e.g., image/jpeg)
    
    status VARCHAR(50) DEFAULT 'Pending',           -- Status of the application (default: 'Pending')
    jobPostId INT,                                 -- Foreign key referencing job offer ID

    -- Constraints for FK
    CONSTRAINT fk_job_post FOREIGN KEY (jobPostId) REFERENCES tblJobPost(jobPostId)
        ON DELETE SET NULL ON UPDATE CASCADE

);
****************************************************************************************


--this is the join i use for job post cards
SELECT 
    j.title,
    j.description AS  Responsibility,
    j.qualification,
    j.minimumSalary,
    j.maximumSalary,
    jo.employmentType,
    jo.datePosted,
    b.location AS branchLocation
FROM 
    tbljobOffer jo
JOIN 
    tbljob j ON jo.jobId = j.jobId
JOIN 
    tbldepartment d ON jo.departmentId = d.departmentId
JOIN 
    tblbranch b ON d.branchId = b.branchId;
