---
name: Marche 1900
description: The heritage market of Marche-en-Famenne — a Belle Époque village green in muted green, warm cream, and Art Nouveau lettering.
colors:
  pine-green: "#515f51"
  sage-green: "#8f9782"
  marche-orange: "#e88335"
  sage-beige: "#dfdcbc"
  warm-cream: "#fff4de"
  paper-white: "#ffffff"
  muted-gray: "#999999"
typography:
  display:
    fontFamily: "metropolitain, 'Limelight', Georgia, serif"
    fontSize: "clamp(1.75rem, 4vw, 2.75rem)"
    fontWeight: 400
    lineHeight: 1.1
    letterSpacing: "0.01em"
  heading:
    fontFamily: "metropolitain, 'Limelight', Georgia, serif"
    fontSize: "clamp(1.25rem, 2.5vw, 1.75rem)"
    fontWeight: 400
    lineHeight: 1.2
    letterSpacing: "0.01em"
  body:
    fontFamily: "goudy, 'Goudy Bookletter 1911', Georgia, serif"
    fontSize: "1.0625rem"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "normal"
  label:
    fontFamily: "metropolitain, 'Limelight', Georgia, serif"
    fontSize: "0.9375rem"
    fontWeight: 400
    lineHeight: 1.3
    letterSpacing: "0.02em"
rounded:
  card: "5px"
spacing:
  sm: "10px"
  md: "30px"
  lg: "50px"
components:
  nav-bar:
    backgroundColor: "{colors.pine-green}"
    textColor: "{colors.warm-cream}"
    height: "60px"
  nav-link-active:
    textColor: "{colors.marche-orange}"
  surface-card:
    backgroundColor: "{colors.sage-beige}"
    textColor: "{colors.pine-green}"
    rounded: "{rounded.card}"
    padding: "10px"
  input:
    backgroundColor: "{colors.sage-beige}"
    textColor: "{colors.pine-green}"
    rounded: "{rounded.card}"
    padding: "10px"
  heading:
    textColor: "{colors.sage-green}"
    typography: "{typography.heading}"
  body-text:
    textColor: "{colors.pine-green}"
    typography: "{typography.body}"
---

# Design System: Marche 1900

## 1. Overview

**Creative North Star: "The Belle Époque Village Green"**

Marche 1900 is the website of a turn-of-the-century heritage market in the Ardennes town of Marche-en-Famenne. The visual system is built around a single image: a sunlit village green during a 1900s summer fête. Muted pine and sage greens stand in for the trees and lawns; warm cream is the dust of the square and the paper of a vintage program; a single warm orange is the bunting and the late-afternoon light. Over all of it sits the lettering of the era: Hector Guimard's Paris Métro signage (the **Metropolitain** typeface) for every title and sign, and **Goudy Bookletter 1911** for the running text, a serif drawn the same decade the event evokes.

The system is quiet and earthy on purpose. Authenticity here comes from real period typography, muted natural color, and generous calm, never from costume-drama props. It explicitly rejects the cream-background, gradient, identical-card SaaS template look; the busy, cramped, small-type feel of a dated 2010s theme; sterile corporate blue with stock business photography; and theme-park kitsch (faux parchment, gold scrollwork, fake-vintage gimmicks). The heritage is genuine, so the design stays understated and lets photography and lettering carry the era.

Density is low and the rhythm is unhurried. Surfaces are flat and tonal: cream page, sage-beige panels, hairline green borders. Color is structural, not decorative; the orange appears rarely, only where something is active or being touched.

**Key Characteristics:**
- Muted heritage greens + warm cream, with a single rare orange accent
- Period typography: Metropolitain (Art Nouveau display) over Goudy Bookletter 1911 (1911 serif)
- Flat, tonal depth: cream → sage-beige → hairline green borders, no drop shadows
- Centered, calm, low-density composition; bilingual FR/NL at parity
- Earthy and authentic, never corporate, never kitsch

## 2. Colors

A muted, earthy palette of greens and warm cream, with one warm orange reserved for interaction.

