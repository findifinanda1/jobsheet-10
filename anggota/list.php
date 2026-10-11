<?php
require __DIR__ . '/../includes/auth.php';   

$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/../includes/koneksi.php';

$perPage = 5;
$page    = max(1, (int) ($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;
$q       = trim($_GET['q'] ?? '');

if ($q !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw");
    $hitung->execute(['kw' => '%' . $q . '%']);
    $totalRows = $hitung->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM anggota WHERE nama ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue('kw', '%' . $q . '%');
} else {
    $totalRows = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
<section>
<h2>Daftar Anggota</h2>

<?php if ($flash): ?>
<p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
<?php endif; ?>

<form method="get" action="list.php" class="search-box">
<div class="search-field">
<label for="search-input">Cari Nama Anggota</label>
<input type="text" id="search-input" name="q" placeholder="Ketik nama anggota..." value="<?php echo htmlspecialchars($q); ?>">
</div>
<button type="submit">Cari</button>
<?php if ($q !== ''): ?>
<a href="list.php" class="btn-reset">Reset</a>
<?php endif; ?>
</form>

<div class="table-responsive">
<table>
<thead>
<tr>
<th>No. Anggota</th>
<th>Nama Lengkap</th>
<th>Alamat</th>
<th>No. HP</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php if (empty($daftarAnggota)): ?>
<tr>
<td colspan="5">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
</tr>
<?php else: ?>
<?php foreach ($daftarAnggota as $anggota): ?>
<tr>
<td><?php echo $anggota['no_anggota']; ?></td>
<td><?php echo $anggota['nama']; ?></td>
<td><?php echo $anggota['alamat']; ?></td>
<td><?php echo $anggota['no_hp']; ?></td>
<td>
<a href="edit.php?id=<?php echo $anggota['id']; ?>" class="btn-edit">Edit</a>

<?php if (($_SESSION['role'] ?? '') === 'admin'): ?>   
<form class="form-hapus" method="post" action="hapus.php">
<input type="hidden" name="id" value="<?php echo $anggota['id']; ?>">
<button type="submit" class="btn-hapus">Hapus</button>
</form>
<?php endif; ?>                                         
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>

<nav class="pagination">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<a href="list.php?page=<?php echo $i; ?><?php echo $q !== '' ? '&q=' . urlencode($q) : ''; ?>"
   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
<?php endfor; ?>
</nav>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>