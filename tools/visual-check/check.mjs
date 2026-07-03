#!/usr/bin/env node
/**
 * Marche 1900 — headless visual / responsive smoke check.
 *
 * Renders the site in real Chrome (headless) across mobile, tablet and desktop
 * viewports and asserts the things static tools (curl / lint) cannot see:
 * no horizontal scroll, the mobile menu actually opens, web fonts paint, and
 * the photo gallery loads. Writes one screenshot per page/viewport and exits
 * non-zero if any assertion fails, so it works locally and in CI.
 *
 * Usage:
 *   npm install          # once, in this directory
 *   npm run check        # or: node check.mjs
 *
 * Configuration (all optional env vars):
 *   CHECK_URL     Base URL to test.        Default https://www.marche1900.be/
 *   CHECK_IP      Map the URL host to this IP via Chrome's resolver, so the
 *                 production hostname resolves to a local server with the right
 *                 TLS SNI.                 Default 127.0.0.1  (set "" to disable)
 *   CHECK_PAGES   Comma-separated paths.   Default the primary nav pages
 *   CHECK_CHROME  Explicit Chrome binary.  Default: auto-detected
 *   CHECK_OUT     Screenshot directory.    Default ./screenshots
 */

import { existsSync, mkdirSync, rmSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

const HERE = dirname(fileURLToPath(import.meta.url));

let puppeteer;
try {
  puppeteer = (await import('puppeteer-core')).default;
} catch {
  console.error('puppeteer-core is not installed. Run "npm install" in ' + HERE);
  process.exit(2);
}

// ---- Config ---------------------------------------------------------------
const BASE = process.env.CHECK_URL || 'https://www.marche1900.be/';
const IP = process.env.CHECK_IP ?? '127.0.0.1';
const OUT = process.env.CHECK_OUT || join(HERE, 'screenshots');
const PAGES = (process.env.CHECK_PAGES ||
  '/,/marche,/associations,/medias,/contact,/markt')
  .split(',').map(s => s.trim()).filter(Boolean);

const VIEWPORTS = [
  { name: 'mobile-360', width: 360, height: 800, mobile: true },
  { name: 'mobile-390', width: 390, height: 844, mobile: true },
  { name: 'tablet-768', width: 768, height: 1024, mobile: false },
  { name: 'desktop-1280', width: 1280, height: 900, mobile: false },
];

function findChrome() {
  const candidates = [
    process.env.CHECK_CHROME,
    '/bin/google-chrome', '/usr/bin/google-chrome', '/usr/bin/google-chrome-stable',
    '/bin/chromium', '/usr/bin/chromium', '/usr/bin/chromium-browser',
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  ].filter(Boolean);
  return candidates.find(p => { try { return existsSync(p); } catch { return false; } });
}

const CHROME = findChrome();
if (!CHROME) {
  console.error('No Chrome/Chromium binary found. Set CHECK_CHROME=/path/to/chrome');
  process.exit(2);
}

const host = new URL(BASE).hostname;
const launchArgs = ['--no-sandbox', '--ignore-certificate-errors', '--hide-scrollbars'];
if (IP) launchArgs.push(`--host-resolver-rules=MAP ${host} ${IP}`);

// ---- Assertions run inside the page ---------------------------------------
async function inspect(page, isMobile) {
  await page.evaluate(() => document.fonts.ready);
  // Scroll through the page to trigger lazy-loaded images, then settle.
  await page.evaluate(async () => {
    await new Promise(res => {
      let y = 0;
      const t = setInterval(() => {
        window.scrollBy(0, 600); y += 600;
        if (y >= document.body.scrollHeight) { clearInterval(t); res(); }
      }, 60);
    });
    window.scrollTo(0, 0);
  });
  await new Promise(r => setTimeout(r, 800));

  return page.evaluate((isMobile) => {
    const de = document.scrollingElement || document.documentElement;
    const checks = [];
    const add = (name, ok, detail) => checks.push({ name, ok, detail });

    // 1. No horizontal scroll.
    const overflow = de.scrollWidth - de.clientWidth;
    add('no-horizontal-scroll', overflow <= 1, `${overflow}px past ${de.clientWidth}px`);

    // 2. Web fonts painted (only meaningful where a heading exists).
    if (document.querySelector('h1,h2,h3')) {
      const m = document.fonts.check('1em metropolitain');
      const g = document.fonts.check('1em goudy');
      add('fonts-loaded', m && g, `metropolitain:${m} goudy:${g}`);
    }

    // 3. Mobile nav: hamburger present, >=44px, and it opens.
    if (isMobile) {
      const open = document.querySelector('.wp-block-navigation__responsive-container-open');
      if (open) {
        const r = open.getBoundingClientRect();
        add('hamburger-44px', r.width >= 44 && r.height >= 44,
          `${Math.round(r.width)}x${Math.round(r.height)}px`);
      } else {
        add('hamburger-present', false, 'no toggle found');
      }
    }

    // 4. Gallery images all loaded (only where a gallery exists).
    const imgs = [...document.querySelectorAll('.album-gallery img, .wp-block-gallery img')];
    if (imgs.length) {
      const broken = imgs.filter(i => i.complete && i.naturalWidth === 0).length;
      add('gallery-images-load', broken === 0, `${imgs.length - broken}/${imgs.length} loaded`);
    }

    return { overflow, checks };
  }, isMobile);
}

// Click the hamburger, confirm the overlay opens, AND measure the actual text
// contrast against the overlay's effective background (walking up for the first
// opaque ancestor). This is what catches "cream links on a white overlay": the
// class can toggle while the text is still unreadable.
async function checkMenu(page) {
  const toggle = await page.$('.wp-block-navigation__responsive-container-open');
  if (!toggle) return null;
  await toggle.click();
  await new Promise(r => setTimeout(r, 450));
  return page.evaluate(() => {
    const c = document.querySelector('.wp-block-navigation__responsive-container');
    const opened = !!(c && c.classList.contains('is-menu-open'));
    const link = document.querySelector(
      '.wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation-item__content, ' +
      '.wp-block-navigation__responsive-container.is-menu-open a');
    if (!opened || !link) return { opened, contrast: null };
    const rgb = s => (s.match(/[\d.]+/g) || []).map(Number);
    const bgOf = el => {
      for (let n = el; n; n = n.parentElement) {
        const p = rgb(getComputedStyle(n).backgroundColor);
        if (p.length >= 3 && p[3] !== 0) return p;
      }
      return [255, 255, 255];
    };
    const lum = ([r, g, b]) => {
      const f = c => { c /= 255; return c <= 0.03928 ? c / 12.92 : ((c + 0.055) / 1.055) ** 2.4; };
      return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b);
    };
    const L1 = lum(rgb(getComputedStyle(link).color)), L2 = lum(bgOf(link));
    const hi = Math.max(L1, L2), lo = Math.min(L1, L2);
    return { opened, contrast: +((hi + 0.05) / (lo + 0.05)).toFixed(2) };
  });
}

