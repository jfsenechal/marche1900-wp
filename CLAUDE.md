# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Self-hosted **WordPress 7.0** install for **marche1900.be** — the site of the "Marche 1900" historical market event in Marche-en-Famenne, Belgium. The site is **bilingual French (default) / Dutch (NL)**. Default locale is `fr_FR`; NL pages are distinct WordPress pages with `-nl` slugs.

Almost everything in the repo root and `wp-admin/`, `wp-includes/` is **WordPress core — do not edit it**.

The **active theme is `wp-content/themes/marche1900`**, a modern **block theme** (Full Site Editing) built to the brand in `DESIGN.md`. The previous classic theme `wp-content/themes/marche` is still on disk but **inactive** — kept for reference while content/layouts are ported. New work goes in the block theme; only touch the classic `marche` theme to consult how a legacy page behaved.

## Environment & commands

- `wp` (WP-CLI) is installed and on PATH. Use it for any site operation that needs the database (options, posts, cache, search-replace, user management). Most `wp` commands require a working DB connection configured in `wp-config.php`.
- PHP 8.5 CLI is available. WordPress core targets older PHP; the active block theme's PHP (`functions.php`, `patterns/*.php`) is modern and procedural. The inactive classic `marche` theme is Twenty Thirteen-era PHP 5.x code.
- `wp theme activate <slug>` switches the active theme. Rollback to the classic theme is `wp theme activate marche`. After editing block templates/`theme.json`, changes are read from disk on the next request; a full-site-editing site may also cache global styles — `wp cache flush` if a `theme.json` change doesn't appear.
- This is **not a git repository** and has no build step, package manager, or test suite. Edits to `.php`/`.css` take effect on the next request.
- `wp-config.php` is gitignored-style local config: `table_prefix = 'wp_'`, `WPLANG = 'fr_FR'`, `WP_DEBUG = false`. Treat it as containing secrets — never print DB credentials or salts.

## Active theme: `wp-content/themes/marche1900` (block theme)

A WordPress **block theme**, `theme.json` **version 3** (matches WP 6.9+/7.0). It implements the brand in `DESIGN.md` and is self-contained (it does **not** rely on any root stylesheet). Structure:

```
marche1900/
├── style.css            # theme header only (Text Domain: marche1900)
├── theme.json           # v3: color palette, font families (@font-face), type scale, spacing, borders, styles, parts
├── functions.php        # asset loading, pattern category, "engraved-sign" heading block style
├── assets/
│   ├── fonts/           # bundled Metropolitain + Goudy Bookletter 1911 (woff/ttf)
│   └── css/marche1900.css   # only what theme.json can't express: focus rings, pine nav-bar hover/active, FR/NL switcher, reduced-motion
├── parts/               # header.html (masthead + FR/NL switch + pine nav bar), footer.html (→ footer pattern)
├── patterns/            # footer.php (partner logos + copyright), practical-info.php, album.php (home gallery) — PHP for dynamic URLs/loops
└── templates/           # front-page, index, page, page-wide, single, archive, search, 404
```

Key facts for working in it:

