<?php
// Pure helpers (unit-testable without a web server or DB).
function clean_name(string $n): string { return mb_substr(trim(preg_replace('/[<>\x00-\x1F]/u', '', $n)), 0, 40); }

/** Returns [ext, null, 0] on success or [null, message, httpCode] on failure. JPG/PNG only. */
function validate_image(string $tmp, int $size, int $max): array {
  if ($size <= 0) return [null, 'The file is empty.', 400];
  if ($size > $max) return [null, 'File too large (max ' . round($max / 1048576, 1) . ' MB).', 413];
  $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
  $map = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
  if (!isset($map[$mime])) return [null, 'Unsupported file type. Please upload a JPG or PNG.', 415];
  if (@getimagesize($tmp) === false) return [null, 'This file is not a valid image.', 415];
  return [$map[$mime], null, 0];
}

/** Concurrency limiter: at most $n uploads run at once; others wait (queue) up to $wait seconds. */
function acquire_slot(int $n, int $wait = 10) {
  $end = microtime(true) + $wait;
  do { for ($i = 0; $i < $n; $i++) {
      $fp = fopen(sys_get_temp_dir() . "/slideshow_slot_$i.lock", 'c');
      if ($fp && flock($fp, LOCK_EX | LOCK_NB)) return $fp;
      if ($fp) fclose($fp);
    } usleep(100000);
  } while (microtime(true) < $end);
  return null;
}
function release_slot($fp) { if ($fp) { flock($fp, LOCK_UN); fclose($fp); } }
