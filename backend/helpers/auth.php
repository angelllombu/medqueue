<?php
// Helper login & role. Dipakai Angel DAN Calista.
// Cara kerja: login.php mengeluarkan token bertanda tangan (HMAC).
// Flutter mengirim token itu di header:  X-Auth-Token: <token>
// Setiap endpoint memanggil requireLogin() / requireAdmin() / requirePatient().
// Role dibaca dari DATABASE, bukan dari kiriman client, jadi tidak bisa dipalsukan.

require_once __DIR__ . '/response.php';
require_once __DIR__ . '/../config/database.php';

// GANTI string ini dengan acak panjang milikmu sendiri sebelum demo.
const AUTH_SECRET = 'ganti-dengan-string-rahasia-panjang-medqueue-2026';
const TOKEN_TTL   = 60 * 60 * 24 * 7; // 7 hari

function issueToken(int $userId): string {
    $payload = $userId . '|' . (time() + TOKEN_TTL);
    $sig = hash_hmac('sha256', $payload, AUTH_SECRET);
    return rtrim(strtr(base64_encode($payload), '+/', '-_'), '=') . '.' . $sig;
}

function readTokenFromRequest(): ?string {
    if (!empty($_SERVER['HTTP_X_AUTH_TOKEN'])) {
        return trim($_SERVER['HTTP_X_AUTH_TOKEN']);
    }
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (stripos($auth, 'Bearer ') === 0) {
        return trim(substr($auth, 7));
    }
    return null;
}

function verifyToken(string $token): ?int {
    $parts = explode('.', $token);
    if (count($parts) !== 2) return null;
    $payload = base64_decode(strtr($parts[0], '-_', '+/'), true);
    if ($payload === false) return null;
    $expected = hash_hmac('sha256', $payload, AUTH_SECRET);
    if (!hash_equals($expected, $parts[1])) return null;
    [$userId, $exp] = array_pad(explode('|', $payload), 2, null);
    if (!ctype_digit((string)$userId) || (int)$exp < time()) return null;
    return (int)$userId;
}

// Mengembalikan baris user (id, name, username, role) atau menghentikan request dengan 401.
function requireLogin(): array {
    $token = readTokenFromRequest();
    if (!$token) fail('Belum login. Kirim header X-Auth-Token.', 401);
    $userId = verifyToken($token);
    if (!$userId) fail('Sesi tidak valid atau sudah habis. Silakan login ulang.', 401);

    $stmt = getDB()->prepare('SELECT id, name, username, role FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) fail('Akun tidak ditemukan.', 401);
    return $user;
}

function requireAdmin(): array {
    $user = requireLogin();
    if ($user['role'] !== 'admin') fail('Akses ditolak. Hanya admin.', 403);
    return $user;
}

function requirePatient(): array {
    $user = requireLogin();
    if ($user['role'] !== 'patient') fail('Akses ditolak. Hanya pasien.', 403);
    return $user;
}

// Ambil baris patients milik user yang login (untuk booking, riwayat, dll).
function getPatientForUser(int $userId): ?array {
    $stmt = getDB()->prepare('SELECT id, user_id, name, phone FROM patients WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}
