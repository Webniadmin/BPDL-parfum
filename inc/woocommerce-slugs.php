<?php
/**
 * Serbian (Latin) URL structure for the storefront.
 *
 * Product base      /parfem/{slug}/
 * Category base     /kategorija-parfema/{slug}/
 * Tag base          /oznaka-parfema/{slug}/
 * Attribute base    /atribut/
 * My Account        /moj-nalog/{porudzbine|preuzimanja|adrese|podaci-naloga|odjava}/
 * Checkout          /placanje/{porudzbina-primljena|placanje-porudzbine}/
 *
 * The values are written to the WooCommerce options (so they stay editable in
 * WooCommerce → Settings → Advanced) and are only applied while the options still
 * hold their stock English values. Nothing here hardcodes the site domain.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Serbian permalink bases for WooCommerce.
 *
 * @return array
 */
function webni_wc_permalink_bases() {
	return apply_filters( 'webni_wc_permalink_bases', array(
		'product_base'           => '/parfem',
		'category_base'          => 'kategorija-parfema',
		'tag_base'               => 'oznaka-parfema',
		'attribute_base'         => 'atribut',
		'use_verbose_page_rules' => false,
	) );
}

/**
 * Serbian endpoint slugs, keyed by WooCommerce option name.
 *
 * @return array
 */
function webni_wc_endpoint_slugs() {
	return apply_filters( 'webni_wc_endpoint_slugs', array(
		// My Account.
		'woocommerce_myaccount_orders_endpoint'          => 'porudzbine',
		'woocommerce_myaccount_view_order_endpoint'      => 'porudzbina',
		'woocommerce_myaccount_downloads_endpoint'       => 'preuzimanja',
		'woocommerce_myaccount_edit_account_endpoint'    => 'podaci-naloga',
		'woocommerce_myaccount_edit_address_endpoint'    => 'adrese',
		'woocommerce_myaccount_payment_methods_endpoint' => 'nacini-placanja',
		'woocommerce_myaccount_lost_password_endpoint'   => 'zaboravljena-lozinka',
		'woocommerce_logout_endpoint'                    => 'odjava',
		// Checkout.
		'woocommerce_checkout_pay_endpoint'                        => 'placanje-porudzbine',
		'woocommerce_checkout_order_received_endpoint'             => 'porudzbina-primljena',
		'woocommerce_myaccount_add_payment_method_endpoint'        => 'dodaj-nacin-placanja',
		'woocommerce_myaccount_delete_payment_method_endpoint'     => 'obrisi-nacin-placanja',
		'woocommerce_myaccount_set_default_payment_method_endpoint' => 'podrazumevani-nacin-placanja',
	) );
}

/**
 * Stock WooCommerce endpoint values (used to detect "never customised").
 *
 * @return array
 */
function webni_wc_endpoint_stock_values() {
	return array(
		'woocommerce_myaccount_orders_endpoint'          => 'orders',
		'woocommerce_myaccount_view_order_endpoint'      => 'view-order',
		'woocommerce_myaccount_downloads_endpoint'       => 'downloads',
		'woocommerce_myaccount_edit_account_endpoint'    => 'edit-account',
		'woocommerce_myaccount_edit_address_endpoint'    => 'edit-address',
		'woocommerce_myaccount_payment_methods_endpoint' => 'payment-methods',
		'woocommerce_myaccount_lost_password_endpoint'   => 'lost-password',
		'woocommerce_logout_endpoint'                    => 'customer-logout',
		'woocommerce_checkout_pay_endpoint'                        => 'order-pay',
		'woocommerce_checkout_order_received_endpoint'             => 'order-received',
		'woocommerce_myaccount_add_payment_method_endpoint'        => 'add-payment-method',
		'woocommerce_myaccount_delete_payment_method_endpoint'     => 'delete-payment-method',
		'woocommerce_myaccount_set_default_payment_method_endpoint' => 'set-default-payment-method',
	);
}

/**
 * Apply the Serbian URL structure. Called on theme activation and from the admin tool.
 *
 * @param bool $force Overwrite values that were customised by hand.
 * @return bool Whether anything changed (rewrite rules need flushing).
 */
function webni_apply_serbian_urls( $force = false ) {
	$changed = false;

	// Core permalinks: /%postname%/ with Serbian category/tag bases. The WP_Rewrite setters
	// update both the option and the live object, so a flush in this same request sees them.
	global $wp_rewrite;
	if ( $force || ! get_option( 'permalink_structure' ) ) {
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$changed = true;
	}
	if ( $force || ! get_option( 'category_base' ) ) {
		$wp_rewrite->set_category_base( 'kategorija' );
		$changed = true;
	}
	if ( $force || ! get_option( 'tag_base' ) ) {
		$wp_rewrite->set_tag_base( 'oznaka' );
		$changed = true;
	}

	// WooCommerce permalink bases.
	$current = (array) get_option( 'woocommerce_permalinks', array() );
	$stock   = array( '', 'product', '/product', 'product-category', 'product-tag', 'shop' );
	$target  = webni_wc_permalink_bases();
	$next    = $current;
	foreach ( array( 'product_base', 'category_base', 'tag_base', 'attribute_base' ) as $k ) {
		$cur = isset( $current[ $k ] ) ? trim( (string) $current[ $k ], '/' ) : '';
		if ( $force || '' === $cur || in_array( $cur, $stock, true ) ) {
			$next[ $k ] = $target[ $k ];
		}
	}
	$next['use_verbose_page_rules'] = false;
	if ( $next !== $current ) {
		update_option( 'woocommerce_permalinks', $next );
		$changed = true;
	}

	// Endpoints.
	$stock_endpoints = webni_wc_endpoint_stock_values();
	foreach ( webni_wc_endpoint_slugs() as $option => $slug ) {
		$cur = (string) get_option( $option, '' );
		if ( $force || '' === $cur || ( isset( $stock_endpoints[ $option ] ) && $cur === $stock_endpoints[ $option ] ) ) {
			if ( $cur !== $slug ) {
				update_option( $option, $slug );
				$changed = true;
			}
		}
	}

	if ( $changed ) {
		// WooCommerce caches endpoint slugs in its query object; make sure the next request sees the new ones.
		if ( function_exists( 'WC' ) && isset( WC()->query ) && method_exists( WC()->query, 'init_query_vars' ) ) {
			WC()->query->init_query_vars();
		}
		update_option( 'webni_flush_rewrite', 1 );
	}

	return $changed;
}

