<?php
$pageTitle = "Kontak - Klopsi Coffee";
require __DIR__ . '/includes/header.php';
?>
<main>
  <section class="section contact-hero">
    <div class="contact-hero-grid">
      <div>
        <span class="eyebrow">Kontak Klopsi</span>
        <h1>Ngobrol dulu sebelum reservasi?</h1>
        <p class="lead">
          Tim Klopsi siap bantu kamu soal paket, layout acara, sampai kebutuhan khusus.
          Hubungi kami lewat WhatsApp atau isi form di bawah ini.
        </p>
        <?php
          require_once __DIR__ . '/includes/db.php';
          require_once __DIR__ . '/includes/helpers.php';
          $settings = [];
          $rows = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
          foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
          }
          $waRaw = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? '');
          if (strpos($waRaw, '0') === 0) {
            $waRaw = '62' . substr($waRaw, 1);
          }
          $waLink = $waRaw !== '' ? "https://wa.me/{$waRaw}" : '#';
          $igLink = trim($settings['instagram'] ?? '');
          if ($igLink === '') {
            $igLink = 'https://www.instagram.com/klopsicoffee/';
          }
        ?>
        <div class="contact-card">
          <div>
            <span class="meta-title">WhatsApp</span>
            <span class="meta-value"><?php echo esc($settings['whatsapp'] ?? ''); ?></span>
          </div>
          <div>
            <span class="meta-title">Alamat</span>
            <span class="meta-value"><?php echo esc($settings['address'] ?? ''); ?></span>
          </div>
        </div>
      </div>
      <div class="contact-panel">
        <h3>Jam operasional</h3>
        <p>Open: <?php echo esc($settings['open_hours'] ?? ''); ?></p>
        <p>Close: <?php echo esc($settings['close_hours'] ?? ''); ?></p>
        <div class="contact-map">
          <div class="map-embed">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.3950119250017!2d106.9466616!3d-6.21152!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698b0067e8234d%3A0xd03977b2175f84d!2sKLOPSI%20CAFE%20%26%20RESTO!5e0!3m2!1sid!2sid!4v1770117144011!5m2!1sid!2sid"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Lokasi Klopsi Coffee">
            </iframe>
          </div>
          <a class="map-link" href="https://maps.app.goo.gl/tahZFAakLVg8sY6M7" target="_blank" rel="noopener">Buka lokasi di Google Maps</a>
          <p class="muted">Lokasi strategis dekat pusat kota Bandung.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section contact-form-section">
    <div class="form-wrapper">
      <div>
        <h2>Hubungi admin</h2>
        <p>Pilih channel favoritmu untuk langsung terhubung.</p>
      </div>
      <div class="social-contact">
        <a class="social-button wa" href="<?php echo esc($waLink); ?>" target="_blank" rel="noopener">WhatsApp</a>
        <a class="social-button ig" href="<?php echo esc($igLink); ?>" target="_blank" rel="noopener">Instagram</a>
        <p class="social-note">Tekan untuk menghubungi admin</p>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
