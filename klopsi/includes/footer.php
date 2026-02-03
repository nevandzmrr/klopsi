<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
$settings = [];
$rows = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
foreach ($rows as $row) {
  $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<footer class="site-footer">
  <div class="footer-grid">
    <div>
      <h3>Klopsi Coffee</h3>
      <p><?php echo esc($settings['address'] ?? ''); ?></p>
      <p>Open: <?php echo esc($settings['open_hours'] ?? ''); ?></p>
      <p>Close: <?php echo esc($settings['close_hours'] ?? ''); ?></p>
    </div>
    <div>
      <h4>Reservasi</h4>
      <p>WhatsApp: <?php echo esc($settings['whatsapp'] ?? ''); ?></p>
      <p>Instagram: @klopsicoffee</p>
    </div>
  </div>
  <p class="footer-note">© 2026 Klopsi Coffee. All rights reserved.</p>
</footer>
<script src="assets/js/app.js"></script>
</body>
</html>
