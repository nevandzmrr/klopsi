<?php
$pageTitle = 'Gallery - Klopsi';
require __DIR__ . '/includes/admin_header.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/helpers.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'add') {
    $title = trim($_POST['title'] ?? '');
    $caption = trim($_POST['caption'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($_FILES['image']['name'])) {
      $uploadDir = __DIR__ . '/../assets/uploads/gallery';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
      }

      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'webp'];
      if (in_array($ext, $allowed, true)) {
        $filename = 'gallery_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
        $target = $uploadDir . '/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
          $path = 'assets/uploads/gallery/' . $filename;
          $stmt = $pdo->prepare("INSERT INTO gallery_images (title, caption, image_path, sort_order, is_active) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$title, $caption, $path, $sort, $isActive]);
        } else {
          $error = 'Upload gagal. Coba lagi.';
        }
      } else {
        $error = 'Format gambar harus jpg, jpeg, png, atau webp.';
      }
    } else {
      $error = 'Pilih gambar terlebih dahulu.';
    }
  }

  if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $caption = trim($_POST['caption'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    if ($id > 0) {
      $stmt = $pdo->prepare("UPDATE gallery_images SET title = ?, caption = ?, sort_order = ?, is_active = ? WHERE id = ?");
      $stmt->execute([$title, $caption, $sort, $isActive, $id]);
    }
  }

  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      $stmt = $pdo->prepare("SELECT image_path FROM gallery_images WHERE id = ?");
      $stmt->execute([$id]);
      $row = $stmt->fetch();
      if ($row && !empty($row['image_path'])) {
        $filePath = __DIR__ . '/../' . $row['image_path'];
        if (is_file($filePath)) {
          unlink($filePath);
        }
      }
      $stmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
      $stmt->execute([$id]);
    }
  }
}

$gallery = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>
<section class="admin-header">
  <div>
    <h1>Gallery</h1>
    <p>Kelola foto yang tampil di landing page.</p>
  </div>
</section>

<?php if ($error): ?>
  <div class="alert"><?php echo esc($error); ?></div>
<?php endif; ?>

<section class="admin-grid">
  <div class="admin-card">
    <h3>Tambah Foto</h3>
    <form method="post" class="stack" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add" />
      <div class="field">
        <label for="title">Judul</label>
        <input id="title" name="title" type="text" placeholder="Private Booth" />
      </div>
      <div class="field">
        <label for="caption">Caption</label>
        <input id="caption" name="caption" type="text" placeholder="Area semi-private dengan lighting hangat" />
      </div>
      <div class="field">
        <label for="sort_order">Urutan</label>
        <input id="sort_order" name="sort_order" type="number" value="0" />
      </div>
      <div class="field">
        <label for="image">File gambar</label>
        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" required />
      </div>
      <label class="checkbox">
        <input type="checkbox" name="is_active" checked />
        <span>Tampilkan di landing page</span>
      </label>
      <button class="btn" type="submit">Upload</button>
    </form>
  </div>
  <div class="admin-card">
    <h3>Daftar Gallery</h3>
    <div class="mini-list">
      <?php if (count($gallery) === 0): ?>
        <p>Belum ada foto.</p>
      <?php else: ?>
        <?php foreach ($gallery as $item): ?>
          <div class="list-row list-row-gallery">
            <div class="gallery-row-main">
              <img class="gallery-thumb" src="../<?php echo esc($item['image_path']); ?>" alt="<?php echo esc($item['title'] ?: 'Gallery'); ?>" />
              <div>
                <strong><?php echo esc($item['title'] ?: 'Tanpa Judul'); ?></strong>
                <span><?php echo esc($item['caption']); ?></span>
              </div>
            </div>
            <form method="post" class="inline-form">
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?php echo esc($item['id']); ?>" />
              <button class="btn btn-outline" type="submit">Hapus</button>
            </form>
          </div>
          <form method="post" class="stack inline-edit">
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="id" value="<?php echo esc($item['id']); ?>" />
            <div class="field">
              <label>Judul</label>
              <input name="title" type="text" value="<?php echo esc($item['title']); ?>" />
            </div>
            <div class="field">
              <label>Caption</label>
              <input name="caption" type="text" value="<?php echo esc($item['caption']); ?>" />
            </div>
            <div class="field">
              <label>Urutan</label>
              <input name="sort_order" type="number" value="<?php echo esc($item['sort_order']); ?>" />
            </div>
            <label class="checkbox">
              <input type="checkbox" name="is_active" <?php echo $item['is_active'] ? 'checked' : ''; ?> />
              <span>Tampilkan</span>
            </label>
            <button class="btn btn-outline" type="submit">Update</button>
          </form>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
