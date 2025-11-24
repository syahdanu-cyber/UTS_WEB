<?php
require_once '../config/database.php';
check_admin();

$conn = getDBConnection();

// Hitung statistik
$total_artikel = $conn->query("SELECT COUNT(*) as total FROM artikel")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user'")->fetch_assoc()['total'];
$total_views = $conn->query("SELECT SUM(views) as total FROM artikel")->fetch_assoc()['total'];
$total_kontak = $conn->query("SELECT COUNT(*) as total FROM kontak")->fetch_assoc()['total'];

// Artikel minggu ini
$artikel_minggu_ini = $conn->query("SELECT COUNT(*) as total FROM artikel WHERE WEEK(created_at) = WEEK(NOW())")->fetch_assoc()['total'];

// User baru minggu ini
$user_baru = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user' AND WEEK(created_at) = WEEK(NOW())")->fetch_assoc()['total'];

// Artikel terpopuler
$top_artikel = $conn->query("SELECT judul, views FROM artikel ORDER BY views DESC LIMIT 1")->fetch_assoc();

// Statistik per kategori
$stats_kategori = $conn->query("SELECT kategori, COUNT(*) as jumlah, SUM(views) as total_views FROM artikel GROUP BY kategori ORDER BY jumlah DESC");

$page_title = 'Dashboard Admin';
$base_url = '../';
include '../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 2rem;">
                <div>
                    <h2>⚙️ Dashboard Admin</h2>
                    <p style="color: #7f8c8d; margin-top: 0.5rem;">
                        Selamat datang, <strong style="color: #2ecc71;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! 
                        Kelola konten edukasi lingkungan Anda di sini.
                    </p>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="artikel/tambah.php" class="btn btn-success">➕ Tambah Artikel</a>
                </div>
            </div>
            
            <!-- Main Statistics -->
            <div class="dashboard-cards">
                <div class="dashboard-card" style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
                    <h3 style="color: white;"><?php echo $total_artikel; ?></h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Artikel</p>
                    <small style="color: rgba(255,255,255,0.8);">+<?php echo $artikel_minggu_ini; ?> minggu ini</small>
                    <a href="artikel/index.php" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Kelola Artikel</a>
                </div>
                
                <div class="dashboard-card" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                    <h3 style="color: white;"><?php echo $total_users; ?></h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Users</p>
                    <small style="color: rgba(255,255,255,0.8);">+<?php echo $user_baru; ?> minggu ini</small>
                    <a href="users/index.php" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Kelola Users</a>
                </div>
                
                <div class="dashboard-card" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">👁️</div>
                    <h3 style="color: white;"><?php echo number_format($total_views ? $total_views : 0); ?></h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Views</p>
                    <small style="color: rgba(255,255,255,0.8);">Semua artikel</small>
                </div>
                
                <div class="dashboard-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📧</div>
                    <h3 style="color: white;"><?php echo $total_kontak; ?></h3>
                    <p style="color: rgba(255,255,255,0.9);">Pesan Kontak</p>
                    <small style="color: rgba(255,255,255,0.8);">Total pesan masuk</small>
                </div>
            </div>
        </section>

        <!-- Quick Stats -->
        <section class="content-section">
            <h2>📊 Statistik Cepat</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2ecc71;">
                    <h3 style="color: #2c3e50; font-size: 1.2rem; margin-bottom: 0.5rem;">🏆 Artikel Terpopuler</h3>
                    <p style="color: #555; margin-bottom: 0.3rem;"><?php echo htmlspecialchars($top_artikel['judul']); ?></p>
                    <small style="color: #7f8c8d;"><?php echo number_format($top_artikel['views']); ?> views</small>
                </div>
                
                <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #3498db;">
                    <h3 style="color: #2c3e50; font-size: 1.2rem; margin-bottom: 0.5rem;">📈 Rata-rata Views</h3>
                    <p style="color: #555; margin-bottom: 0.3rem; font-size: 1.5rem; font-weight: 600;">
                        <?php echo $total_artikel > 0 ? number_format($total_views / $total_artikel, 0) : 0; ?>
                    </p>
                    <small style="color: #7f8c8d;">views per artikel</small>
                </div>
                
                <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #e74c3c;">
                    <h3 style="color: #2c3e50; font-size: 1.2rem; margin-bottom: 0.5rem;">📂 Total Kategori</h3>
                    <p style="color: #555; margin-bottom: 0.3rem; font-size: 1.5rem; font-weight: 600;">
                        <?php echo $stats_kategori->num_rows; ?>
                    </p>
                    <small style="color: #7f8c8d;">kategori aktif</small>
                </div>
            </div>
        </section>

        <!-- Statistik per Kategori -->
        <section class="content-section">
            <h2>📂 Statistik per Kategori</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Artikel</th>
                            <th>Total Views</th>
                            <th>Rata-rata Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($stat = $stats_kategori->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($stat['kategori']); ?></strong></td>
                            <td><?php echo $stat['jumlah']; ?> artikel</td>
                            <td><?php echo number_format($stat['total_views']); ?> views</td>
                            <td><?php echo number_format($stat['total_views'] / $stat['jumlah'], 0); ?> views</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- Artikel Terbaru -->
        <section class="content-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>📖 Artikel Terbaru</h2>
                <a href="artikel/index.php" class="btn btn-primary">Lihat Semua →</a>
            </div>
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
                        $query = "SELECT * FROM artikel ORDER BY created_at DESC LIMIT 5";
                        $result = $conn->query($query);
                        $no = 1;
                        while($artikel = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td style="max-width: 300px;"><?php echo htmlspecialchars($artikel['judul']); ?></td>
                            <td><span class="kategori-badge"><?php echo htmlspecialchars($artikel['kategori']); ?></span></td>
                            <td><?php echo htmlspecialchars($artikel['penulis']); ?></td>
                            <td><strong><?php echo number_format($artikel['views']); ?></strong></td>
                            <td><?php echo date('d M Y', strtotime($artikel['created_at'])); ?></td>
                            <td style="white-space: nowrap;">
                                <a href="../detail_artikel.php?slug=<?php echo $artikel['slug']; ?>" 
                                   class="btn btn-primary" target="_blank" title="Lihat">👁️</a>
                                <a href="artikel/edit.php?id=<?php echo $artikel['id']; ?>" 
                                   class="btn btn-warning" title="Edit">✏️</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- User Terbaru -->
        <section class="content-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>👥 User Terbaru</h2>
                <a href="users/index.php" class="btn btn-primary">Lihat Semua →</a>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users WHERE role='user' ORDER BY created_at DESC LIMIT 5";
                        $result = $conn->query($query);
                        $no = 1;
                        while($user = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></td>
                            <td>
                                <a href="users/edit.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Pesan Kontak Terbaru -->
        <section class="content-section">
            <h2>📧 Pesan Kontak Terbaru</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM kontak ORDER BY created_at DESC LIMIT 5";
                        $result = $conn->query($query);
                        $no = 1;
                        while($kontak = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($kontak['nama']); ?></td>
                            <td><?php echo htmlspecialchars($kontak['email']); ?></td>
                            <td style="max-width: 300px;"><?php echo htmlspecialchars($kontak['subjek']); ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($kontak['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<?php
$conn->close();
include '../includes/footer.php';
?>