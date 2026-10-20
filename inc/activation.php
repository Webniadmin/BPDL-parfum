<?php
/**
 * Theme activation: pages, WooCommerce store defaults, menus, Serbian URLs.
 *
 * Everything here is idempotent and conservative: existing pages are reused (by slug),
 * store options are only written while they still hold WooCommerce's stock values, and
 * nothing is deleted. The one-click demo product import lives in inc/demo-import.php.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pages the theme expects, keyed by slug.
 *
 * @return array[]
 */
function webni_required_pages() {
	$content = function_exists( 'webni_demo_page_content' ) ? 'webni_demo_page_content' : null;
	$pages   = array(
		'pocetna'                => array( 'title' => 'Početna', 'content' => '', 'option' => 'page_on_front' ),
		'parfemi'                => array( 'title' => 'Parfemi', 'content' => '', 'option' => 'woocommerce_shop_page_id' ),
		'korpa'                  => array( 'title' => 'Korpa', 'content' => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->', 'option' => 'woocommerce_cart_page_id' ),
		'placanje'               => array( 'title' => 'Plaćanje', 'content' => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->', 'option' => 'woocommerce_checkout_page_id' ),
		'moj-nalog'              => array( 'title' => 'Moj nalog', 'content' => '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->', 'option' => 'woocommerce_myaccount_page_id' ),
		'dnevnik'                => array( 'title' => 'Dnevnik', 'content' => '', 'option' => 'page_for_posts' ),
		'pomoc'                  => array( 'title' => 'Pomoć', 'content' => $content ? $content( 'pomoc' ) : '' ),
		'o-nama'                 => array( 'title' => 'O nama', 'content' => $content ? $content( 'o-nama' ) : '' ),
		'kontakt'                => array( 'title' => 'Kontakt', 'content' => $content ? $content( 'kontakt' ) : '[webni_contact_form]' ),
		'uslovi-kupovine'        => array( 'title' => 'Uslovi kupovine', 'content' => $content ? $content( 'uslovi-kupovine' ) : '', 'option' => 'woocommerce_terms_page_id' ),
		'politika-privatnosti'   => array( 'title' => 'Politika privatnosti i kolačići', 'content' => $content ? $content( 'politika-privatnosti' ) : '', 'option' => 'wp_page_for_privacy_policy' ),
		'reklamacije-i-povrat'   => array( 'title' => 'Reklamacije i povrat', 'content' => $content ? $content( 'reklamacije-i-povrat' ) : '' ),
		'podaci-o-trgovcu'       => array( 'title' => 'Podaci o trgovcu', 'content' => $content ? $content( 'podaci-o-trgovcu' ) : '' ),
	);
	return apply_filters( 'webni_required_pages', $pages );
}

/**
 * Whether a page is one of the stock pages WooCommerce creates on install (English slugs).
 *
 * @param int $page_id Page ID.
 * @return bool
 */
function webni_is_stock_wc_page( $page_id ) {
	$page = get_post( $page_id );
	return $page && in_array( $page->post_name, array( 'shop', 'cart', 'checkout', 'my-account', 'refund_returns' ), true );
}

/**
 * Create the missing pages and point the WordPress/WooCommerce page options at them.
 *
 * @return int Number of pages created.
 */
function webni_create_pages() {
	$created = 0;
	$changed = false;
	foreach ( webni_required_pages() as $slug => $page ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		$id       = $existing ? (int) $existing->ID : 0;

		if ( ! $id ) {
			$id = wp_insert_post( array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $page['title'],
				'post_name'      => $slug,
				'post_content'   => $page['content'],
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			), false );
			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}
			$created++;
		} elseif ( 'publish' !== $existing->post_status && 'trash' !== $existing->post_status ) {
			wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
		}

		if ( ! empty( $page['option'] ) ) {
			$current = (int) get_option( $page['option'] );
			if ( ! $current || 'publish' !== get_post_status( $current ) || $current === $id ) {
				if ( $current !== $id ) {
					update_option( $page['option'], $id );
					$changed = true;
				}
			} elseif ( webni_is_stock_wc_page( $current ) ) {
				// WooCommerce's own English page (Shop, Cart, …) is still selected: switch to the
				// Serbian page and park the stock one as a draft so the two never compete.
				update_option( $page['option'], $id );
				wp_update_post( array( 'ID' => $current, 'post_status' => 'draft' ) );
				$changed = true;
			}
		}
	}

	if ( (int) get_option( 'page_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
	}
	if ( $changed || $created ) {
		// The product archive slug follows the shop page, so rewrite rules must be rebuilt.
		update_option( 'webni_flush_rewrite', 1 );
	}
	return $created;
}

/**
 * WooCommerce store defaults for a Serbian shop (RSD, Serbia, cash on delivery, Serbian emails).
 * Applied once (option "webni_store_defaults_applied") or on demand from the admin tool.
 *
 * @param bool $force Re-apply even if already applied.
 * @return bool
 */
function webni_store_defaults( $force = false ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}
	if ( ! $force && get_option( 'webni_store_defaults_applied' ) ) {
		return false;
	}

	$options = array(
		// Currency & price format: "5.900 RSD".
		'woocommerce_currency'                    => 'RSD',
		'woocommerce_currency_pos'                => 'right_space',
		'woocommerce_price_thousand_sep'          => '.',
		'woocommerce_price_decimal_sep'           => ',',
		'woocommerce_price_num_decimals'          => '0',
		// Location.
		'woocommerce_default_country'             => 'RS',
		'woocommerce_allowed_countries'           => 'specific',
		'woocommerce_specific_allowed_countries'  => array( 'RS' ),
		'woocommerce_ship_to_countries'           => 'specific',
		'woocommerce_specific_ship_to_countries'  => array( 'RS' ),
		'woocommerce_default_customer_address'    => 'base',
		'woocommerce_calc_taxes'                  => 'no',
		'woocommerce_prices_include_tax'          => 'yes',
		'woocommerce_weight_unit'                 => 'kg',
		'woocommerce_dimension_unit'              => 'cm',
		// Catalog.
		'woocommerce_shop_page_display'           => '',
		'woocommerce_default_catalog_orderby'     => 'menu_order',
		'woocommerce_catalog_columns'             => '4',
		'woocommerce_catalog_rows'                => '6',
		'woocommerce_enable_reviews'              => 'no',
		'woocommerce_enable_ajax_add_to_cart'     => 'yes',
		'woocommerce_cart_redirect_after_add'     => 'no',
		'woocommerce_manage_stock'                => 'yes',
		'woocommerce_hide_out_of_stock_items'     => 'no',
		'woocommerce_stock_format'                => 'no_amount',
		'woocommerce_enable_coupons'              => 'yes',
		// Accounts & checkout.
		'woocommerce_enable_guest_checkout'       => 'yes',
		'woocommerce_checkout_phone_field'        => 'required',
		'woocommerce_checkout_company_field'      => 'hidden',
		'woocommerce_checkout_address_2_field'    => 'optional',
		'woocommerce_checkout_privacy_policy_text' => 'Tvoji podaci se koriste za obradu porudžbine i dostavu, kako je opisano u našoj [privacy_policy].',
		'woocommerce_registration_privacy_policy_text' => 'Tvoji podaci se koriste za vođenje naloga i obradu porudžbina, kako je opisano u našoj [privacy_policy].',
		'woocommerce_checkout_terms_and_conditions_checkbox_text' => 'Pročitao/la sam i prihvatam [terms]',
		'woocommerce_enable_checkout_login_reminder' => 'yes',
		'woocommerce_enable_signup_and_login_from_checkout' => 'yes',
		'woocommerce_enable_myaccount_registration' => 'yes',
		'woocommerce_registration_generate_username' => 'yes',
		'woocommerce_registration_generate_password' => 'no',
		// Classic product editor keeps the "BP|DL karakter" meta box available.
		'woocommerce_feature_product_block_editor_enabled' => 'no',
		// Make the store visible (new WooCommerce installs start in "coming soon" mode).
		'woocommerce_coming_soon'                 => 'no',
		// Cash on delivery (card payments are added later through the bank's plugin).
		'woocommerce_cod_settings'                => array(
			'enabled'            => 'yes',
			'title'              => 'Plaćanje pouzećem',
			'description'        => 'Platiš kuriru prilikom preuzimanja.',
			'instructions'       => 'Platiš kuriru prilikom preuzimanja paketa.',
			'enable_for_methods' => array(),
			'enable_for_virtual' => 'no',
		),
		// Customer emails in Serbian (bodies are translated by the theme at render time).
		'woocommerce_email_from_name'             => get_bloginfo( 'name' ),
		'woocommerce_email_footer_text'           => '{site_title} — Osam karaktera. Jedan je tvoj.',
		'woocommerce_customer_processing_order_settings' => array( 'enabled' => 'yes', 'subject' => 'Tvoja porudžbina #{order_number} je primljena', 'heading' => 'Hvala na porudžbini', 'additional_content' => 'Javljamo ti se čim paket krene. Dostava je obično za 1–3 radna dana.', 'email_type' => 'html' ),
		'woocommerce_customer_on_hold_order_settings'    => array( 'enabled' => 'yes', 'subject' => 'Tvoja porudžbina #{order_number} čeka potvrdu', 'heading' => 'Porudžbina je na čekanju', 'additional_content' => 'Javićemo se čim potvrdimo uplatu.', 'email_type' => 'html' ),
		'woocommerce_customer_completed_order_settings'  => array( 'enabled' => 'yes', 'subject' => 'Tvoja porudžbina #{order_number} je poslata', 'heading' => 'Paket je krenuo', 'additional_content' => 'Hvala što si deo priče. Ako ti se dopadne — reci nam koji si karakter.', 'email_type' => 'html' ),
		'woocommerce_customer_refunded_order_settings'   => array( 'enabled' => 'yes', 'subject_full' => 'Porudžbina #{order_number} je refundirana', 'subject_partial' => 'Porudžbina #{order_number} je delimično refundirana', 'heading_full' => 'Refundacija', 'heading_partial' => 'Delimična refundacija', 'email_type' => 'html' ),
		'woocommerce_customer_invoice_settings'          => array( 'subject' => 'Račun za porudžbinu #{order_number}', 'heading' => 'Račun za porudžbinu #{order_number}', 'subject_paid' => 'Račun za porudžbinu #{order_number}', 'heading_paid' => 'Račun za porudžbinu #{order_number}', 'email_type' => 'html' ),
		'woocommerce_customer_note_settings'             => array( 'enabled' => 'yes', 'subject' => 'Napomena uz porudžbinu #{order_number}', 'heading' => 'Napomena uz tvoju porudžbinu', 'email_type' => 'html' ),
		'woocommerce_customer_reset_password_settings'   => array( 'enabled' => 'yes', 'subject' => 'Nova lozinka za {site_title}', 'heading' => 'Nova lozinka', 'email_type' => 'html' ),
		'woocommerce_customer_new_account_settings'      => array( 'enabled' => 'yes', 'subject' => 'Tvoj nalog na sajtu {site_title}', 'heading' => 'Dobrodošao/la', 'email_type' => 'html' ),
	);

	foreach ( $options as $name => $value ) {
		update_option( $name, $value );
	}

	// Numeric Serbian date format (month names would otherwise print in English).
	if ( in_array( get_option( 'date_format' ), array( 'F j, Y', '' ), true ) ) {
		update_option( 'date_format', 'j. n. Y.' );
	}
	if ( in_array( get_option( 'time_format' ), array( 'g:i a', '' ), true ) ) {
		update_option( 'time_format', 'H:i' );
	}
	if ( ! get_option( 'timezone_string' ) && ! (float) get_option( 'gmt_offset' ) ) {
		update_option( 'timezone_string', 'Europe/Belgrade' );
	}
	if ( ! get_option( 'start_of_week' ) ) {
		update_option( 'start_of_week', 1 );
	}

	webni_default_shipping();

	update_option( 'webni_store_defaults_applied', WEBNI_THEME_VERSION );
	return true;
}

