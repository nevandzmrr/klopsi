<?php
$pageTitle = 'Pengaturan — Klopsi';
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fields = [
    'address' => trim($_POST['address'] ?? ''),
    'whatsapp' => trim($_POST['whatsapp'] ?? ''),
    'instagram' => trim($_POST['instagram'] ?? ''),
    'open_hours' => trim($_POST['open_hours'] ?? ''),
    'close_hours' => trim($_POST['close_hours'] ?? ''),
  ];

  $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
  foreach ($fields as $key => $value) {
    $stmt->execute([$key, $value]);
  }
}

$settings = [];
$rows = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
foreach ($rows as $row) {
  $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<section class="admin-header">
  <div>
    <h1>Pengaturan</h1>
    <p>Atur informasi toko, jam operasional, dan kontak.</p>
  </div>
</section>

<section class="admin-grid">
  <div class="admin-card">
    <h3>Informasi Utama</h3>
    <form class="stack" method="post">
      <div class="field">
        <label>Alamat</label>
        <input name="address" type="text" value="<?php echo esc($settings['address'] ?? ''); ?>" placeholder="Jl. Mawar No. 18, Bandung" />
      </div>
      <div class="field">
        <label>WhatsApp</label>
        <input name="whatsapp" type="text" value="<?php echo esc($settings['whatsapp'] ?? ''); ?>" placeholder="0812-3456-7890" />
      </div>
      <div class="field">
        <label>Instagram (URL)</label>
        <input name="instagram" type="text" value="<?php echo esc($settings['instagram'] ?? ''); ?>" placeholder="https://instagram.com/klopsicoffee" />
      </div>
      <div class="field">
        <label>Open</label>
        <input name="open_hours" type="text" value="<?php echo esc($settings['open_hours'] ?? ''); ?>" placeholder="All day (16.00 WIB)" />
      </div>
      <div class="field">
        <label>Close</label>
        <input name="close_hours" type="text" value="<?php echo esc($settings['close_hours'] ?? ''); ?>" placeholder="Weekday (23.00 WIB) • Weekend (00.00 WIB)" />
      </div>
      <button class="btn" type="submit">Simpan Perubahan</button>
    </form>
  </div>
  <div class="admin-card">
    <h3>Catatan</h3>
    <p>Perubahan akan langsung tampil di website user.</p>
  </div>
</section>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
