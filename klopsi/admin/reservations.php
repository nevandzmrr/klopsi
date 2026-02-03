<?php
$pageTitle = 'Kelola Reservasi — Klopsi';
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);
  $status = $_POST['status'] ?? 'baru';
  if ($id > 0) {
    $stmt = $pdo->prepare('UPDATE reservations SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
  }
}

$statusFilter = $_GET['status'] ?? '';
$dateFrom = $_GET['from'] ?? '';
$dateTo = $_GET['to'] ?? '';
$keyword = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'latest';

$conditions = [];
$params = [];

if (in_array($statusFilter, ['baru', 'approved', 'cancel'], true)) {
  $conditions[] = 'status = ?';
  $params[] = $statusFilter;
}
if ($dateFrom !== '') {
  $conditions[] = 'reservation_date >= ?';
  $params[] = $dateFrom;
}
if ($dateTo !== '') {
  $conditions[] = 'reservation_date <= ?';
  $params[] = $dateTo;
}
if ($keyword !== '') {
  $conditions[] = '(name LIKE ? OR phone LIKE ?)';
  $params[] = '%' . $keyword . '%';
  $params[] = '%' . $keyword . '%';
}

$orderBy = 'created_at DESC';
if ($sort === 'oldest') {
  $orderBy = 'created_at ASC';
} elseif ($sort === 'date_asc') {
  $orderBy = 'reservation_date ASC';
} elseif ($sort === 'date_desc') {
  $orderBy = 'reservation_date DESC';
}

$whereSql = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';
$stmt = $pdo->prepare("SELECT * FROM reservations {$whereSql} ORDER BY {$orderBy}");
$stmt->execute($params);
$reservations = $stmt->fetchAll();
?>
<section class="admin-header">
  <div>
    <h1>Reservasi</h1>
    <p>Kelola semua permintaan reservasi dari user.</p>
  </div>
</section>

<section class="admin-filters">
  <form method="get" class="filter-bar">
    <div class="field">
      <label>Status</label>
      <select name="status">
        <option value="">Semua</option>
        <option value="baru" <?php echo $statusFilter === 'baru' ? 'selected' : ''; ?>>baru</option>
        <option value="approved" <?php echo $statusFilter === 'approved' ? 'selected' : ''; ?>>approved</option>
        <option value="cancel" <?php echo $statusFilter === 'cancel' ? 'selected' : ''; ?>>cancel</option>
      </select>
    </div>
    <div class="field">
      <label>Dari</label>
      <input type="date" name="from" value="<?php echo esc($dateFrom); ?>" />
    </div>
    <div class="field">
      <label>Sampai</label>
      <input type="date" name="to" value="<?php echo esc($dateTo); ?>" />
    </div>
    <div class="field">
      <label>Cari</label>
      <input type="text" name="q" value="<?php echo esc($keyword); ?>" placeholder="Nama / WhatsApp" />
    </div>
    <button class="btn" type="submit">Terapkan</button>
    <a class="btn btn-outline" href="reservations.php">Reset</a>
  </form>
</section>

<section class="admin-table">
  <table>
    <thead>
      <tr>
        <th>Nama</th>
        <th>WhatsApp</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reservations as $row): ?>
      <tr>
        <td><?php echo esc($row['name']); ?></td>
        <?php
          $waRaw = preg_replace('/[^0-9]/', '', $row['phone'] ?? '');
          if (strpos($waRaw, '0') === 0) {
            $waRaw = '62' . substr($waRaw, 1);
          }
          $formattedDate = $row['reservation_date'] ? date('d-m-Y', strtotime($row['reservation_date'])) : '';
          $message = "Terima kasih telah melakukan reservasi di Klopsi Coffee.\n";
          $message .= "Reservasi Anda telah BERHASIL DIKONFIRMASI dengan detail sebagai berikut:\n\n";
          $message .= "Detail Reservasi:\n\n";
          $message .= "Nama Pemesan : {$row['name']}\n\n";
          $message .= "Tanggal : {$formattedDate}\n\n";
          $message .= "Jumlah Orang : {$row['guest_count']} orang";
          $waLink = $waRaw !== '' ? "https://wa.me/{$waRaw}?text=" . urlencode($message) : '#';
        ?>
        <td>
          <a class="wa-link" href="<?php echo esc($waLink); ?>" target="_blank" rel="noopener">
            <?php echo esc($row['phone']); ?>
          </a>
        </td>
        <td><?php echo esc(date('d-m-Y', strtotime($row['reservation_date']))); ?></td>
        <td><?php echo esc($row['guest_count']); ?></td>
        <td><span class="status status-<?php echo esc($row['status']); ?>"><?php echo esc($row['status']); ?></span></td>
        <td>
          <form method="post" class="inline-form">
            <input type="hidden" name="id" value="<?php echo esc($row['id']); ?>" />
            <select name="status">
              <option value="baru">baru</option>
              <option value="approved">approved</option>
              <option value="cancel">cancel</option>
            </select>
            <button class="btn btn-outline" type="submit">Update</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
