<?php
require_once '../../config/database.php';
check_admin();

$conn = getDBConnection();

// Ambil ID artikel
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data artikel
$query = "SELECT * FROM artikel WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$artikel = $result->fetch_assoc();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // Cek slug sudah ada (kecuali artikel ini sendiri)
        $check_query = "SELECT id FROM artikel WHERE slug = ? AND id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("si", $slug, $id);
        $stmt->execute();
        $result_check = $stmt->get_result();
        
        if ($result_check->num_rows > 0) {
            $slug = $slug . '-' . time();
        }
        
        $update_query = "UPDATE artikel SET judul = ?, slug = ?, konten = ?, penulis = ?, kategori = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssi", $judul, $slug, $konten, $penulis, $kategori, $id);
        
        if ($stmt->execute()) {
            $success = 'Artikel berhasil diperbarui!';
            // Refresh data artikel
            $artikel['judul'] = $judul;
            $artikel['konten'] = $konten;
            $artikel['penulis'] = $penulis;
            $artikel['kategori'] = $kategori;
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}

$page_title = 'Edit Artikel';
$base_url = '../../';
include '../../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section">
            <a href="index.php" class="btn btn-primary" style="margin-bottom: 1rem;">← Kembali</a>
            
            <h2>✏️ Edit Artikel</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" id="editArtikelForm">
                <div class="form-group">
                    <label for="judul">Judul Artikel *</label>
                    <input type="text" id="judul" name="judul" 
                           value="<?php echo htmlspecialchars($artikel['judul']); ?>" 
                           required>
                    <small style="color: #7f8c8d;">Minimal 10 karakter</small>
                </div>
                
                <div class="form-group">
                    <label for="kategori">Kategori *</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Hutan" <?php echo $artikel['kategori'] == 'Hutan' ? 'selected' : ''; ?>>🌳 Hutan</option>
                        <option value="Sampah" <?php echo $artikel['kategori'] == 'Sampah' ? 'selected' : ''; ?>>♻️ Sampah</option>
                        <option value="Energi" <?php echo $artikel['kategori'] == 'Energi' ? 'selected' : ''; ?>>⚡ Energi</option>
                        <option value="Air" <?php echo $artikel['kategori'] == 'Air' ? 'selected' : ''; ?>>💧 Air</option>
                        <option value="Udara" <?php echo $artikel['kategori'] == 'Udara' ? 'selected' : ''; ?>>🌫️ Udara</option>
                        <option value="Satwa" <?php echo $artikel['kategori'] == 'Satwa' ? 'selected' : ''; ?>>🐾 Satwa</option>
                        <option value="Lainnya" <?php echo $artikel['kategori'] == 'Lainnya' ? 'selected' : ''; ?>>📌 Lainnya</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="penulis">Penulis *</label>
                    <input type="text" id="penulis" name="penulis" 
                           value="<?php echo htmlspecialchars($artikel['penulis']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="konten">Konten Artikel *</label>
                    <textarea id="konten" name="konten" rows="20" required><?php echo htmlspecialchars($artikel['konten']); ?></textarea>
                    <small style="color: #7f8c8d;">Minimal 100 karakter. Gunakan enter untuk membuat paragraf baru.</small>
                </div>
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-success">💾 Update Artikel</button>
                    <a href="index.php" class="btn btn-danger">❌ Batal</a>
                    <a href="../../detail_artikel.php?slug=<?php echo $artikel['slug']; ?>" 
                       class="btn btn-primary" target="_blank">👁️ Preview</a>
                </div>
            </form>
        </section>
    </div>
</main>

<script>
document.getElementById('editArtikelForm').addEventListener('submit', function(e) {
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

<?php
$conn->close();
include '../../includes/footer.php';
?>