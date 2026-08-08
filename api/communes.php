<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$wilaya_id = (int)($_GET['wilaya_id'] ?? 0);

if (!$wilaya_id) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = db()->prepare('SELECT id, name FROM communes WHERE wilaya_id = ? ORDER BY name');
    $stmt->execute([$wilaya_id]);
    $communes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($communes);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'تعذر جلب البلديات']);
}
