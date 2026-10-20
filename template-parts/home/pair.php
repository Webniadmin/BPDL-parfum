<?php
/**
 * Par set band: the two-perfume set with its picture, price, the two perfumes it contains and "U korpu".
 * Used below the home collection and on the product pages of the perfumes sold in the set.
 *
 * Args:
 * - context  'home' (default) or 'product'
 * - current  product ID of the page (product context) — highlighted among the two perfumes
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

if ( ! webni_option( 'pair_section_on', 1 ) ) {
	return;
}
$pair = webni_pair_product();
if ( ! $pair ) {
	return;
}

$context  = isset( $args['context'] ) ? $args['context'] : 'home';
$current  = isset( $args['current'] ) ? (int) $args['current'] : 0;
$members  = webni_pair_members();
$image_id = (int) $pair->get_image_id();
$eyebrow  = 'product' === $context ? __( 'Dolazi i u paru', 'webni-custom-version-1' ) : webni_option( 'pair_eyebrow' );
$title    = webni_option( 'pair_title' );
if ( ! $title ) {
	$title = $pair->get_name();
}
$can_buy = $pair->is_purchasable() && $pair->is_in_stock() && $pair->is_type( 'simple' );
?>
<section class="section section--pair<?php echo 'product' === $context ? ' section--pair-product' : ''; ?>" id="<?php echo 'product' === $context ? 'par-set-proizvod' : 'par-set'; ?>">
	<div class="container">
		<div class="pair-band reveal" data-product-name="<?php echo esc_attr( $pair->get_name() ); ?>">
			<a class="pair-band__visual" href="<?php echo esc_url( $pair->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $pair->get_name() ); ?>">
				<?php if ( $image_id ) : ?>
					<?php echo wp_get_attachment_image( $image_id, 'webni-hero', false, array( 'class' => 'pair-band__img', 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 900px) 100vw, 50vw' ) ); ?>
				<?php endif; ?>
				<?php if ( $pair->is_on_sale() ) : ?><span class="card__badge"><?php esc_html_e( 'Akcija', 'webni-custom-version-1' ); ?></span><?php endif; ?>
			</a>
			<div class="pair-band__body">
				<p class="eyebrow eyebrow--gold"><?php echo esc_html( $eyebrow ); ?></p>
				<h2 class="h-l"><?php echo webni_text( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php if ( webni_option( 'pair_line' ) ) : ?>
					<p class="pair-band__line"><?php echo webni_text( webni_option( 'pair_line' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>
				<p class="lead"><?php echo esc_html( webni_option( 'pair_text' ) ); ?></p>

				<?php if ( $members ) : ?>
					<ul class="pair-band__members" aria-label="<?php esc_attr_e( 'Parfemi u setu', 'webni-custom-version-1' ); ?>">
						<?php foreach ( $members as $member ) : $md = webni_perfume( $member ); ?>
							<li<?php echo $current && (int) $member->get_id() === $current ? ' class="is-current"' : ''; ?>>
								<a href="<?php echo esc_url( $md['url'] ); ?>" style="<?php echo esc_attr( webni_palette_style( $md ) ); ?>">
									<span class="pair-band__thumb"><?php echo webni_perfume_image( $md, 'bottle', array( 'class' => 'pack', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<span>
										<b><?php echo webni_text( $md['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b>
										<?php if ( $md['character'] ) : ?><small><?php echo esc_html( $md['character'] ); ?></small><?php endif; ?>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<p class="pair-band__price"><?php echo wp_kses_post( $pair->get_price_html() ); ?></p>
				<?php if ( webni_option( 'pair_section_note' ) ) : ?>
					<p class="tiny"><?php echo esc_html( webni_option( 'pair_section_note' ) ); ?></p>
				<?php endif; ?>
				<div class="hero__actions">
					<?php if ( $can_buy ) : ?>
						<?php webni_add_to_cart_button( $pair, 'btn btn--gold' ); ?>
					<?php endif; ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( $pair->get_permalink() ); ?>"><?php echo esc_html( webni_option( 'pair_section_cta' ) ? webni_option( 'pair_section_cta' ) : __( 'Uzmi par', 'webni-custom-version-1' ) ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>
