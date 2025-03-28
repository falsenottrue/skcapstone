<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT program_id, program_name, description, start_date, end_date FROM programs ORDER BY start_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($programs);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->program_name) && !empty($data->description) && !empty($data->start_date) && !empty($data->end_date)) {
        $query = "INSERT INTO programs (program_name, description, start_date, end_date) VALUES (:nprogram_name, :description, :start_date, :end_date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":program_name", $data->program_name);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":start_date", $data->start_date);
        $stmt->bindParam(":end_date", $data->end_date);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Program added successfully."]);
        } else {
            echo json_encode(["message" => "Failed to add program."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input."]);
    }
} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->program_id) && !empty($data->program_name) && !empty($data->description) && !empty($data->start_date) && !empty($data->end_date)) {
        $query = "UPDATE programs SET program_name=:program_name, description=:description, start_date=:start_date, end_date=:end_date WHERE program_id=:program_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":program_id", $data->program_id);
        $stmt->bindParam(":program_name", $data->program_name);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":start_date", $data->start_date);
        $stmt->bindParam(":end_date", $data->end_date);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Program updated successfully."]);
        } else {
            echo json_encode(["message" => "Failed to update program."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input."]);
    }
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->program_id)) {
        $query = "DELETE FROM programs WHERE program_id = :program_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":program_id", $data->program_id);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Program deleted successfully."]);
        } else {
            echo json_encode(["message" => "Failed to delete program."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input."]);
    }
} else {
    echo json_encode(["message" => "Method not allowed"]);
}
?>
