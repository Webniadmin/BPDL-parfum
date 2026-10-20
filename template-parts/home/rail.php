<?php
/**
 * Product rail (Istaknuti / Novo / Najprodavaniji / Akcije / Preporučeni).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$kind     = isset( $args['kind'] ) ? $args['kind'] : '';
$title    = isset( $args['title'] ) ? $args['title'] : '';
$products = isset( $args['products'] ) ? $args['products'] : array();
if ( ! $products ) {
	return;
}
$links = array(
	'sale' => add_query_arg( 'orderby', 'popularity', webni_shop_url() ),
	'new'  => add_query_arg( 'orderby', 'date', webni_shop_url() ),
	'best' => add_query_arg( 'orderby', 'popularity', webni_shop_url() ),
);
?>
<section class="section rail rail--<?php echo esc_attr( $kind ); ?>" id="rail-<?php echo esc_attr( $kind ); ?>">
	<div class="container">
		<div class="section__head reveal">
			<div><h2 class="h-m"><?php echo webni_text( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2></div>
			<a href="<?php echo esc_url( isset( $links[ $kind ] ) ? $links[ $kind ] : webni_shop_url() ); ?>"><?php esc_html_e( 'Svi parfemi →', 'webni-custom-version-1' ); ?></a>
		</div>
		<div class="grid--cards">
			<?php foreach ( $products as $i => $product ) { webni_product_card( $product, $i ); } ?>
		</div>
	</div>
</section>
