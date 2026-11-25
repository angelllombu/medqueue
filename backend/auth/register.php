<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);

$in = getInput();
$name     = trim($in['name'] ?? '');
$username = trim($in['username'] ?? '');
$password = (string)($in['password'] ?? '');
$phone    = trim($in['phone'] ?? '');

if ($name === '' || $username === '' || $password === '') {
    fail('Nama, username, dan password wajib diisi.');
}
if (strlen($password) < 6) {
    fail('Password minimal 6 karakter.');
}
if (!preg_match('/^[A-Za-z0-9_.]{3,50}$/', $username)) {
    fail('Username 3-50 karakter: huruf, angka, titik, atau underscore.');
}

$db = getDB();

$cek = $db->prepare('SELECT id FROM users WHERE username = ?');
$cek->execute([$username]);
if ($cek->fetch()) {
    fail('Username sudah dipakai.', 409);
}

try {
    $db->beginTransaction();

    // Role SELALU patient. Client tidak boleh menentukan role.
    $u = $db->prepare('INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, "patient")');
    $u->execute([$name, $username, password_hash($password, PASSWORD_DEFAULT)]);
    $userId = (int)$db->lastInsertId();

    $p = $db->prepare('INSERT INTO patients (user_id, name, phone) VALUES (?, ?, ?)');
    $p->execute([$userId, $name, $phone !== '' ? $phone : null]);
    $patientId = (int)$db->lastInsertId();

    $db->commit();
} catch (Throwable $e) {
    if ($db->inTransaction()) $db->rollBack();
    fail('Gagal mendaftar: ' . $e->getMessage(), 500);
}

ok([
    'user_id'    => $userId,
    'patient_id' => $patientId,
], 'Registrasi berhasil. Silakan login.');
