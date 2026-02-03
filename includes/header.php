<?php
  $pageTitle = $pageTitle ?? "Klopsi Coffee";
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<header class="site-hero">
  <nav class="site-nav">
    <div class="brand">
      <img class="brand-logo" src="assets/img/klopsi-logo.png" alt="Klopsi Coffee" />
      <div>
        <div class="brand-name">Klopsi Coffee</div>
        <div class="brand-tag">Cafe and Resto</div>
      </div>
    </div>
    <div class="nav-links">
      <a href="index.php#home">Home</a>
      <a href="index.php#gallery">Gallery</a>
      <a href="reservasi.php">Reservasi</a>
      <a href="kontak.php">Kontak</a>
    </div>
    <a class="btn btn-outline" href="reservasi.php">Reservasi</a>
    <button class="nav-toggle" aria-label="Toggle menu">Menu</button>
  </nav>
</header>
