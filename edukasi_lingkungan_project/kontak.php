<?php
require_once 'config/database.php';
$page_title = 'Kontak';
include 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDBConnection();
    
    $nama = clean_input($_POST['nama']);
    $email = clean_input($_POST['email']);
    $subjek = clean_input($_POST['subjek']);
    $pesan = clean_input($_POST['pesan']);
    
    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        $error = 'Semua field harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        $query = "INSERT INTO kontak (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
        
        if ($stmt->execute()) {
            $success = 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.';
            // Reset form
            $_POST = array();
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
    
    $conn->close();
}
?>

<section class="hero">
    <div class="container">
        <h2>📧 Hubungi Kami</h2>
        <p>Ada pertanyaan atau saran? Jangan ragu untuk menghubungi kami!</p>
    </div>
</section>

<main>
    <div class="container">
        <section class="content-section">
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                <div>
                    <h2>📝 Formulir Kontak</h2>
                    <p style="margin-bottom: 2rem; color: #7f8c8d;">
                        Isi formulir di bawah ini dan kami akan merespon secepat mungkin.
                    </p>
                    
                    <form method="POST" action="" id="contactForm">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" 
                                   value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subjek">Subjek *</label>
                            <input type="text" id="subjek" name="subjek" 
                                   value="<?php echo isset($_POST['subjek']) ? htmlspecialchars($_POST['subjek']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="pesan">Pesan *</label>
                            <textarea id="pesan" name="pesan" required><?php echo isset($_POST['pesan']) ? htmlspecialchars($_POST['pesan']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success" data-original-text="Kirim Pesan">
                            📤 Kirim Pesan
                        </button>
                    </form>
                </div>
                
                <div>
                    <h2>📍 Informasi Kontak</h2>
                    <div style="line-height: 2.5; font-size: 1.1rem;">
                        <div style="margin-bottom: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
                            <p><strong>🏢 Alamat:</strong><br>
                            Jl. Lingkungan Hijau No. 123<br>
                            Jakarta Selatan, DKI Jakarta 12345<br>
                            Indonesia</p>
                        </div>
                        
                        <div style="margin-bottom: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
                            <p><strong>📧 Email:</strong><br>
                            info@edukasi-lingkungan.com<br>
                            support@edukasi-lingkungan.com</p>
                        </div>
                        
                        <div style="margin-bottom: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
                            <p><strong>📱 Telepon:</strong><br>
                            +62 21 1234 5678<br>
                            +62 812 3456 7890</p>
                        </div>
                        
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
                            <p><strong>⏰ Jam Operasional:</strong><br>
                            Senin - Jumat: 09.00 - 17.00 WIB<br>
                            Sabtu: 09.00 - 13.00 WIB<br>
                            Minggu & Libur: Tutup</p>
                        </div>
                    </div>
                    
                    <h3 style="margin-top: 2rem;">🌐 Media Sosial</h3>
                    <p style="color: #7f8c8d; margin-bottom: 1rem;">
                        Ikuti kami untuk update terbaru!</p>
                    <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
                        <a href="#" class="btn btn-primary">Facebook</a>
                        <a href="#" class="btn btn-primary">Instagram</a>
                        <a href="#" class="btn btn-primary">Twitter</a>
                        <a href="#" class="btn btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-section" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white;">
            <h2 style="color: white; text-align: center;">❓ FAQ (Pertanyaan yang Sering Diajukan)</h2>
            <div style="margin-top: 2rem;">
                <details style="margin-bottom: 1rem; background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 5px;">
                    <summary style="cursor: pointer; font-weight: bold;">Apa itu Edukasi Lingkungan?</summary>
                    <p style="margin-top: 1rem;">Edukasi Lingkungan adalah platform online yang menyediakan informasi dan artikel tentang pelestarian lingkungan untuk meningkatkan kesadaran masyarakat.</p>
                </details>
                <details style="margin-bottom: 1rem; background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 5px;">
                    <summary style="cursor: pointer; font-weight: bold;">Apakah gratis untuk membaca artikel?</summary>
                    <p style="margin-top: 1rem;">Ya, semua artikel di platform kami dapat diakses secara gratis oleh siapa saja.</p>
                </details>
                <details style="margin-bottom: 1rem; background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 5px;">
                    <summary style="cursor: pointer; font-weight: bold;">Bagaimana cara berkontribusi?</summary>
                    <p style="margin-top: 1rem;">Anda dapat berkontribusi dengan membagikan artikel ke media sosial, menerapkan tips yang kami bagikan, dan menghubungi kami untuk kolaborasi.</p>
                </details>
            </div>
        </section>
    </div>
</main>

<script>
// Validasi form sebelum submit
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Format email tidak valid!');
        return false;
    }
});
</script>

<?php include 'includes/footer.php'; ?>