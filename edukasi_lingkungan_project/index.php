<?php
require_once 'config/database.php';
$page_title = 'Beranda';
include 'includes/header.php';

$conn = getDBConnection();

// Ambil 6 artikel terbaru
$query = "SELECT * FROM artikel ORDER BY created_at DESC LIMIT 6";
$result = $conn->query($query);

// Ambil artikel populer (berdasarkan views)
$popular_query = "SELECT * FROM artikel ORDER BY views DESC LIMIT 3";
$popular_result = $conn->query($popular_query);

// Hitung total artikel
$total_artikel = $conn->query("SELECT COUNT(*) as total FROM artikel")->fetch_assoc()['total'];
$total_views = $conn->query("SELECT SUM(views) as total FROM artikel")->fetch_assoc()['total'];

// Ambil kategori dengan jumlah artikel
$kategori_query = "SELECT kategori, COUNT(*) as jumlah FROM artikel GROUP BY kategori ORDER BY jumlah DESC";
$kategori_result = $conn->query($kategori_query);
?>

<section class="hero">
    <div class="container">
        <h2>🌍 Selamat Datang di Portal Edukasi Lingkungan</h2>
        <p>Belajar dan berbagi pengetahuan tentang pelestarian lingkungan untuk masa depan yang lebih hijau dan berkelanjutan</p>
        <div style="margin-top: 2rem;">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="user/register.php" class="btn btn-primary" style="margin: 0.5rem;">Daftar Sekarang</a>
                <a href="artikel.php" class="btn btn-warning" style="margin: 0.5rem;">Jelajahi Artikel</a>
            <?php else: ?>
                <a href="artikel.php" class="btn btn-primary" style="margin: 0.5rem;">Jelajahi Artikel</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<main>
    <div class="container">
        <!-- Statistik Platform -->
        <section class="content-section">
            <h2>📊 Statistik Platform</h2>
            <div class="artikel-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <div class="artikel-card stats-card">
                    <div class="artikel-image" style="height: 150px; font-size: 3rem; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);">📚</div>
                    <div class="artikel-content" style="text-align: center;">
                        <h3 style="font-size: 2.5rem; color: #2ecc71; margin-bottom: 0.5rem;"><?php echo $total_artikel; ?></h3>
                        <p style="font-weight: 600; color: #555;">Artikel Tersedia</p>
                    </div>
                </div>
                <div class="artikel-card stats-card">
                    <div class="artikel-image" style="height: 150px; font-size: 3rem; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">👁️</div>
                    <div class="artikel-content" style="text-align: center;">
                        <h3 style="font-size: 2.5rem; color: #3498db; margin-bottom: 0.5rem;"><?php echo number_format($total_views); ?></h3>
                        <p style="font-weight: 600; color: #555;">Total Pembaca</p>
                    </div>
                </div>
                <div class="artikel-card stats-card">
                    <div class="artikel-image" style="height: 150px; font-size: 3rem; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">🔥</div>
                    <div class="artikel-content" style="text-align: center;">
                        <h3 style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 0.5rem;"><?php echo $kategori_result->num_rows; ?></h3>
                        <p style="font-weight: 600; color: #555;">Kategori</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kategori Artikel -->
        <section class="content-section">
            <h2>📂 Kategori Artikel</h2>
            <div class="kategori-grid">
                <?php 
                $kategori_result->data_seek(0); // Reset pointer
                $kategori_icons = [
                    'Hutan' => '🌳',
                    'Sampah' => '♻️',
                    'Energi' => '⚡',
                    'Air' => '💧',
                    'Udara' => '🌫️',
                    'Satwa' => '🐾',
                    'Lainnya' => '📌'
                ];
                while($kat = $kategori_result->fetch_assoc()): 
                    $icon = isset($kategori_icons[$kat['kategori']]) ? $kategori_icons[$kat['kategori']] : '📌';
                ?>
                    <a href="artikel.php?kategori=<?php echo urlencode($kat['kategori']); ?>" class="kategori-card">
                        <span class="kategori-icon"><?php echo $icon; ?></span>
                        <h3><?php echo htmlspecialchars($kat['kategori']); ?></h3>
                        <p><?php echo $kat['jumlah']; ?> artikel</p>
                    </a>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Artikel Populer -->
        <section class="content-section" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <h2 style="color: white;">🔥 Artikel Populer</h2>
            <div class="artikel-grid">
                <?php while($artikel = $popular_result->fetch_assoc()): ?>
                    <div class="artikel-card">
                        <div class="artikel-image">
                            <?php 
                            $kategori_icon = isset($kategori_icons[$artikel['kategori']]) ? $kategori_icons[$artikel['kategori']] : '🌿';
                            echo $kategori_icon;
                            ?>
                        </div>
                        <div class="artikel-content">
                            <span class="kategori-badge"><?php echo htmlspecialchars($artikel['kategori']); ?></span>
                            <h3><?php echo htmlspecialchars($artikel['judul']); ?></h3>
                            <div class="artikel-meta">
                                <span>📅 <?php echo format_tanggal($artikel['created_at']); ?></span>
                                <span>👁️ <?php echo number_format($artikel['views']); ?> views</span>
                            </div>
                            <p class="artikel-excerpt">
                                <?php echo excerpt($artikel['konten'], 120); ?>
                            </p>
                            <a href="detail_artikel.php?slug=<?php echo $artikel['slug']; ?>" class="btn btn-primary">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Artikel Terbaru -->
        <section class="content-section">
            <h2>📖 Artikel Terbaru</h2>
            <div class="artikel-grid">
                <?php while($artikel = $result->fetch_assoc()): ?>
                    <div class="artikel-card">
                        <div class="artikel-image">
                            <?php 
                            $kategori_icon = isset($kategori_icons[$artikel['kategori']]) ? $kategori_icons[$artikel['kategori']] : '🌿';
                            echo $kategori_icon;
                            ?>
                        </div>
                        <div class="artikel-content">
                            <span class="kategori-badge"><?php echo htmlspecialchars($artikel['kategori']); ?></span>
                            <h3><?php echo htmlspecialchars($artikel['judul']); ?></h3>
                            <div class="artikel-meta">
                                <span>📅 <?php echo format_tanggal($artikel['created_at']); ?></span>
                                <span>👁️ <?php echo number_format($artikel['views']); ?> views</span>
                                <span>✍️ <?php echo htmlspecialchars($artikel['penulis']); ?></span>
                            </div>
                            <p class="artikel-excerpt">
                                <?php echo excerpt($artikel['konten'], 150); ?>
                            </p>
                            <a href="detail_artikel.php?slug=<?php echo $artikel['slug']; ?>" class="btn btn-primary">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="artikel.php" class="btn btn-success btn-lg">Lihat Semua Artikel →</a>
            </div>
        </section>

        <!-- Mengapa Edukasi Lingkungan Penting -->
        <section class="content-section">
            <h2>🌍 Mengapa Edukasi Lingkungan Penting?</h2>
            <div class="artikel-grid">
                <div class="artikel-card feature-card">
                    <div class="artikel-image" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">🌳</div>
                    <div class="artikel-content">
                        <h3>Menjaga Kelestarian Bumi</h3>
                        <p>Dengan edukasi yang tepat, kita dapat memahami cara menjaga dan melestarikan lingkungan untuk generasi mendatang.</p>
                    </div>
                </div>
                <div class="artikel-card feature-card">
                    <div class="artikel-image" style="background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);">♻️</div>
                    <div class="artikel-content">
                        <h3>Mengurangi Dampak Negatif</h3>
                        <p>Pengetahuan tentang lingkungan membantu kita mengurangi jejak karbon dan dampak negatif terhadap ekosistem.</p>
                    </div>
                </div>
                <div class="artikel-card feature-card">
                    <div class="artikel-image" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">🌱</div>
                    <div class="artikel-content">
                        <h3>Masa Depan Berkelanjutan</h3>
                        <p>Edukasi lingkungan adalah kunci untuk menciptakan masa depan yang lebih berkelanjutan dan ramah lingkungan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="content-section cta-section">
            <div style="text-align: center;">
                <h2 style="color: white; font-size: 2.5rem; margin-bottom: 1rem;">🚀 Mulai Kontribusi Anda Sekarang!</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; color: rgba(255,255,255,0.9);">
                    Bergabunglah dengan ribuan orang yang peduli lingkungan. Baca, pelajari, dan terapkan!
                </p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="user/register.php" class="btn btn-primary btn-lg" style="margin: 0.5rem;">Daftar Sekarang</a>
                    <a href="artikel.php" class="btn btn-warning btn-lg" style="margin: 0.5rem;">Jelajahi Artikel</a>
                <?php else: ?>
                    <a href="artikel.php" class="btn btn-primary btn-lg" style="margin: 0.5rem;">Jelajahi Artikel</a>
                    <a href="kontak.php" class="btn btn-warning btn-lg" style="margin: 0.5rem;">Hubungi Kami</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php
$conn->close();
include 'includes/footer.php';
?>