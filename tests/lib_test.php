<?php // Run: php tests/lib_test.php   (no DB needed)
require __DIR__ . '/../lib.php'; $fail = 0;
function check($label, $ok) { global $fail; echo ($ok ? 'PASS' : 'FAIL') . " - $label\n"; if (!$ok) $fail++; }
$png = tempnam(sys_get_temp_dir(), 't'); imagepng(imagecreatetruecolor(2, 2), $png);
$txt = tempnam(sys_get_temp_dir(), 't'); file_put_contents($txt, 'hello');
check('valid PNG accepted', validate_image($png, filesize($png), 5e6)[0] === 'png');
check('text file rejected (415)', validate_image($txt, filesize($txt), 5e6)[2] === 415);
check('oversize rejected (413)', validate_image($png, 10, 5)[2] === 413);
check('empty rejected (400)', validate_image($png, 0, 5e6)[2] === 400);
check('name sanitised', clean_name("  <b>Ann</b>\n ") === 'bAnn/b');
check('name truncated to 40', mb_strlen(clean_name(str_repeat('x', 99))) === 40);
$a = acquire_slot(1, 1); $b = acquire_slot(1, 1);
check('limiter blocks 2nd holder when full', $a && !$b); release_slot($a);
check('limiter frees after release', ($c = acquire_slot(1, 1)) !== null); release_slot($c);
exit($fail ? 1 : 0);