/**
 * Flush rewrite rules once after the URL structure changed (never on every request).
 *
 * Hard flush: on Apache the pretty-permalink block must also be written to .htaccess,
 * otherwise every pretty URL *and every /wp-json/ REST call* returns 404 — and a burst
 * of REST 404s from the WooCommerce admin screens is exactly what hosting firewalls ban.
 * If the block cannot be written, pretty permalinks are switched off again (plain URLs
 * keep REST and the admin working) and an admin notice explains what to do.
 */
function webni_maybe_flush_rewrite() {
	if ( ! get_option( 'webni_flush_rewrite' ) ) {
		return;
	}
	delete_option( 'webni_flush_rewrite' );
	if ( ! function_exists( 'save_mod_rewrite_rules' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/misc.php';
	}
	flush_rewrite_rules( true );
	webni_verify_permalinks();
}
add_action( 'init', 'webni_maybe_flush_rewrite', 99 );

/**
 * The rewrite block WordPress needs in .htaccess (what Settings → Permalinks writes).
 *
 * @return string
 */
function webni_htaccess_block() {
	$home_root = wp_parse_url( home_url() );
	$path      = isset( $home_root['path'] ) ? trailingslashit( $home_root['path'] ) : '/';
	return "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase {$path}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$path}index.php [L]
</IfModule>
# END WordPress
";
}

/**
 * Whether the site's .htaccess carries the WordPress rewrite block.
 *
 * @return bool
 */
function webni_htaccess_has_rules() {
	if ( ! function_exists( 'get_home_path' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	$file = get_home_path() . '.htaccess';
	if ( ! is_readable( $file ) ) {
		return false;
	}
	$contents = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
	return false !== strpos( $contents, '# BEGIN WordPress' ) && false !== strpos( $contents, 'RewriteRule' );
}

/**
 * Whether pretty permalinks can work on this server without any further step.
 * nginx/IIS serve them from their own config; Apache (and LiteSpeed) need .htaccess.
 *
 * @return bool
 */
function webni_pretty_permalinks_ready() {
	global $is_apache, $is_nginx, $is_iis7;
	if ( $is_nginx ) {
		return true;
	}
	if ( $is_iis7 ) {
		return function_exists( 'iis7_supports_permalinks' ) ? iis7_supports_permalinks() : true;
	}
	if ( $is_apache ) {
		return webni_htaccess_has_rules();
	}
	return true; // Unknown server (CLI, dev server): nothing to verify.
}

/**
 * After a flush: keep pretty permalinks only when the server can serve them;
 * otherwise fall back to plain URLs and leave a notice for the administrator.
 */
function webni_verify_permalinks() {
	if ( ! get_option( 'permalink_structure' ) ) {
		return;
	}
	if ( webni_pretty_permalinks_ready() ) {
		delete_option( 'webni_permalink_notice' );
		return;
	}
	// Plain permalinks keep /wp-json/ reachable as ?rest_route= and avoid 404 floods.
	update_option( 'permalink_structure', '' );
	update_option( 'webni_permalink_notice', 1 );
	flush_rewrite_rules( false );
}

/**
 * Admin notice: .htaccess could not be written, pretty URLs are off until it is fixed.
 */
function webni_permalink_notice() {
	if ( ! get_option( 'webni_permalink_notice' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( webni_pretty_permalinks_ready() && get_option( 'permalink_structure' ) ) {
		delete_option( 'webni_permalink_notice' );
		return;
	}
	?>
	<div class="notice notice-warning">
		<p><strong>BP|DL Parfum:</strong> <?php esc_html_e( 'Serbian URLs (/parfemi/, /parfem/…) are switched off because WordPress could not write its rewrite block to .htaccess. The site works with plain URLs in the meantime.', 'webni-custom-version-1' ); ?></p>
		<p><?php esc_html_e( 'Fix: make .htaccess in the site root writable (or paste the block below into it), then open Settings → Permalinks, choose "Post name" and save — or use WooCommerce → BP|DL alati → Apply Serbian URLs.', 'webni-custom-version-1' ); ?></p>
		<pre style="background:#f6f7f7;padding:10px;overflow:auto;font-size:12px"><?php echo esc_html( webni_htaccess_block() ); ?></pre>
	</div>
	<?php
}
add_action( 'admin_notices', 'webni_permalink_notice' );
