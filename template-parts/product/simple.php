<?php
/**
 * Standard product layout (sets and any product without character data):
 * image + gallery on the left, WooCommerce summary on the right, description below, "Probaj i".
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

global $product;

$d       = $args['d'];
$notices = isset( $args['notices'] ) ? $args['notices'] : '';
$related = webni_related_products( $product, 4 );
$gallery = $product->get_gallery_image_ids();
?>
<div class="container product-simple">
	<?php woocommerce_breadcrumb(); ?>
	<?php echo $notices; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce notices. ?>
	<div class="product-simple__grid">
		<div class="product-simple__media">
			<div class="product-simple__main" style="<?php echo esc_attr( $d ? webni_palette_style( $d ) : '' ); ?>">
				<?php
				if ( $product->get_image_id() ) {
					echo wp_get_attachment_image( $product->get_image_id(), 'webni-hero', false, array( 'class' => 'pack', 'loading' => 'eager', 'fetchpriority' => 'high' ) );
				} else {
					echo wp_kses_post( wc_placeholder_img( 'webni-hero' ) );
				}
				?>
			</div>
			<?php if ( $gallery ) : ?>
				<div class="product-simple__thumbs">
					<?php foreach ( $gallery as $gid ) : ?>
						<a href="<?php echo esc_url( wp_get_attachment_image_url( $gid, 'full' ) ); ?>" target="_blank" rel="noopener"><?php echo wp_get_attachment_image( $gid, 'webni-thumb', false, array( 'loading' => 'lazy' ) ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="summary entry-summary product-simple__summary">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
			<p class="tiny"><?php echo esc_html( webni_option( 'shop_delivery_line' ) . ' · ' . webni_free_shipping_text() ); ?></p>
		</div>
	</div>

	<?php if ( $product->get_description() ) : ?>
		<div class="product-simple__desc entry-content">
			<h2><?php esc_html_e( 'Opis', 'webni-custom-version-1' ); ?></h2>
			<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $related ) : ?>
		<section class="rail section">
			<div class="section__head reveal"><div><h2 class="h-m"><?php esc_html_e( 'Probaj i', 'webni-custom-version-1' ); ?></h2></div></div>
			<div class="grid--cards">
				<?php foreach ( $related as $i => $rp ) { webni_product_card( $rp, $i ); } ?>
			</div>
		</section>
	<?php endif; ?>
</div>
