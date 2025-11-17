<?php
require_once '../includes/auth.php';
require_login();

$pdo = require_once '../includes/database.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['message'] = "ID kontak tidak valid.";
    header('Location: index.php');
    exit();
}

try {
    $stmt_fetch = $pdo->prepare("SELECT nama FROM kontak WHERE id = ?");
    $stmt_fetch->execute([$id]);
    $kontak = $stmt_fetch->fetch();
    
    if (!$kontak) {
        $_SESSION['message'] = "Kontak tidak ditemukan.";
        header('Location: index.php');
        exit();
    }
    
    $nama = $kontak['nama'];
    
    $stmt_delete = $pdo->prepare("DELETE FROM kontak WHERE id = ?");
    $stmt_delete->execute([$id]);

    $_SESSION['message'] = "Kontak $nama berhasil dihapus.";
    header('Location: index.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['message'] = "Gagal menghapus kontak: " . $e->getMessage();
    header('Location: index.php');
    exit();
}