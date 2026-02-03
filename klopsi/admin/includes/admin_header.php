<?php
require __DIR__ . '/auth.php';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($pageTitle ?? 'Admin Klopsi'); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="admin-body">
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="brand">
        <img class="brand-logo" src="../assets/img/klopsi-logo.png" alt="Klopsi Coffee" />
        <div>
          <div class="brand-name">Klopsi Admin</div>
          <div class="brand-tag">Panel Reservasi</div>
        </div>
      </div>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="reservations.php">Reservasi</a>
        <a href="capacity.php">Kapasitas Tanggal</a>
        <a href="gallery.php">Gallery</a>
        <a href="settings.php">Pengaturan</a>
        <a href="logout.php">Keluar</a>
      </nav>
    </aside>
    <main class="admin-main">
