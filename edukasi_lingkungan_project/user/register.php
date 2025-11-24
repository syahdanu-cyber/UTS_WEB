<?php
require_once '../config/database.php';
session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDBConnection();
    
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif (strlen($username) < 4) {
        $error = 'Username minimal 4 karakter!';
    } else {
        // Cek username sudah ada
        $check_query = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username atau email sudah digunakan!';
        } else {
            // Insert user baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Terjadi kesalahan. Silakan coba lagi.';
            }
        }
    }
    
    $conn->close();
}

$page_title = 'Register';
$base_url = '../';
include '../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section" style="max-width: 500px; margin: 3rem auto;">
            <h2 style="text-align: center;">📝 Daftar Akun Baru</h2>
            <p style="text-align: center; color: #7f8c8d; margin-bottom: 2rem;">
                Bergabunglah dengan komunitas peduli lingkungan
            </p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="login.php" class="btn btn-success">Login Sekarang →</a>
                </div>
            <?php else: ?>
            
            <form method="POST" action="" id="registerForm">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Minimal 4 karakter" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required>
                    <small style="color: #7f8c8d;">Username akan digunakan untuk login</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" 
                           placeholder="contoh@email.com" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Minimal 6 karakter" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           placeholder="Ketik ulang password" 
                           required>
                </div>
                
                <button type="submit" class="btn btn-success" style="width: 100%;" data-original-text="✨ Daftar Sekarang">
                    ✨ Daftar Sekarang
                </button>
            </form>
            
            <?php endif; ?>
            
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #ddd;">
                <p>Sudah punya akun? <a href="login.php" style="color: #2ecc71; font-weight: bold;">Login di sini</a></p>
                <p style="margin-top: 0.5rem;">
                    <a href="../index.php" style="color: #7f8c8d;">← Kembali ke Beranda</a>
                </p>
            </div>
        </section>
    </div>
</main>

<script>
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const username = document.getElementById('username').value;
    
    if (username.length < 4) {
        e.preventDefault();
        alert('Username minimal 4 karakter!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        return false;
    }
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
});
</script>

<?php include '../includes/footer.php'; ?>