<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: reservasi.php');
  exit;
}

$nama = trim($_POST['nama'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$tanggal = $_POST['tanggal'] ?? '';
$waktu = $_POST['waktu'] ?? '';
$tamu = (int)($_POST['tamu'] ?? 0);
$catatan = trim($_POST['catatan'] ?? '');

if ($nama === '' || $telepon === '' || $tanggal === '' || $waktu === '' || $tamu < 1) {
  header('Location: reservasi.php?error=invalid');
  exit;
}

$stmt = $pdo->prepare("SELECT capacity FROM reservation_dates WHERE reserve_date = ? LIMIT 1");
$stmt->execute([$tanggal]);
$capacityRow = $stmt->fetch();
if (!$capacityRow) {
  header('Location: reservasi.php?error=capacity');
  exit;
}

$stmt = $pdo->prepare("SELECT COALESCE(SUM(guest_count), 0) AS reserved FROM reservations WHERE reservation_date = ? AND status != 'cancel'");
$stmt->execute([$tanggal]);
$reserved = (int)$stmt->fetchColumn();
$capacity = (int)$capacityRow['capacity'];

if ($reserved + $tamu > $capacity) {
  header('Location: reservasi.php?error=capacity');
  exit;
}

$stmt = $pdo->prepare('INSERT INTO reservations (name, phone, reservation_date, time_slot, package_name, guest_count, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$nama, $telepon, $tanggal, $waktu, 'Regular', $tamu, $catatan, 'baru']);

header('Location: konfirmasi.php');
exit;
