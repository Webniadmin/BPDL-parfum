# Webni Custom Version 1 Theme

Custom WordPress + WooCommerce theme for **BP|DL Parfum** — eight AI-written perfume recipes, one dark "character" world, Serbian (Latin) storefront.

- Theme slug / text domain: `webni-custom-version-1`
- Version: 1.1.3
- Requires: WordPress 6.4+, PHP 8.1+, WooCommerce 8.0+ (tested with 10.1)
- Forms: Gravity Forms (optional at install time, required for newsletter / quiz e-mail / contact)
- No page builders, no CDN fonts, no third-party requests from the theme.

---

## 1. Installation

1. **Appearance → Themes → Add New → Upload Theme**, choose `webni-custom-version-1.zip`, install and activate.
2. Activation runs once and is safe to repeat:
   - creates the missing pages: *Početna* (front page), *Parfemi* (shop), *Korpa*, *Plaćanje*, *Moj nalog*, *Dnevnik* (blog), *Pomoć*, *O nama*, *Kontakt*, *Uslovi kupovine*, *Politika privatnosti i kolačići*, *Reklamacije i povrat*, *Podaci o trgovcu*;
   - points WooCommerce to the Serbian pages (the stock English *Shop/Cart/Checkout/My account* pages are set to draft if they were still selected);
   - applies store defaults (see §4) unless they were applied before;
   - applies the Serbian URL structure (§3) and flushes rewrite rules;
   - creates the four menus and assigns them to empty menu locations.
3. Open **WooCommerce → BP|DL alati** and click **Učitaj demo proizvode** to load the eight perfumes, the Discovery set and the Par set (images included). The tool is idempotent and non-destructive: products are matched by slug; existing ones keep their names, prices, texts, images and character data and only empty fields are filled; nothing is deleted. It is safe to run again after a theme update.
4. Choose the home hero picture in **Appearance → Customize → BP|DL Parfum → Hero (prvi ekran)** (see §7). Until one is chosen the first perfume's product image is shown next to the headline.
5. Install Gravity Forms, create the forms (§5) and enter their IDs in **Appearance → Customize → BP|DL Parfum → Newsletter i forme**.

Nothing in the theme hardcodes a domain; it works on `bpdlparfum-dev.webni.me`, on production, or locally.

## 2. What the theme renders

| URL | Template | Notes |
| --- | --- | --- |
| `/` | `front-page.php` + `template-parts/home/*` | Hero (the eight characters cycle over a slowly drifting WebGL mist by default — nothing follows the pointer; or one large static picture), marquee, collection (all perfumes + filters), Par set band, product rails, "Zašto 30%", quiz teaser, gift (zodiac + Discovery/Par set), story + worlds, newsletter. |
| `/parfemi/` | WooCommerce archive (hooks, `content-product.php`) | Breadcrumb, title + intro, filter chips (client-side), result count, sorting, pagination, AJAX "U korpu" on cards. |
| `/kategorija-parfema/{slug}/` | same archive | Category name + description. |
| `/parfem/{slug}/` | `woocommerce/content-single-product.php` | Perfumes (products with character data) get the full landing: hero with mist + add-to-cart, "Ko ga nosi", facts, notes, 30%, Par set band (on the two perfumes sold in the set), gift + "Probaj i", CTA band. Other products (sets) use `template-parts/product/simple.php`. |
| `/korpa/`, `/placanje/`, `/moj-nalog/…` | WooCommerce shortcodes on the pages, styled by `assets/css/woocommerce.css` | Classic cart/checkout/account templates (hooks, not overrides). |
| `/pretraga/{term}/?post_type=product` | WooCommerce archive | Header search searches products. |
| blog / pages / search / 404 | `index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php` | |

All visitor-facing text is Serbian Latin. Code, comments and this file are English.

## 3. URL structure (Serbian)

Set as WooCommerce/WordPress options on activation (still editable in **Settings → Permalinks** and **WooCommerce → Settings → Advanced**). The theme only writes a value while it still holds the stock English one.

- Permalinks `/%postname%/`, category base `kategorija`, tag base `oznaka`
- Product `/parfem/{slug}/`, product category `/kategorija-parfema/{slug}/`, product tag `/oznaka-parfema/{slug}/`, attribute base `atribut`
- Shop page `/parfemi/`, cart `/korpa/`, checkout `/placanje/`, account `/moj-nalog/`
- Account endpoints: `porudzbine`, `porudzbina`, `preuzimanja`, `adrese`, `podaci-naloga`, `nacini-placanja`, `zaboravljena-lozinka`, `odjava`
- Checkout endpoints: `porudzbina-primljena`, `placanje-porudzbine`
- Core bases: pagination `strana`, search `pretraga`, author `autor`

