# Visitor Photo Slideshow (HTML/CSS/JS + PHP + MySQL)
Visitors enter a name, consent, and upload JPG/PNG photos. `slideshow.html` on a TV/computer cycles all photos (default 5 s each); new uploads play next. A cookie identifies returning visitors, whose earlier photos stay in the rotation (stored in MySQL).

**Stack:** HTML, CSS, vanilla JS, PHP 8+ (PDO), MySQL/MariaDB, Apache (XAMPP locally).

## Run locally (Windows, XAMPP)
1. Install XAMPP (apachefriends.org); start **Apache** and **MySQL** in the control panel.
2. Copy this folder to `C:\xampp\htdocs\slideshow`.
3. Open http://localhost/phpmyadmin → *Import* → choose `schema.sql`.
4. Copy `.env.example` to `.env`; for default XAMPP set `DB_USER=root`, `DB_PASS=` (empty).
5. Open http://localhost/slideshow/ (upload) and http://localhost/slideshow/slideshow.html (display).
6. Tests: `C:\xampp\php\php.exe tests\lib_test.php`. If large files are rejected, raise `upload_max_filesize`/`post_max_size` to ≥ 6M in `php.ini`.

**Deployed URL:** _TODO — add after deploying to any PHP+MySQL host._

Docs: ASSUMPTIONS.md · ARCHITECTURE.md · AI_LOG.md · TESTS.md · privacy.html
