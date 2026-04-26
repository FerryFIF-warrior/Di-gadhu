<?php
session_start();
require_once'koneksi.php';

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$email= $_SESSION['user_email'] ?? '';
$role = $_SESSION['role'] ?? '';

if (!$isLoggedIn && isset($_COOKIE['user_login'])) {
    $userId = $_COOKIE['user_login'];
    $stmt = $conn->prepare("SELECT id, username, email, role FROM login WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['role'] = $row['role'];

        $isLoggedIn = true;
        $username = $row['username'];
        $email = $row['email'];
        $role = $row['role'];
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Di-Gadhu | System Prediksi Cuaca Lokal</title>
    <link rel="stylesheet" href="/Di-Gadhu/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <nav>
        <div class="logo-web">
            <img src="/Di-Gadhu/Resource/Logo2.png" alt="Logo">
            <h2>DI-GADHU</h2>
        </div>
            <ul class="navigasi">
                <li><a href="/Di-Gadhu/budidaya.html">Budidaya</a></li>
                <li><a href="/Di-Gadhu/analisis.HTML">Analisis</a></li>
                <li><a href="/Di-Gadhu/tanaman.HTML">Jenis Tanaman</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="user-menu">
                        <a href="#" class="user-profile">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="user-dropdown">
                            <li><span class="dropdown-item"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></span></li>
                            <li><span class="dropdown-item"><i class="fas fa-tag"></i> Role: <?php echo htmlspecialchars($role === 'admin' ? 'Admin' : 'User'); ?></span></li>
                            <?php if ($role === 'admin'): ?>
                                <li><a href="adminDash.php"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</a></li>
                            <?php endif; ?>
                            <li><a href="/Di-Gadhu/api/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="/Di-Gadhu/api/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
    </nav>

    <section class="block">
        <div class="block-text">
            <h1>Sistem Informasi Rekomendasi Jadwal Tanam Berbasis data Cuaca Lokal</h1>
            <p>Platform ini bertujuan untuk membantu menentukan waktu tanam yang ideal berdasarkan analisis data cuaca</p>
            <button class="bAnalis">Mulai Analisis</button>
            <button class="fitur">Lihat Fitur</button>
        </div>
    </section>

    <section class="analysis" id="analysis-section">
        <div class="analysis-container">
            <div class="analysis-left">
                <img src="/Di-Gadhu/Resource/3.png" alt="karakter">
            </div>
            <div class="analysis-right">
                <h2>Analisis Jadwal Tanam</h2>
                <div class="form-group">
                    <label>Desa</label>
                    <input type="text" id="desa">
                </div>
                <div class="form-group">
                    <label>Kabupaten</label>
                    <input type="text" id="kabupaten">
                </div>
                <div class="form-group">
                    <label>Provinsi</label>
                    <input type="text" id="provinsi">
                </div>

                <div class="form-group">
                    <label>Jenis Tanaman(Opsional)</label>

                    <select id="tanaman">
                        <option value="">--- Pilih Tanaman ---</option>
                        <option value="padi">Padi</option>
                        <option value="jagung">Jagung</option>
                        <option value="cabai">Cabai</option>
                        <option value="terong">Terong</option>
                        <option value="kacang">Kacang Tanah</option>
                    </select>
                </div>
                <button onclick="analisisCuaca()">Mulai Analisis</button>
            </div>
        </div>
        <div id="hasilAnalisis"></div>
    </section>

    <section class="section reveal">
        <h2>Jenis Tanaman</h2>

        <div class="card1">
            <div class="card2">
                <img src="/Di-Gadhu/Resource/padi.png" alt="Padi">
                <h3>Padi</h3>
                <p>Rekomendasi jadwal tanam ketika curah hujan sedang sampai tinggi.</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Jagung.png" alt="Jagung">
                <h3>Jagung</h3>
                <p>Rekomendasi jadwal tanam setelah panen padi dengan curah hujan sedang sampai rendah.</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Cabai.png" alt="Cabai">
                <h3>Cabai</h3>
                <p>Rekomendasi jadwal tanam ketika curah hujan rendah atau saat pergantian musim kemarau.</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Kacang-tanah.png" alt="Kacang Tanah">
                <h3>Kacang Tanah</h3>
                <p>Rekomendasi jadwal tanam ketika musim kemarau panjang karena ketahanannya terhadap kemarau.</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Terong.png" alt="Terong">
                <h3>Terong</h3>
                <p>Rekomendasi jadwal tanam ketika curah hujan rendah atau saat pergantian musim.</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Tomat.png" alt="Tomat">
                <h3>Tomat</h3>
                <p>Rekomendasi jadwal tanam ketika mulai pergantian musim dari musim hujan ke kemarau</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Wortel.png" alt="wortel">
                <h3>Wortel</h3>
                <p>Rekomendasi jadwal tanam ketika awal musim kemarau sekitar buan april-juni atau september - oktober</p>
            </div>
            <div class="card2">
                <img src="/Di-Gadhu/Resource/Bawang_putih.png" alt="Bawang Putih">
                <h3>Bawang Putih</h3>
                <p>Rekomendasi jadwal tanam ketika suhu lingkungan sekitar memiliki suhu agak dingin lebih cocok di daerah dataran tinggi</p>
            </div>
        </div>
    </section>

    <section class="workflow">
        <h2 class="workflow-title">Bagaimana Sistem bekerja?</h2>
        <div class="step">
            <div class="section-step">
                <div class="circle"></div>
                <p>Input data</p>
            </div>
            <div class="section-step">
                <div class="circle"></div>
                <p>Analisis</p>
            </div>
            <div class="section-step">
                <div class="circle"></div>
                <p>Evaluasi</p>
            </div>
            <div class="section-step">
                <div class="circle"></div>
                <p>Rekomendasi</p>
            </div>
        </div>
    </section>

    <section class="section fade-section">
        <div class="about">
            <div>
                <h2>Tentang Sistem</h2>
                <p>Sistem ini bertujuan untuk membantu menentukan waktu tanam yang ideal berdasarkan analisis data cuaca</p>
            </div>
            <div>
                <ul>
                    <li>Berbasis data cuaca</li>
                    <li>Rule-based analysis</li>
                    <li>Semi-adaptif</li>
                </ul>
            </div>
        </div>
    </section>

    <footer>
        <p>Ferry Iqbal Fuadi | V3925006 | Web Programing | 2026</p>
    </footer>
    <script src="/Di-Gadhu/script.js"></script>
</body>
</html>