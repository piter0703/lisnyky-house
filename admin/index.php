<?php
session_start();
require_once __DIR__ . '/config.php';

if ($_SESSION['admin'] ?? false) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw = $_POST['password'] ?? '';
    if (password_verify($pw, ADMIN_HASH)) {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Невірний пароль.';
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>Lisnyky House · Адмін</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f4f2; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
  .box { background: #fff; border: 1px solid #e0ddd8; padding: 40px; width: 100%; max-width: 420px; border-radius: 8px; }
  .logo { font-size: 13px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #4f5b4c; margin-bottom: 24px; }
  h2 { font-size: 20px; font-weight: 600; margin-bottom: 6px; }
  .sub { font-size: 13px; color: #888; margin-bottom: 28px; line-height: 1.5; }
  label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #888; letter-spacing: .06em; margin-bottom: 6px; }
  input[type=password] { width: 100%; border: 1px solid #d8d5d0; padding: 10px 14px; font-size: 14px; border-radius: 4px; outline: none; }
  input[type=password]:focus { border-color: #4f5b4c; }
  button { width: 100%; background: #4f5b4c; color: #fff; border: none; padding: 12px; font-size: 14px; font-weight: 600; cursor: pointer; border-radius: 4px; margin-top: 16px; }
  button:hover { background: #3d4a3a; }
  .error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 16px; }
</style>
</head>
<body>
<div class="box">
  <div class="logo">Lisnyky House</div>
  <h2>Вхід</h2>
  <p class="sub">Панель управління сайтом</p>
  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <label>Пароль</label>
    <input type="password" name="password" autofocus required>
    <button type="submit">Увійти</button>
  </form>
</div>
</body>
</html>
