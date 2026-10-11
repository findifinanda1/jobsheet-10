<?php
require __DIR__ . '/../includes/auth.php';   

// (Latihan 1): hanya admin boleh menghapus 
if (($_SESSION['role'] ?? '') !== 'admin') {
    $_SESSION['flash'] = [
        'type'  => 'error',
        'pesan' => 'Anda tidak punya akses untuk menghapus anggota.'
    ];
    header('Location: list.php');
    exit;
}


require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
}

header('Location: list.php');
exit;