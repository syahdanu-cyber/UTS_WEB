<?php
require_once 'koneksi.php';

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Jika request OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Hanya boleh POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, "Method not allowed. Use POST", null, 405);
}

try {
    // Ambil JSON body
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        sendResponse(false, "Invalid JSON input", null, 400);
    }

    // Validasi input wajib
    $required = ['username', 'email', 'password'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || trim($input[$field]) === "") {
            sendResponse(false, "Field '$field' is required", null, 400);
        }
    }

    // Sanitasi input
    $username = trim($input['username']);
    $email = trim($input['email']);
    $password_raw = trim($input['password']);
    $password = password_hash($password_raw, PASSWORD_BCRYPT);
    $role = 'user';

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, "Invalid email format", null, 400);
    }

    // Koneksi DB
    $conn = getConnection();

    // Cek apakah username sudah ada
    $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_username->bind_param("s", $username);
    $check_username->execute();
    if ($check_username->get_result()->num_rows > 0) {
        sendResponse(false, "Username already exists", null, 409);
    }

    // Cek apakah email sudah ada
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    if ($check_email->get_result()->num_rows > 0) {
        sendResponse(false, "Email already exists", null, 409);
    }

    // Insert user baru
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        $new_id = $conn->insert_id;

        // Ambil data user yang baru dibuat (tanpa password)
        $select = $conn->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
        $select->bind_param("i", $new_id);
        $select->execute();
        $result = $select->get_result();
        $new_user = $result->fetch_assoc();

        sendResponse(true, "User created successfully", $new_user, 201);
    } else {
        sendResponse(false, "Failed to create user: " . $stmt->error, null, 500);
    }

} catch (Exception $e) {
    sendResponse(false, "Error: " . $e->getMessage(), null, 500);
}
?>
