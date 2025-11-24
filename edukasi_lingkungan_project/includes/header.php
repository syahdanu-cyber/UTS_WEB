<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tentukan base URL jika belum ada
if (!isset($base_url)) {
    $base_url = '';
}

// Default page title
if (!isset($page_title)) {
    $page_title = 'Portal Edukasi Lingkungan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Edukasi Lingkungan</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="<?php echo $base_url; ?>index.php" style="color: white; text-decoration: none;">🌿 Edukasi Lingkungan</a></h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="<?php echo $base_url; ?>index.php">🏠 Beranda</a></li>
                        <li><a href="<?php echo $base_url; ?>artikel.php">📚 Artikel</a></li>
                        <li><a href="<?php echo $base_url; ?>tentang.php">ℹ️ Tentang</a></li>
                        <li><a href="<?php echo $base_url; ?>kontak.php">📧 Kontak</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <li><a href="<?php echo $base_url; ?>admin/index.php">⚙️ Admin</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="auth-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span style="color: white;">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="<?php echo $base_url; ?>admin/logout.php" class="btn btn-warning" onclick="return confirmLogout()">🚪 Logout</a>
                    <?php else: ?>
                        <a href="<?php echo $base_url; ?>user/login.php" class="btn btn-primary">🔐 Login</a>
                        <a href="<?php echo $base_url; ?>user/register.php" class="btn btn-success">📝 Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>