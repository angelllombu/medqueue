<?php
require_once __DIR__ . '/../helpers/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Gunakan method POST.', 405);
requireAdmin();

$in   = getInput();
$name = trim($in['name'] ?? '');
$spec = trim($in['specialization'] ?? '');

if ($name === '' || $spec === '') fail('Nama dan spesialisasi wajib diisi.');

$db = getDB();
$db->prepare('INSERT INTO doctors (name, specialization) VALUES (?, ?)')
   ->execute([$name, $spec]);

ok([
    'id'             => (int)$db->lastInsertId(),
    'name'           => $name,
    'specialization' => $spec,
], 'Dokter berhasil ditambahkan.');