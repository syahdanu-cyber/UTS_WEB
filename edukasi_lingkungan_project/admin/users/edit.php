<?php
require_once '../../config/database.php';
check_admin();

$conn = getDBConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data user
$query = "SELECT * FROM users WHERE id = ? AND role = 'user'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$user = $result->fetch_assoc();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($email)) {
        $error = 'Username dan email harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($username) < 4) {
        $error = 'Username minimal 4 karakter!';
    } else {
        // Cek username/email sudah digunakan user lain
        $check_query = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $stmt_check = $conn->prepare($check_query);
        $stmt_check->bind_param("ssi", $username, $email, $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error = 'Username atau email sudah digunakan user lain!';
        } else {
            if (!empty($password)) {
                // Update dengan password baru
                if (strlen($password) < 6) {
                    $error = 'Password minimal 6 karakter!';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $update_query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                    $stmt_update = $conn->prepare($update_query);
                    $stmt_update->bind_param("sssi", $username, $email, $hashed_password, $id);
                }
            } else {
                // Update tanpa password
                $update_query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                $stmt_update = $conn->prepare($update_query);
                $stmt_update->bind_param("ssi", $username, $email, $id);
            }
            
            if (!isset($error) || empty($error)) {
                if ($stmt_update->execute()) {
                    $success = 'User berhasil diperbarui!';
                    $user['username'] = $username;
                    $user['email'] = $email;
                } else {
                    $error = 'Terjadi kesalahan. Silakan coba lagi.';
                }
            }
        }
    }
}

$page_title = 'Edit User';
$base_url = '../../';
include '../../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section">
            <a href="index.php" class="btn btn-primary" style="margin-bottom: 1rem;">← Kembali</a>
            
            <h2>✏️ Edit User</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" id="editUserForm">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" 
                           required>
                    <small style="color: #7f8c8d;">Minimal 4 karakter</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <small style="color: #7f8c8d;">Minimal 6 karakter jika diisi</small>
                </div>
                
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 5px; margin-bottom: 1rem;">
                    <p style="margin: 0; color: #7f8c8d;">
                        <strong>Info:</strong> User ini terdaftar sejak 
                        <strong><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></strong>
                    </p>
                </div>
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-success">💾 Update User</button>
                    <a href="index.php" class="btn btn-danger">❌ Batal</a>
                    <a href="hapus.php?id=<?php echo $user['id']; ?>" 
                       class="btn btn-warning"
                       onclick="return confirmDelete('Apakah Anda yakin ingin menghapus user ini?')">
                        🗑️ Hapus User
                    </a>
                </div>
            </form>
        </section>
    </div>
</main>

<script>
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (username.length < 4) {
        e.preventDefault();
        alert('Username minimal 4 karakter!');
        return false;
    }
    
    if (password && password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        return false;
    }
});
</script>

<?php
$conn->close();
include '../../includes/footer.php';
?>