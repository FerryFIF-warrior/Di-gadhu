<?php
require_once 'koneksi.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

function sendResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit();
}

if ($method === 'GET') {
    if ($action === 'tanaman') {
        $result = $conn->query("SELECT * FROM tanaman ORDER BY id DESC");
        $tanaman = $result->fetch_all(MYSQLI_ASSOC);
        sendResponse($tanaman);
    } elseif ($action === 'cuaca') {
        $result = $conn->query("SELECT * FROM cuaca ORDER BY id DESC");
        $cuaca = $result->fetch_all(MYSQLI_ASSOC);
        sendResponse($cuaca);
    } elseif ($action === 'user') {
        $result = $conn->query("SELECT id, username, email, role FROM login WHERE role = 'user' ORDER BY id DESC");
        $users = $result->fetch_all(MYSQLI_ASSOC);
        sendResponse($users);
    } else {
        sendResponse(['error' => 'Invalid action'], 400);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($action) {
        case 'tambah_tanaman':
            $nama = $conn->real_escape_string($data['nama']);
            $musim = $conn->real_escape_string($data['musim']);
            $suhu = $conn->real_escape_string($data['suhu']);
            $deskripsi = $conn->real_escape_string($data['deskripsi'] ?? '');
            $conn->query("INSERT INTO tanaman (nama, musim, suhu, deskripsi) VALUES ('$nama', '$musim', '$suhu', '$deskripsi')");
            sendResponse(['success' => true, 'id' => $conn->insert_id]);
            break;
        case 'edit_tanaman':
            $id = (int)$data['id'];
            $nama = $conn->real_escape_string($data['nama']);
            $musim = $conn->real_escape_string($data['musim']);
            $suhu = $conn->real_escape_string($data['suhu']);
            $deskripsi = $conn->real_escape_string($data['deskripsi'] ?? '');
            $conn->query("UPDATE tanaman SET nama='$nama', musim='$musim', suhu='$suhu', deskripsi='$deskripsi' WHERE id=$id");
            sendResponse(['success' => true]);
            break;
        case 'hapus_tanaman':
            $id = (int)$data['id'];
            $conn->query("DELETE FROM tanaman WHERE id=$id");
            sendResponse(['success' => true]);
            break;
        case 'tambah_cuaca':
            $lokasi = $conn->real_escape_string($data['lokasi']);
            $suhu = (int)$data['suhu'];
            $kelembaban = (int)$data['kelembaban'];
            $curah_hujan = $conn->real_escape_string($data['curah_hujan']);
            $conn->query("INSERT INTO cuaca (lokasi, suhu, kelembaban, curah_hujan) VALUES ('$lokasi', $suhu, $kelembaban, '$curah_hujan')");
            sendResponse(['success' => true]);
            break;
        case 'edit_cuaca':
            $id = (int)$data['id'];
            $lokasi = $conn->real_escape_string($data['lokasi']);
            $suhu = (int)$data['suhu'];
            $kelembaban = (int)$data['kelembaban'];
            $curah_hujan = $conn->real_escape_string($data['curah_hujan']);
            $conn->query("UPDATE cuaca SET lokasi='$lokasi', suhu=$suhu, kelembaban=$kelembaban, curah_hujan='$curah_hujan' WHERE id=$id");
            sendResponse(['success' => true]);
            break;
        case 'hapus_cuaca':
            $id = (int)$data['id'];
            $conn->query("DELETE FROM cuaca WHERE id=$id");
            sendResponse(['success' => true]);
            break;
        case 'tambah_user':
            $username = $conn->real_escape_string($data['username']);
            $email = $conn->real_escape_string($data['email']);
            $role = $conn->real_escape_string($data['role']);
            $password = password_hash('default123', PASSWORD_DEFAULT);
            $conn->query("INSERT INTO login (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')");
            sendResponse(['success' => true]);
            break;
        case 'edit_user':
            $id = (int)$data['id'];
            $username = $conn->real_escape_string($data['username']);
            $email = $conn->real_escape_string($data['email']);
            $role = $conn->real_escape_string($data['role']);
            $conn->query("UPDATE login SET username='$username', email='$email', role='$role' WHERE id=$id");
            sendResponse(['success' => true]);
            break;
        case 'hapus_user':
            $id = (int)$data['id'];
            if ($id == $_SESSION['user_id']) {
                sendResponse(['error' => 'Tidak bisa menghapus akun sendiri'], 400);
            }
            $conn->query("DELETE FROM login WHERE id=$id");
            sendResponse(['success' => true]);
            break;
        default:
            sendResponse(['error' => 'Invalid action'], 400);
    }
} else {
    sendResponse(['error' => 'Method not allowed'], 405);
}
?>
