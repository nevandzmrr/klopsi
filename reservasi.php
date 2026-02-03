<?php
$pageTitle = "Reservasi - Klopsi Coffee";
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$error = $_GET['error'] ?? '';

$dates = $pdo->query(
  "SELECT rd.id, rd.reserve_date, rd.capacity,
   COALESCE(SUM(r.guest_count), 0) AS reserved
   FROM reservation_dates rd
   LEFT JOIN reservations r ON r.reservation_date = rd.reserve_date AND r.status != 'cancel'
   WHERE rd.reserve_date >= CURDATE()
   GROUP BY rd.id, rd.reserve_date, rd.capacity
   ORDER BY rd.reserve_date ASC"
)->fetchAll();

?>
<main>
  <section class="section reservation-flow">
    <div class="section-title align-left">
      <h1>Reservasi Klopsi Coffee</h1>
      <p>Pilih tanggal yang tersedia, tentukan jumlah pax, lalu isi data diri.</p>
    </div>

    <?php if ($error === 'capacity'): ?>
      <div class="alert-box">Maaf, kapasitas tanggal itu sudah penuh. Silakan pilih tanggal lain.</div>
    <?php elseif ($error === 'invalid'): ?>
      <div class="alert-box">Lengkapi semua data reservasi terlebih dahulu.</div>
    <?php endif; ?>

    <form class="reservation-form" method="post" action="process_reservation.php">
      <div class="step-block">
        <div class="step-head">
          <span class="step-number">1</span>
          <div>
            <h3>Pilih tanggal</h3>
            <p>Lihat tanggal yang ready dan sisa kapasitasnya.</p>
          </div>
        </div>
        <div class="date-grid">
          <?php if (count($dates) === 0): ?>
            <div class="empty-card">
              <h4>Belum ada tanggal yang diatur.</h4>
              <p>Admin bisa mengisi kapasitas per tanggal di menu Kapasitas Tanggal.</p>
            </div>
          <?php else: ?>
            <?php foreach ($dates as $date): ?>
              <?php
                $remaining = (int)$date['capacity'] - (int)$date['reserved'];
                $isFull = $remaining <= 0;
              ?>
              <button
                class="date-card <?php echo $isFull ? 'is-full' : ''; ?>"
                type="button"
                data-date="<?php echo esc($date['reserve_date']); ?>"
                data-remaining="<?php echo esc($remaining); ?>"
                <?php echo $isFull ? 'disabled' : ''; ?>
              >
                <div>
                  <h4><?php echo esc(date('d-m-Y', strtotime($date['reserve_date']))); ?></h4>
                  <p><?php echo esc($remaining); ?> kursi tersisa</p>
                </div>
                <span class="pill"><?php echo $isFull ? 'Penuh' : 'Tersedia'; ?></span>
              </button>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <input type="hidden" name="tanggal" id="reservation-date" required />
      </div>

      <div class="step-block is-hidden" id="step-pax">
        <div class="step-head">
          <span class="step-number">2</span>
          <div>
            <h3>Jumlah pax</h3>
            <p>Sisa kapasitas: <strong id="remaining-text">0</strong> orang.</p>
          </div>
        </div>
        <div class="field">
          <label for="tamu">Jumlah pax / person</label>
          <input id="tamu" name="tamu" type="number" min="1" placeholder="Contoh: 20" required />
        </div>
      </div>

      <div class="step-block is-hidden" id="step-details">
        <div class="step-head">
          <span class="step-number">3</span>
          <div>
            <h3>Data diri</h3>
            <p>Lengkapi data untuk konfirmasi via WhatsApp.</p>
          </div>
        </div>
        <div class="field-grid">
          <div class="field">
            <label for="nama">Nama pemesan</label>
            <input id="nama" name="nama" type="text" placeholder="Nama lengkap" required />
          </div>
          <div class="field">
            <label for="telepon">Nomor WhatsApp</label>
            <input id="telepon" name="telepon" type="tel" placeholder="08xxxxxxxx" required />
          </div>
          <div class="field">
            <label for="catatan">Catatan tambahan</label>
            <textarea id="catatan" name="catatan" rows="4" placeholder="Opsional"></textarea>
          </div>
        </div>
        <input type="hidden" name="waktu" value="-" />
        <button class="btn btn-full" type="submit">Kirim Permintaan Reservasi</button>
      </div>
    </form>
  </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
