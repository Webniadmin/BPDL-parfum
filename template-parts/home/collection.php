<?php
/**
 * Collection: all perfumes with client-side filters.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$perfumes = isset( $args['perfumes'] ) ? $args['perfumes'] : array();
?>
<section class="section" id="kolekcija">
	<div class="container">
		<div class="section__head">
			<div>
				<p class="eyebrow"><?php echo esc_html( webni_option( 'collection_eyebrow' ) ); ?></p>
				<h2 class="h-l"><?php echo webni_text( webni_option( 'collection_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			</div>
			<?php get_template_part( 'template-parts/content/filters' ); ?>
		</div>
		<?php if ( $perfumes ) : ?>
			<div class="grid--cards">
				<?php foreach ( $perfumes as $i => $product ) { webni_product_card( $product, $i ); } ?>
			</div>
			<p class="lead filters__empty" hidden><?php esc_html_e( 'Nema parfema za ovaj izbor.', 'webni-custom-version-1' ); ?></p>
		<?php else : ?>
			<p class="lead"><?php esc_html_e( 'Parfemi stižu. Učitaj proizvode u WooCommerce-u (ili demo katalog u WooCommerce → BP|DL alati).', 'webni-custom-version-1' ); ?></p>
		<?php endif; ?>
	</div>
</section>
