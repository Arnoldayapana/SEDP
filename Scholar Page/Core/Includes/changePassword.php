    <?php
    // Only execute PHP code if the request is an AJAX POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['currentPassword'], $_POST['newPassword'])) {
        require '../../../Database/db.php'; // Ensure this path is correct for the database file

        $username = $_POST['username'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        try {
            // Check if the database connection is valid
            if (!$pdo) {
                throw new Exception("Database connection not established.");
            }

            // Prepare statement to find the user
            $stmt = $pdo->prepare("SELECT * FROM scholar_login WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            // Check if user exists and password matches
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid username or current password.']);
                exit;
            }

            // Update password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE scholar_login SET password = :newPassword WHERE username = :username");
            $stmt->execute(['newPassword' => $newPasswordHash, 'username' => $username]);

            echo json_encode(['success' => true, 'message' => 'Password successfully updated.']);
        } catch (Exception $e) {
            // Send detailed error information back for debugging
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
        exit;
    }
    ?>
