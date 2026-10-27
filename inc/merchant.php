<?php
/**
 * Merchant data ("Podaci o trgovcu") in one place.
 *
 * Serbian distance-selling rules and the card acquirer (RaiAccept) require the seller's legal data,
 * delivery terms and the card/3-D Secure logos on the site. The values live in the Customizer
 * (BP|DL Parfum → Podaci o trgovcu) and reach the pages through [webni_trgovac polje="…"], so the
 * Kontakt page, the legal pages and the footer always show the same data. Delivery price and the
 * free-shipping threshold are read from the WooCommerce shipping zone, never typed twice.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode fields: field => [Customizer key or callback, admin label, original placeholder label].
 *
 * @return array
 */
function webni_merchant_fields() {
	return array(
		'naziv'            => array( 'company_name', 'pun naziv firme' ),
		'adresa'           => array( 'company_address', 'adresa sedišta' ),
		'mb'               => array( 'company_mb', 'MB' ),
		'pib'              => array( 'company_pib', 'PIB' ),
		'email'            => array( 'contact_email', 'email' ),
		'telefon'          => array( 'contact_phone', 'telefon' ),
		'radno_vreme'      => array( 'support_hours', 'dani i sati' ),
		'adresa_povrata'   => array( 'return_address', 'adresa za povrat' ),
		'email_privatnost' => array( 'privacy_email', 'email za privatnost' ),
		'kurir'            => array( 'courier', 'kurir' ),
		'sat'              => array( 'dispatch_cutoff', 'sat' ),
		'grad'             => array( 'court_city', 'grad' ),
		'banka'            => array( 'bank_name', 'naziv banke' ),
		'datum'            => array( 'legal_updated', 'datum' ),
		'domen'            => array( 'webni_merchant_domain', 'domen' ),
		'dostava'          => array( 'webni_merchant_shipping_cost', 'iznos' ),
		'prag'             => array( 'webni_merchant_free_threshold', 'prag' ),
	);
}

/**
 * Placeholders used in the legal drafts (inc/demo-content.php) and the field that replaces each.
 *
 * @return array
 */
function webni_merchant_placeholders() {
	return array(
		'[pun naziv firme]'     => 'naziv',
		'[Pun naziv firme]'     => 'naziv',
		'[adresa sedišta]'      => 'adresa',
		'[adresa]'              => 'adresa',
		'[MB]'                  => 'mb',
		'[PIB]'                 => 'pib',
		'[email za privatnost]' => 'email_privatnost',
		'[email]'               => 'email',
		'[telefon]'             => 'telefon',
		'[dani i sati]'         => 'radno_vreme',
		'[adresa za povrat]'    => 'adresa_povrata',
		'[kurir]'               => 'kurir',
		'[sat]'                 => 'sat',
		'[grad]'                => 'grad',
		'[naziv banke]'         => 'banka',
		'[banka]'               => 'banka',
		'[datum]'               => 'datum',
		'[domen]'               => 'domen',
		'[iznos]'               => 'dostava',
		'[prag]'                => 'prag',
	);
}

/**
 * Value of one merchant field ('' when not filled in).
 *
 * @param string $field Field name (see webni_merchant_fields()).
 * @return string
 */
function webni_merchant( $field ) {
	$fields = webni_merchant_fields();
	if ( ! isset( $fields[ $field ] ) ) {
		return '';
	}
	$source = $fields[ $field ][0];
	if ( 0 === strpos( $source, 'webni_merchant_' ) && function_exists( $source ) ) {
		return (string) call_user_func( $source );
	}
	$value = trim( (string) webni_option( $source, '' ) );
	if ( '' === $value && 'company_address' === $source ) {
		$value = trim( (string) webni_option( 'contact_address', '' ) );
	}
	if ( '' === $value && in_array( $source, array( 'return_address' ), true ) ) {
		$value = webni_merchant( 'adresa' );
	}
	if ( '' === $value && 'privacy_email' === $source ) {
		$value = trim( (string) webni_option( 'contact_email', '' ) );
	}
	return $value;
}

/**
 * Site domain without scheme (for the legal texts).
 *
 * @return string
 */
function webni_merchant_domain() {
	return (string) wp_parse_url( home_url(), PHP_URL_HOST );
}

/**
 * Paid and free-shipping figures of the first shipping zone that covers Serbia.
 *
 * @return array{cost: float|null, free: float|null}
 */
