<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);

$user = requireLogin();
$in = getInput();
$patient = getPatientForUser((int)$user['id']);

$name = trim($in['name'] ?? '');
$phone = array_key_exists('phone', $in) ? trim((string)$in['phone']) : ($patient['phone'] ?? '');

if ($name === '') fail('Nama wajib diisi.');
if ($phone !== '' && !preg_match('/^\+?[0-9]{8,15}$/', $phone)) {
    fail('Nomor HP tidak valid (8-15 angka).');
}

$db = getDB();
$db->beginTransaction();
try {
    $db->prepare('UPDATE users SET name = ? WHERE id = ?')
       ->execute([$name, $user['id']]);
    if ($patient) {
        $db->prepare('UPDATE patients SET name = ?, phone = ? WHERE id = ?')
           ->execute([$name, $phone, $patient['id']]);
    }
    $db->commit();
} catch (Throwable $e) {
    $db->rollBack();
    fail('Gagal menyimpan profil.', 500);
}

ok([
    'id'         => (int)$user['id'],
    'name'       => $name,
    'username'   => $user['username'],
    'role'       => $user['role'],
    'patient_id' => $patient ? (int)$patient['id'] : null,
    'phone'      => $patient ? $phone : null,
], 'Profil berhasil diperbarui.');