`WooCommerce → BP|DL alati → Apply Serbian URLs` re-applies everything and flushes rewrite rules.

## 4. Store defaults applied once

RSD shown as `5.900 RSD` (0 decimals, `.` thousands separator), Serbia as the only selling/shipping country, taxes off (prices include PDV), cash on delivery enabled (*Plaćanje pouzećem*), one shipping zone *Srbija* with *Kurirska dostava* 390 RSD and *Besplatna dostava* from 6.000 RSD (paid rates are hidden when free shipping applies), guest checkout on, account registration on, reviews off, phone required at checkout, Serbian e-mail subjects/headings, Serbian privacy/terms texts, numeric date format `j. n. Y.`, store visibility "live", classic product editor (needed for the character meta box).

Card payments: leave the gateway work to the bank's plugin (Raiffeisen) later — the theme styles whatever `woocommerce_checkout_payment` renders.

## 5. Gravity Forms

The theme never renders its own form handlers. Form IDs are entered in the Customizer (**BP|DL Parfum → Newsletter i forme**), or created in one click with **WooCommerce → BP|DL alati → Napravi forme** (Kontakt, Newsletter, Quiz e-mail with Serbian labels; existing forms are kept):

| Setting | Where it renders | Suggested fields |
| --- | --- | --- |
| Newsletter form ID | Home newsletter section; footer on other pages | E-mail (required) |
| Quiz e-mail form ID (optional) | Last step of the quiz "Koji si karakter?" | E-mail; a **Hidden** field with CSS class `webni-quiz-result` receives the result ("Noćna muza (Luna)"). Enable AJAX in the form settings. Without an ID the quiz skips the e-mail step. |
| Contact form ID | `[webni_contact_form]` shortcode (used on the *Kontakt* page) | Name, e-mail, message |

If Gravity Forms is inactive or an ID is wrong, visitors see nothing; administrators see a dashed note explaining what to set. The `gf_` CSS classes get dark-theme styling automatically.

## 6. Products: the "BP|DL karakter" meta box

Perfumes are ordinary WooCommerce simple products. Price, sale price, stock, featured flag, images, categories (*Za njega*, *Za nju*, *Unisex*, *Setovi*), cross-sells and descriptions are managed by WooCommerce. The meta box adds brand data (saved as `_bpdl_*` post meta):

character, tagline, alternative tagline, pronunciation, five words, "Ko ga nosi", occasion, season, day/night (filter), top/heart/base notes, zodiac signs, two "Probaj i" products with reasons, palette (dark, mid, light/glow, accent, light-palette switch), name typography (serif / script / caps), hero image (bottle on tube), bottle image, visual world sentence, quiz result description, quiz key.

**Pictures.** The product's featured image is the card. *Hero slika* (product page hero, animated home hero, the home fallback picture) and *Mala slika* (quiz result, "Probaj i", Par set band) default to it; the demo cutouts linked by the import are replaced automatically as soon as the featured image is changed to a picture of your own, so uploading new renders in WooCommerce is enough. The second section of a perfume page (*Ko ga nosi*) shows the first picture of the WooCommerce **product gallery** (demo pictures from the import and the featured image itself are skipped); without a gallery picture it shows the featured image. A picture with a transparent background floats on the character's palette; a rectangular photo is framed and cropped (4:5) with soft edges instead. The decision is made once per image (corner-pixel alpha, cached as `_webni_cutout` attachment meta; filter `webni_image_is_cutout`).

A product appears in the home collection, the hero strip and the quiz only when *Karakter* is filled. Order = product **menu order** (Products → Sorting). The quiz results reference products by *quiz key* (defaults to the slug): `terros, secondo, luna, arden, ravon, orren, lemorae, deuxieme`. The zodiac table derives "for him / for her" from the signs ticked on each product and its audience category.

Home rails (Istaknuti = featured products, Novo, Najprodavaniji, Akcije = on sale, Preporučeni = hand-picked IDs) are switched on/off in **Customize → Kolekcija i sekcije sa proizvodima**.

**Par set.** The set product is chosen in **Customize → Poklon → Par set — proizvod**; the two perfumes it contains are its WooCommerce *Linked products → Cross-sells* (the demo import links Secondo and Deuxième). The theme shows the set as a large band below the home collection and on the product pages of those two perfumes (toggle, button text and note in **Customize → Poklon**), as the third gift card, and WooCommerce cross-sells it in the cart next to those two perfumes.

