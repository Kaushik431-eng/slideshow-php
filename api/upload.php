<?php
require __DIR__ . '/../config.php'; require __DIR__ . '/../lib.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(405, ['error' => 'POST only.']);
if (($_POST['consent'] ?? '') !== 'yes') out(400, ['error' => 'You must consent to public display before uploading.']);
$name = clean_name($_POST['name'] ?? '');
if ($name === '') out(400, ['error' => 'Please enter your name.']);
if (!isset($_FILES['photo'])) out(400, ['error' => 'No file received (it may exceed the server limit).']);
$f = $_FILES['photo'];
if ($f['error'] !== UPLOAD_ERR_OK) out(in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE]) ? 413 : 400, ['error' => 'Upload failed (code ' . $f['error'] . '). Please retry.']);
[$ext, $err, $code] = validate_image($f['tmp_name'], $f['size'], (int) env('MAX_UPLOAD_BYTES', 5242880));
if ($err) out($code, ['error' => $err]);

$slot = acquire_slot((int) env('MAX_CONCURRENT_UPLOADS', 4));
if (!$slot) out(503, ['error' => 'Server busy, retrying…'], ['Retry-After: 3']);
try {
  $pdo = db(); $pdo->beginTransaction();
  $v = current_visitor(); $returning = (bool) $v; $now = date('Y-m-d H:i:s');
  if ($v) {
    $pdo->prepare('UPDATE visitors SET name=?, consent_at=?, consent_version=? WHERE id=?')->execute([$name, $now, env('POLICY_VERSION', '1.0'), $v['id']]);
    $vid = $v['id'];
  } else {
    $token = bin2hex(random_bytes(32));
    $pdo->prepare('INSERT INTO visitors (token_hash, name, consent_at, consent_version) VALUES (?,?,?,?)')->execute([hash('sha256', $token), $name, $now, env('POLICY_VERSION', '1.0')]);
    $vid = (int) $pdo->lastInsertId(); set_vid($token, 31536000);
  }
  $file = bin2hex(random_bytes(16)) . '.' . $ext;          // never trust the client filename
  if (!move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $file)) throw new RuntimeException('move failed');
  $pdo->prepare('INSERT INTO photos (visitor_id, filename) VALUES (?,?)')->execute([$vid, $file]);
  $pdo->commit();
  $c = $pdo->prepare('SELECT COUNT(*) FROM photos WHERE visitor_id=?'); $c->execute([$vid]);
  out(201, ['returning' => $returning, 'total' => (int) $c->fetchColumn()]);
} catch (Throwable $e) {
  if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
  error_log($e->getMessage()); out(500, ['error' => 'Server error. Please try again.']);
} finally { release_slot($slot); }
