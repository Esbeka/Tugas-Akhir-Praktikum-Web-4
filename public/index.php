<?php
require_once '../includes/auth.php';
require_login(); 

$pdo = require_once '../includes/database.php';

$stmt = $pdo->query("SELECT id, nama, telepon, email FROM kontak ORDER BY nama ASC");
$kontak_list = $stmt->fetchAll();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Kontak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
        background-color: #f5f5dc; 
    }
</style>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-xl">
        <header class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-extrabold text-gray-800">Daftar Kontak </h1>
            <div class="space-x-2">
                <span class="text-sm text-gray-600">Halo, <?= $_SESSION['username'] ?>!</span>
                <a href="index.php?action=logout" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded">Logout</a>
            </div>
        </header>

        <a href="tambah.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out mb-6 inline-block">
            + Tambah Kontak Baru
        </a>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                <p class="font-bold">Informasi</p>
                <p><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Telepon</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php if (empty($kontak_list)): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td colspan="4" class="py-3 px-6 text-center font-bold text-gray-500">
                                Belum ada data kontak.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($kontak_list as $kontak): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($kontak['nama']) ?></div>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <?= htmlspecialchars($kontak['telepon']) ?>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <?= htmlspecialchars($kontak['email'] ?? '-') ?>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        <a href="edit.php?id=<?= $kontak['id'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs transition duration-150">Edit</a>
                                        <a href="hapus.php?id=<?= $kontak['id'] ?>" onclick="return confirm('Yakin ingin menghapus kontak ini?')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs transition duration-150">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>