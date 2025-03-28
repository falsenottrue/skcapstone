<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT pr.registration_id, pr.login_id, pr.status, l.usernm, p.program_name, pr.created_at 
            FROM program_registrations pr
            JOIN login l ON pr.login_id = l.login_id
            JOIN programs p ON pr.program_id = p.program_id
            ORDER BY pr.registration_id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($registrations);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->login_id) && !empty($data->program_id)) {
        $check_query = "SELECT * FROM program_registrations WHERE login_id = :login_id AND program_id = :program_id";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bindParam(":login_id", $data->login_id);
        $check_stmt->bindParam(":program_id", $data->program_id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            echo json_encode(["message" => "User already registered for this program."]);
            exit();
        }

        $query = "INSERT INTO program_registrations (login_id, program_id, status) VALUES (:login_id, :program_id, 'Registered')";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":login_id", $data->login_id);
        $stmt->bindParam(":program_id", $data->program_id);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "User registered successfully."]);
        } else {
            echo json_encode(["message" => "Failed to register user."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input."]);
    }
} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->registration_id) && !empty($data->status)) {
        $query = "UPDATE program_registrations SET status=:status WHERE registration_id=:registration_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":registration_id", $data->registration_id);
        $stmt->bindParam(":status", $data->status);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Registration status updated."]);
        } else {
            echo json_encode(["message" => "Failed to update status."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input."]);
    }
} else {
    echo json_encode(["message" => "Method not allowed"]);
}
?>
