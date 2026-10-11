<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
session_start();

$no_anggota = trim($_POST['no_anggota'] ?? '');
$nama       = trim($_POST['nama'] ?? '');
$alamat     = trim($_POST['alamat'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

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
    header('Location: tambah.php');
    exit;
}

require __DIR__ . '/../includes/koneksi.php';

$stmt = $pdo->prepare(
    "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
     VALUES (:nama, :no_anggota, :alamat, :no_hp)
     RETURNING id"
);

try {
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $no_anggota,
        'alamat'     => $alamat,
        'no_hp'      => $no_hp,
    ]);
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {  
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.',
        ];
        header('Location: tambah.php');
        exit;
    }
    throw $e;   
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;