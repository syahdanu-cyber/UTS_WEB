<?php
require_once 'koneksi.php';

// CORS for OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Hanya menerima DELETE request
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendResponse(false, "Method not allowed. Use DELETE", null, 405);
}

try {
    // Ambil JSON dari body
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        sendResponse(false, "Invalid JSON data", null, 400);
    }

    // Validasi field wajib
    validateInput($input, ['id']);

    $id = intval($input['id']);
    $conn = getConnection();

    // Cek apakah user ada
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse(false, "User not found", null, 404);
    }

    // Simpan data sebelum dihapus
    $deleted_data = $result->fetch_assoc();

    // Eksekusi delete
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            sendResponse(true, "User deleted successfully", $deleted_data, 200);
        } else {
            sendResponse(false, "Failed to delete user", null, 500);
        }
    } else {
        sendResponse(false, "Delete query error: " . $delete_stmt->error, null, 500);
    }

} catch (Exception $e) {
    sendResponse(false, "Server error: " . $e->getMessage(), null, 500);
}
?>
