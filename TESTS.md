# Tests
## Automated — `php tests/lib_test.php`
Covers: valid PNG, text file rejected, oversize, empty, name sanitising/truncation, concurrency limiter.
**Result: NOT YET RUN** — the author's build environment had no PHP. Run it and paste output here.
## Manual plan (record results)
| # | Step | Expected | Result |
|---|---|---|---|
| 1 | Upload JPG with consent | Appears on slideshow ≤ 5 s | |
| 2 | Upload .pdf / .gif | Clear "JPG or PNG" error | |
| 3 | Upload >5 MB file | "File too large" | |
| 4 | Submit with consent unticked / empty name | Blocked with message | |
| 5 | Reload page after upload | "Welcome back", name prefilled; both photos in loop | |
| 6 | Select 10 files at once | All upload in sequence | |
| 7 | Two phones upload simultaneously | Both succeed, no errors | |
| 8 | "Delete all my data" | Photos vanish from slideshow; DB rows gone | |
| 9 | Try opening `uploads/x.php` | 403/not executed | |