### Primary
- **Pine Green** (`#515f51`): The structural backbone. The main navigation bar, all body text set in Goudy, hairline borders on cards and panels, and form-field text. This is the "ink" of the system, a deep, slightly grey-green that reads almost black at small sizes but stays warm.

### Secondary
- **Sage Green** (`#8f9782`): The voice of every title and label. All Metropolitain headings (h1–h3, h6), section titles, the language switcher, and divider rules. A soft, dusty olive-green that signals "this is a heading" without shouting.

### Tertiary
- **Marche Orange** (`#e88335`): The single interactive accent. The active menu item, link hover, and program highlights. Warm, like bunting or late sun. Used sparingly by design; its scarcity is what makes it read as "active."

### Neutral
- **Warm Cream** (`#fff4de`): The page background (historically carrying a faint tiled paper texture) and the cream of navigation links sitting on the pine-green bar. The "paper" of the whole site.
- **Sage-Beige** (`#dfdcbc`): The surface color. Cards, panels, secondary menus, and form fields all sit on this pale warm sage. The one step of tonal elevation above the cream page.
- **Paper White** (`#ffffff`): Hairline dividers inside sage panels and the occasional pure-white detail.
- **Muted Gray** (`#999999`): Quiet borders on embedded media thumbnails only. Not a text color.

### Named Rules
**The Rare Orange Rule.** Marche Orange (`#e88335`) is an interaction color, never a decoration. It appears only on the active or hovered element, never as a fill, a heading color, or a band. If more than one orange thing is visible on a screen at rest, remove all but the active one.

**The Two-Green Rule.** Pine Green is for reading (body, structure, borders); Sage Green is for labelling (titles, signs). Don't set body copy in sage (it drops below readable contrast) and don't set titles in pine (it flattens the hierarchy).

## 3. Typography

**Display Font:** Metropolitain (Art Nouveau / Paris Métro lettering), with Limelight then Georgia, serif as fallbacks
**Body Font:** Goudy Bookletter 1911, with Georgia, serif as fallback
**Label Font:** Metropolitain (same as display, at small sizes)

**Character:** A true period pairing on a strong contrast axis. Metropolitain is ornamental, geometric Belle Époque signage; Goudy Bookletter 1911 is a calm, readable old-style serif from the same decade. The display face is the event's "voice"; the serif is its "page." Because Metropolitain is decorative, it lives only at title sizes and short labels, never in running text.

### Hierarchy
- **Display** (Metropolitain, regular, clamp ~1.75–2.75rem, line-height 1.1): Page-level titles and hero lettering. Sage Green.
- **Heading** (Metropolitain, regular, clamp ~1.25–1.75rem, line-height 1.2): Section titles (h2/h3), historically paired with decorative ornament backgrounds. Sage Green.
- **Body** (Goudy Bookletter 1911, regular, ~1.0625rem, line-height 1.6): All running text, descriptions, and form fields. Pine Green. Cap measure at 65–75ch.
- **Label** (Metropolitain, regular, ~0.9375rem, slight tracking): Navigation links, the language switcher, field labels, detail titles. Cream on the green bar; Sage or Pine elsewhere.

### Named Rules
**The Signage-Only Rule.** Metropolitain is signage, not text. Use it for titles, navigation, and labels of four words or fewer. Never set a sentence or a paragraph in it; running copy is always Goudy.

**The Period-Fidelity Rule.** Both faces are early-1900s designs and must stay legible. Don't squeeze tracking on Metropolitain to "decorate," and don't drop Goudy below ~16px for body; the era is the point, eye strain is not.

## 4. Elevation

The system is **flat with tonal layering**. There are no drop shadows anywhere. Depth is communicated by a single tonal step (warm-cream page → sage-beige surface) and by hairline 1px Pine Green borders around the most important panels (access info, detail cards). The historical page background also carried a faint repeating paper texture, reinforcing the "printed program" feel rather than any sense of floating UI.

