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
	$form = webni_gravity_form( webni_option( 'contact_gf_id', 0 ), 'kontakt' );
	if ( '' !== trim( wp_strip_all_tags( $form, true ) ) && false === strpos( $form, 'webni-admin-note' ) ) {
		return $form;
	}
	// No working form yet: visitors get direct e-mail/phone links instead of an empty spot.
	$links = array();
	$email = (string) webni_option( 'contact_email', '' );
	$phone = (string) webni_option( 'contact_phone', '' );
	if ( $email ) {
		$links[] = '<a class="btn btn--solid" href="mailto:' . esc_attr( antispambot( $email ) ) . '">' . esc_html__( 'Pošalji e-mail', 'webni-custom-version-1' ) . '</a>';
	}
	if ( $phone ) {
		$links[] = '<a class="btn btn--ghost" href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
	}
	$fallback = $links ? '<div class="contact-fallback">' . implode( '', $links ) . '</div>' : '';
	return $form . $fallback;
}
add_shortcode( 'webni_contact_form', 'webni_contact_form_shortcode' );

/**
 * Create the theme's forms in Gravity Forms (Kontakt, Newsletter, Quiz e-mail) and store their IDs
 * in the Customizer — only for forms that are not set yet or no longer exist. Triggered from
 * WooCommerce → BP|DL alati; the forms can be edited in Gravity Forms afterwards.
 *
 * @return array{created: string[], kept: string[], error: string}
 */
function webni_gf_create_forms() {
	$result = array( 'created' => array(), 'kept' => array(), 'error' => '' );
	if ( ! webni_gf_active() || ! method_exists( 'GFAPI', 'add_form' ) ) {
		$result['error'] = 'Gravity Forms is not active.';
		return $result;
	}
	$to = sanitize_email( (string) webni_option( 'contact_email', '' ) );
	$to = $to ? $to : '{admin_email}';

	$confirmation = function ( $message ) {
		$id = uniqid( 'webni' );
		return array( $id => array( 'id' => $id, 'name' => 'Potvrda', 'isDefault' => true, 'type' => 'message', 'message' => $message ) );
	};
	$forms = array(
		'contact_gf_id'    => array(
			'title'         => 'Kontakt (BP|DL)',
			'fields'        => array(
				array( 'id' => 1, 'type' => 'text', 'label' => 'Ime i prezime', 'isRequired' => true, 'autocompleteAttribute' => 'name' ),
				array( 'id' => 2, 'type' => 'email', 'label' => 'E-mail', 'isRequired' => true ),
				array( 'id' => 3, 'type' => 'phone', 'label' => 'Telefon', 'isRequired' => false, 'phoneFormat' => 'international' ),
				array( 'id' => 4, 'type' => 'textarea', 'label' => 'Poruka', 'isRequired' => true ),
			),
			'button'        => 'Pošalji poruku',
			'confirmation'  => 'Hvala! Poruka je stigla — javljamo se radnim danima, obično istog dana.',
			'notifications' => array(
				'subject' => 'Nova poruka sa sajta: {Ime i prezime:1}',
				'replyTo' => '{E-mail:2}',
			),
		),
		'newsletter_gf_id' => array(
			'title'        => 'Newsletter (BP|DL)',
			'fields'       => array(
				array( 'id' => 1, 'type' => 'email', 'label' => 'E-mail', 'isRequired' => true, 'placeholder' => 'tvoj@email.rs' ),
			),
			'button'       => 'Prijavi me',
			'confirmation' => 'Hvala! Prvi mejl stiže pre lansiranja.',
		),
		'quiz_gf_id'       => array(
			'title'        => 'Quiz e-mail (BP|DL)',
			'fields'       => array(
				array( 'id' => 1, 'type' => 'email', 'label' => 'E-mail', 'isRequired' => true, 'placeholder' => 'tvoj@email.rs' ),
				array( 'id' => 2, 'type' => 'hidden', 'label' => 'Rezultat quiza', 'cssClass' => 'webni-quiz-result' ),
			),
			'button'       => 'Pošalji mi rezultat',
			'confirmation' => 'Poslato! Proveri inbox.',
		),
	);

	foreach ( $forms as $option => $def ) {
		$current = absint( webni_option( $option, 0 ) );
		if ( $current ) {
			$existing = GFAPI::get_form( $current );
			if ( $existing && empty( $existing['is_trash'] ) ) {
				$result['kept'][] = $def['title'] . ' (ID ' . $current . ')';
				continue;
			}
		}
		$form = array(
			'title'          => $def['title'],
			'labelPlacement' => 'top_label',
			'enableHoneypot' => true,
			'button'         => array( 'type' => 'text', 'text' => $def['button'] ),
			'fields'         => $def['fields'],
			'confirmations'  => $confirmation( $def['confirmation'] ),
			'notifications'  => array(),
		);
		if ( ! empty( $def['notifications'] ) ) {
			$nid                           = uniqid( 'webni' );
			$form['notifications'][ $nid ] = array(
				'id'       => $nid,
				'name'     => 'Obaveštenje prodavnici',
				'isActive' => true,
				'event'    => 'form_submission',
				'toType'   => 'email',
				'to'       => $to,
				'subject'  => $def['notifications']['subject'],
				'message'  => '{all_fields}',
				'from'     => '{admin_email}',
				'replyTo'  => $def['notifications']['replyTo'],
			);
		}
		$id = GFAPI::add_form( $form );
		if ( is_wp_error( $id ) ) {
			$result['error'] = $id->get_error_message();
			continue;
		}
		set_theme_mod( 'webni_' . $option, absint( $id ) );
		$result['created'][] = $def['title'] . ' (ID ' . absint( $id ) . ')';
	}
	return $result;
}


