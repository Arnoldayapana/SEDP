<?php

class InterviewModel
{
    private $pdo;

    public function __construct()
    {
        include_once(__DIR__ . '/../../../Database/database.php');
        $database = new Database();
        $this->pdo = $database->connect();
    }
    // Method to get interviews for a specific applicant
    public function getInterviewsByApplicantId($interviewApplicantId)
    {
        $sql = "SELECT 
                 interviewId, 
                        title, 
                        interviewDate, 
                        interviewType, 
                        videocallLink,
                        phoneNumber,
                        officeAddress,
                        interviewDescription_video, 
                        interviewDescription_phone,  
                        interviewDescription_office,
                        status, 
                        notes 
                FROM tblinterview 
                WHERE interviewApplicantId = :interviewApplicantId  
                ORDER BY interviewDate DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':interviewApplicantId', $interviewApplicantId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Method to get all interviews
    public function getAllInterviews()
    {
        $sql = "SELECT * FROM tblinterview";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to delete an interview
    public function deleteInterview($interviewId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tblinterview WHERE interviewId = ?");
        return $stmt->execute([$interviewId]);
    }

    // Method to update an interview
    public function updateInterview($interviewId, $applicantId, $title, $interviewDate, $interviewType, $videocallLink, $phoneNumber, $officeAddress, $interviewDescription, $status, $notes)
    {
        $stmt = $this->pdo->prepare("UPDATE tblinterview SET applicantId = ?, title = ?, interviewDate = ?, interviewType = ?, videocallLink = ?, phoneNumber = ?, officeAddress = ?, interviewSescription = ?, status = ?, notes = ? WHERE interviewId = ?");

        return $stmt->execute([$applicantId, $title, $interviewDate, $interviewType, $videocallLink, $phoneNumber, $officeAddress, $interviewDescription, $status, $notes, $interviewId]);
    }
    public function updateNotes($interviewId, $notes)
    {
        $sql = "UPDATE tblinterview 
                SET notes = :notes 
                WHERE interviewId = :interviewId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
        $stmt->bindParam(':interviewId', $interviewId, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return ['success' => true];
            }
            return ['success' => false, 'message' => 'Failed to update notes'];
        } catch (PDOException $e) {
            // Log error message or return it (not recommended to expose sensitive info)
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    public function createInterview($postData)
    {
        // Validate and sanitize input data
        $interviewApplicantId = htmlspecialchars(trim($postData['interviewApplicantId'] ?? null));
        $title = htmlspecialchars(trim($postData['title']));
        $interviewDate = htmlspecialchars(trim($postData['date']));
        $interviewType = htmlspecialchars(trim($postData['interviewType']));
        $videocallLink = isset($postData['videocallLink']) ? htmlspecialchars(trim($postData['videocallLink'])) : null;
        $phoneNumber = isset($postData['phoneNumber']) ? htmlspecialchars(trim($postData['phoneNumber'])) : null;
        $officeAddress = isset($postData['officeAddress']) ? htmlspecialchars(trim($postData['officeAddress'])) : null;

        // Depending on interview type, set the correct interview description field
        $interviewDescription_video = ($interviewType == 'video') ? htmlspecialchars(trim($postData['interviewDescription_video'])) : null;
        $interviewDescription_phone = ($interviewType == 'phone') ? htmlspecialchars(trim($postData['interviewDescription_phone'])) : null;
        $interviewDescription_office = ($interviewType == 'in-office') ? htmlspecialchars(trim($postData['interviewDescription_office'])) : null;

        $notes = isset($postData['notes']) ? htmlspecialchars(trim($postData['notes'])) : null;

        // Prepare the SQL statement with the new columns
        $query = "INSERT INTO tblinterview 
                  (interviewApplicantId, title, interviewDate, interviewType, videocallLink, phoneNumber, officeAddress, 
                   interviewDescription_video, interviewDescription_phone, interviewDescription_office, notes, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($query);

        try {
            // Execute the statement with the input data
            $stmt->execute([
                $interviewApplicantId,
                $title,
                $interviewDate,
                $interviewType,
                $videocallLink,
                $phoneNumber,
                $officeAddress,
                $interviewDescription_video,
                $interviewDescription_phone,
                $interviewDescription_office,
                $notes,
                'pending'
            ]);
            return true; // Success
        } catch (PDOException $e) {
            error_log('Interview creation failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }



    // Method to retrieve interviews with filtering and search criteria
    public function getFilteredInterviews($search)
    {
        $sql = "SELECT * FROM tblinterview WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND title LIKE :search";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    public function updateNotes($interviewId, $newNotes)
    {
        $newNotes = htmlspecialchars(trim($newNotes)); // Sanitize input

        // Prepare the SQL statement
        $query = "UPDATE tblinterview SET notes = ?, updatedAt = CURRENT_TIMESTAMP WHERE interviewId = ?";
        $stmt = $this->pdo->prepare($query);

        try {
            // Execute the statement with the interviewId and newNotes
            return $stmt->execute([$newNotes, $interviewId]);
        } catch (PDOException $e) {
            error_log('Updating notes failed: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
        */
}
