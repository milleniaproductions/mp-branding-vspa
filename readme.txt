=== VolcanoSpa® Front-end Branding ===
Tags: branding, trademark, formatting
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.1.5
License: Proprietary
License URI: https://lapalmproducts.com

Front-end trademark formatting for VolcanoSpa®. Normalizes “Volcano Spa” / “VolcanoSpa” variants and renders the registered mark consistently.

== Description ==

VolcanoSpa® Front-end Branding ensures consistent trademark rendering across the front end of WordPress and WooCommerce.

Features:

* Normalizes "Volcano Spa" and "VolcanoSpa" (any case)
* Handles spacing variants, including non-breaking spaces (NBSP)
* Renders as: VolcanoSpa<span class="regmark">®</span>
* Prevents duplicate ® symbols (including same-node cases like "VolcanoSpa® ...")
* Avoids mutation inside:
  - <script>
  - <style>
  - <noscript>
  - <textarea>
* Enqueues a dedicated branding stylesheet for consistent superscript rendering
* Does not modify SEO or meta title output

This plugin is intended for internal use by Millenia Productions LLC / La Palm® Spa Products.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin panel

== Frequently Asked Questions ==

= Does this plugin modify database content? =

No. All normalization occurs at output time only.

= Does it affect SEO meta tags or the document title? =

No. The plugin does not filter `bloginfo` and does not modify head-level metadata.

== Changelog ==

= 1.1.5 =
* Fix: Prevent double ® in same-text-node cases (e.g., "VolcanoSpa® CBD+ Edition").
* Fix: Improve duplicate-mark stripping when input is already marked.
* Fix: Match Elementor/Builder spacing variants (NBSP-safe matching).

= 1.1.4 =
* Fix: Remove `bloginfo` filter to prevent unintended modification of document title and meta outputs.
* Update: Synchronize stable tag and version metadata.

= 1.1.3 =
* Fix: Correct packaging issue and ensure branding stylesheet is properly included and enqueued.

= 1.1.2 =
* Fix: Enqueue branding stylesheet so `.regmark` styling is applied on the front end.

= 1.1.1 =
* Enhancement: Add dedicated branding stylesheet for superscript rendering.

= 1.1.0 =
* Improvement: Strengthen DOM processing and duplicate mark prevention.

= 1.0.0 =
* Initial release.