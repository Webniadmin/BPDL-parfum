<?php
/**
 * Styles and scripts. Loaded only where they are used.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current request is a WooCommerce storefront page.
 *
 * @return bool
 */
function webni_is_shop_page() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}
	return is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url();
}

/**
 * Front-end assets.
 */
function webni_enqueue_assets() {
	$v = WEBNI_THEME_VERSION;

	wp_enqueue_style( 'webni-fonts', WEBNI_THEME_URI . '/assets/css/fonts.css', array(), $v );
	wp_enqueue_style( 'webni-theme', WEBNI_THEME_URI . '/assets/css/theme.css', array( 'webni-fonts' ), $v );

	if ( webni_is_shop_page() ) {
		wp_enqueue_style( 'webni-woocommerce', WEBNI_THEME_URI . '/assets/css/woocommerce.css', array( 'webni-theme' ), $v );
	}

	// Global behaviour: navigation, cursor, reveals, toast, quiz modal, filters, zodiac picker.
	wp_enqueue_script( 'webni-theme', WEBNI_THEME_URI . '/assets/js/theme.js', array(), $v, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script( 'webni-theme', 'BPDL', webni_js_data() );

	// WebGL mist is only needed where an animated hero exists (product pages; home only in the "animated" mode).
	$home_animated = is_front_page() && 'animated' === webni_hero_mode();
	if ( $home_animated || ( function_exists( 'is_product' ) && is_product() ) ) {
		wp_enqueue_script( 'webni-mist', WEBNI_THEME_URI . '/assets/js/mist.js', array(), $v, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_front_page() ) {
		wp_enqueue_script( 'webni-home', WEBNI_THEME_URI . '/assets/js/home.js', $home_animated ? array( 'webni-theme', 'webni-mist' ) : array( 'webni-theme' ), $v, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script( 'webni-product', WEBNI_THEME_URI . '/assets/js/product.js', array( 'webni-theme', 'webni-mist' ), $v, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'webni_enqueue_assets' );

/**
 * WooCommerce ships its own stylesheets; the theme styles the shop itself.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Preload the display font (above-the-fold headline).
 */
function webni_preload_fonts() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( WEBNI_THEME_URI . '/assets/fonts/bodoni-moda-latin-standard-normal.woff2' )
	);
}
add_action( 'wp_head', 'webni_preload_fonts', 2 );

/**
 * Data shared with the JavaScript (perfumes, zodiac, quiz, URLs, strings).
 *
 * @return array
 */
function webni_js_data() {
	$perfumes = array();
	foreach ( webni_get_perfumes() as $p ) {
		$perfumes[] = webni_perfume_js( $p );
	}

	return array(
		'perfumes' => $perfumes,
		'mist'     => webni_mist_intensity(),
		'zodiac'   => webni_zodiac_table(),
		'quiz'     => webni_quiz_config(),
		'gift'     => webni_gift_config(),
		'urls'     => array(
			'home'      => esc_url_raw( home_url( '/' ) ),
			'shop'      => esc_url_raw( webni_shop_url() ),
			'cart'      => class_exists( 'WooCommerce' ) ? esc_url_raw( wc_get_cart_url() ) : '',
			'discovery' => esc_url_raw( webni_discovery_url() ),
			'gift'      => esc_url_raw( home_url( '/#poklon' ) ),
		),
		'i18n'     => array(
			'added'      => __( '%s je u korpi.', 'webni-custom-version-1' ),
			'viewCart'   => __( 'Vidi korpu', 'webni-custom-version-1' ),
			'copied'     => __( 'Kopirano. Nalepi u story ili poruku.', 'webni-custom-version-1' ),
			'noProducts' => __( 'Nema parfema za ovaj izbor.', 'webni-custom-version-1' ),
		),
	);
}