/**
 * One shipping zone (Srbija) with a flat rate and free shipping above a threshold —
 * only when the store has no shipping zones yet.
 */
function webni_default_shipping() {
	if ( ! class_exists( 'WC_Shipping_Zones' ) ) {
		return;
	}
	$zones = WC_Shipping_Zones::get_zones();
	if ( ! empty( $zones ) ) {
		return;
	}
	$zone = new WC_Shipping_Zone();
	$zone->set_zone_name( 'Srbija' );
	$zone->set_zone_order( 1 );
	$zone->add_location( 'RS', 'country' );
	$zone->save();

	$flat_id = $zone->add_shipping_method( 'flat_rate' );
	if ( $flat_id ) {
		update_option( 'woocommerce_flat_rate_' . $flat_id . '_settings', array( 'title' => 'Kurirska dostava', 'tax_status' => 'none', 'cost' => '390' ) );
	}
	$free_id = $zone->add_shipping_method( 'free_shipping' );
	if ( $free_id ) {
		update_option( 'woocommerce_free_shipping_' . $free_id . '_settings', array( 'title' => 'Besplatna dostava', 'requires' => 'min_amount', 'min_amount' => '9000', 'ignore_discounts' => 'no' ) );
	}
}

/**
 * Navigation menus with the standard items, assigned only to empty locations.
 */
