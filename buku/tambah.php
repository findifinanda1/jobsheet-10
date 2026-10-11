<?php
require __DIR__ . '/../includes/auth.php';   
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
<h2>Tambah Buku</h2>

<?php if ($flash): ?>
<p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
<?php endif; ?>

<form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
    <div>
        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" name="judul" placeholder="Masukkan judul buku" required>
    </div>
    <div>
        <label for="pengarang">Pengarang</label>
        <input type="text" id="pengarang" name="pengarang" placeholder="Masukkan nama pengarang" required>
    </div>
    <div>
        <label for="tahun">Tahun Terbit</label>
        <input type="number" id="tahun" name="tahun" min="1900" max="2026" placeholder="Contoh: 2024" required>
    </div>
    <div>
        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn" placeholder="Contoh: 978-602-0000-00-0">
    </div>
    <div>
        <label for="stok">Stok</label>
        <input type="number" id="stok" name="stok" min="0" placeholder="Contoh: 10" required>
    </div>
    <div>
        <label for="kategori">Kategori</label>
        <input type="text" id="kategori" name="kategori" placeholder="Contoh: Novel / Komik">
    </div>
    <button type="submit">Simpan</button>
</form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>