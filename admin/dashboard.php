<?php
session_start();
require_once __DIR__ . '/config.php';

if (!($_SESSION['admin'] ?? false)) {
    header('Location: index.php');
    exit;
}

$data      = json_decode(file_get_contents(DATA_FILE), true);
$units     = $data['units'];
$mainPrice = $data['settings']['main_price'] ?? 'від 1 250 $/м²';

// Index sections by id
$sections = [];
foreach ($data['sections'] as $s) {
    $sections[$s['id']] = $s;
}

// Group units by section
$bySection = [];
foreach ($units as $u) {
    $bySection[$u['section']][] = $u;
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
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f4f2; color: #1a1c1a; min-height: 100vh; }
  header { background: #1a1c1a; color: #fff; padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; }
  header h1 { font-size: 15px; font-weight: 600; letter-spacing: .04em; }
  header span { font-size: 12px; opacity: .5; }
  .logout { background: none; border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.7); padding: 6px 14px; font-size: 12px; cursor: pointer; border-radius: 4px; text-decoration: none; }
  .logout:hover { border-color: rgba(255,255,255,.5); color: #fff; }
  main { max-width: 960px; margin: 40px auto; padding: 0 24px; }
  .notice { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 12px 16px; font-size: 13px; color: #92400e; margin-bottom: 32px; }
  .section-h { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #888; margin-bottom: 16px; }
  .section-block { background: #fff; border: 1px solid #e0ddd8; border-radius: 8px; margin-bottom: 16px; overflow: hidden; }
  .section-title { padding: 14px 20px; background: #f9f8f6; border-bottom: 1px solid #e0ddd8; display: flex; align-items: center; justify-content: space-between; }
  .section-name { font-size: 14px; font-weight: 600; }
  .section-right { display: flex; align-items: center; gap: 12px; }
  .badge-building { font-size: 11px; color: #6b7280; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 12px; padding: 3px 10px; }
  .badge-active { font-size: 11px; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 3px 10px; }
  .btn-activate { background: #4f5b4c; color: #fff; border: none; padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 4px; cursor: pointer; }
  .btn-activate:hover { background: #3d4a3a; }
  .btn-deactivate { background: #fff; color: #6b7280; border: 1px solid #d1d5db; padding: 6px 14px; font-size: 12px; border-radius: 4px; cursor: pointer; }
  .btn-deactivate:hover { border-color: #9ca3af; color: #374151; }
  table { width: 100%; border-collapse: collapse; }
  td, th { padding: 12px 20px; font-size: 13px; text-align: left; border-bottom: 1px solid #f0ede8; }
  th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #aaa; background: #fafaf9; }
  tr:last-child td { border-bottom: none; }
  tr.disabled-row td { opacity: .45; }
  select { border: 1px solid #d8d5d0; padding: 6px 10px; font-size: 13px; border-radius: 4px; background: #fff; cursor: pointer; }
  select:disabled { background: #f4f4f2; cursor: default; }
  input[type=text] { border: 1px solid #d8d5d0; padding: 6px 10px; font-size: 13px; border-radius: 4px; width: 180px; }
  input[type=text]:disabled { background: #f4f4f2; }
  .btn-save { background: #4f5b4c; color: #fff; border: none; padding: 7px 16px; font-size: 12px; font-weight: 600; border-radius: 4px; cursor: pointer; }
  .btn-save:hover { background: #3d4a3a; }
  .btn-save:disabled { background: #d1d5db; cursor: default; }
  .inline-msg { font-size: 12px; color: #16a34a; margin-left: 8px; display: none; }
  .footer-bar { max-width: 960px; margin: 0 auto 40px; padding: 0 24px; display: flex; align-items: center; gap: 16px; }
  .btn-save-all { background: #4f5b4c; color: #fff; border: none; padding: 12px 32px; font-size: 14px; font-weight: 600; border-radius: 6px; cursor: pointer; }
  .btn-save-all:hover { background: #3d4a3a; }
  .save-result { font-size: 13px; color: #16a34a; display: none; }
</style>
</head>
<body>

<header>
  <div>
    <h1>Lisnyky House · Адмін</h1>
    <span>Управління будинками</span>
  </div>
  <a href="logout.php" class="logout">Вийти</a>
</header>

<main>
  <div class="notice">
    ⚠️ Зміни зберігаються одразу на сайті. Перевіряйте перед збереженням.
  </div>

  <p class="section-h">Головна сторінка</p>
  <div class="section-block" style="margin-bottom:32px">
    <div class="section-title">
      <span class="section-name">Блок 07 — Колекція будинків</span>
      <span style="font-size:12px;color:#888">Ціна на головній сторінці</span>
    </div>
    <table>
      <thead><tr><th>Поле</th><th>Значення</th><th></th></tr></thead>
      <tbody>
        <tr>
          <td>Ціна (плашка на фото)</td>
          <td><input type="text" id="main-price-input" value="<?= htmlspecialchars($mainPrice) ?>" style="width:220px"></td>
          <td>
            <button class="btn-save" onclick="saveMainPrice()">Зберегти</button>
            <span class="inline-msg" id="main-price-msg">✓ Збережено</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <p class="section-h">Секції та будинки</p>

  <?php foreach ($bySection as $secId => $secUnits): ?>
    <?php $building = ($sections[$secId]['status'] ?? 'building') === 'building'; ?>
    <div class="section-block" data-section="<?= $secId ?>">
      <div class="section-title">
        <span class="section-name">Секція <?= $secId ?></span>
        <div class="section-right">
          <?php if ($building): ?>
            <span class="badge-building">🔧 Будується</span>
            <button class="btn-activate" onclick="toggleSection(<?= $secId ?>, 'active', this)">Ввести в експлуатацію</button>
          <?php else: ?>
            <span class="badge-active">✅ Введено в експлуатацію</span>
            <button class="btn-deactivate" onclick="toggleSection(<?= $secId ?>, 'building', this)">Повернути в будівництво</button>
          <?php endif; ?>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Будинок</th>
            <th>Статус</th>
            <th>Ціна</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($secUnits as $u): ?>
            <tr data-id="<?= htmlspecialchars($u['id']) ?>" class="<?= $building ? 'disabled-row' : '' ?>">
              <td>Таунхаус <?= sprintf('%02d', $u['unit']) ?></td>
              <td>
                <select class="field-status" <?= $building ? 'disabled' : '' ?>>
                  <option value="free"     <?= $u['status'] === 'free'     ? 'selected' : '' ?>>Вільний</option>
                  <option value="reserved" <?= $u['status'] === 'reserved' ? 'selected' : '' ?>>Під завдатком</option>
                  <option value="sold"     <?= $u['status'] === 'sold'     ? 'selected' : '' ?>>Продано</option>
                </select>
              </td>
              <td>
                <input type="text" class="field-price" value="<?= htmlspecialchars($u['price']) ?>" <?= $building ? 'disabled' : '' ?>>
              </td>
              <td>
                <button class="btn-save btn-save-row" <?= $building ? 'disabled' : '' ?>>Зберегти</button>
                <span class="inline-msg">✓ Збережено</span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</main>

<div class="footer-bar">
  <button class="btn-save-all" onclick="saveAll()">Зберегти всі зміни</button>
  <span class="save-result" id="save-result"></span>
</div>

<script>
// Save main page price
function saveMainPrice() {
  const val = document.getElementById('main-price-input').value.trim();
  if (!val) return;
  fetch('save.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ settings: { main_price: val } })
  }).then(r => r.json()).then(d => {
    if (d.ok) {
      const msg = document.getElementById('main-price-msg');
      msg.style.display = 'inline';
      setTimeout(() => msg.style.display = 'none', 2000);
    } else alert('Помилка: ' + d.error);
  }).catch(() => alert('Помилка збереження'));
}

// Save individual row
document.querySelectorAll('.btn-save-row').forEach(btn => {
  btn.addEventListener('click', () => {
    const row = btn.closest('tr');
    const id     = row.dataset.id;
    const status = row.querySelector('.field-status').value;
    const price  = row.querySelector('.field-price').value;
    const [sec, unit] = id.split('-').map(Number);
    saveUnits([{ id, section: sec, unit, status, price }], () => {
      const msg = row.querySelector('.inline-msg');
      msg.style.display = 'inline';
      setTimeout(() => msg.style.display = 'none', 2000);
    });
  });
});

// Save all
function saveAll() {
  const units = [];
  document.querySelectorAll('tr[data-id]:not(.disabled-row)').forEach(row => {
    const id = row.dataset.id;
    const [sec, unit] = id.split('-').map(Number);
    units.push({ id, section: sec, unit,
      status: row.querySelector('.field-status').value,
      price:  row.querySelector('.field-price').value });
  });
  saveUnits(units, () => {
    const el = document.getElementById('save-result');
    el.textContent = '✓ Всі зміни збережено';
    el.style.display = 'inline';
    setTimeout(() => el.style.display = 'none', 3000);
  });
}

function saveUnits(units, onSuccess) {
  fetch('save.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ units })
  }).then(r => r.json()).then(d => {
    if (d.ok) onSuccess();
    else alert('Помилка: ' + d.error);
  }).catch(() => alert('Помилка збереження'));
}

// Toggle section status (building ↔ active)
function toggleSection(secId, newStatus, btn) {
  const label = newStatus === 'active' ? 'Ввести секцію ' + secId + ' в експлуатацію?' : 'Повернути секцію ' + secId + ' в будівництво?';
  if (!confirm(label)) return;

  fetch('save.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ section: { id: secId, status: newStatus } })
  }).then(r => r.json()).then(d => {
    if (d.ok) location.reload();
    else alert('Помилка: ' + d.error);
  }).catch(() => alert('Помилка збереження'));
}
</script>

</body>
</html>
