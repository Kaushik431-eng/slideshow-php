<?php  // GDPR Art. 17 "right to erasure": removes the visitor, their photos (DB + files) and cookie.
require __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(405, ['error' => 'POST only.']);
$v = current_visitor(); if (!$v) out(404, ['error' => 'No data found for this device.']);
$s = db()->prepare('SELECT filename FROM photos WHERE visitor_id=?'); $s->execute([$v['id']]);
foreach ($s->fetchAll() as $r) @unlink(__DIR__ . '/../uploads/' . basename($r['filename']));
db()->prepare('DELETE FROM visitors WHERE id=?')->execute([$v['id']]);   // photos cascade
set_vid('', -3600); out(200, ['deleted' => true]);