function webni_shipping_figures() {
	static $figures = null;
	if ( null !== $figures ) {
		return $figures;
	}
	$figures = array( 'cost' => null, 'free' => null );
	if ( ! class_exists( 'WC_Shipping_Zones' ) ) {
		return $figures;
	}
	foreach ( WC_Shipping_Zones::get_zones() as $zone ) {
		$covers = false;
		foreach ( (array) $zone['zone_locations'] as $loc ) {
			if ( 'country' === $loc->type && 'RS' === $loc->code ) {
				$covers = true;
			}
		}
		if ( ! $covers ) {
			continue;
		}
		foreach ( $zone['shipping_methods'] as $method ) {
			if ( ! $method->is_enabled() ) {
				continue;
			}
			if ( 'flat_rate' === $method->id && null === $figures['cost'] && is_numeric( $method->get_option( 'cost' ) ) ) {
				$figures['cost'] = (float) $method->get_option( 'cost' );
			}
			if ( 'free_shipping' === $method->id && null === $figures['free'] && in_array( $method->get_option( 'requires' ), array( 'min_amount', 'either', 'both' ), true ) ) {
				$figures['free'] = (float) $method->get_option( 'min_amount' );
			}
		}
		break;
	}
	return $figures;
}

/**
 * Amount in the shop format without currency ("6.000").
 *
 * @param float|null $amount Amount.
 * @return string
 */
function webni_merchant_amount( $amount ) {
	if ( null === $amount ) {
		return '';
	}
	return number_format( (float) $amount, 0, ',', '.' );
}

/**
 * Delivery price from the shipping zone.
 *
 * @return string
 */
function webni_merchant_shipping_cost() {
	return webni_merchant_amount( webni_shipping_figures()['cost'] );
}

/**
 * Free-shipping threshold from the shipping zone.
 *
 * @return string
 */
function webni_merchant_free_threshold() {
	return webni_merchant_amount( webni_shipping_figures()['free'] );
}

/**
 * [webni_trgovac polje="pib"] — one merchant value. Empty values keep the [placeholder] for
 * visitors (as the drafts did) and show a pointer to the Customizer to administrators.
 *
 * @param array $atts Attributes.
 * @return string
 */
function webni_merchant_shortcode( $atts ) {
	$atts   = shortcode_atts( array( 'polje' => '' ), $atts, 'webni_trgovac' );
	$field  = sanitize_key( $atts['polje'] );
	$fields = webni_merchant_fields();
	if ( ! isset( $fields[ $field ] ) ) {
		return '';
	}
	$value = webni_merchant( $field );
	if ( '' !== $value ) {
		if ( 'email' === $field || 'email_privatnost' === $field ) {
			$mail = antispambot( $value );
			return '<a href="mailto:' . esc_attr( $mail ) . '">' . esc_html( $mail ) . '</a>';
		}
		if ( 'telefon' === $field ) {
			return '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $value ) ) . '">' . esc_html( $value ) . '</a>';
		}
		return esc_html( $value );
	}
	$label = '[' . $fields[ $field ][1] . ']';
	if ( current_user_can( 'edit_theme_options' ) ) {
		$where = in_array( $field, array( 'dostava', 'prag' ), true )
			? 'WooCommerce → Podešavanja → Dostava'
			: 'Izgled → Prilagodi → BP|DL Parfum → Podaci o trgovcu';
		return '<mark class="webni-missing" title="' . esc_attr( 'Popuni u: ' . $where ) . '">' . esc_html( $label ) . '</mark>';
	}
	return esc_html( $label );
}
add_shortcode( 'webni_trgovac', 'webni_merchant_shortcode' );

/**
 * Replace the [placeholders] of the legal drafts with merchant shortcodes. Only exact
 * placeholder strings are touched, so text the shop already wrote stays as it is.
 *
 * @param string $content Page content.
 * @return string
 */
function webni_link_merchant_placeholders( $content ) {
	$map = array(
		// Delivery sentences of the drafts: amounts follow the WooCommerce shipping zone.
		'Dostava je 390 RSD, a za porudžbine iznad 9.000 RSD je besplatna.' => 'Dostava je [webni_trgovac polje="dostava"] RSD, a za porudžbine od [webni_trgovac polje="prag"] RSD je besplatna.',
		'za porudžbine iznad [prag] RSD dostava je besplatna' => 'za porudžbine od [webni_trgovac polje="prag"] RSD dostava je besplatna',
		'za porudžbine iznad [webni_trgovac polje="prag"] RSD dostava je besplatna' => 'za porudžbine od [webni_trgovac polje="prag"] RSD dostava je besplatna',
	);
	foreach ( webni_merchant_placeholders() as $placeholder => $field ) {
		$map[ $placeholder ] = '[webni_trgovac polje="' . $field . '"]';
	}
	return strtr( (string) $content, $map );
}

