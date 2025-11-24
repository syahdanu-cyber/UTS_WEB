<?php
require_once 'koneksi.php';

// CORS OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, "Method not allowed. Use PUT", null, 405);
}

try {
    // Ambil JSON
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        sendResponse(false, "Invalid JSON input", null, 400);
    }

    // Validasi field wajib
    validateInput($input, ['id', 'username', 'email']);

    $id = intval($input['id']);
    $username = trim($input['username']);
    $email = trim($input['email']);
    $password = isset($input['password']) ? trim($input['password']) : null;
    $role = isset($input['role']) ? trim($input['role']) : 'user';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, "Invalid email format", null, 400);
    }

    $conn = getConnection();

    // Cek user ID
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows === 0) {
        sendResponse(false, "User not found", null, 404);
    }

    // Cek email duplikat
    $email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $email_stmt->bind_param("si", $email, $id);
    $email_stmt->execute();
    if ($email_stmt->get_result()->num_rows > 0) {
        sendResponse(false, "Email already used by another user", null, 409);
    }

    // Jika password diisi → update password
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username=?, email=?, role=?, password=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $username, $email, $role, $hashed, $id);
    } else {
        // Jika password tidak diganti
        $query = "UPDATE users SET username=?, email=?, role=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }

    if ($stmt->execute()) {
        sendResponse(true, "User updated successfully", null, 200);
    } else {
        sendResponse(false, "Update failed: " . $stmt->error, null, 500);
    }

} catch (Exception $e) {
    sendResponse(false, "Error: " . $e->getMessage(), null, 500);
}
?>
