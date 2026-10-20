<?php
/**
 * Marquee strip under the hero (one line per item in the Customizer).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$items = array_values( array_filter( array_map( 'trim', explode( "\n", (string) webni_option( 'marquee_items' ) ) ) ) );
if ( ! $items ) {
	return;
}
$row = '';
foreach ( $items as $item ) {
	$row .= '<span>' . webni_text( $item ) . ' <i>·</i></span>';
}
?>
<div class="marquee" aria-hidden="true"><div class="marquee__track"><?php echo $row . $row; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above; duplicated for the loop. ?></div></div>
