# VolcanoSpa® Front-end Branding

Front-end trademark normalization plugin for VolcanoSpa®.  
Ensures consistent rendering of the VolcanoSpa® brand across WordPress and WooCommerce output.

---

## Overview

This plugin enforces canonical formatting of the VolcanoSpa® brand in visible front-end output.

It:

- Normalizes all case variations of `Volcano Spa` and `VolcanoSpa`
- Removes duplicate registered marks (`®`)
- Safely rewrites only text nodes (never attributes)
- Skips `<script>`, `<style>`, `<noscript>`, and `<textarea>` content
- Avoids `appendXML()` and fragile regex-only approaches
- Prevents double-marking in adjacent DOM nodes

Canonical output:

```html
VolcanoSpa<span class="regmark">®</span>
```

---

## Scope

The plugin operates only on:

- WordPress front-end output
- WooCommerce product and cart display strings
- Text nodes inside rendered HTML

It does **not**:

- Modify database values
- Mutate product slugs or URLs
- Alter JSON-LD or JavaScript
- Store any persistent data

---

## Installation

1. Upload the `mp-branding-vspa` folder to:

   ```
   wp-content/plugins/
   ```

2. Activate **VolcanoSpa® Front-end Branding** in WordPress.

---

## Versioning

This plugin follows semantic versioning:

- Patch → bug fixes
- Minor → non-breaking improvements
- Major → breaking behavioral changes

Current version: `1.1.1`

---

## Repository Structure

```
mp-branding-vspa/
  mp-branding-vspa.php
  readme.txt
  README.md
  uninstall.php
  assets/
    css/
      regmark.css
```

---

## Ownership

Author: Millenia Productions LLC  
Author URI: https://lapalmproducts.com