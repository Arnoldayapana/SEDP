<?php

class InterviewJobApplicantModel
{
    private $pdo;

    public function __construct()
    {
        include_once(__DIR__ . '/../../../Database/database.php');
        $database = new Database();
        $this->pdo = $database->connect();
    }
    public function createInterviewApplicant($data)
    {
        $query = "INSERT INTO tblInterviewJobApplicant 
              (uniqueId, name, email, contactNumber, appliedDate, 
               formFileName, formfileSize, formfileType, 
               letterFileName, letterFileSize, letterFileType, 
               photoFileName, photoFileSize, photoFileType, 
               jobPostId, interviewStageDate, interviewDatetime) 
              VALUES 
              (:uniqueId, :name, :email, :contactNumber, :appliedDate, 
               :formFileName, :formfileSize, :formfileType, 
               :letterFileName, :letterFileSize, :letterFileType, 
               :photoFileName, :photoFileSize, :photoFileType, 
               :jobPostId, :interviewStageDate, :interviewDatetime)";

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($data);
    }

    public function getApplicantbyId($interviewApplicantId)
    {
        $sql = "SELECT 
        -- From tblInterviewJobApplicant
        ja.interviewApplicantId,
        ja.uniqueId,
        ja.name AS applicantName,
        ja.email,
        ja.contactNumber,
        ja.appliedDate,
        ja.formFileName,
        ja.formFileSize,
        ja.formFileType,
        
        ja.letterFileName,
        ja.letterFileSize,
        ja.letterFileType,
    
        ja.photoFileName,
        ja.photoFileSize,
        ja.photoFileType,
    
        ja.status,
        ja.jobPostId,
        ja.interviewDatetime,
        ja.isViewed,
    
        -- From tblJobPost
        jo.jobPostId AS jobPostId,  -- Aliased to avoid conflicts
        jo.jobId,
        jo.departmentId,
    
        -- From tblJob
        j.jobId,
        j.jobTitle,
    
        -- From tblDepartment
        d.departmentId,
        d.name AS departmentName,
        d.branchId,
    
        -- From tblBranch
        b.branchId,
        b.name AS branchName,
        b.country,
        b.region,
        b.province,
        b.city
    
    FROM 
        tblInterviewJobApplicant ja
    JOIN 
        tblJobPost jo ON ja.jobPostId = jo.jobPostId
    JOIN 
        tblJob j ON jo.jobId = j.jobId
    JOIN 
        tblDepartment d ON jo.departmentId = d.departmentId
    JOIN 
        tblBranch b ON d.branchId = b.branchId
    WHERE ja.interviewApplicantId = ?";  // Updated WHERE clause

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$interviewApplicantId]);

        $applicantDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        return $applicantDetails ?: false;
    }

    public function getJobApplicantsWithFilters($filter, $search)
    {
        $sql = "SELECT 
        -- From tblJobApplicant
        ja.interviewApplicantId,
        ja.uniqueId,
        ja.name AS applicantName,
        ja.email,
        ja.contactNumber,
        ja.appliedDate,
        ja.formFileName,
        ja.formFileSize,
        ja.formFileType,
        
        ja.letterFileName,
        ja.letterFileSize,
        ja.letterFileType,

        ja.photoFileName,
        ja.photoFileSize,
        ja.photoFileType,

        ja.status,
        ja.jobPostId,
        ja.isViewed,

        -- From tblJobPost
        jo.jobPostId AS jobPostId,  -- Aliased to avoid conflicts
        jo.jobId,
        jo.departmentId,

        -- From tblJob
        j.jobId,
        j.jobTitle,

        -- From tblDepartment
        d.departmentId,
        d.name AS departmentName,
        d.branchId,

        -- From tblBranch
        b.branchId,
        b.name AS branchName,
        b.country,
        b.region,
        b.province,
        b.city

    FROM 
        tblinterviewjobapplicant ja
    JOIN 
        tblJobPost jo ON ja.jobPostId = jo.jobPostId
    JOIN 
        tblJob j ON jo.jobId = j.jobId
    JOIN 
        tblDepartment d ON jo.departmentId = d.departmentId
    JOIN 
        tblBranch b ON d.branchId = b.branchId
    WHERE 1=1
    ";

        // Apply search logic
        if (!empty($search)) {
            $sql .= " AND ja.name LIKE :search";
        }

        // Apply filter logic
        if ($filter === 'newest') {
            $sql .= " ORDER BY ja.appliedDate DESC";
        } elseif ($filter === 'oldest') {
            $sql .= " ORDER BY ja.appliedDate ASC";
        }

        $stmt = $this->pdo->prepare($sql);

        // Bind search parameter if it exists
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function markAsViewed($interviewApplicantId)
    {
        $query = "UPDATE tblInterviewJobApplicant SET isViewed = 1 WHERE interviewApplicantId = :interviewApplicantId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':interviewApplicantId', $interviewApplicantId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