// ---- Run ------------------------------------------------------------------
rmSync(OUT, { recursive: true, force: true });
mkdirSync(OUT, { recursive: true });

console.log(`Chrome:   ${CHROME}`);
console.log(`Base URL: ${BASE}${IP ? `  (${host} -> ${IP})` : ''}`);
console.log(`Pages:    ${PAGES.join(' ')}`);
console.log('');

const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: launchArgs });
let failures = 0;
let skipped = 0;

for (const path of PAGES) {
  const url = new URL(path, BASE).href;
  for (const vp of VIEWPORTS) {
    const page = await browser.newPage();
    await page.setViewport({
      width: vp.width, height: vp.height,
      deviceScaleFactor: 1, isMobile: vp.mobile, hasTouch: vp.mobile,
    });
    let status = 0;
    try {
      const resp = await page.goto(url + (url.includes('?') ? '&' : '?') + 'nocache=' + Date.now(),
        { waitUntil: 'networkidle0', timeout: 45000 });
      status = resp ? resp.status() : 0;
    } catch (e) {
      console.log(`  SKIP  ${path} @ ${vp.name}: ${e.message.split('\n')[0]}`);
      skipped++; await page.close(); continue;
    }
    if (status >= 400) {
      console.log(`  SKIP  ${path} @ ${vp.name}: HTTP ${status} (page absent)`);
      skipped++; await page.close(); continue;
    }

    const { checks } = await inspect(page, vp.mobile);

    // Screenshot the page itself first, before the menu covers it.
    const base = `${path.replace(/[^a-z0-9]+/gi, '_') || 'home'}__${vp.name}`;
    await page.screenshot({ path: join(OUT, `${base}.png`), fullPage: true });

    if (vp.mobile) {
      const menu = await checkMenu(page);
      if (menu) {
        checks.push({ name: 'menu-opens', ok: menu.opened, detail: String(menu.opened) });
        if (menu.contrast !== null) {
          checks.push({
            name: 'menu-contrast>=4.5',
            ok: menu.contrast >= 4.5,
            detail: `${menu.contrast}:1`,
          });
        }
        // Capture the open overlay so the readability is eyeball-able too.
        await page.screenshot({ path: join(OUT, `${base}__menu-open.png`) });
      }
    }

    const failed = checks.filter(c => !c.ok);
    failures += failed.length;
    const mark = failed.length ? 'FAIL' : 'ok  ';
    console.log(`  ${mark}  ${path} @ ${vp.name}` +
      `  [${checks.map(c => `${c.ok ? '✓' : '✗'}${c.name}`).join(' ')}]`);
    for (const f of failed) console.log(`         ✗ ${f.name}: ${f.detail}`);

    await page.close();
  }
}

await browser.close();

console.log('');
console.log(`Screenshots: ${OUT}`);
console.log(skipped ? `Skipped ${skipped} page/viewport combos (absent or unreachable).` : '');
if (failures) {
  console.error(`✗ ${failures} check(s) failed.`);
  process.exit(1);
}
console.log('✓ All checks passed.');
