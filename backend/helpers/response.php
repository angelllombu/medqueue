<?php
// Format JSON seragam: {"success": true/false, "data": ..., "message": ""}
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function jsonResponse(bool $success, $data = null, string $message = '', int $code = 200): void {
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'data'    => $data,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function ok($data = null, string $message = ''): void {
    jsonResponse(true, $data, $message, 200);
}

function fail(string $message, int $code = 400, $data = null): void {
    jsonResponse(false, $data, $message, $code);
}

// Baca body JSON; fallback ke $_POST (untuk form-data di Postman)
function getInput(): array {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    if (is_array($json)) {
        return $json;
    }
    return $_POST ?: [];
}
