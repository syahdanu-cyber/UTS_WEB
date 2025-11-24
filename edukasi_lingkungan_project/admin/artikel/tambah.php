<?php
require_once '../../config/database.php';
check_admin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDBConnection();
    
    $judul = clean_input($_POST['judul']);
    $konten = clean_input($_POST['konten']);
    $penulis = clean_input($_POST['penulis']);
    $kategori = clean_input($_POST['kategori']);
    $slug = create_slug($judul);
    
    if (empty($judul) || empty($konten) || empty($penulis) || empty($kategori)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($judul) < 10) {
        $error = 'Judul artikel minimal 10 karakter!';
    } elseif (strlen($konten) < 100) {
        $error = 'Konten artikel minimal 100 karakter!';
    } else {
        // Cek slug sudah ada
        $check_query = "SELECT id FROM artikel WHERE slug = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $slug = $slug . '-' . time();
        }
        
        $insert_query = "INSERT INTO artikel (judul, slug, konten, penulis, kategori) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssss", $judul, $slug, $konten, $penulis, $kategori);
        
        if ($stmt->execute()) {
            $success = 'Artikel berhasil ditambahkan!';
            header("refresh:2;url=index.php");
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
    
    $conn->close();
}

$page_title = 'Tambah Artikel';
$base_url = '../../';
include '../../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section">
            <a href="index.php" class="btn btn-primary" style="margin-bottom: 1rem;">← Kembali</a>
            
            <h2>➕ Tambah Artikel Baru</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" id="artikelForm">
                <div class="form-group">
                    <label for="judul">Judul Artikel *</label>
                    <input type="text" id="judul" name="judul" 
                           placeholder="Masukkan judul artikel yang menarik" 
                           value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>" 
                           required>
                    <small style="color: #7f8c8d;">Minimal 10 karakter</small>
                </div>
                
                <div class="form-group">
                    <label for="kategori">Kategori *</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Hutan" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Hutan') ? 'selected' : ''; ?>>🌳 Hutan</option>
                        <option value="Sampah" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Sampah') ? 'selected' : ''; ?>>♻️ Sampah</option>
                        <option value="Energi" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Energi') ? 'selected' : ''; ?>>⚡ Energi</option>
                        <option value="Air" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Air') ? 'selected' : ''; ?>>💧 Air</option>
                        <option value="Udara" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Udara') ? 'selected' : ''; ?>>🌫️ Udara</option>
                        <option value="Satwa" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Satwa') ? 'selected' : ''; ?>>🐾 Satwa</option>
                        <option value="Lainnya" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Lainnya') ? 'selected' : ''; ?>>📌 Lainnya</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="penulis">Penulis *</label>
                    <input type="text" id="penulis" name="penulis" 
                           value="<?php echo isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : htmlspecialchars($_SESSION['username']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="konten">Konten Artikel *</label>
                    <textarea id="konten" name="konten" rows="20" 
                              placeholder="Tulis konten artikel Anda di sini..." 
                              required><?php echo isset($_POST['konten']) ? htmlspecialchars($_POST['konten']) : ''; ?></textarea>
                    <small style="color: #7f8c8d;">Minimal 100 karakter. Gunakan enter untuk membuat paragraf baru.</small>
                </div>
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-success">💾 Simpan Artikel</button>
                    <a href="index.php" class="btn btn-danger">❌ Batal</a>
                </div>
            </form>
        </section>
    </div>
</main>

<script>
document.getElementById('artikelForm').addEventListener('submit', function(e) {
    const judul = document.getElementById('judul').value;
    const konten = document.getElementById('konten').value;
    
    if (judul.length < 10) {
        e.preventDefault();
        alert('Judul artikel minimal 10 karakter!');
        return false;
    }
    
    if (konten.length < 100) {
        e.preventDefault();
        alert('Konten artikel minimal 100 karakter!');
        return false;
    }
});
</script>

<?php include '../../includes/footer.php'; ?>