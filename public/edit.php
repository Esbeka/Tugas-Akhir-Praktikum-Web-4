<?php
require_once '../includes/auth.php';
require_login();

$pdo = require_once '../includes/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT id, nama, telepon, email FROM kontak WHERE id = ?");
$stmt->execute([$id]);
$kontak = $stmt->fetch();

if (!$kontak) {
    header('Location: index.php');
    exit();
}

$errors = [];
$nama = $kontak['nama'];
$telepon = $kontak['telepon'];
$email = $kontak['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_baru = trim($_POST['nama']);
    $telepon_baru = trim($_POST['telepon']);
    $email_baru = trim($_POST['email']);

    if (empty($nama_baru)) {
        $errors['nama'] = "Nama wajib diisi.";
    }
    if (empty($telepon_baru)) {
        $errors['telepon'] = "Nomor Telepon wajib diisi.";
    } elseif (!preg_match('/^[0-9]+$/', $telepon_baru)) {
        $errors['telepon'] = "Nomor Telepon hanya boleh berisi angka.";
    }
    if (!empty($email_baru) && !filter_var($email_baru, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format Email tidak valid.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM kontak WHERE id != ? AND (telepon = ? OR (email IS NOT NULL AND email = ?))");
        $stmt->execute([$id, $telepon_baru, $email_baru]);
        if ($stmt->fetchColumn() > 0) {
            $errors['duplicate'] = "Nomor Telepon atau Email sudah terdaftar pada kontak lain.";
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE kontak SET nama = ?, telepon = ?, email = ? WHERE id = ?");
            $stmt->execute([$nama_baru, $telepon_baru, $email_baru ?: null, $id]);
            
            $_SESSION['message'] = "Kontak $nama_baru berhasil diperbarui!";
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            $errors['db'] = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
        }
    }
    
    $nama = $nama_baru;
    $telepon = $telepon_baru;
    $email = $email_baru;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kontak</title>
    <script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        background-color: #f5f5dc; 
    }
</style>
</head>
<body class="p-8">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Kontak: <?= htmlspecialchars($kontak['nama']) ?></h2>
        
        <?php if (!empty($errors['db']) || !empty($errors['duplicate'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?= $errors['db'] ?? $errors['duplicate'] ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">Nama *</label>
                <input class="shadow appearance-none border <?= isset($errors['nama']) ? 'border-red-500' : '' ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama" type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                <?php if (isset($errors['nama'])): ?>
                    <p class="text-red-500 text-xs italic mt-1"><?= $errors['nama'] ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telepon">Telepon *</label>
                <input class="shadow appearance-none border <?= isset($errors['telepon']) ? 'border-red-500' : '' ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="telepon" type="tel" name="telepon" value="<?= htmlspecialchars($telepon) ?>" required>
                <?php if (isset($errors['telepon'])): ?>
                    <p class="text-red-500 text-xs italic mt-1"><?= $errors['telepon'] ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow appearance-none border <?= isset($errors['email']) ? 'border-red-500' : '' ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-xs italic mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center justify-between">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150" type="submit">
                    Simpan Perubahan
                </button>
                <a href="index.php" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</body>
</html>