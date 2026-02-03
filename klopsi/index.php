<?php
$pageTitle = "Klopsi Coffee - Home";
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$gallery = $pdo->query("SELECT * FROM gallery_images WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>
<main>
  <section id="home" class="hero">
    <div class="hero-grid">
      <div class="hero-copy">
        <div class="logo-badge">
          <img src="assets/img/klopsi-logo.png" alt="Klopsi Coffee logo" />
          <div>
            <span class="eyebrow">Klopsi Coffee</span>
            <p class="badge-tag">Cafe & Resto</p>
          </div>
        </div>
        <h1>Buka Puasa di Klopsi.</h1>
        <p class="lead">
          Sambut Ramadan dengan suasana hangat penuh kebersamaan. Klopsi menghadirkan
          aroma kopi, hidangan favorit, dan ambience yang menenangkan untuk momen berbuka,
          silaturahmi, hingga ibadah malam.
        </p>
        <div class="hero-actions">
          <a class="btn" href="reservasi.php">Reservasi Sekarang</a>
          <a class="btn btn-ghost" href="#gallery">Explore Gallery</a>
        </div>
        <div class="hero-highlights">
          <div class="highlight">
            <span class="highlight-value">Ramadan</span>
            <span class="highlight-label">Reservasi khusus iftar</span>
          </div>
          <div class="highlight">
            <span class="highlight-value">16:00 - 00:00</span>
            <span class="highlight-label">Jam Ramadan</span>
          </div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="hero-spotlight">
          <div class="spotlight-card">
            <h3>Signature Ramadan</h3>
            <p>Suasana temaram, aroma kopi hangat, dan sajian berbuka yang menenangkan.</p>
            <div class="hero-tags">
              <span>Menu iftar</span>
              <span>Komunal hangat</span>
              <span>Private room</span>
            </div>
            <a class="btn btn-outline" href="kontak.php">Hubungi Kami</a>
          </div>
          
        </div>
      </div>
    </div>
  </section>

  <section class="section story">
    <div class="story-grid">
      <div>
        <h2>Ruang Ramadan untuk kebersamaan.</h2>
        <p>
          Klopsi Coffee menghadirkan suasana Ramadan yang khusyuk dan hangat
          untuk iftar keluarga, buka bersama komunitas, hingga gathering after tarawih.
        </p>
      </div>
      <div class="story-card">
        <h3>Kenapa Ramadan di Klopsi?</h3>
        <ul class="checklist">
          <li>Menu berbuka diracik hangat, nikmat, dan nyaman di lidah</li>
          <li>Ambience temaram yang tenang untuk momen berbuka bersama</li>
          <li>Area hangat dengan tata ruang nyaman untuk keluarga dan komunitas</li>
          <li>Tim Klopsi sigap membantu kebutuhan anda</li>
        </ul>
      </div>
    </div>
  </section>

  <section id="gallery" class="section gallery">
    <div class="section-title">
      <h2>Gallery Ramadan Klopsi</h2>
      <p>Nuansa berbuka yang hangat, siap untuk reservasi Ramadanmu.</p>
    </div>
    <?php if (count($gallery) === 0): ?>
      <div class="empty-card">
        <h4>Gallery belum diisi</h4>
        <p>Admin bisa menambahkan foto di menu Gallery.</p>
      </div>
    <?php else: ?>
      <div class="gallery-slider">
        <button class="gallery-nav prev" type="button" aria-label="Slide sebelumnya">‹</button>
        <div class="gallery-track">
          <?php foreach ($gallery as $item): ?>
            <article class="gallery-slide">
              <div class="gallery-frame">
                <div class="gallery-photo" style="background-image: url('<?php echo esc($item['image_path']); ?>');"></div>
              </div>
              <div class="gallery-info">
                <h4><?php echo esc($item['title'] ?: 'Klopsi Moment'); ?></h4>
                <p><?php echo esc($item['caption']); ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        <button class="gallery-nav next" type="button" aria-label="Slide berikutnya">›</button>
      </div>
      <div class="gallery-hint">Geser ke kanan untuk melihat foto lainnya.</div>
    <?php endif; ?>
  </section>

  <section class="section reserve-cta">
    <div class="reserve-box">
      <div>
        <h2>Siap reservasi Ramadan?</h2>
        <p>Pilih tanggal yang tersedia untuk iftar atau buka bersama.</p>
      </div>
      <a class="btn" href="reservasi.php">Cek Ketersediaan</a>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
