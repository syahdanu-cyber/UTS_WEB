<?php
require_once '../../config/database.php';
check_admin();

$conn = getDBConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    // Ambil data user untuk log
    $query = "SELECT username FROM users WHERE id = ? AND role = 'user'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Hapus user
        $delete_query = "DELETE FROM users WHERE id = ? AND role = 'user'";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "User '" . $user['username'] . "' berhasil dihapus!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Gagal menghapus user!";
            $_SESSION['message_type'] = 'danger';
        }
    }
}

$conn->close();
header("Location: index.php");
exit();
?>