function webni_create_menus() {
	// Items: array( label, page slug ) link to a page (URL follows the page), array( label, '#anchor' ) is a home anchor.
	$menus = array(
		'primary'      => array( 'name' => 'Glavna navigacija', 'items' => array(
			array( 'Parfemi', 'parfemi' ),
			array( 'Zašto 30%', '#kvalitet' ),
			array( 'Poklon', '#poklon' ),
			array( 'Priča', '#prica' ),
		) ),
		'footer-site'  => array( 'name' => 'Futer — Sajt', 'items' => array(
			array( 'Parfemi', 'parfemi' ),
			array( 'Koji si karakter?', '#quiz' ),
			array( 'Poklon', '#poklon' ),
			array( 'Zašto 30%', '#kvalitet' ),
			array( 'Priča', '#prica' ),
		) ),
		'footer-help'  => array( 'name' => 'Futer — Pomoć', 'items' => array(
			array( 'Dostava i plaćanje', 'pomoc' ),
			array( 'Reklamacije i povrat', 'reklamacije-i-povrat' ),
			array( 'Kontakt', 'kontakt' ),
			array( 'Moj nalog', 'moj-nalog' ),
		) ),
		'footer-legal' => array( 'name' => 'Futer — Pravno', 'items' => array(
			array( 'Uslovi kupovine', 'uslovi-kupovine' ),
			array( 'Privatnost i kolačići', 'politika-privatnosti' ),
			array( 'Podaci o trgovcu', 'podaci-o-trgovcu' ),
		) ),
	);

	$locations = (array) get_theme_mod( 'nav_menu_locations', array() );
	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}
		$existing = wp_get_nav_menu_object( $menu['name'] );
		$menu_id  = $existing ? (int) $existing->term_id : wp_create_nav_menu( $menu['name'] );
		if ( is_wp_error( $menu_id ) || ! $menu_id ) {
			continue;
		}
		if ( ! $existing ) {
			foreach ( $menu['items'] as $i => $item ) {
				$args = array(
					'menu-item-title'    => $item[0],
					'menu-item-status'   => 'publish',
					'menu-item-position' => $i + 1,
				);
				$page = '#' === $item[1][0] ? null : get_page_by_path( $item[1], OBJECT, 'page' );
				if ( $page ) {
					$args['menu-item-type']      = 'post_type';
					$args['menu-item-object']    = 'page';
					$args['menu-item-object-id'] = (int) $page->ID;
				} else {
					$args['menu-item-type'] = 'custom';
					$args['menu-item-url']  = '#' === $item[1][0] ? home_url( '/' . $item[1] ) : home_url( '/' . $item[1] . '/' );
				}
				wp_update_nav_menu_item( $menu_id, 0, $args );
			}
		}
		$locations[ $location ] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * After a theme update (files replaced over FTP or via "Replace active with uploaded"):
 * re-check the URL structure once, so .htaccess gets written or pretty URLs are safely disabled.
 */
