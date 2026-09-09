<?php
require_once __DIR__.'/../includes/functions.php';

header('Content-Type: application/json');

$code = strtoupper(trim($_GET['code'] ?? ''));

if (!$code) {
    echo json_encode(['ok' => false, 'error' => 'الرجاء إدخال كود الخصم']);
    exit;
}

$stmt = db()->prepare("SELECT * FROM promo_codes WHERE code = ? AND status = 'active' LIMIT 1");
$stmt->execute([$code]);
$promo = $stmt->fetch();

if (!$promo) {
    echo json_encode(['ok' => false, 'error' => 'كود الخصم غير صحيح أو غير مفعل']);
    exit;
}

// Check if expired
if ($promo['expires_at'] && strtotime($promo['expires_at']) < time()) {
    echo json_encode(['ok' => false, 'error' => 'كود الخصم منتهي الصلاحية']);
    exit;
}

// Check usage limit
if ($promo['usage_limit'] > 0 && $promo['used_count'] >= $promo['usage_limit']) {
    echo json_encode(['ok' => false, 'error' => 'لقد تم تجاوز الحد الأقصى لاستخدام هذا الكود']);
    exit;
}

echo json_encode([
    'ok' => true,
    'discount_type' => $promo['discount_type'],
    'discount_value' => (float)$promo['discount_value']
]);
