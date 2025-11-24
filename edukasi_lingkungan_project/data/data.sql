-- Database: edukasi_lingkungan

CREATE DATABASE IF NOT EXISTS edukasi_lingkungan;
USE edukasi_lingkungan;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel artikel
CREATE TABLE IF NOT EXISTS artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    konten TEXT NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_kategori (kategori),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel kontak
CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subjek VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@edukasi-lingkungan.com', 'Admin123', 'admin');

-- Insert sample artikel
INSERT INTO artikel (judul, slug, konten, penulis, kategori, views) VALUES
('Pentingnya Menjaga Kelestarian Hutan', 'pentingnya-menjaga-kelestarian-hutan', 'Hutan merupakan paru-paru dunia yang sangat penting bagi kelangsungan hidup makhluk hidup di bumi. Hutan tidak hanya menyediakan oksigen, tetapi juga menjadi habitat bagi berbagai spesies flora dan fauna.\n\nDeforestasi atau penebangan hutan secara massal telah menjadi ancaman serius bagi ekosistem global. Setiap tahun, jutaan hektar hutan hilang akibat aktivitas manusia seperti pembukaan lahan pertanian, penambangan, dan pembangunan infrastruktur.\n\nUntuk itu, kita semua perlu berperan aktif dalam menjaga kelestarian hutan. Beberapa langkah yang dapat dilakukan antara lain: mendukung program reboisasi, tidak membuang sampah sembarangan di kawasan hutan, dan mengurangi penggunaan produk-produk yang merusak hutan.\n\nDengan menjaga hutan, kita juga turut menjaga keseimbangan alam dan masa depan generasi mendatang.', 'Admin', 'Hutan', 125),

('Cara Mengelola Sampah dengan Bijak', 'cara-mengelola-sampah-dengan-bijak', 'Sampah menjadi salah satu permasalahan lingkungan yang paling serius di era modern ini. Setiap hari, manusia menghasilkan ton sampah yang jika tidak dikelola dengan baik dapat mencemari lingkungan.\n\nPrinsip 3R (Reduce, Reuse, Recycle) adalah kunci dalam pengelolaan sampah yang efektif. Reduce berarti mengurangi penggunaan barang-barang yang tidak perlu. Reuse berarti menggunakan kembali barang-barang yang masih bisa dipakai. Recycle berarti mendaur ulang sampah menjadi produk baru yang bermanfaat.\n\nMemisahkan sampah organik dan anorganik juga sangat penting. Sampah organik dapat dijadikan kompos yang berguna untuk tanaman, sementara sampah anorganik seperti plastik, kertas, dan logam dapat didaur ulang.\n\nDengan mengelola sampah dengan bijak, kita dapat mengurangi pencemaran lingkungan dan menciptakan lingkungan yang lebih bersih dan sehat.', 'Admin', 'Sampah', 98),

('Energi Terbarukan untuk Masa Depan Berkelanjutan', 'energi-terbarukan-untuk-masa-depan-berkelanjutan', 'Energi terbarukan adalah solusi untuk mengatasi krisis energi dan perubahan iklim global. Berbeda dengan energi fosil yang terbatas dan mencemari lingkungan, energi terbarukan berasal dari sumber yang tidak akan habis seperti matahari, angin, air, dan panas bumi.\n\nPenggunaan energi terbarukan memiliki banyak keuntungan. Selain ramah lingkungan, energi terbarukan juga dapat mengurangi ketergantungan pada bahan bakar fosil dan menurunkan emisi gas rumah kaca yang menyebabkan pemanasan global.\n\nBeberapa contoh energi terbarukan yang dapat diterapkan dalam kehidupan sehari-hari antara lain panel surya untuk menghasilkan listrik, turbin angin untuk pembangkit listrik, dan biogas dari limbah organik.\n\nPemerintah dan masyarakat perlu bekerja sama untuk mendorong penggunaan energi terbarukan melalui kebijakan, insentif, dan edukasi. Dengan beralih ke energi terbarukan, kita dapat menciptakan masa depan yang lebih berkelanjutan untuk generasi mendatang.', 'Admin', 'Energi', 156),

('Konservasi Air: Menjaga Sumber Kehidupan', 'konservasi-air-menjaga-sumber-kehidupan', 'Air adalah sumber kehidupan yang sangat penting bagi semua makhluk hidup. Namun, ketersediaan air bersih semakin terbatas akibat polusi, perubahan iklim, dan penggunaan yang tidak bijak.\n\nKonservasi air adalah upaya untuk menjaga dan menghemat penggunaan air agar ketersediaannya tetap terjaga. Setiap orang dapat berkontribusi dalam konservasi air melalui tindakan sederhana dalam kehidupan sehari-hari.\n\nBeberapa cara menghemat air antara lain: mematikan keran saat tidak digunakan, memperbaiki keran yang bocor, menggunakan air bekas cucian untuk menyiram tanaman, dan menampung air hujan untuk kebutuhan non-konsumsi.\n\nSelain menghemat, menjaga kualitas air juga penting. Hindari membuang sampah atau limbah ke sungai dan sumber air lainnya. Dengan menjaga air, kita menjaga kelangsungan hidup semua makhluk di bumi.', 'Admin', 'Air', 87),

('Mengurangi Polusi Udara di Perkotaan', 'mengurangi-polusi-udara-di-perkotaan', 'Polusi udara menjadi masalah serius terutama di kota-kota besar. Asap kendaraan, industri, dan pembakaran sampah menjadi penyumbang utama polusi udara yang dapat membahayakan kesehatan manusia.\n\nDampak polusi udara sangat luas, mulai dari gangguan pernapasan, penyakit jantung, hingga perubahan iklim global. Anak-anak dan lansia merupakan kelompok yang paling rentan terhadap dampak polusi udara.\n\nUntuk mengurangi polusi udara, kita dapat melakukan berbagai tindakan seperti: menggunakan transportasi umum atau kendaraan ramah lingkungan, menanam pohon di sekitar rumah, mengurangi penggunaan bahan bakar fosil, dan tidak membakar sampah.\n\nPemerintah juga perlu menerapkan regulasi ketat terhadap industri dan kendaraan bermotor untuk mengendalikan emisi polutan. Udara bersih adalah hak setiap orang, dan kita semua bertanggung jawab untuk menjaganya.', 'Admin', 'Udara', 112),

('Melindungi Satwa Liar dari Kepunahan', 'melindungi-satwa-liar-dari-kepunahan', 'Keanekaragaman hayati bumi semakin terancam dengan semakin banyaknya spesies satwa yang terancam punah. Perburuan liar, perusakan habitat, dan perubahan iklim menjadi ancaman utama bagi kelangsungan hidup satwa liar.\n\nKepunahan satwa tidak hanya merugikan ekosistem, tetapi juga mengganggu keseimbangan alam. Setiap spesies memiliki peran penting dalam rantai makanan dan ekosistem.\n\nUpaya konservasi satwa dapat dilakukan melalui berbagai cara: melindungi habitat alami, melarang perburuan liar, mendukung program penangkaran, dan meningkatkan kesadaran masyarakat tentang pentingnya melindungi satwa.\n\nKita semua dapat berkontribusi dengan tidak membeli produk dari satwa liar, melaporkan aktivitas perburuan ilegal, dan mendukung organisasi konservasi. Mari bersama-sama menjaga keanekaragaman hayati untuk masa depan yang lebih baik.', 'Admin', 'Satwa', 94);