## 7. Customizer (Appearance → Customize → BP|DL Parfum)

Hero (display mode: the eight characters cycling over the mist (default) or *Jedna velika slika* — full-bleed with the copy over it or next to the copy, optional portrait version for phones, crop focus; *Jačina magle* = mist opacity in %, also used on product pages; texts and buttons), Kolekcija i sekcije sa proizvodima, Zašto 30%, Quiz, Poklon (incl. Discovery set / Par set product pickers and the Par set band), Priča, Newsletter i forme (Gravity Forms IDs), Prodavnica i stranica proizvoda (shop title/intro, delivery line, free-shipping text, product texts), Futer i kontakt (tagline, contact e-mail/phone/address, Instagram/TikTok/Facebook/YouTube, payment line, copyright with `{year}`). Logo: **Site Identity → Logo** (text wordmark is used when no logo is set). Menus: *Glavna navigacija*, *Futer — Sajt*, *Futer — Pomoć*, *Futer — Pravno*. One optional widget area: *Futer — dodatno*.

## 8. Serbian storefront strings

The official WooCommerce `sr_RS` pack is Cyrillic, so the site locale stays `en_US` and `inc/woocommerce-strings.php` translates the `woocommerce` text domain on the fly (front end, AJAX, REST, cron and while customer e-mails render — never in wp-admin). Add or change a string with the `webni_wc_strings` filter:

```php
add_filter( 'webni_wc_strings', function ( $table ) {
	$table['Add to cart'] = 'Dodaj u korpu';
	return $table;
} );
```

Theme strings use the `webni-custom-version-1` domain (`languages/webni-custom-version-1.pot`).

## 9. Developer notes

- Everything is wired from `functions.php` → `inc/*.php`. Hooks are preferred over template overrides; the only WooCommerce overrides are `woocommerce/content-product.php` (card) and `woocommerce/content-single-product.php` (product layout).
- Assets: `assets/css/fonts.css` (self-hosted Bodoni Moda, Jost, Alex Brush), `theme.css`, `woocommerce.css` (shop pages only); `assets/js/theme.js` (navigation, overlays, quiz, filters, WooCommerce feedback), `mist.js` (WebGL, only where a hero exists), `home.js`, `product.js`, `admin.js`. Scripts are deferred; data reaches JS through `wp_localize_script` (`BPDL`).
- Display-font dashes/plus signs are hairlines in Bodoni Moda; `webni_text()` / `webni_dashes()` (PHP) and `dash()` (JS) wrap them in `.dash` so they render in the body font. Use `webni_text()` for any dynamic text placed in a heading.
- Useful filters: `webni_quiz_config`, `webni_gift_config`, `webni_wc_strings`, `webni_wc_permalink_bases`, `webni_wc_endpoint_slugs`, `webni_required_pages`.
- Security: every option is sanitized in the Customizer, meta box saves check a nonce + `edit_post`, the tools page checks a nonce + `manage_woocommerce`, output is escaped, there is no direct SQL, no credentials and no API keys in the theme.
- SEO: `title-tag` support, WooCommerce structured data left in place, clean head; works with Yoast / Rank Math without changes.
- Performance: no WooCommerce stylesheets or block styles, fonts preloaded, WebGL renders at reduced resolution only while visible and stops under `prefers-reduced-motion`, lazy-loaded images with theme image sizes (`webni-card`, `webni-hero`, `webni-thumb`).

## 10. Before launch

- Settings → General: site title *BP|DL Parfum*, tagline, a real administration e-mail (hosting panels install with a placeholder like `info@<domain>`), and WooCommerce → Settings → Emails → "From" address; Appearance → Customize → Site Identity → site icon.
- Legal pages are drafts with `[placeholders]` for the company data — have them reviewed.
- Set the contact data and social links in the Customizer, upload a logo if needed.
- Create the Gravity Forms and enter their IDs.
- Replace demo texts/prices as needed; WooCommerce → Settings → Shipping for real courier prices.
- Card payments: install and configure the bank's plugin; no theme change is needed.

## Changelog

**1.1.9** — Free delivery from 6.000 RSD instead of 9.000. On update the Serbian zone's free shipping that still has the theme's earlier 9.000 RSD threshold (or no condition) moves to 6.000 RSD (`webni_free_shipping_min`, filterable); the line under the buy button reads the threshold from the zone (`{prag}` in the Customizer text) and the delivery sentence on Pomoć and in the terms do too.

