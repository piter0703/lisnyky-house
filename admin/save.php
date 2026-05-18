<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!($_SESSION['admin'] ?? false)) {
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$data  = json_decode(file_get_contents(DATA_FILE), true);

// --- Update settings (main page price, etc.) ---
if (isset($input['settings']) && is_array($input['settings'])) {
    if (!isset($data['settings'])) $data['settings'] = [];
    if (isset($input['settings']['main_price'])) {
        $val = htmlspecialchars(strip_tags($input['settings']['main_price']));
        if (mb_strlen($val) > 0 && mb_strlen($val) <= 60) {
            $data['settings']['main_price'] = $val;
        }
    }
}

// --- Update section status ---
if (isset($input['section'])) {
    $upd     = $input['section'];
    $allowed = ['active', 'building'];
    if (!isset($upd['id']) || !in_array($upd['status'] ?? '', $allowed)) {
        echo json_encode(['ok' => false, 'error' => 'Bad section input']);
        exit;
    }
    foreach ($data['sections'] as &$sec) {
        if ($sec['id'] == $upd['id']) {
            $sec['status'] = $upd['status'];
            break;
        }
    }
    unset($sec);
}

// --- Update units ---
if (isset($input['units']) && is_array($input['units'])) {
    $updates = [];
    foreach ($input['units'] as $u) {
        if (isset($u['id'])) $updates[$u['id']] = $u;
    }
    $allowedStatus = ['free', 'reserved', 'sold'];
    foreach ($data['units'] as &$unit) {
        if (isset($updates[$unit['id']])) {
            $upd = $updates[$unit['id']];
            if (in_array($upd['status'] ?? '', $allowedStatus)) {
                $unit['status'] = $upd['status'];
            }
            if (!empty($upd['price'])) {
                $unit['price'] = htmlspecialchars(strip_tags($upd['price']));
            }
        }
    }
    unset($unit);
}

$result = file_put_contents(DATA_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
if ($result === false) {
    echo json_encode(['ok' => false, 'error' => 'Cannot write file. Check permissions on data/houses.json']);
    exit;
}

echo json_encode(['ok' => true]);
