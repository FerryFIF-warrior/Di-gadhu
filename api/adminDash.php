<?php
require_once'koneksi.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Di-Gadhu</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../adminDash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="logo-web">
            <img src="../Resource/Logo2.png" alt="Logo">
            <h2>DI-GADHU</h2>
        </div>
        <ul class="navigasi">
            <li><a href="mainMenu.php">Beranda</a></li>
            <li><a href="../analisis.HTML">Analisis</a></li>
            <li><a href="../tanaman.HTML">Jenis Tanaman</a></li>
            <li class="user-menu">
                <a href="#" class="user-profile">
                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <ul class="user-dropdown">
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <h2><i class="fas fa-tachometer-alt"></i> Admin Menu</h2>
            <ul>
                <li onclick="loadPage('dashboard')"><i class="fas fa-home"></i> Dashboard</li>
                <li onclick="loadPage('tanaman')"><i class="fas fa-seedling"></i> Data Tanaman</li>
                <li onclick="loadPage('cuaca')"><i class="fas fa-cloud-sun-rain"></i> Data Cuaca</li>
                <li onclick="loadPage('user')"><i class="fas fa-users"></i> Manajemen User</li>
                <li onclick="loadPage('statistik')"><i class="fas fa-chart-line"></i> Statistik</li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-content" id="contentArea">
            <div class="admin-card">
                <h2><i class="fas fa-chalkboard-user"></i> Dashboard</h2>
                <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> di panel Admin Di-Gadhu.</p>
                <p>Gunakan menu di samping untuk mengelola data.</p>
            </div>
        </main>
    </div>

    <script src="../adminDash.js"></script>
</body>
</html>