### Named Rules
**The Flat-Paper Rule.** Surfaces are paper on a table, not cards floating above one. No `box-shadow`, ever. If a panel needs to separate from the page, change its fill to sage-beige and/or add a 1px Pine Green border; never add a shadow.

## 5. Components

### Navigation
- **Primary bar:** Full-width Pine Green (`#515f51`) band, 60px tall.
- **Links:** Metropolitain, ~18px, Warm Cream (`#fff4de`), generous horizontal spacing.
- **Active / hover:** Marche Orange (`#e88335`), historically with a small ornament beneath the active item.
- **Secondary menu (`menu_bis` / `menu_bis_nl`):** A centered sage-beige pill (`#dfdcbc`, 5px radius) holding Metropolitain links in Pine Green; hover shifts to Sage Green.

### Cards / Containers
- **Corner Style:** Gently rounded, 5px (`{rounded.card}`). This is the system's only radius; keep it.
- **Background:** Sage-Beige (`#dfdcbc`) on the cream page.
- **Shadow Strategy:** None. See Elevation (Flat-Paper Rule).
- **Border:** Optional 1px Pine Green (`#515f51`) on emphasis panels (access, details).
- **Internal Padding:** 10px on small tiles; 30px rhythm between blocks.

### Inputs / Fields
- **Style:** Sage-Beige fill (`#dfdcbc`), no border, 5px radius, Goudy ~16px in Pine Green.
- **Labels:** Metropolitain, uppercase, Pine Green, used sparingly for short field names.
- **Focus:** (historically unstyled) — must gain a visible Pine Green or Marche Orange focus ring; see Do's and Don'ts.

### Headings as Signage
- A signature pattern: section headings are Metropolitain in Sage Green, centered, sometimes flanked by small decorative ornament images (`back-title.png` family). This "engraved sign" treatment is the most recognizable brand device. Keep the lettering and color; the ornament art is replaceable but the centered-sign feel should stay.

### Language Switcher
- A small FR/NL toggle in Metropolitain (~15px). Active language in Pine Green, inactive in Sage Green. A permanent fixture: the site is bilingual at parity.

## 6. Do's and Don'ts

### Do:
- **Do** keep the two-green + cream + rare-orange palette. Pine `#515f51` for text/structure, Sage `#8f9782` for titles, Cream `#fff4de` for the page, Sage-Beige `#dfdcbc` for surfaces, Orange `#e88335` only for active/hover.
- **Do** set every title, nav item, and label in Metropolitain and every paragraph in Goudy Bookletter 1911. The period pairing is the identity.
- **Do** keep surfaces flat (Flat-Paper Rule): tonal fills and 1px green borders for separation, never shadows.
- **Do** bump body text to at least ~16–17px and cap measure at 65–75ch; the heritage feel must stay comfortably readable for an all-ages family audience.
- **Do** add visible keyboard focus states (Pine Green or Marche Orange ring) and `prefers-reduced-motion` fallbacks; target WCAG 2.1 AA contrast.
- **Do** treat FR and NL as equals: any layout must flex for both languages' text lengths.

### Don't:
- **Don't** ship the generic SaaS / website-builder template look: no near-white cream-bg-plus-gradient hero, no grid of identical icon-heading-text cards. (PRODUCT.md anti-reference.)
- **Don't** reproduce the dated, cramped feel: no fixed 980px desktop-only layout, no sub-16px body text, no busy heavy textures crowding the content. Modernize the layout while keeping the palette and type. (PRODUCT.md anti-reference.)
- **Don't** drift corporate/cold: no corporate blue, no sterile business stock photography with no local character. (PRODUCT.md anti-reference.)
- **Don't** descend into theme-park kitsch: no faux parchment, ornate gold scrollwork, or fake-vintage gimmicks. Heritage is carried by real photography, period type, and restraint. (PRODUCT.md anti-reference.)
- **Don't** set body copy in Metropolitain or in Sage Green, and don't use orange as a fill or heading color (Two-Green and Rare Orange Rules).
- **Don't** add `box-shadow`, gradient text, or side-stripe borders; none belong in this system.
