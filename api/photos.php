<?php
require __DIR__ . '/../config.php';
$rows = db()->query('SELECT p.id, v.name, p.filename FROM photos p JOIN visitors v ON v.id = p.visitor_id ORDER BY p.id')->fetchAll();
out(200, ['slideMs' => (int) env('SLIDE_MS', 5000), 'photos' => array_map(fn($r) => ['id' => (int) $r['id'], 'name' => $r['name'], 'url' => 'uploads/' . $r['filename']], $rows)]);
