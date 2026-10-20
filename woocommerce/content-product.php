<?php
/**
 * Product card in the shop loop (theme override of woocommerce/content-product.php).
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package webni-custom-version-1
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

$webni_index = isset( $woocommerce_loop['loop'] ) ? (int) $woocommerce_loop['loop'] - 1 : 0;
webni_product_card( $product, max( 0, $webni_index ), true );
