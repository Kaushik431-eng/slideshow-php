# AI Log
Tool: Claude. Prompts: (1) original brief (Node version); (2) revised brief requiring HTML/CSS/JS + PHP + MySQL, privacy policy, GDPR, TESTS.md → this repo.

## Mistakes by the AI (documented honestly)
1. **Shell brace expansion** (earlier Node prototype): `mkdir -p dir/{a,b}` in `/bin/sh` created nothing, so later file writes failed ("Directory nonexistent"). *Detected:* stderr. *Fixed:* plain `mkdir -p a b`, rewrote files.
2. **`node --test tests/`** failed on this Node version. *Detected:* a spurious failing test "tests". *Fixed:* pointed script at the test file.
3. **Unverified build (this repo):** Claude wrote the PHP/MySQL code in a sandbox without PHP or MySQL, so none of it was executed. *Detected:* `which php` returned nothing. *Action:* tests marked "not yet run" in TESTS.md; the user must run them and record results here. Any bugs found should be logged below.

## Your entries
_Add your own prompts and any AI mistakes you find while running this._
