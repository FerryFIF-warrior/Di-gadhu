<?php
session_start();
require_once 'koneksi.php';

if (!$conn) {
    $error = "Koneksi database sedang bermasalah. Silahkan coba lagi nanti!!";

    goto skip_db_process;
}

skip_db_process:

$error = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    header("Location: mainMenu.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM login WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            
            $insert = $conn->prepare("INSERT INTO login (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);

            if ($insert->execute()) {
                header("Location: login.php?status=sukses");
                
                exit();
            } else {
                $error = "Terjadi kesalahan: " . $insert->error;
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Di-Gadhu</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --dark-green: #1f3d2b;
            --forest-green: #2f5d3a;
            --leaf-green: #4f8a5b;
            --light-green: #8ccf9b;
            --water-blue: #4aa3c7;
            --soil-brown: #8b6b4c;
            --soft-cream: #f4f1e6;
        }

        .bg-dark-green { background-color: var(--dark-green); }
        .bg-forest-green { background-color: var(--forest-green); }
        .bg-leaf-green { background-color: var(--leaf-green); }
        .bg-light-green { background-color: var(--light-green); }
        .bg-water-blue { background-color: var(--water-blue); }
        .bg-soil-brown { background-color: var(--soil-brown); }
        .bg-soft-cream { background-color: var(--soft-cream); }

        .text-dark-green { color: var(--dark-green); }
        .text-forest-green { color: var(--forest-green); }
        .text-leaf-green { color: var(--leaf-green); }
        .text-light-green { color: var(--light-green); }
        .text-water-blue { color: var(--water-blue); }
        .text-soil-brown { color: var(--soil-brown); }
        .text-soft-cream { color: var(--soft-cream); }

        .border-dark-green { border-color: var(--dark-green); }
        .border-forest-green { border-color: var(--forest-green); }
        .border-leaf-green { border-color: var(--leaf-green); }
        .border-light-green { border-color: var(--light-green); }
        .border-water-blue { border-color: var(--water-blue); }
        .border-soil-brown { border-color: var(--soil-brown); }
        .border-soft-cream { border-color: var(--soft-cream); }

        .hover-bg-forest-green:hover { background-color: var(--forest-green); }
        .hover-bg-leaf-green:hover { background-color: var(--leaf-green); }
        .hover-text-light-green:hover { color: var(--light-green); }

        .focus-ring-light-green:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--light-green);
        }
    </style>
</head>
<body class="bg-soft-cream font-sans">

    <nav class="bg-dark-green/70 backdrop-blur-sm fixed w-full z-10 p-4 flex justify-between items-center text-white">
        <div class="flex items-center gap-2">
            <img src="../Resource/Logo2.png" alt="logo" class="w-10 h-10 object-contain">
            <h2 class="text-light-green tracking-wide">DI-GADHU</h2>
        </div>
        <ul class="flex gap-6">
            <li><a href="index.php" class="text-light-green font-medium mb-2">Beranda</a></li>
        </ul>
    </nav>

    <div class="min-h-screen flex items-center justify-center px-4 pt-20">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border-t-4 border-light-green">
            <div class="text-center mb-8">
                <img src="../Resource/Logo2.png" alt="Logo" class="w-20 h-20 mx-auto mb-3">
                <h2 class="text-3xl font-bold text-dark-green">Daftar Akun Baru</h2>
                <p class="text-gray-600 mt-2">Silakan isi data diri Anda</p>
            </div>

            <form method="POST" action="">
                <!-- Username -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="username" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-light-green focus:border-transparent" required>
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="contoh@email.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-light-green focus:border-transparent" required>
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Kata Sandi</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-light-green focus:border-transparent" required>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" name="confirm_password" placeholder="Ulangi password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-light-green focus:border-transparent" required>
                </div>

                <button type="submit" class="w-full bg-forest-green hover:bg-leaf-green text-white font-semibold py-3 rounded-lg transition duration-300">Daftar</button>
            </form>

            <?php if (!empty($error)): ?>
                <div class="mt-4 text-center text-red-600"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="mt-4 text-center text-green-600"><?php echo $success; ?></div>
            <?php endif; ?>

            <p class="text-center text-gray-600 mt-6">
                Sudah punya akun? 
                <a href="login.php" class="text-forest-green font-semibold hover:underline">Masuk</a>
            </p>
        </div>
    </div>

    <footer class="bg-dark-green text-white text-center py-4 mt-10">
        <p>Ferry Iqbal Fuadi | V3925006 | Web Programing | 2026</p>
    </footer>
</body>
</html>
