<?php
/**
 * Gravity Forms integration.
 *
 * Forms are rendered by Gravity Forms itself; the theme only decides where a form
 * appears (newsletter, quiz email step, contact page) and which form ID to use.
 * Never fatals when Gravity Forms is inactive.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether Gravity Forms is active.
 *
 * @return bool
 */
function webni_gf_active() {
	return class_exists( 'GFAPI' ) && function_exists( 'gravity_form' );
}

/**
 * Render a Gravity Form by ID. Returns markup (empty string when unavailable).
 *
 * @param int    $form_id Form ID.
 * @param string $context Short context name for the admin notice.
 * @param bool   $ajax    Use AJAX submission.
 * @return string
 */
function webni_gravity_form( $form_id, $context = '', $ajax = true ) {
	$form_id = absint( $form_id );

	if ( ! $form_id ) {
		return webni_gf_admin_notice( sprintf( 'Nije podešen Gravity Form ID (%s). Podesi ga u Izgled → Prilagodi → BP|DL Parfum → Newsletter i forme.', $context ) );
	}
	if ( ! webni_gf_active() ) {
		return webni_gf_admin_notice( 'Gravity Forms nije aktivan — forma se ne prikazuje posetiocima.' );
	}
	$form = GFAPI::get_form( $form_id );
	if ( ! $form || ! empty( $form['is_trash'] ) ) {
		return webni_gf_admin_notice( sprintf( 'Gravity Form sa ID %d ne postoji.', $form_id ) );
	}

	ob_start();
	// gravity_form( $id, $display_title, $display_description, $display_inactive, $field_values, $ajax, $tabindex, $echo ).
	gravity_form( $form_id, false, false, false, null, $ajax, 0, true );
	return (string) ob_get_clean();
}

/**
 * Notice shown only to users who can manage options (visitors see nothing).
 *
 * @param string $text Message.
 * @return string
 */
function webni_gf_admin_notice( $text ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return '';
	}
	return '<p class="webni-admin-note">' . esc_html( $text ) . '</p>';
}

/**
 * Make sure Gravity Forms scripts/styles are available for AJAX forms rendered in templates.
 */
function webni_gf_enqueue() {
	if ( ! webni_gf_active() || ! function_exists( 'gravity_form_enqueue_scripts' ) ) {
		return;
	}
	$ids = array_filter( array( absint( webni_option( 'newsletter_gf_id', 0 ) ), absint( webni_option( 'quiz_gf_id', 0 ) ) ) );
	if ( is_page() && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'webni_contact_form' ) ) {
		$ids[] = absint( webni_option( 'contact_gf_id', 0 ) );
	}
	foreach ( array_unique( array_filter( $ids ) ) as $id ) {
		gravity_form_enqueue_scripts( $id, true );
	}
}
add_action( 'wp_enqueue_scripts', 'webni_gf_enqueue', 20 );

/**
 * [webni_contact_form] — renders the contact form chosen in the Customizer.
 *
 * @return string
 */
function webni_contact_form_shortcode() {
	return webni_gravity_form( webni_option( 'contact_gf_id', 0 ), 'kontakt' );
}
add_shortcode( 'webni_contact_form', 'webni_contact_form_shortcode' );
