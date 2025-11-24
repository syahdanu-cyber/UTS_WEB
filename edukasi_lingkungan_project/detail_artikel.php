<?php
require_once 'config/database.php';
include 'includes/header.php';

$conn = getDBConnection();

// Ambil slug dari URL
$slug = isset($_GET['slug']) ? clean_input($_GET['slug']) : '';

if (!$slug) {
    header("Location: artikel.php");
    exit();
}

// Ambil artikel
$query = "SELECT * FROM artikel WHERE slug = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: artikel.php");
    exit();
}

$artikel = $result->fetch_assoc();
$page_title = $artikel['judul'];

// Update views
$update_views = "UPDATE artikel SET views = views + 1 WHERE id = ?";
$stmt_update = $conn->prepare($update_views);
$stmt_update->bind_param("i", $artikel['id']);
$stmt_update->execute();

// Ambil artikel terkait (kategori sama)
$related_query = "SELECT * FROM artikel WHERE kategori = ? AND id != ? ORDER BY RAND() LIMIT 3";
$stmt_related = $conn->prepare($related_query);
$stmt_related->bind_param("si", $artikel['kategori'], $artikel['id']);
$stmt_related->execute();
$related_result = $stmt_related->get_result();
?>

<main>
    <div class="container">
        <section class="content-section">
            <a href="artikel.php" class="btn btn-primary" style="margin-bottom: 1rem;">
                ← Kembali ke Artikel
            </a>
            
            <div class="artikel-image" style="height: 300px; margin-bottom: 2rem; border-radius: 10px; font-size: 4rem;">
                🌿
            </div>
            
            <h2><?php echo htmlspecialchars($artikel['judul']); ?></h2>
            
            <div class="artikel-meta" style="margin-bottom: 2rem; font-size: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 5px;">
                <span>✍️ <strong>Penulis:</strong> <?php echo htmlspecialchars($artikel['penulis']); ?></span> |
                <span>📅 <strong>Tanggal:</strong> <?php echo format_tanggal($artikel['created_at']); ?></span> |
                <span>👁️ <strong>Dibaca:</strong> <?php echo $artikel['views']; ?> kali</span> |
                <span>📂 <strong>Kategori:</strong> <?php echo htmlspecialchars($artikel['kategori']); ?></span>
            </div>
            
            <div style="line-height: 1.8; font-size: 1.1rem; text-align: justify;">
                <?php 
                $konten = nl2br(htmlspecialchars($artikel['konten']));
                // Split menjadi paragraf
                $paragraphs = explode("\n\n", $konten);
                foreach ($paragraphs as $p) {
                    echo "<p style='margin-bottom: 1.5rem;'>$p</p>";
                }
                ?>
            </div>
            
            <!-- Share Section -->
            <div style="margin-top: 3rem; padding: 1.5rem; background: #f8f9fa; border-radius: 10px; text-align: center;">
                <h3>📤 Bagikan Artikel Ini</h3>
                <p style="color: #7f8c8d;">Bantu sebarkan informasi penting tentang lingkungan!</p>
                <div style="margin-top: 1rem;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                       target="_blank" class="btn btn-primary">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($artikel['judul']); ?>" 
                       target="_blank" class="btn btn-primary">Twitter</a>
                    <a href="https://wa.me/?text=<?php echo urlencode($artikel['judul'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                       target="_blank" class="btn btn-success">WhatsApp</a>
                </div>
            </div>
        </section>

        <?php if ($related_result->num_rows > 0): ?>
        <section class="content-section">
            <h2>📖 Artikel Terkait</h2>
            <div class="artikel-grid">
                <?php while($related = $related_result->fetch_assoc()): ?>
                    <div class="artikel-card">
                        <div class="artikel-image">🌿</div>
                        <div class="artikel-content">
                            <h3><?php echo htmlspecialchars($related['judul']); ?></h3>
                            <div class="artikel-meta">
                                <span>📅 <?php echo format_tanggal($related['created_at']); ?></span>
                                <span>👁️ <?php echo $related['views']; ?></span>
                            </div>
                            <p class="artikel-excerpt">
                                <?php echo excerpt($related['konten'], 100); ?>
                            </p>
                            <a href="detail_artikel.php?slug=<?php echo $related['slug']; ?>" class="btn btn-primary">
                                Baca →
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>

<?php
$conn->close();
include 'includes/footer.php';
?>