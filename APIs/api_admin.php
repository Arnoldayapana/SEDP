<?php
header("Content-Type: application/json");
include '../Database/api_db.php';  // Ensure this file properly sets up the $pdo variable

// Check if the PDO object exists
if (!$pdo) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Fetch HTTP request method (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Sanitize incoming data (if applicable)
function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags($data));
}

// Validate required fields
function validateFields($data, $fields)
{
    foreach ($fields as $field) {
        if (empty($data->$field)) {
            return false;
        }
    }
    return true;
}

// Error response helper
function errorResponse($code, $message)
{
    http_response_code($code);
    echo json_encode([
        'status' => $code,
        'message' => $message,
        'data' => null
    ]);
    exit;
}

switch ($method) {
    case 'GET':
        // Validate ID for GET request (if provided)
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id <= 0) {
                errorResponse(400, "Invalid ID provided");
            }

            $stmt = $pdo->prepare("SELECT * FROM admin_login WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                errorResponse(404, "User not found");
            }
        } else {
            // No specific ID, return all users
            $stmt = $pdo->query("SELECT * FROM admin_login");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode($users);
        }
        break;

    case 'POST':
        // Decode JSON input
        $data = json_decode(file_get_contents("php://input"));

        // Validate required fields: username, email, password
        if (!$data || !validateFields($data, ['username', 'email', 'password'])) {
            errorResponse(400, "Missing required fields");
        }

        // Sanitize inputs
        $username = sanitizeInput($data->username);
        $email = filter_var($data->email, FILTER_VALIDATE_EMAIL);
        $password = sanitizeInput($data->password);

        // Validate email format
        if (!$email) {
            errorResponse(400, "Invalid email format");
        }

        $stmt = $pdo->prepare("INSERT INTO admin_login (username, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$username, $email, $password]);

        if ($result) {
            http_response_code(201);
            echo json_encode(["success" => true, "message" => "User created successfully"]);
        } else {
            errorResponse(500, "Failed to create user");
        }
        break;

    case 'PUT':
        // Decode JSON input
        $data = json_decode(file_get_contents("php://input"));

        // Validate required fields: id, username, email, password
        if (!$data || !validateFields($data, ['id', 'username', 'email', 'password'])) {
            errorResponse(400, "Missing required fields");
        }

        // Sanitize inputs
        $id = intval($data->id);
        $username = sanitizeInput($data->username);
        $email = filter_var($data->email, FILTER_VALIDATE_EMAIL);
        $password = sanitizeInput($data->password);

        // Validate ID and email format
        if ($id <= 0) {
            errorResponse(400, "Invalid ID provided");
        }
        if (!$email) {
            errorResponse(400, "Invalid email format");
        }

        $stmt = $pdo->prepare("UPDATE admin_login SET username = ?, email = ?, password = ? WHERE id = ?");
        $result = $stmt->execute([$username, $email, $password, $id]);

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "User updated successfully"]);
        } else {
            errorResponse(500, "Failed to update user");
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id <= 0) {
                errorResponse(400, "Invalid ID provided");
            }

            $stmt = $pdo->prepare("DELETE FROM admin_login WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                http_response_code(200);
                echo json_encode(["success" => true, "message" => "User deleted successfully"]);
            } else {
                errorResponse(404, "User not found");
            }
        } else {
            errorResponse(400, "ID is required for deletion");
        }
        break;

    default:
        errorResponse(405, "Method Not Allowed");
        break;
}
