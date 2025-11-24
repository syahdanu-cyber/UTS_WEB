<?php
require_once '../../config/database.php';
check_admin();

$conn = getDBConnection();

// Handle search
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';
$where = $search ? "AND (username LIKE '%$search%' OR email LIKE '%$search%')" : '';

$query = "SELECT * FROM users WHERE role='user' $where ORDER BY created_at DESC";
$result = $conn->query($query);

$page_title = 'Kelola Users';
$base_url = '../../';
include '../../includes/header.php';
?>

<main>
    <div class="container">
        <section class="content-section">
            <h2>👥 Kelola Users</h2>
            
            <!-- Search Bar -->
            <form method="GET" action="" style="margin-bottom: 2rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" name="search" placeholder="🔍 Cari user..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           style="width: 100%; padding: 1rem;">
                </div>
            </form>

            <p style="color: #7f8c8d; margin-bottom: 1rem;">
                Total: <strong><?php echo $result->num_rows; ?></strong> users
            </p>
            
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
                        if ($result->num_rows > 0):
                            $no = 1;
                            while($user = $result->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></td>
                            <td style="white-space: nowrap;">
                                <a href="edit.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-warning" title="Edit">✏️</a>
                                <a href="hapus.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirmDelete('Apakah Anda yakin ingin menghapus user <?php echo htmlspecialchars($user['username']); ?>?')" 
                                   title="Hapus">🗑️</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                Tidak ada user ditemukan.
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