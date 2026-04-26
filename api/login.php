<?php
session_start();
require_once 'koneksi.php';
if (!$conn) {
    $error = "Koneksi database bermasalah. silahkan cobalagi nanti!!";

    goto skip_db_process;
} 

skip_db_process:

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: adminDash.php");
    } else {
        header("Location: mainMenu.php");
    }
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $error = "Username/email dan password harus diisi!";
    } else {
        
        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM login WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($remember) {
                    setcookie("user_login", $user['id'], time() + (86400 * 30), "/");
                }

                //redirec
                if ($user['role'] === 'admin') {
                    header("Location: admindash.php");
                } else {
                    header("Location: mainMenu.php");
                }
                exit();

            } else {
                $error = "Username/email atau password salah!";
            }
        } else {
            $error = "Username/email atau password salah!";
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
    <title>Login Di-Gadhu</title>

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

    <nav class="bg-dark-green/70 backdrop-blur-sm fixed w-full z-10 p-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <img src="../Resource/Logo2.png" alt="logo" class="w-10 h-10 object-contain">
            <h2 class="text-light-green tracking-wide">DI-GADHU</h2>
        </div>
        <ul class="flex gap-6">
            <li><a href="../index.html" class="text-light-green font-medium mb-2">Beranda</a></li>
        </ul>
    </nav>

    <div class="min-h-screen flex items-center justify-center px-4 pt-20">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border-t-4 border-light-green">
            <div class="text-center mb-8">
                <img src="../Resource/Logo2.png" alt="Logo" class="w-20 h-20 mx-auto mb-3">
                <h2 class="text-3xl font-bold text-dark-green">Masuk ke DI-GADHU</h2>
                <p class="text-gray-600 mt-2">Silakan masuk untuk melanjutkan</p>
            </div>

            <form method="POST" action="">
                <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                    <div class="mb-4 text-center text-green-600 bg-green-100 p-3 rounded-lg">
                        Pendaftaran berhasil! Silakan login dengan akun barumu.
                    </div>
                <?php endif; ?>
                <!-- Username atau Email -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Username atau Email</label>
                    <input type="text" name="username" placeholder="username atau email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-light-green focus:border-transparent" required>
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Kata Sandi</label>
                    <input type="password" name="password" placeholder="*********" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-light-green focus:border-transparent" required>
                </div>

                <!-- Ingat saya & Lupa password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-forest-green focus:ring-light-green">
                        <span class="ml-2 text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-forest-green hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full bg-forest-green hover:bg-leaf-green text-white font-semibold py-3 rounded-lg transition duration-300">Masuk</button>
            </form>

            <?php if (!empty($error)): ?>
                <div class="mt-4 text-center text-red-600"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <p class="text-center text-gray-600 mt-6">
                Belum punya akun?
                <a href="register.php" class="text-forest-green font-semibold hover:underline">Daftar</a>
            </p>
        </div>
    </div>

    <footer class="bg-dark-green text-white text-center py-4 mt-10">
        <p>Ferry Iqbal Fuadi | V3925006 | Web Programing | 2026</p>
    </footer>
</body>
</html>
