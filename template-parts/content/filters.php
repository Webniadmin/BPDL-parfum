<?php
/**
 * Filter chips for product grids (client-side; cards carry data-cat / data-time).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="filters" aria-label="<?php esc_attr_e( 'Filter', 'webni-custom-version-1' ); ?>">
	<button type="button" class="is-active" data-f="all"><?php esc_html_e( 'Svi', 'webni-custom-version-1' ); ?></button>
	<button type="button" data-f="M"><?php esc_html_e( 'Za njega', 'webni-custom-version-1' ); ?></button>
	<button type="button" data-f="Ž"><?php esc_html_e( 'Za nju', 'webni-custom-version-1' ); ?></button>
	<button type="button" data-f="U"><?php esc_html_e( 'Unisex', 'webni-custom-version-1' ); ?></button>
	<button type="button" data-f="dan"><?php esc_html_e( 'Dan', 'webni-custom-version-1' ); ?></button>
	<button type="button" data-f="noć"><?php esc_html_e( 'Noć', 'webni-custom-version-1' ); ?></button>
</div>
