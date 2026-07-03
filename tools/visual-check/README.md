# Visual / responsive smoke check

Renders the live site in real headless Chrome across mobile, tablet, and desktop
viewports and asserts the things `curl` and PHP lint can't see:

- **No horizontal scroll** at 360 / 390 / 768 / 1280px (catches wide images,
  fixed widths, and overflow bugs).
- **Mobile menu works**: the hamburger is present, is at least 44x44px, the
  overlay opens on tap, and its link text has at least 4.5:1 contrast against the
  overlay background (catches "light text on a white overlay").
- **Web fonts paint**: Metropolitain and Goudy load (not the Georgia fallback).
- **Gallery loads**: every photo resolves (no broken images) after scrolling.

A screenshot of every page/viewport is written to `screenshots/`, and the script
exits non-zero if any assertion fails, so it doubles as a CI gate.

## Run it

```bash
cd tools/visual-check
npm install        # once; pulls puppeteer-core (uses the system Chrome, no download)
npm run check
```

You need a Chrome or Chromium binary on the machine. The script auto-detects the
common locations; override with `CHECK_CHROME=/path/to/chrome`.

## Configuration

All optional, via env vars:

| Var | Default | Purpose |
|-----|---------|---------|
| `CHECK_URL` | `https://www.marche1900.be/` | Base URL to test |
| `CHECK_IP` | `127.0.0.1` | Maps the URL's host to this IP in Chrome's resolver, so the production hostname hits a local server with the correct TLS SNI. Set to `""` to disable and hit the URL as-is. |
| `CHECK_PAGES` | `/,/marche,/associations,/medias,/contact,/markt` | Comma-separated paths. Pages returning 4xx are skipped, not failed. |
| `CHECK_CHROME` | auto | Explicit Chrome/Chromium binary |
| `CHECK_OUT` | `./screenshots` | Where screenshots are written |

Examples:

```bash
# Test a staging URL directly (no host mapping)
CHECK_IP="" CHECK_URL="https://staging.example/" npm run check

# Only the front page, against a local dev server
CHECK_URL="http://localhost:8080/" CHECK_IP="" CHECK_PAGES="/" npm run check
```

Per-page, per-viewport results print as `ok`/`FAIL` with the individual checks;
failures list the offending detail (e.g. `no-horizontal-scroll: 15px past 390px`).