/**
 * Pages whose drafts contain merchant placeholders.
 *
 * @return string[]
 */
function webni_merchant_page_slugs() {
	return array( 'kontakt', 'podaci-o-trgovcu', 'uslovi-kupovine', 'politika-privatnosti', 'reklamacije-i-povrat', 'pomoc' );
}

/**
 * Link the existing Kontakt and legal pages to the merchant data (once per site, and on demand
 * from BP|DL alati). Returns the number of pages changed.
 *
 * @return int
 */
function webni_link_merchant_pages() {
	$changed = 0;
	foreach ( webni_merchant_page_slugs() as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page ) {
			continue;
		}
		$new = webni_link_merchant_placeholders( $page->post_content );
		if ( $new !== $page->post_content ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_content' => $new ) );
			$changed++;
		}
	}
	return $changed;
}

/**
 * New pages get the shortcodes straight away.
 *
 * @param array $pages Required pages.
 * @return array
 */
function webni_merchant_required_pages( $pages ) {
	foreach ( $pages as $slug => $page ) {
		if ( ! empty( $page['content'] ) && in_array( $slug, webni_merchant_page_slugs(), true ) ) {
			$pages[ $slug ]['content'] = webni_link_merchant_placeholders( $page['content'] );
		}
	}
	return $pages;
}
add_filter( 'webni_required_pages', 'webni_merchant_required_pages' );

/**
 * One line of legal data for the footer ("Naziv · Adresa · PIB … · MB …"), empty until filled in.
 *
 * @return string Plain text.
 */
function webni_merchant_line() {
	$parts = array();
	foreach ( array( 'naziv', 'adresa' ) as $field ) {
		$value = webni_merchant( $field );
		if ( '' !== $value ) {
			$parts[] = $value;
		}
	}
	if ( webni_merchant( 'pib' ) ) {
		/* translators: %s tax ID */
		$parts[] = sprintf( __( 'PIB %s', 'webni-custom-version-1' ), webni_merchant( 'pib' ) );
	}
	if ( webni_merchant( 'mb' ) ) {
		/* translators: %s company registration number */
		$parts[] = sprintf( __( 'MB %s', 'webni-custom-version-1' ), webni_merchant( 'mb' ) );
	}
	return implode( ' · ', $parts );
}

/**
 * Card and 3-D Secure logos supplied by the bank (one image), optionally linked.
 *
 * @param string $context footer|checkout.
 * @return string HTML.
 */
function webni_payment_logos( $context = 'footer' ) {
	$id = absint( webni_option( 'payment_logos', 0 ) );
	if ( ! $id ) {
		return '';
	}
	$img = wp_get_attachment_image( $id, 'full', false, array(
		'class'    => 'payment-logos__img',
		'loading'  => 'lazy',
		'decoding' => 'async',
		'alt'      => __( 'Kartice i sigurno plaćanje', 'webni-custom-version-1' ),
	) );
	if ( ! $img ) {
		return '';
	}
	return '<div class="payment-logos payment-logos--' . esc_attr( $context ) . '">' . $img . '</div>';
}

/**
 * Logos under the payment methods at checkout.
 */
function webni_checkout_payment_logos() {
	echo webni_payment_logos( 'checkout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from wp_get_attachment_image().
}
add_action( 'woocommerce_review_order_after_payment', 'webni_checkout_payment_logos' );

/**
 * Free-delivery line under the buy button: the Customizer text with {prag} replaced by the
 * threshold from the shipping zone; empty when the shop has no free delivery.
 *
 * @return string Plain text.
 */
function webni_free_shipping_text() {
	$text  = (string) webni_option( 'free_shipping_text' );
	$prag  = webni_merchant_free_threshold();
	if ( false !== strpos( $text, '{prag}' ) ) {
		if ( '' === $prag ) {
			return '';
		}
		$text = str_replace( '{prag}', $prag, $text );
	}
	return $text;
}
