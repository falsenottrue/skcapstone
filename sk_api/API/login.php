<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT login_id, usernm, email FROM login";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->first_name) && !empty($data->last_name) && !empty($data->email)) {
        $query = "INSERT INTO login (usenm, email) VALUES (:usernm, :email)";
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(":usernm", $data->first_name);
        $stmt->bindParam(":email", $data->email);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "User added successfully."]);
        } else {
            echo json_encode(["message" => "Failed to add user."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input."]);
    }
} else {
    echo json_encode(["message" => "Method not allowed"]);
}
?>
