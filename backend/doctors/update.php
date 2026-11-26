<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);
requireAdmin();

$in   = getInput();
$id   = (int)($in['id'] ?? 0);
$name = trim($in['name'] ?? '');
$spec = trim($in['specialization'] ?? '');

if ($id <= 0) fail('ID dokter wajib diisi.');
if ($name === '' || $spec === '') fail('Nama dan spesialisasi wajib diisi.');

$db = getDB();
$cek = $db->prepare('SELECT id FROM doctors WHERE id = ?');
$cek->execute([$id]);
if (!$cek->fetch()) fail('Dokter tidak ditemukan.', 404);

$db->prepare('UPDATE doctors SET name = ?, specialization = ? WHERE id = ?')
   ->execute([$name, $spec, $id]);

ok(['id' => $id, 'name' => $name, 'specialization' => $spec], 'Dokter berhasil diperbarui.');