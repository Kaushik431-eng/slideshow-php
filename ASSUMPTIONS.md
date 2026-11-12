# Assumptions
- **Identity is a cookie, not a login.** `vid` = 256-bit random token, 1-year, HttpOnly, SameSite=Lax; DB stores only its SHA-256. New browser/cleared cookies = new visitor. Chosen to avoid accounts at casual events.
- **Strictly necessary vs consent:** the cookie is set only on upload, after the consent box, so no separate cookie banner is assumed necessary. A lawyer should confirm for your jurisdiction.
- **Public display, no moderation.** Anything uploaded appears at once; the organiser removes content via the DB/uploads folder.
- **JPG/PNG only, 5 MB max** (the spec's example formats); type verified from file contents (finfo + getimagesize), not filename or client MIME.
- **Queue = semaphore + client retry.** PHP has no in-process queue, so N lock-file slots limit concurrency; extra requests wait up to 10 s, then get 503 and the browser retries with backoff. Adequate for event scale on one server.
- **Retention:** 30 days after the event (stated in policy; organiser to enforce). **Controller details** in privacy.html are placeholders.
- **One server, local disk storage** for uploads.
