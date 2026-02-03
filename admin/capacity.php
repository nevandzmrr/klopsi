<?php
$pageTitle = 'Kapasitas Tanggal - Klopsi';
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/helpers.php';

$pdo->exec("DELETE FROM reservation_dates WHERE reserve_date < CURDATE()");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $date = $_POST['reserve_date'] ?? '';
  $capacity = (int)($_POST['capacity'] ?? 70);

  if ($action === 'save' && $date !== '') {
    $editId = (int)($_POST['id'] ?? 0);
    if ($editId > 0) {
      $check = $pdo->prepare("SELECT id FROM reservation_dates WHERE reserve_date = ? LIMIT 1");
      $check->execute([$date]);
      $existingId = (int)($check->fetchColumn() ?? 0);
      if ($existingId > 0 && $existingId !== $editId) {
        $stmt = $pdo->prepare("UPDATE reservation_dates SET capacity = ? WHERE id = ?");
        $stmt->execute([$capacity, $existingId]);
        $stmt = $pdo->prepare("DELETE FROM reservation_dates WHERE id = ?");
        $stmt->execute([$editId]);
      } else {
        $stmt = $pdo->prepare("UPDATE reservation_dates SET reserve_date = ?, capacity = ? WHERE id = ?");
        $stmt->execute([$date, $capacity, $editId]);
      }
    } else {
      $stmt = $pdo->prepare("INSERT INTO reservation_dates (reserve_date, capacity) VALUES (?, ?) ON DUPLICATE KEY UPDATE capacity = VALUES(capacity)");
      $stmt->execute([$date, $capacity]);
    }
  }

  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      $stmt = $pdo->prepare("DELETE FROM reservation_dates WHERE id = ?");
      $stmt->execute([$id]);
    }
  }
}

$rows = $pdo->query(
  "SELECT rd.id, rd.reserve_date, rd.capacity,
   COALESCE(SUM(r.guest_count), 0) AS reserved
   FROM reservation_dates rd
   LEFT JOIN reservations r ON r.reservation_date = rd.reserve_date AND r.status != 'cancel'
   WHERE rd.reserve_date >= CURDATE()
   GROUP BY rd.id, rd.reserve_date, rd.capacity
   ORDER BY rd.reserve_date ASC"
)->fetchAll();

$editId = (int)($_GET['edit'] ?? 0);
$editRow = null;
if ($editId > 0) {
  $stmt = $pdo->prepare("SELECT * FROM reservation_dates WHERE id = ?");
  $stmt->execute([$editId]);
  $editRow = $stmt->fetch();
}
?>
<section class="admin-header">
  <div>
    <h1>Kapasitas Tanggal</h1>
    <p>Atur kapasitas maksimal per tanggal reservasi.</p>
  </div>
</section>

<section class="admin-grid">
  <div class="admin-card admin-card-highlight">
    <div class="card-head">
      <h3><?php echo $editRow ? 'Edit Tanggal' : 'Tambah / Update Tanggal'; ?></h3>
    </div>
    <form method="post" class="stack form-card confirm-submit" data-confirm-text="<?php echo $editRow ? 'Simpan perubahan kapasitas tanggal ini?' : 'Simpan tanggal dan kapasitas baru?'; ?>">
      <input type="hidden" name="action" value="save" />
      <input type="hidden" name="id" value="<?php echo esc($editRow['id'] ?? 0); ?>" />
      <div class="field">
        <label for="reserve_date">Tanggal</label>
        <input id="reserve_date" name="reserve_date" type="date" value="<?php echo esc($editRow['reserve_date'] ?? ''); ?>" required />
      </div>
      <div class="field">
        <label for="capacity">Kapasitas</label>
        <input id="capacity" name="capacity" type="number" min="1" value="<?php echo esc($editRow['capacity'] ?? 70); ?>" required />
      </div>
      <button class="btn btn-full" type="submit"><?php echo $editRow ? 'Simpan Perubahan' : 'Simpan'; ?></button>
      <?php if ($editRow): ?>
        <a class="btn btn-outline" href="capacity.php">Batal</a>
      <?php endif; ?>
    </form>
  </div>
  <div class="admin-card">
    <div class="card-head">
      <h3>Daftar Tanggal</h3>
    </div>
    <div class="mini-list list-grid">
      <?php if (count($rows) === 0): ?>
        <p>Belum ada tanggal yang diatur.</p>
      <?php else: ?>
        <?php foreach ($rows as $row): ?>
          <?php
            $remaining = (int)$row['capacity'] - (int)$row['reserved'];
            $percent = $row['capacity'] > 0 ? (int)round(($remaining / $row['capacity']) * 100) : 0;
          ?>
          <div class="capacity-card">
            <div class="capacity-top">
              <div>
                <strong><?php echo esc(date('d-m-Y', strtotime($row['reserve_date']))); ?></strong>
                <span><?php echo esc($remaining); ?> sisa dari <?php echo esc($row['capacity']); ?></span>
              </div>
              <span class="capacity-badge"><?php echo esc($percent); ?>%</span>
            </div>
            <div class="capacity-bar">
              <div style="width: <?php echo esc($percent); ?>%;"></div>
            </div>
            <div class="inline-form">
              <a class="btn btn-outline" href="capacity.php?edit=<?php echo esc($row['id']); ?>">Edit</a>
              <form method="post">
                <input type="hidden" name="action" value="delete" />
                <input type="hidden" name="id" value="<?php echo esc($row['id']); ?>" />
                <button class="btn btn-outline" type="submit">Hapus</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
<div class="modal-overlay" id="confirm-modal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <h3 id="confirm-title">Konfirmasi</h3>
    <p id="confirm-message">Lanjutkan menyimpan data?</p>
    <div class="modal-actions">
      <button class="btn btn-outline" type="button" id="confirm-cancel">Batal</button>
      <button class="btn" type="button" id="confirm-ok">Ya, Simpan</button>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
