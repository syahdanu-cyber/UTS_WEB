<?php
require_once '../../config/database.php';
check_admin();

$conn = getDBConnection();

// Handle search
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';
$where = $search ? "WHERE judul LIKE '%$search%' OR konten LIKE '%$search%' OR kategori LIKE '%$search%'" : '';

$query = "SELECT * FROM artikel $where ORDER BY created_at DESC";
$result = $conn->query($query);

$page_title = 'Kelola Artikel';
$base_url = '../../';
include '../../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <h2>📚 Kelola Artikel</h2>
                <a href="tambah.php" class="btn btn-success">➕ Tambah Artikel Baru</a>
            </div>

            <!-- Search Bar -->
            <form method="GET" action="" style="margin-bottom: 2rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" name="search" placeholder="🔍 Cari artikel..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           style="width: 100%; padding: 1rem;">
                </div>
            </form>

            <p style="color: #7f8c8d; margin-bottom: 1rem;">
                Total: <strong><?php echo $result->num_rows; ?></strong> artikel
            </p>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Views</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result->num_rows > 0):
                            $no = 1;
                            while($artikel = $result->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td style="max-width: 300px;">
                                <?php echo htmlspecialchars($artikel['judul']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($artikel['kategori']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['penulis']); ?></td>
                            <td><?php echo $artikel['views']; ?></td>
                            <td><?php echo date('d M Y', strtotime($artikel['created_at'])); ?></td>
                            <td style="white-space: nowrap;">
                                <a href="../../detail_artikel.php?slug=<?php echo $artikel['slug']; ?>" 
                                   class="btn btn-primary" target="_blank" title="Lihat">👁️</a>
                                <a href="edit.php?id=<?php echo $artikel['id']; ?>" 
                                   class="btn btn-warning" title="Edit">✏️</a>
                                <a href="hapus.php?id=<?php echo $artikel['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirmDelete('Apakah Anda yakin ingin menghapus artikel ini?')" 
                                   title="Hapus">🗑️</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                Tidak ada artikel ditemukan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                <a href="../index.php" class="btn btn-primary">← Kembali ke Dashboard</a>
            </div>
        </section>
    </div>
</main>

<?php
$conn->close();
include '../../includes/footer.php';
?>