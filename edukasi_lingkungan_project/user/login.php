<?php
require_once '../config/database.php';
session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDBConnection();
    
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] == 'admin') {
                    header("Location: ../admin/index.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
    }
    
    $conn->close();
}

$page_title = 'Login';
$base_url = '../';
include '../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section" style="max-width: 500px; margin: 3rem auto;">
            <h2 style="text-align: center;">🔐 Login</h2>
            <p style="text-align: center; color: #7f8c8d; margin-bottom: 2rem;">
                Silakan login untuk mengakses platform
            </p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Masukkan username Anda" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Masukkan password Anda" 
                           required>
                </div>
                
                <button type="submit" class="btn btn-success" style="width: 100%;" data-original-text="🔓 Login Sekarang">
                    🔓 Login Sekarang
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #ddd;">
                <p>Belum punya akun? <a href="register.php" style="color: #2ecc71; font-weight: bold;">Daftar di sini</a></p>
                <p style="margin-top: 0.5rem;">
                    <a href="../index.php" style="color: #7f8c8d;">← Kembali ke Beranda</a>
                </p>
            </div>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 5px; text-align: center;">
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <strong>Demo Account:</strong>
                </p>
                <p style="color: #7f8c8d; font-size: 0.9rem;">
                    Admin - Username: <code>admin</code> | Password: <code>admin123</code>
                </p>
            </div>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>