/**
 * Orbital colours for every form that uses the default style: Gravity Forms derives its focus
 * rings, choice controls and button states from these values (assets/css/forms.css does the rest).
 *
 * @param string|array $styles Default styles (JSON string in Gravity Forms 2.7+).
 * @return string|array
 */
function webni_gf_default_styles( $styles ) {
	$ours = array(
		'theme'                        => 'orbital',
		'inputSize'                    => 'lg',
		'inputBorderRadius'            => '2',
		'inputBorderColor'             => '#5b5650',
		'inputBackgroundColor'         => '#14120f',
		'inputColor'                   => '#efe9df',
		'inputPrimaryColor'            => '#d4af37',
		'labelFontSize'                => '12',
		'labelColor'                   => '#cfc7bb',
		'descriptionFontSize'          => '13',
		'descriptionColor'             => '#9a9187',
		'buttonPrimaryBackgroundColor' => '#efe9df',
		'buttonPrimaryColor'           => '#111111',
	);
	if ( is_array( $styles ) ) {
		return array_merge( $styles, $ours );
	}
	$current = json_decode( (string) $styles, true );
	return wp_json_encode( array_merge( is_array( $current ) ? $current : array(), $ours ) );
}
add_filter( 'gform_default_styles', 'webni_gf_default_styles' );

/**
 * Serbian (Latin) texts that Gravity Forms shows to visitors (required mark, validation, buttons).
 * Front end only, like the WooCommerce table.
 *
 * @return array
 */
function webni_gf_strings() {
	return array(
		'(Required)'                                   => '(obavezno)',
		'Required'                                     => 'Obavezno',
		'"%s" indicates required fields'               => '„%s“ označava obavezna polja',
		'This field is required.'                      => 'Ovo polje je obavezno.',
		'Please enter a valid email address.'          => 'Unesi ispravnu e-mail adresu.',
		'Please enter a valid phone number.'           => 'Unesi ispravan broj telefona.',
		'Please enter a valid phone number in the correct format.' => 'Unesi broj telefona u ispravnom formatu.',
		'Please enter a valid number.'                 => 'Unesi ispravan broj.',
		'The text entered exceeds the maximum number of characters.' => 'Tekst je duži od dozvoljenog.',
		'The text entered contains invalid characters.' => 'Tekst sadrži nedozvoljene znakove.',
		'There was a problem with your submission.'    => 'Poruka nije poslata.',
		'Please review the fields below.'              => 'Proveri označena polja.',
		'Please complete the following fields: %s.'    => 'Popuni sledeća polja: %s.',
		'At least one field must be filled out.'       => 'Popuni bar jedno polje.',
		'Thanks for contacting us! We will get in touch with you shortly.' => 'Hvala! Javićemo ti se uskoro.',
		'Submit'                                       => 'Pošalji',
		'Next'                                         => 'Dalje',
		'Previous'                                     => 'Nazad',
		'Email'                                        => 'E-mail',
		'Enter Email'                                  => 'Unesi e-mail',
		'Confirm Email'                                => 'Potvrdi e-mail',
		'Loading...'                                   => 'Učitavanje…',
		'max characters'                               => 'najviše znakova',
	);
}

/**
 * gettext filter for the "gravityforms" domain on the storefront.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function webni_gf_gettext( $translation, $text, $domain ) {
	if ( 'gravityforms' !== $domain || ( is_admin() && ! wp_doing_ajax() ) ) {
		return $translation;
	}
	static $table = null;
	if ( null === $table ) {
		$table = webni_gf_strings();
	}
	return isset( $table[ $text ] ) ? $table[ $text ] : $translation;
}
add_filter( 'gettext', 'webni_gf_gettext', 10, 3 );
