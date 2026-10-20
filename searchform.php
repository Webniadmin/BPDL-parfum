<?php
/**
 * Search form (products first: the shop handles results with sorting and pagination).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$webni_sid = 'search-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="<?php echo esc_attr( $webni_sid ); ?>"><?php esc_html_e( 'Pretraga', 'webni-custom-version-1' ); ?></label>
	<input id="<?php echo esc_attr( $webni_sid ); ?>" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Pretraži parfeme…', 'webni-custom-version-1' ); ?>" autocomplete="off">
	<?php if ( class_exists( 'WooCommerce' ) ) : ?><input type="hidden" name="post_type" value="product"><?php endif; ?>
	<button type="submit" class="btn btn--solid"><?php esc_html_e( 'Traži', 'webni-custom-version-1' ); ?></button>
</form>
