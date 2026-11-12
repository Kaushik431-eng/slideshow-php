<?php
require __DIR__ . '/../config.php';
$v = current_visitor(); if (!$v) out(200, []);
$c = db()->prepare('SELECT COUNT(*) FROM photos WHERE visitor_id=?'); $c->execute([$v['id']]);
out(200, ['name' => $v['name'], 'count' => (int) $c->fetchColumn()]);
