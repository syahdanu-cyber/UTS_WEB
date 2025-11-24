// File: koneksi.php
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'edukasi_lingkungan');

// Fungsi Koneksi Database
function getConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Database connection error: " . $e->getMessage()
        ]);
        exit();
    }
}

// Fungsi Response JSON
function sendResponse($success, $message, $data = null, $code = 200) {
    http_response_code($code);
    $response = [
        "success" => $success,
        "message" => $message
    ];
    
    if ($data !== null) {
        $response["data"] = $data;
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

// Fungsi Validasi Input
function validateInput($data, $required_fields) {
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        sendResponse(false, "Missing required fields: " . implode(", ", $missing_fields), null, 400);
    }
    
    return true;
}
?>