function webni_maybe_upgrade() {
	if ( get_option( 'webni_version' ) === WEBNI_THEME_VERSION ) {
		return;
	}
	update_option( 'webni_version', WEBNI_THEME_VERSION );
	webni_apply_serbian_urls();
	update_option( 'webni_flush_rewrite', 1 ); // Consumed by webni_maybe_flush_rewrite() on init 99.
	if ( ! get_option( 'webni_widgets_cleaned' ) ) {
		webni_clear_stock_widgets();
		update_option( 'webni_widgets_cleaned', 1 );
	}
}
add_action( 'init', 'webni_maybe_upgrade', 98 );

/**
 * WordPress drops its sample widgets (search, recent posts/comments, archives, categories) into the
 * first registered widget area on install and on theme switch. They are English and empty on a shop,
 * so they are moved to the inactive list; anything else the shop placed in "Futer — dodatno" stays.
 */
function webni_clear_stock_widgets() {
	$sidebars = wp_get_sidebars_widgets();
	if ( empty( $sidebars['footer-extra'] ) || ! is_array( $sidebars['footer-extra'] ) ) {
		return;
	}
	$stock = array();
	$keep  = array();
	foreach ( $sidebars['footer-extra'] as $widget_id ) {
		if ( webni_is_stock_widget( $widget_id ) ) {
			$stock[] = $widget_id;
		} else {
			$keep[] = $widget_id;
		}
	}
	if ( ! $stock ) {
		return;
	}
	$sidebars['footer-extra']        = $keep;
	$inactive                        = isset( $sidebars['wp_inactive_widgets'] ) && is_array( $sidebars['wp_inactive_widgets'] ) ? $sidebars['wp_inactive_widgets'] : array();
	$sidebars['wp_inactive_widgets'] = array_values( array_unique( array_merge( $inactive, $stock ) ) );
	wp_set_sidebars_widgets( $sidebars );
}

