<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Tambah Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
<h2>Tambah Anggota Baru</h2>

<?php if ($flash): ?>
<p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
<?php endif; ?>

<form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
    <div>
        <label for="no_anggota">No. Anggota *</label>
        <input type="text" id="no_anggota" name="no_anggota" placeholder="Contoh: A001" required>
    </div>
    <div>
        <label for="nama">Nama Lengkap *</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
    </div>
    <div>
        <label for="alamat">Alamat *</label>
        <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat lengkap (minimal 5 karakter)" required>
    </div>
    <div>
        <label for="no_hp">No. HP *</label>
        <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890 (10-13 digit)" required>
    </div>
    
    <button type="submit">Simpan Anggota</button>
    <a href="list.php" class="btn" style="background-color: #ccc; color: #333; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; margin-left: 0.5rem;">Batal</a>
</form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>