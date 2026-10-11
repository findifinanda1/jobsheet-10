<?php
$page_title = "Edit Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    header('Location: list.php');
    exit;
}
?>
<section>
<h2>Edit Anggota</h2>

<?php if ($flash): ?>
<p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
<?php endif; ?>

<form id="form-tambah" method="post" action="proses_edit.php">
    <input type="hidden" name="id" value="<?php echo $anggota['id']; ?>">
    <div>
        <label for="no_anggota">No. Anggota *</label>
        <input type="text" id="no_anggota" name="no_anggota" value="<?php echo $anggota['no_anggota']; ?>" required>
    </div>
    <div>
        <label for="nama">Nama Lengkap *</label>
        <input type="text" id="nama" name="nama" value="<?php echo $anggota['nama']; ?>" required>
    </div>
    <div>
        <label for="alamat">Alamat *</label>
        <input type="text" id="alamat" name="alamat" value="<?php echo $anggota['alamat']; ?>" required>
    </div>
    <div>
        <label for="no_hp">No. HP *</label>
        <input type="text" id="no_hp" name="no_hp" value="<?php echo $anggota['no_hp']; ?>" required>
    </div>
    <button type="submit">Simpan Perubahan</button>
    <a href="list.php" class="btn-batal">Batal</a>
</form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>