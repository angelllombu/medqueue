<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);
requireAdmin();

$in = getInput();
$id = (int)($in['id'] ?? 0);
if ($id <= 0) fail('ID dokter wajib diisi.');

$db = getDB();
$cek = $db->prepare('SELECT id FROM doctors WHERE id = ?');
$cek->execute([$id]);
if (!$cek->fetch()) fail('Dokter tidak ditemukan.', 404);

// Dokter yang sudah punya jadwal/riwayat tidak boleh dihapus
$jadwal = $db->prepare('SELECT COUNT(*) FROM schedules WHERE doctor_id = ?');
$jadwal->execute([$id]);
if ((int)$jadwal->fetchColumn() > 0) {
    fail('Dokter ini sudah punya jadwal atau riwayat, tidak dapat dihapus.', 409);
}

$db->prepare('DELETE FROM doctors WHERE id = ?')->execute([$id]);
ok(null, 'Dokter berhasil dihapus.');