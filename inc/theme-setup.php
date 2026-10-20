<?php
/**
 * Theme setup: supports, menus, image sizes, rewrite bases.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation menus.
 */
function webni_setup() {
	load_theme_textdomain( WEBNI_TEXT_DOMAIN, WEBNI_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 320, 'flex-height' => true, 'flex-width' => true ) );

	// WooCommerce: theme styles everything itself (no WC zoom/lightbox/slider scripts).
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 1000,
		'product_grid'          => array( 'default_columns' => 4, 'min_columns' => 2, 'max_columns' => 4 ),
	) );

	register_nav_menus( array(
		'primary'      => __( 'Glavna navigacija', 'webni-custom-version-1' ),
		'footer-site'  => __( 'Futer — Sajt', 'webni-custom-version-1' ),
		'footer-help'  => __( 'Futer — Pomoć', 'webni-custom-version-1' ),
		'footer-legal' => __( 'Futer — Pravno', 'webni-custom-version-1' ),
	) );

	// Image sizes used by the theme (product cards keep the cut-out proportions).
	add_image_size( 'webni-card', 800, 920, false );
	add_image_size( 'webni-hero', 1200, 1400, false );
	add_image_size( 'webni-thumb', 240, 280, false );
}
add_action( 'after_setup_theme', 'webni_setup' );

/**
 * One optional widget area in the footer (the layout itself has no sidebars).
 */
function webni_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Futer — dodatno', 'webni-custom-version-1' ),
		'id'            => 'footer-extra',
		'description'   => __( 'Prikazuje se u futeru iznad linije sa autorskim pravima (npr. logotipi kartica, sertifikati).', 'webni-custom-version-1' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'webni_widgets_init' );

/**
 * Content width for embeds.
 */
function webni_content_width() {
	$GLOBALS['content_width'] = 1240;
}
add_action( 'after_setup_theme', 'webni_content_width', 0 );

/**
 * Serbian rewrite bases for WordPress core archives.
 * Pagination, search and author URLs are produced by WordPress; the bases below
 * make them Serbian without touching the database.
 */
function webni_rewrite_bases() {
	global $wp_rewrite;
	$wp_rewrite->pagination_base          = 'strana';
	$wp_rewrite->comments_pagination_base = 'komentari-strana';
	$wp_rewrite->search_base              = 'pretraga';
	$wp_rewrite->author_base              = 'autor';
	$wp_rewrite->feed_base                = 'feed';
}
add_action( 'init', 'webni_rewrite_bases', 1 );

/**
 * Redirect ?s= searches to the pretty /pretraga/<term>/ form.
 */
function webni_pretty_search_redirect() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET parameters of a search request.
	if ( is_admin() || ! is_search() || empty( $_GET['s'] ) || ! get_option( 'permalink_structure' ) || isset( $_GET['orderby'] ) ) {
		return;
	}
	$term = sanitize_text_field( wp_unslash( $_GET['s'] ) );
	if ( '' === $term ) {
		return;
	}
	$url = home_url( user_trailingslashit( 'pretraga/' . rawurlencode( $term ) ) );
	if ( isset( $_GET['post_type'] ) ) {
		$url = add_query_arg( 'post_type', sanitize_key( wp_unslash( $_GET['post_type'] ) ), $url );
	}
	// phpcs:enable
	wp_safe_redirect( $url, 301 );
	exit;
}
add_action( 'template_redirect', 'webni_pretty_search_redirect' );

/**
 * Body classes used by the stylesheet.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function webni_body_classes( $classes ) {
	$classes[] = 'webni';
	if ( is_front_page() ) {
		$classes[] = 'is-home';
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'page-perfume';
	}
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'is-shop';
	}
	// Pages without a full-bleed hero get a solid header from the start.
	if ( ! is_front_page() && ! ( function_exists( 'is_product' ) && is_product() ) ) {
		$classes[] = 'has-solid-nav';
	}
	return $classes;
}
add_filter( 'body_class', 'webni_body_classes' );

/**
 * Remove emoji scripts and other front-end bloat the design does not need.
 */
function webni_cleanup_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'webni_cleanup_head' );

/**
 * Excerpt length and "read more" text.
 */
add_filter( 'excerpt_length', function () { return 28; } );
add_filter( 'excerpt_more', function () { return '…'; } );
