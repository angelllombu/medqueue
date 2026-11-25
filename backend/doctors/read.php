<?php
require_once __DIR__ . '/../helpers/auth.php';

// Sementara terbuka tanpa login supaya mudah dites dari browser HP (tes server bersama).
// Setelah server stabil, aktifkan baris di bawah sesuai kontrak API (akses: Login):
// requireLogin();

$specialization = trim($_GET['specialization'] ?? '');

$db = getDB();
if ($specialization !== '') {
    $stmt = $db->prepare('SELECT id, name, specialization FROM doctors WHERE specialization = ? ORDER BY name');
    $stmt->execute([$specialization]);
} else {
    $stmt = $db->query('SELECT id, name, specialization FROM doctors ORDER BY name');
}

ok($stmt->fetchAll(), '');
