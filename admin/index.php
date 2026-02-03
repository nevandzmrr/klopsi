<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/helpers.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND role = ? LIMIT 1');
  $stmt->execute([$email, 'admin']);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['admin_id'] = $user['id'];
    header('Location: dashboard.php');
    exit;
  }
  $error = 'Email atau password salah.';
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login — Klopsi Coffee</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="admin-body">
  <div class="admin-login">
    <div class="admin-login-card">
      <h1>Admin Klopsi</h1>
      <p>Masuk untuk mengelola reservasi Ramadhan.</p>
      <?php if ($error): ?>
        <div class="alert"><?php echo esc($error); ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="admin@klopsi.id" required />
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" required />
        </div>
        <button class="btn btn-full" type="submit">Masuk</button>
      </form>
    </div>
  </div>
</body>
</html>
