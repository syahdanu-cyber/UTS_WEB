<?php
require_once 'config/database.php';
$page_title = 'Artikel Edukasi';
include 'includes/header.php';

$conn = getDBConnection();

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter kategori
$kategori = isset($_GET['kategori']) ? clean_input($_GET['kategori']) : '';
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';

// Build WHERE clause
$where_conditions = [];
$params = [];
$types = '';

if ($kategori) {
    $where_conditions[] = "kategori = ?";
    $params[] = $kategori;
    $types .= 's';
}

if ($search) {
    $where_conditions[] = "(judul LIKE ? OR konten LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'ss';
}

$where = $where_conditions ? "WHERE " . implode(" AND ", $where_conditions) : '';

// Hitung total artikel
$count_query = "SELECT COUNT(*) as total FROM artikel $where";
$stmt_count = $conn->prepare($count_query);
if ($params) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$total_articles = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_articles / $limit);

// Ambil artikel
$query = "SELECT * FROM artikel $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Ambil semua kategori
$kategori_query = "SELECT DISTINCT kategori FROM artikel ORDER BY kategori";
$kategori_result = $conn->query($kategori_query);
?>

<section class="hero">
    <div class="container">
        <h2>📚 Artikel Edukasi Lingkungan</h2>
        <p>Temukan berbagai artikel menarik seputar lingkungan dan keberlanjutan</p>
    </div>
</section>

<main>
    <div class="container">
        <section class="content-section">
            <!-- Search Bar -->
            <form method="GET" action="artikel.php" style="margin-bottom: 2rem;">
                <div class="form-group">
                    <input type="text" 
                           name="search" 
                           placeholder="🔍 Cari artikel..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           style="width: 100%; padding: 1rem;">
                </div>
                <?php if ($kategori): ?>
                    <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
                <?php endif; ?>
            </form>

            <!-- Filter Kategori -->
            <div style="margin-bottom: 2rem;">
                <h3>🏷️ Filter Kategori:</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem;">
                    <a href="artikel.php<?php echo $search ? '?search=' . urlencode($search) : ''; ?>" 
                       class="btn <?php echo !$kategori ? 'btn-success' : 'btn-primary'; ?>">
                        Semua
                    </a>
                    <?php while($kat = $kategori_result->fetch_assoc()): ?>
                        <a href="artikel.php?kategori=<?php echo urlencode($kat['kategori']); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="btn <?php echo $kategori == $kat['kategori'] ? 'btn-success' : 'btn-primary'; ?>">
                            <?php echo htmlspecialchars($kat['kategori']); ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Info hasil -->
            <p style="color: #7f8c8d; margin-bottom: 1rem;">
                Menampilkan <?php echo $result->num_rows; ?> dari <?php echo $total_articles; ?> artikel
                <?php if ($kategori): ?>
                    dalam kategori <strong><?php echo htmlspecialchars($kategori); ?></strong>
                <?php endif; ?>
                <?php if ($search): ?>
                    dengan kata kunci <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                <?php endif; ?>
            </p>

            <!-- Grid Artikel -->
            <div class="artikel-grid">
                <?php if($result->num_rows > 0): ?>
                    <?php while($artikel = $result->fetch_assoc()): ?>
                        <div class="artikel-card">
                            <div class="artikel-image">🌿</div>
                            <div class="artikel-content">
                                <h3><?php echo htmlspecialchars($artikel['judul']); ?></h3>
                                <div class="artikel-meta">
                                    <span>📅 <?php echo format_tanggal($artikel['created_at']); ?></span>
                                    <span>👁️ <?php echo $artikel['views']; ?></span>
                                    <span>📂 <?php echo htmlspecialchars($artikel['kategori']); ?></span>
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
                <?php else: ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
                        <p style="font-size: 1.2rem; color: #7f8c8d;">
                            😔 Tidak ada artikel yang ditemukan.
                        </p>
                        <a href="artikel.php" class="btn btn-primary" style="margin-top: 1rem;">
                            Lihat Semua Artikel
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
                <div style="text-align: center; margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                    <?php if($page > 1): ?>
                        <a href="artikel.php?page=<?php echo $page-1; ?><?php echo $kategori ? '&kategori='.$kategori : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="btn btn-primary">← Sebelumnya</a>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                            <a href="artikel.php?page=<?php echo $i; ?><?php echo $kategori ? '&kategori='.$kategori : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                               class="btn <?php echo $page == $i ? 'btn-success' : 'btn-primary'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php elseif($i == $page - 3 || $i == $page + 3): ?>
                            <span style="padding: 0.5rem;">...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if($page < $total_pages): ?>
                        <a href="artikel.php?page=<?php echo $page+1; ?><?php echo $kategori ? '&kategori='.$kategori : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="btn btn-primary">Selanjutnya →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php
$conn->close();
include 'includes/footer.php';
?>