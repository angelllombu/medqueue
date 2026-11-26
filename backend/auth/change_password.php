<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);

$user = requireLogin();
$in = getInput();
$old = (string)($in['old_password'] ?? '');
$new = (string)($in['new_password'] ?? '');

if ($old === '' || $new === '') fail('Password lama dan baru wajib diisi.');
if (strlen($new) < 6) fail('Password baru minimal 6 karakter.');
if ($old === $new) fail('Password baru harus berbeda dari yang lama.');

$db = getDB();
$stmt = $db->prepare('SELECT password FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$row = $stmt->fetch();

if (!$row || !password_verify($old, $row['password'])) {
    fail('Password lama salah.', 401);
}

$db->prepare('UPDATE users SET password = ? WHERE id = ?')
   ->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);

ok(null, 'Password berhasil diubah.');