<?php
$pageTitle = 'Dashboard Admin — Klopsi';
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/helpers.php';

$pending = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'baru'")->fetchColumn();
?>
<section class="admin-header">
  <div>
    <h1>Dashboard</h1>
    <p>Ringkasan reservasi Ramadhan minggu ini.</p>
  </div>
  <a class="btn" href="reservations.php">Kelola Reservasi</a>
</section>

<div class="admin-cards">
  <div class="admin-card">
    <h3>Total Reservasi (Pending)</h3>
    <span><?php echo esc($pending); ?></span>
  </div>
</div>

<section class="admin-table">
  <h2>Reservasi Terbaru</h2>
  <table>
    <thead>
      <tr>
        <th>Nama</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $rows = $pdo->query("SELECT name, reservation_date, guest_count, status FROM reservations ORDER BY created_at DESC LIMIT 5")->fetchAll();
        foreach ($rows as $row):
      ?>
      <tr>
        <td><?php echo esc($row['name']); ?></td>
        <td><?php echo esc(date('d-m-Y', strtotime($row['reservation_date']))); ?></td>
        <td><?php echo esc($row['guest_count']); ?></td>
        <td><span class="status status-<?php echo esc($row['status']); ?>"><?php echo esc($row['status']); ?></span></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
