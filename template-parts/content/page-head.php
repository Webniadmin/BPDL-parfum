<?php
/**
 * Page heading block.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$title   = isset( $args['title'] ) ? $args['title'] : '';
$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$lead    = isset( $args['lead'] ) ? $args['lead'] : '';
$center  = ! empty( $args['center'] );
?>
<div class="page-head<?php echo $center ? ' page-head--center' : ''; ?>">
	<?php if ( $eyebrow ) : ?><p class="eyebrow eyebrow--gold"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
	<h1 class="h-l"><?php echo webni_dashes( wp_kses_post( $title ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
	<?php if ( $lead ) : ?><p class="lead"><?php echo wp_kses_post( $lead ); ?></p><?php endif; ?>
</div>
