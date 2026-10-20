<?php
/**
 * Brand story + the eight "worlds".
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$perfumes = isset( $args['perfumes'] ) ? $args['perfumes'] : array();
?>
<section class="section" id="prica">
	<div class="container">
		<div class="split reveal">
			<div>
				<p class="eyebrow"><?php echo esc_html( webni_option( 'story_eyebrow' ) ); ?></p>
				<h2 class="h-l"><?php echo webni_text( webni_option( 'story_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			</div>
			<div>
				<p class="lead"><?php echo esc_html( webni_option( 'story_text_1' ) ); ?></p>
				<p class="lead"><?php echo esc_html( webni_option( 'story_text_2' ) ); ?></p>
				<p class="tiny"><?php echo esc_html( webni_option( 'story_note' ) ); ?></p>
			</div>
		</div>
		<?php if ( $perfumes ) : ?>
			<div class="worlds reveal">
				<?php foreach ( $perfumes as $i => $product ) : $d = webni_perfume( $product ); if ( ! $d['world'] ) { continue; } ?>
					<a class="world<?php echo $d['palette']['light'] ? ' is-light' : ''; ?>" href="<?php echo esc_url( $d['url'] ); ?>" style="<?php echo esc_attr( webni_palette_style( $d ) ); ?>">
						<p class="eyebrow"><?php /* translators: %s number */ echo esc_html( sprintf( __( 'Svet %s', 'webni-custom-version-1' ), str_pad( (string) ( $d['n'] ? $d['n'] : $i + 1 ), 2, '0', STR_PAD_LEFT ) ) ); ?></p>
						<h3><?php echo esc_html( $d['name'] ); ?></h3>
						<p><?php echo webni_text( $d['world'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
