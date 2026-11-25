<?php
// Tes cepat: buka http://IP-SERVER/medqueue_api/ping.php di browser HP.
// Kalau muncul "db":"connected", server + database beres.
require_once __DIR__ . '/helpers/response.php';
require_once __DIR__ . '/config/database.php';

try {
    getDB()->query('SELECT 1');
    ok(['db' => 'connected', 'time' => date('c')], 'MedQueue API hidup.');
} catch (Throwable $e) {
    fail('Database tidak terhubung: ' . $e->getMessage(), 500);
}
