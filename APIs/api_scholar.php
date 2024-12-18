<?php
header("Content-Type: application/json");
include '../Database/api_db.php';

// Check if $pdo is set
if (!$pdo) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Fetch HTTP request method (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['recipient_id'])) {
            $id = intval($_GET['recipient_id']); // Use 'employee_id' here
            $stmt = $pdo->prepare("SELECT * FROM recipient WHERE recipient_id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // User found, return the user data with a 200 OK status
                http_response_code(200); // Optional, 200 is the default for successful responses
                echo json_encode($user);
            } else {
                // User not found, return a 404 Not Found status
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'User not found',
                    'data' => null
                ]);
            }
        } else {
            // No specific user ID provided, return all users
            $stmt = $pdo->query("SELECT * FROM recipient");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200); // Return all users with a 200 OK status
            echo json_encode($users);
        }
        break;


    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $pdo->prepare("INSERT INTO recipient (name, email, school, contact, branch, GradeLevel, admission_date, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$data->name, $data->email, $data->school, $data->contact, $data->branch, $data->GradeLevel, $data->admission_date, $data->password]);

        if ($result) {
            http_response_code(201); // Created
            echo json_encode(["success" => true, "message" => "User created successfully"]);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["success" => false, "message" => "Failed to create user"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $pdo->prepare("UPDATE recipient SET name = ?, email = ?, school = ?, contact = ?, branch = ?, GradeLevel = ?, admission_date = ?, password = ? WHERE recipient_id = ?");
        $result = $stmt->execute(params: [
            $data->name,
            $data->email,
            $data->school,
            $data->contact,
            $data->branch,
            $data->GradeLevel,
            $data->admission_date,
            $data->password,
            $data->recipient_id
        ]);

        if ($result) {
            http_response_code(200); // OK
            echo json_encode(["success" => true, "message" => "User updated successfully"]);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["success" => false, "message" => "Failed to update user"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $pdo->prepare("DELETE FROM recipient WHERE recipient_id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "message" => "User deleted successfully"]);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(["success" => false, "message" => "User not found"]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["success" => false, "message" => "ID is required for deletion"]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode([
            'status' => 405,
            'message' => 'Method Not Allowed',
            'data' => null
        ]);
        break;
}
