# Webni Custom Version 1 Theme — working notes for Claude Code

WordPress + WooCommerce theme for **BP|DL Parfum** (eight AI-written perfume recipes, one dark "character" world).
Repo root **is** the theme folder: clone it into `wp-content/themes/webni-custom-version-1/`.
Full product documentation: `README.md`. Staging: the client's `*-dev` subdomain (never hardcode any domain).

## Rules that must not drift
- Everything a visitor sees is **Serbian Latin** (`ti` form, no Cyrillic). Code, comments, commit messages, docs: **English**.
- WooCommerce is the source of truth for products, prices, stock, cart, checkout, orders. Never build a parallel product/cart/checkout system.
- Forms only through **Gravity Forms** (`inc/gravity-forms.php`); form IDs come from the Customizer, never hardcoded. No fatal when GF is inactive.
- Prefer hooks over template overrides. Existing overrides: `woocommerce/content-product.php`, `woocommerce/content-single-product.php` only.
- Security: escape output (`esc_*`, `wp_kses_post`, `webni_text()`), sanitize input, nonces + capability checks on every admin action, no direct SQL, no credentials/API keys.
- No page builders, no CDN fonts, no new plugins. Text domain `webni-custom-version-1`; PHP 8.1+.

## Map
| Area | Files |
| --- | --- |
| Bootstrap | `functions.php` → `inc/*.php` in order |
| Setup, menus, image sizes, rewrite bases, body classes | `inc/theme-setup.php` |
| Assets + `BPDL` JS data | `inc/enqueue.php` (shop CSS only on shop pages; mist/home/product JS only where needed) |
| Perfume data model | `inc/template-tags.php` (`webni_perfume()` reads WC product + `_bpdl_*` meta; `webni_product_card()`; `webni_text()` wraps hairline glyphs) |
| Product meta box "BP|DL karakter" | `inc/product-meta.php` (+ `assets/js/admin.js`) |
| Theme settings | `inc/customizer.php` (`webni_option()` with defaults; panel *BP\|DL Parfum*) |
| Quiz / gift logic data | `inc/quiz.php` (PHP config) + `assets/js/theme.js` (branching, results) |
| Serbian WooCommerce strings | `inc/woocommerce-strings.php` (gettext table for the `woocommerce` domain, front end + e-mails; filter `webni_wc_strings`) |
| Serbian URLs, .htaccess handling | `inc/woocommerce-slugs.php` (`webni_apply_serbian_urls()`, hard flush, fallback to plain URLs + admin notice) |
| WooCommerce hooks/layout | `inc/woocommerce.php` (loop, single summary, cart fragments, checkout fields, rails) |
| Activation, pages, store defaults, menus, upgrade routine | `inc/activation.php` (+ `inc/demo-content.php` page texts) |
| Admin tools + demo import | `inc/demo-import.php` (WooCommerce → BP\|DL alati), data in `inc/demo-data.php` |
| Home | `front-page.php` + `template-parts/home/*` |
| Product page | `template-parts/product/perfume.php` (character landing) / `simple.php` (sets) |
| Styles | `assets/css/fonts.css`, `theme.css`, `woocommerce.css` |
| Scripts | `assets/js/theme.js`, `mist.js` (WebGL), `home.js`, `product.js` |

## Gotchas
- Bodoni Moda renders `— – + × − =` as invisible hairlines at display sizes: pass dynamic heading text through `webni_text()` (PHP) or `escd()`/`dash()` (JS).
- `.notes` is a WooCommerce checkout class — the product notes grid is `.note-grid`.
- Changing the shop page or permalink options requires a rewrite flush: set `update_option('webni_flush_rewrite', 1)`; `webni_maybe_flush_rewrite()` does a hard flush and verifies `.htaccess` (missing block ⇒ every `/wp-json/` call 404s ⇒ hosting firewalls ban the admin's IP).
- Cart/checkout/account pages are WooCommerce shortcode pages rendered by `page.php` with the wide `.shop-page` layout.
- Product cards carry `data-cat` (M/Ž/U/'') and `data-time` for the client-side filters.

## Commands
```bash
npm install                 # once; installs @wordpress/env (needs Docker)
npm start                   # WordPress + WooCommerce at http://localhost:8888 (admin/password), theme mounted live
npm run wp -- plugin list   # any WP-CLI command inside the container
npm run import              # activates the theme and runs the demo catalogue import
npm run build               # dist/webni-custom-version-1.zip (git archive; dev files export-ignored)
npm run pot                 # regenerate languages/webni-custom-version-1.pot
npm run lint                # php -l on every PHP file
```

## Definition of done for a change
1. `npm run lint` clean, `WP_DEBUG` log clean on the pages touched.
2. Checked at 1440px and 400px; no horizontal scroll; keyboard focus visible.
3. Any new visitor-facing string is Serbian Latin and goes through `__()`/`esc_html__()` with the theme domain (WooCommerce strings into the gettext table).
4. Version bump in `style.css` + `functions.php` (`WEBNI_THEME_VERSION`) and a `README.md` changelog line when shipping a zip.
