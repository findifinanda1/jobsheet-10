<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id         = $_POST['id'] ?? null;
$no_anggota = trim($_POST['no_anggota'] ?? '');
$nama       = trim($_POST['nama'] ?? '');
$alamat     = trim($_POST['alamat'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];

if ($no_anggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}
if ($nama === '') {
    $errors[] = "Nama lengkap wajib diisi.";
}
if ($alamat === '') {
    $errors[] = "Alamat wajib diisi.";
} elseif (strlen($alamat) < 5) {
    $errors[] = "Alamat minimal 5 karakter.";
}
if ($no_hp === '') {
    $errors[] = "No. HP wajib diisi.";
} elseif (!preg_match('/^[0-9]{10,13}$/', $no_hp)) {
    $errors[] = "No. HP harus berupa angka (10-13 digit).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

require __DIR__ . '/../includes/koneksi.php';

$stmt = $pdo->prepare(
    "UPDATE anggota
     SET no_anggota = :no_anggota, nama = :nama, alamat = :alamat, no_hp = :no_hp
     WHERE id = :id"
);

try {
    $stmt->execute([
        'no_anggota' => $no_anggota,
        'nama'       => $nama,
        'alamat'     => $alamat,
        'no_hp'      => $no_hp,
        'id'         => $id,
    ]);
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.',
        ];
        header('Location: edit.php?id=' . urlencode($id));
        exit;
    }
    throw $e;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil diubah.'];
header('Location: list.php');
exit;