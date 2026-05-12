<?php
// One-time setup: creates config.php with password hash.
// DELETE THIS FILE after setup!

$configFile = __DIR__ . '/config.php';

$error = '';
$done  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw  = $_POST['password']  ?? '';
    $pw2 = $_POST['password2'] ?? '';

    if (strlen($pw) < 6) {
        $error = 'Пароль повинен бути мінімум 6 символів.';
    } elseif ($pw !== $pw2) {
        $error = 'Паролі не співпадають.';
    } else {
        $hash = password_hash($pw, PASSWORD_DEFAULT);
        $content = "<?php\ndefine('ADMIN_HASH', '$hash');\ndefine('DATA_FILE', __DIR__ . '/../data/houses.json');\n";
        file_put_contents($configFile, $content);
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Налаштування адмінки</title>
<style>
  body { font-family: sans-serif; background: #f4f4f2; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
  .box { background: #fff; border: 1px solid #e0ddd8; padding: 40px; width: 100%; max-width: 400px; border-radius: 8px; }
  h2 { margin: 0 0 8px; font-size: 20px; }
  p { font-size: 13px; color: #666; margin: 0 0 24px; line-height: 1.6; }
  label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #888; letter-spacing: .06em; margin-bottom: 6px; }
  input { width: 100%; box-sizing: border-box; border: 1px solid #d8d5d0; padding: 10px 14px; font-size: 14px; border-radius: 4px; margin-bottom: 16px; }
  button { width: 100%; background: #4f5b4c; color: #fff; border: none; padding: 12px; font-size: 14px; font-weight: 600; border-radius: 4px; cursor: pointer; }
  .error { background: #fee; border: 1px solid #fcc; color: #c00; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 16px; }
  .success { background: #efe; border: 1px solid #cfc; color: #060; padding: 14px; border-radius: 4px; font-size: 14px; line-height: 1.7; }
</style>
</head>
<body>
<div class="box">
<?php if ($done): ?>
  <h2>Готово!</h2>
  <div class="success">
    Пароль встановлено. config.php створено.<br><br>
    <strong>Видаліть цей файл (setup.php) з сервера!</strong><br><br>
    <a href="index.php">→ Перейти до входу</a>
  </div>
<?php else: ?>
  <h2>Встановити пароль</h2>
  <p>Цей файл запускається один раз. Після налаштування — видаліть його з сервера.</p>
  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <label>Новий пароль</label>
    <input type="password" name="password" required autofocus>
    <label>Повторіть пароль</label>
    <input type="password" name="password2" required>
    <button type="submit">Зберегти пароль</button>
  </form>
<?php endif; ?>
</div>
</body>
</html>
