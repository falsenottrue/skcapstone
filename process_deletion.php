<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
header('Content-Type: application/json');

include 'connection.php';

function respond($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    respond(false, "Unauthorized user.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, "Invalid request method.");
}

if (!isset($_POST['request_id'], $_POST['action'])) {
    respond(false, "Missing required fields.");
}

$request_id = intval($_POST['request_id']);
$action = strtolower($_POST['action']);
$comment = isset($_POST['admin_comment']) ? trim($_POST['admin_comment']) : '';

if (!in_array($action, ['approve', 'deny'])) {
    respond(false, "Invalid action.");
}

// Get login_id
$stmt = $conn->prepare("SELECT login_id FROM deletion_requests WHERE id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    respond(false, "Request not found.");
}

$login_id = $row['login_id'];

if ($action === 'approve') {
    $conn->begin_transaction();
    try {
        $conn->query("DELETE FROM guardian_info WHERE user_id = $login_id");
        $conn->query("DELETE FROM demographics WHERE user_id = $login_id");
        $conn->query("DELETE FROM users WHERE user_id = $login_id");
        $conn->query("DELETE FROM program_registrations WHERE login_id = $login_id");
        $conn->query("DELETE FROM login WHERE login_id = $login_id");

        $status = 'approved';
        $update = $conn->prepare("UPDATE deletion_requests SET status = ?, admin_comment = ?, updated_at = NOW() WHERE id = ?");
        $update->bind_param("ssi", $status, $comment, $request_id);
        $update->execute();
        $update->close();

        $conn->commit();
        respond(true, "Request approved and account deleted.");
    } catch (Exception $e) {
        $conn->rollback();
        respond(false, "Error: " . $e->getMessage());
    }
} else {
    $status = 'denied';
    $update = $conn->prepare("UPDATE deletion_requests SET status = ?, admin_comment = ?, updated_at = NOW() WHERE id = ?");
    $update->bind_param("ssi", $status, $comment, $request_id);
    $update->execute();
    $update->close();
    respond(true, "Request denied.");
}