/**
 * Whether a widget is one of WordPress' sample widgets (classic or block form).
 *
 * @param string $widget_id Widget ID (e.g. "block-5", "archives-2").
 * @return bool
 */
function webni_is_stock_widget( $widget_id ) {
	if ( preg_match( '/^(search|recent-posts|recent-comments|archives|categories|meta|calendar)-\d+$/', $widget_id ) ) {
		return true;
	}
	if ( preg_match( '/^block-(\d+)$/', $widget_id, $m ) ) {
		$blocks  = get_option( 'widget_block', array() );
		$content = isset( $blocks[ (int) $m[1] ]['content'] ) ? (string) $blocks[ (int) $m[1] ]['content'] : '';
		return (bool) preg_match( '/<!-- wp:(search|latest-posts|latest-comments|archives|categories|calendar)[ \/]/', $content );
	}
	return false;
}

/**
 * Runs when the theme is activated.
 */
function webni_activate() {
	webni_create_pages();
	webni_store_defaults();
	webni_apply_serbian_urls();
	webni_create_menus();
	webni_clear_stock_widgets();
	update_option( 'webni_widgets_cleaned', 1 );
	update_option( 'webni_flush_rewrite', 1 );
	webni_maybe_flush_rewrite(); // Writes .htaccess now, while we are in the admin context.
	update_option( 'webni_version', WEBNI_THEME_VERSION );
	set_transient( 'webni_activation_notice', 1, 60 );
}
add_action( 'after_switch_theme', 'webni_activate' );

/**
 * Once after activation: point the admin to the tools page.
 */
function webni_activation_notice() {
	if ( ! get_transient( 'webni_activation_notice' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	delete_transient( 'webni_activation_notice' );
	printf(
		'<div class="notice notice-success is-dismissible"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'Webni Custom Version 1 Theme is active: pages, Serbian URLs and store defaults are set.', 'webni-custom-version-1' ),
		esc_url( admin_url( 'admin.php?page=webni-tools' ) ),
		esc_html__( 'Open the BP|DL tools (demo products, settings check).', 'webni-custom-version-1' )
	);
}
add_action( 'admin_notices', 'webni_activation_notice' );
