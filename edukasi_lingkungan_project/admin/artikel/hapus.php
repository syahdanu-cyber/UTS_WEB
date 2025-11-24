<?php
require_once '../../config/database.php';
check_admin();

$conn = getDBConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    // Ambil data artikel untuk log (opsional)
    $query = "SELECT judul FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $artikel = $result->fetch_assoc();
        
        // Hapus artikel
        $delete_query = "DELETE FROM artikel WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Artikel '" . $artikel['judul'] . "' berhasil dihapus!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Gagal menghapus artikel!";
            $_SESSION['message_type'] = 'danger';
        }
    }
}

$conn->close();
header("Location: index.php");
exit();
?>