# VolcanoSpa® Front-end Branding

**Plugin Slug:** `mp-branding-vspa`  
**Current Version:** 1.1.4  
**Author:** Millenia Productions LLC  
**Author URI:** https://lapalmproducts.com  

---

## Overview

VolcanoSpa® Front-end Branding enforces consistent trademark rendering for the VolcanoSpa® brand across WordPress and WooCommerce front-end output.

The plugin normalizes variations such as:

- `Volcano Spa`
- `volcanospa`
- `VOLCANO SPA`
- `Volcano Spa®`

and renders them as:

```
VolcanoSpa<span class="regmark">®</span>
```

All processing occurs at output time. The database is never modified.

---

## Behavior

### Normalization Rules

- Matches `Volcano Spa` and `VolcanoSpa` (any case)
- Prevents duplicate registered symbols
- Safely handles adjacent text-node marks
- Avoids mutation inside:
  - `<script>`
  - `<style>`
  - `<noscript>`
  - `<textarea>`

### Rendering

All occurrences are rendered identically:

```
VolcanoSpa<span class="regmark">®</span>
```

Trademark styling is applied via:

```
assets/css/branding.css
```

The stylesheet is enqueued on the front end only.

---

## Architecture

### Output-Based Processing

- Uses DOM-based text node replacement
- Does not use output buffering
- Does not modify attribute values
- Does not mutate structured data

### SEO Safety

As of version 1.1.4:

- The plugin does **not** filter `bloginfo`
- The plugin does **not** modify document `<title>` output
- The plugin does **not** alter meta tag content

---

## Installation

1. Upload the plugin folder to:

   ```
   /wp-content/plugins/
   ```

2. Activate via WordPress Admin.

---

## Versioning

This plugin follows Semantic Versioning:

- **Major** → Breaking change
- **Minor** → New functionality
- **Patch** → Fixes / packaging corrections

---

## Changelog

### 1.1.4
- Remove `bloginfo` filter to prevent modification of document title and meta outputs.
- Synchronize readme metadata with plugin version.

### 1.1.3
- Correct packaging issue.
- Ensure branding stylesheet is properly included and enqueued.

### 1.1.2
- Enqueue branding stylesheet for `.regmark` styling.

### 1.1.1
- Introduce dedicated branding stylesheet.

### 1.1.0
- Strengthen DOM processing and duplicate mark prevention.

### 1.0.0
- Initial release.

---

## Internal Use Notice

This plugin is proprietary and intended for use within Millenia Productions LLC and associated brand properties only.