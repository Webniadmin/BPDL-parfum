<?php
/**
 * Single product content (theme override of woocommerce/content-single-product.php).
 *
 * Perfumes with character data get the full landing layout (template-parts/product/perfume.php);
 * every other product (sets, future items) uses template-parts/product/simple.php.
 * WooCommerce hooks for the summary, add-to-cart and structured data are kept.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package webni-custom-version-1
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
ob_start();
do_action( 'woocommerce_before_single_product' );
$webni_notices = ob_get_clean();

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$webni_data = webni_perfume( $product );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'product-page', $product ); ?>>
	<?php
	if ( $webni_data && $webni_data['character'] ) {
		get_template_part( 'template-parts/product/perfume', null, array( 'd' => $webni_data, 'notices' => $webni_notices ) );
	} else {
		get_template_part( 'template-parts/product/simple', null, array( 'd' => $webni_data, 'notices' => $webni_notices ) );
	}

	/**
	 * Hook: woocommerce_after_single_product_summary.
	 * The theme removed the tabs, upsells and related products; the character sections and
	 * "Probaj i" are rendered by the template parts above. Plugins can still hook here.
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
