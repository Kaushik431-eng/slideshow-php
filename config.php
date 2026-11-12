<?php
function env($k, $d = null) {
  static $e = null;
  if ($e === null) { $e = []; $f = __DIR__ . '/.env';
    if (is_file($f)) foreach (file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $l)
      if ($l[0] !== '#' && strpos($l, '=') !== false) { [$a, $b] = explode('=', $l, 2); $e[trim($a)] = trim($b); } }
  return ($e[$k] ?? getenv($k)) ?: $d;
}
function db() {
  static $p = null;
  if (!$p) $p = new PDO('mysql:host=' . env('DB_HOST', '127.0.0.1') . ';dbname=' . env('DB_NAME', 'slideshow') . ';charset=utf8mb4',
    env('DB_USER', 'root'), env('DB_PASS', ''), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
  return $p;
}
function out($code, $a, $h = []) { http_response_code($code); header('Content-Type: application/json'); foreach ($h as $x) header($x); echo json_encode($a); exit; }
function current_visitor() {
  if (empty($_COOKIE['vid'])) return null;
  $s = db()->prepare('SELECT * FROM visitors WHERE token_hash = ?'); $s->execute([hash('sha256', $_COOKIE['vid'])]);
  return $s->fetch() ?: null;
}
function set_vid($token, $ttl) { setcookie('vid', $token, ['expires' => time() + $ttl, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']); }
