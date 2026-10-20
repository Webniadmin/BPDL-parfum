<?php
/**
 * Gift section: zodiac picker + three cards (gift flow, discovery set, pair set).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$has_wc    = class_exists( 'WooCommerce' );
$discovery = $has_wc ? wc_get_product( (int) webni_option( 'discovery_product', 0 ) ) : null;
$pair      = $has_wc ? wc_get_product( (int) webni_option( 'pair_product', 0 ) ) : null;
$zodiac    = $has_wc ? webni_zodiac_table() : array();
?>
<section class="section" id="poklon">
	<div class="container">
		<div class="section__head reveal">
			<div>
				<p class="eyebrow"><?php echo esc_html( webni_option( 'gift_eyebrow' ) ); ?></p>
				<h2 class="h-l"><?php echo webni_text( webni_option( 'gift_title_1' ) ); ?><br><?php echo webni_text( webni_option( 'gift_title_2' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			</div>
			<p class="lead" style="max-width:34ch"><?php echo esc_html( webni_option( 'gift_text' ) ); ?></p>
		</div>

		<?php if ( $zodiac ) : ?>
			<div class="zodiac reveal">
				<?php foreach ( $zodiac as $z ) : ?>
					<button type="button" class="zodiac__chip" data-sign="<?php echo esc_attr( $z['sign'] ); ?>"><b><?php echo esc_html( $z['sign'] ); ?></b><small><?php echo esc_html( $z['dates'] ); ?></small></button>
				<?php endforeach; ?>
			</div>
			<div class="zodiac__result" id="zodiacResult" hidden data-him="<?php esc_attr_e( 'Za njega', 'webni-custom-version-1' ); ?>" data-her="<?php esc_attr_e( 'Za nju', 'webni-custom-version-1' ); ?>"></div>
			<p class="tiny"><?php echo esc_html( webni_option( 'gift_note' ) ); ?></p>
		<?php endif; ?>

		<div class="gift-cards reveal">
			<article class="gift-card">
				<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'gift_card1_eyebrow' ) ); ?></p>
				<h3><?php echo webni_text( webni_option( 'gift_card1_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				<p><?php echo esc_html( webni_option( 'gift_card1_text' ) ); ?></p>
				<button type="button" class="btn btn--gold" data-gift><?php echo esc_html( webni_option( 'gift_card1_cta' ) ); ?></button>
			</article>

			<article class="gift-card">
				<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'discovery_eyebrow' ) ); ?></p>
				<h3><?php echo webni_text( webni_option( 'discovery_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				<p><?php echo esc_html( webni_option( 'discovery_text' ) ); ?></p>
				<?php if ( $discovery ) : ?>
					<p class="price"><?php echo wp_kses_post( $discovery->get_price_html() ); ?></p>
					<a class="btn btn--ghost" href="<?php echo esc_url( $discovery->get_permalink() ); ?>"><?php esc_html_e( 'Naruči discovery set', 'webni-custom-version-1' ); ?></a>
				<?php else : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( webni_shop_url() ); ?>"><?php esc_html_e( 'Pogledaj parfeme', 'webni-custom-version-1' ); ?></a>
				<?php endif; ?>
			</article>

			<article class="gift-card gift-card--pair">
				<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'pair_eyebrow' ) ); ?></p>
				<h3><?php echo webni_text( webni_option( 'pair_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				<p class="ital" style="font-family:var(--font-display);font-size:20px"><?php echo webni_text( webni_option( 'pair_line' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<p><?php echo esc_html( webni_option( 'pair_text' ) ); ?></p>
				<?php if ( $pair ) : ?>
					<p class="price"><?php echo wp_kses_post( $pair->get_price_html() ); ?></p>
					<a class="btn btn--ghost" href="<?php echo esc_url( $pair->get_permalink() ); ?>"><?php esc_html_e( 'Uzmi par', 'webni-custom-version-1' ); ?></a>
				<?php else : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( webni_shop_url() ); ?>"><?php esc_html_e( 'Pogledaj parfeme', 'webni-custom-version-1' ); ?></a>
				<?php endif; ?>
			</article>
		</div>
	</div>
</section>