- **Design tokens are normative in `theme.json`** (presets `pine`, `sage`, `orange`, `orange-deep`, `beige`, `cream`, `white`; fonts `metropolitain`/`goudy`; spacing scale `20`–`80`; radius presets incl. `card` = 5px). Default core color/shadow presets are disabled. Prefer editing `theme.json` over adding CSS.
- **Images & dynamic URLs live in PHP patterns**, not in the `.html` templates/parts (block HTML can't run PHP). Footer partner logos, the home gallery (`album.php`), and dynamic year/`home_url()` values are rendered there via `wp_get_attachment_image()`. The masthead banner, hero photo, and gallery photos are **media-library attachments** (referenced by ID); only the legacy classic theme used `/static/img/`. Fonts are bundled in the theme, not loaded from `/static/fonts/`.
- **Adding a new `patterns/*.php` file? Clear the pattern cache** or it won't register and any `<!-- wp:pattern -->` reference renders empty. WordPress caches the theme's pattern file list per theme; a freshly added file is invisible until you run `wp eval 'wp_get_theme()->delete_pattern_cache();'` (then `wp cache flush`). Editing an already-registered pattern's contents does not need this. Symptom: the pattern is missing from the page and `WP_Block_Patterns_Registry::get_instance()->is_registered('marche1900/<slug>')` returns false.
- **Navigation is hardcoded** as `core/navigation-link` items in `parts/header.html` (Accueil / Le Marché / Associations / Médias / Contact). Editable via the Site Editor (Appearance → Editor), which this theme enables.
- **Accessibility:** the legacy "sage titles on cream" combo fails WCAG AA (~2.8:1), so headings use accessible pine green and the Metropolitain display face carries hierarchy; sage/orange are reserved for decoration and state. Keep AA when adding UI.
- **Bilingual:** UI strings are French via the `marche1900` text domain; the header FR/NL switcher links NL → `/markt`.

## Legacy classic theme: `wp-content/themes/marche` (inactive, reference only)

A customized **Twenty Thirteen** copy, kept to consult how legacy pages behaved. Its two project-specific mechanisms (no longer executing, since it's inactive):

- **Per-page header routing** — `header.php` dispatches on page slug via `get_header('<name>')` to `header-<slug>.php` partials (e.g. `accueil`, `contact`/`contact-nl`, `medias`/`media-nl`, `associations`/`verenigingen`, and `markt` for several pages; `marche` default).
- **Per-category single-post templates** — a `single_template` filter (`check_for_category_single_template` in `functions.php`) loads `single-category-{slug}.php` when present (`associations` FR, `verenigingen` NL). These are **full standalone HTML documents** with their own `<head>`/SEO meta, not partials.

When porting a legacy page into the block theme, read the corresponding `header-*.php` / `single-category-*.php` here for its content and SEO meta, then rebuild it as a block template or pattern.

## `static/` directory (legacy hand-built assets)

`static/` at the repo root holds the original hand-coded site assets, served directly (not through WordPress). The **legacy classic theme** referenced it by absolute path. The **active block theme no longer references `/static/img/` at all**: the masthead (2382), hero photo (2381), and all eight footer partner logos (2383–2390) were imported into the **media library** (`wp-content/uploads/2026/06/`) and are referenced by attachment ID — `patterns/footer.php` renders them via `wp_get_attachment_image()`. The block theme also bundles its **own** copies of the Goudy/Metropolitain fonts under `marche1900/assets/fonts/`. So `static/` is now used only by the (inactive) legacy theme; the originals remain there as the import source.

```
static/
├── css/                 # bxSlider + Spry form-validation stylesheets
│   ├── jquery.bxslider.css
│   ├── SpryValidationTextField.css
│   └── SpryValidationTextarea.css
├── js/                  # bxSlider carousel, fitVids, easing, Spry validation, script.js
│   ├── jquery.bxslider.js / .min.js
│   ├── jquery.easing.1.3.js
│   ├── jquery.fitvids.js
│   ├── script.js
│   └── SpryValidationTextField.js / SpryValidationTextarea.js
├── fonts/               # @font-face web fonts (Goudy Bookletter 1911, Metropolitain) — eot/svg/ttf/woff
├── img/                 # ~92 images: sliders (slider-*.jpg), partner logos (logo-*.png),
│                        #   social icons, yearly posters (marche-20xx.jpg, affiche-2014.jpg)
└── programme2014.pdf … programme2019.pdf   # downloadable yearly event programmes
```

When updating sliders or form validation in the standalone templates, the relevant libraries are bxSlider (carousels) and Adobe Spry (form field validation) in `static/css` and `static/js`.

## `wp-content/uploads/` (media library — runtime data, ~1.1 GB)

WordPress-managed media. This is **runtime content, not source** — don't bulk-edit or commit it; manage media through WordPress / `wp media` instead.

```
wp-content/uploads/
├── 2014/ … 2026/        # standard WP date-based media, one dir per year, MM subfolders (01–12)
├── photo-gallery/       # ~797 MB — flat photo archive named YYYY-NN.JPG (e.g. 2011-01.JPG); legacy gallery dump, not WP's date tree
├── maps-backup/         # backup folder (currently empty)
└── wpcf7_uploads/       # Contact Form 7 file-upload staging (currently empty)
```

The `photo-gallery/` folder dominates the directory size and predates the year-based structure (contains photos back to 2011). The presence of `wpcf7_uploads/` indicates the **Contact Form 7** plugin is (or was) in use even though it's not present in `wp-content/plugins/`.

## Conventions

- Keep FR and NL at parity. In the block theme that means designing layouts that flex for both languages' text lengths; in legacy content it means mirroring `associations`↔`verenigingen`, `contact`↔`contact-nl`, `medias`↔`media-nl`.
- In the block theme, follow WordPress block-markup conventions in `templates/`/`parts/` (valid `<!-- wp:* -->` comments) and modern WP coding standards in `functions.php`/`patterns/*.php`. `style.css` is the theme header only — do not put rules there; use `theme.json` first, then `assets/css/marche1900.css`.
- `index.php` at the repo root is the WordPress front-controller entry point. (A legacy standalone `index-nl.php` was removed once the NL pages were ported to the block theme.)

## Design Context

Strategic and visual design are documented for agents in two root files; read them before any UI/design work:

- **`PRODUCT.md`** — register (`brand`), audiences (visitors / associations / press), the two equal jobs (help plan a visit + convey the event's character), brand personality (**heritage & authentic; authentic, warm, timeless**), anti-references, and five design principles.
- **`DESIGN.md`** (+ `.impeccable/design.json` sidecar) — the visual system, North Star **"The Belle Époque Village Green"**.

`DESIGN.md` is **implemented by the active block theme**, primarily in `marche1900/theme.json`. That file is the source of truth for tokens. (The legacy classic theme's root `custom-style.css`, which `DESIGN.md` was reverse-engineered from, has since been removed.) Brand summary:

- **Palette:** Pine Green `#515f51` (body text, nav bar, borders), Sage Green `#8f9782` (decoration/labels), Warm Cream `#fff4de` (page bg), Sage-Beige `#dfdcbc` (flat surfaces/cards, 5px radius), Marché Orange `#e88335` (active/hover only — rare); plus Burnt Orange `#9c5212` for accessible link/hover text.
- **Type:** **Metropolitain** (Art Nouveau / Paris Métro display) for titles, nav, labels; **Goudy Bookletter 1911** (1911 serif) for running text. Bundled and loaded via `theme.json` `fontFace` from `marche1900/assets/fonts/`.
- **Feel:** flat (no shadows), tonal, calm, low-density; bilingual FR/NL at parity. Avoid: generic SaaS/template, dated/cramped, corporate/cold, and theme-park kitsch.

Impeccable's `live` mode is not configured: this is server-rendered PHP/WordPress with no static HTML entry or JS dev server to inject into.
