<?php
/**
 * Webni Custom Version 1 Theme — bootstrap.
 *
 * Everything lives in /inc; this file only wires it together.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

define( 'WEBNI_THEME_VERSION', '1.1.5' );
define( 'WEBNI_THEME_DIR', get_template_directory() );
define( 'WEBNI_THEME_URI', get_template_directory_uri() );
define( 'WEBNI_TEXT_DOMAIN', 'webni-custom-version-1' );

$webni_includes = array(
	'inc/theme-setup.php',       // Theme supports, menus, image sizes, rewrite bases.
	'inc/enqueue.php',           // Conditional CSS/JS, fonts, localized data.
	'inc/template-tags.php',     // Output helpers (icons, cards, prices, perfume data).
	'inc/product-meta.php',      // "BP|DL karakter" product meta box.
	'inc/customizer.php',        // Theme settings panel.
	'inc/gravity-forms.php',     // Safe Gravity Forms rendering.
	'inc/quiz.php',              // Quiz configuration (branches, copy).
	'inc/woocommerce-strings.php', // Serbian (Latin) storefront strings.
	'inc/woocommerce-slugs.php',   // Serbian URL bases and endpoints.
	'inc/woocommerce.php',       // WooCommerce hooks and layout.
	'inc/demo-content.php',      // Default page content (help, legal drafts).
	'inc/activation.php',        // Pages, store defaults, menus on activation.
	'inc/demo-import.php',       // Admin tools page + one-click demo catalogue.
);

foreach ( $webni_includes as $webni_file ) {
	require_once WEBNI_THEME_DIR . '/' . $webni_file;
}
