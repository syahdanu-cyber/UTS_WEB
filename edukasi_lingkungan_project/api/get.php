// File: get.php
<?php
require_once 'koneksi.php';

// Handle OPTIONS request untuk CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Hanya menerima GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, "Method not allowed. Use GET", null, 405);
}

try {
    // Koneksi database
    $conn = getConnection();
    
    // Cek apakah ada parameter ID
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // Get single data by ID
        $id = intval($_GET['id']);
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            sendResponse(true, "Data found", $data, 200);
        } else {
            sendResponse(false, "Data not found", null, 404);
        }
        
    } else {
        // Get all data dengan pagination
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = ($page - 1) * $limit;
        
        // Search functionality
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Query untuk count total data
        if (!empty($search)) {
            $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?");
            $search_param = "%{$search}%";
            $count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
        } else {
            $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
        }
        
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_data = $count_result->fetch_assoc()['total'];
        
        // Query untuk get data
        if (!empty($search)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $search_param = "%{$search}%";
            $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $limit, $offset);
        } else {
            $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->bind_param("ii", $limit, $offset);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $response_data = [
            "users" => $data,
            "pagination" => [
                "total_data" => intval($total_data),
                "total_pages" => ceil($total_data / $limit),
                "current_page" => $page,
                "per_page" => $limit
            ]
        ];
        
        sendResponse(true, "Data retrieved successfully", $response_data, 200);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    sendResponse(false, "Error: " . $e->getMessage(), null, 500);
}
?>