<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);

$in = getInput();
$username = trim($in['username'] ?? '');
$password = (string)($in['password'] ?? '');

if ($username === '' || $password === '') {
    fail('Username dan password wajib diisi.');
}

$db = getDB();
$stmt = $db->prepare('SELECT id, name, username, password, role FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    fail('Username atau password salah.', 401);
}

$patient = getPatientForUser((int)$user['id']);

ok([
    'token'      => issueToken((int)$user['id']),
    'user'       => [
        'id'       => (int)$user['id'],
        'name'     => $user['name'],
        'username' => $user['username'],
        'role'     => $user['role'],
    ],
    'patient_id' => $patient ? (int)$patient['id'] : null,
    'phone'      => $patient['phone'] ?? null,
], 'Login berhasil.');