**1.1.8** — Everything a customer sees is Serbian: a walk through shop, cart, checkout (with validation errors), thank-you page, log-in/registration/lost password, the account pages and all customer e-mails found ~90 English strings ("Shipment", calculator labels, coupon placeholder, password strength meter and hints, WordPress log-in errors, screen-reader price texts, WooCommerce 10/11 e-mail wording, cancelled/failed e-mails). WordPress core strings shown on the storefront have their own table (`webni_wp_strings`). The district field (English district names) is hidden for Serbian addresses everywhere. Delivery: a setup-wizard "Free shipping" without a condition (which made every order free) gets the 9.000 RSD threshold and Serbian titles on update.

**1.1.7** — Delivery in Serbia is set up on existing stores too: the theme used to create the "Srbija" zone only when the store had no zones at all, so a store with a zone from the setup wizard had no rates and orders went through without delivery. On update (and from BP|DL alati → *Podesi dostavu*) the zone covering Serbia gets "Kurirska dostava" 390 RSD and "Besplatna dostava" from 9.000 RSD; only missing methods are added, existing rates are kept. The custom cursor that trailed the mouse is removed (the system cursor is used); magnetic buttons stay.

**1.1.6** — Light perfume palettes (Deuxième, Orren …) get a dark header, breadcrumb and small print on product pages; on phones the perfume picture no longer covers the breadcrumb. Quiz: the first answer is no longer painted gold before a choice (focus goes to the question; hover only on hover-capable devices). New Customizer section *Podaci o trgovcu* feeds `[webni_trgovac polje="…"]` — the Kontakt and legal drafts are linked to it once on update (only exact [placeholders] are replaced), delivery price and free-shipping threshold come from the WooCommerce shipping zone, and the footer shows the merchant line and the bank's card/3-D Secure logo strip (also under the payment methods at checkout). BP|DL alati: *Napravi forme* creates the Kontakt/Newsletter/Quiz forms in Gravity Forms and fills in their IDs; without a contact form, visitors see e-mail/phone buttons instead of an empty spot.

**1.1.5** — Home hero (animated mode): the character cycle now shows every perfume's picture. The first picture's `srcset` made the browser ignore every other swap, so the slideshow kept falling back to the first perfume; swaps can no longer flip the two layers twice, all pictures are preloaded, framed/cutout styling follows each perfume, and the hover pause no longer fights taps on touch screens.

**1.1.4** — The *Ko ga nosi* section of a perfume page shows the first picture of the WooCommerce product gallery instead of repeating the featured image (demo pictures are skipped; the featured image stays as the fallback).

**1.1.3** — WordPress' sample widgets (Archives, Categories…) are moved out of the *Futer — dodatno* area on activation/update. Pretty-URL check is less strict: any rewrite to `index.php` in `.htaccess` counts (no WordPress markers required) and, when the file has none, the theme asks the server itself with one HEAD request to `/wp-json/` before switching to plain URLs; the admin notice names the expected file path.

**1.1.2** — The cycling character hero is the default again. The mist no longer follows the pointer (home and product pages) and drifts slowly on its own; its strength is a Customizer setting (*Jačina magle*, default 50 %, lower = more transparent). The bottle no longer bobs up and down.

**1.1.1** — New product pictures uploaded in WooCommerce are now used everywhere (product hero, home hero, quiz result, "Probaj i", Par set band) instead of the demo cutouts the import linked; pictures with a transparent background float on the palette, photos are framed and cropped. Meta box image fields relabelled.

**1.1.0** — Home hero can be one large static picture (Customizer: image, optional phone image, cover/split layout, crop focus) instead of the WebGL animation; `mist.js` is no longer loaded on the home page in image mode. New Par set band below the home collection and on the product pages of the two perfumes in the set, with AJAX add-to-cart; Secondo/Deuxième cross-sell the set in the cart. Demo import is now non-destructive (existing products keep their data and images; only empty fields are filled). Fixed: AJAX "U korpu" buttons were empty on the home collection and rails.

**1.0.1** — Pretty permalinks are now written to `.htaccess` on activation/update (hard flush) and verified; when the file cannot be written the theme falls back to plain URLs and shows an admin notice with the block to paste (a missing block made every `/wp-json/` call 404, which hosting firewalls treat as a scan). Update routine after file replacement. Removed 16 unused images.

**1.0.0** — Initial release: home, shop, product landings, cart, checkout, account, blog/pages, quiz + gift flows, Serbian URLs and strings, Customizer settings, Gravity Forms integration, demo catalogue import.
