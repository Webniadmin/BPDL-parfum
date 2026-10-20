<?php
/**
 * Home hero.
 *
 * Two modes (Customizer → BP|DL Parfum → Hero):
 * - image    one large static picture (full-bleed "cover" or "split" next to the copy), no WebGL;
 * - animated WebGL mist cycling the eight characters with the bottle photo and the strip of eight.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$perfumes = isset( $args['perfumes'] ) ? $args['perfumes'] : array();
$first    = $perfumes ? webni_perfume( $perfumes[0] ) : null;
$style    = $first ? webni_palette_style( $first ) : '';
$mode     = webni_hero_mode();
$title_1  = webni_option( 'hero_title_1' );
$title_2  = webni_option( 'hero_title_2' );
$cta2_url = (string) webni_option( 'hero_cta2_url' );
if ( '' !== $cta2_url && '#' === $cta2_url[0] ) {
	$cta2_url = home_url( '/' . $cta2_url );
}

$image  = array( 'id' => 0, 'fallback' => true );
$layout = 'cover';
$pos    = 'center';
if ( 'image' === $mode ) {
	$image  = webni_hero_image();
	$layout = 'split' === webni_option( 'hero_layout', 'cover' ) ? 'split' : 'cover';
	$pos    = (string) webni_option( 'hero_image_pos', 'center' );
	if ( ! in_array( $pos, array( 'center', 'top', 'bottom', 'left', 'right' ), true ) ) {
		$pos = 'center';
	}
	if ( $image['fallback'] ) {
		// A product shot next to the copy reads better than a cropped product shot behind it.
		$layout = $image['id'] ? 'split' : 'cover'; // No products yet: gradient only.
	}
}

$classes = array( 'hero', 'hero--home', 'hero--' . $mode );
if ( 'image' === $mode ) {
	$classes[] = 'hero--' . $layout;
} elseif ( $first && $first['palette']['light'] ) {
	$classes[] = 'is-light';
}

/**
 * Split a headline into animated words.
 *
 * @param string $text  Line.
 * @param float  $delay Start delay in seconds.
 * @return string
 */
$webni_words = function ( $text, $delay ) {
	$out = array();
	foreach ( preg_split( '/\s+/', trim( (string) $text ) ) as $i => $word ) {
		$out[] = sprintf( '<span class="word" style="animation-delay:%ss">%s</span>', esc_attr( number_format( $delay + $i * 0.09, 2, '.', '' ) ), webni_text( $word ) );
	}
	return '<span class="line">' . implode( ' ', $out ) . '</span>';
};
?>
<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" id="hero" data-mode="<?php echo esc_attr( $mode ); ?>" style="<?php echo esc_attr( $style ); ?>">
	<?php if ( 'image' === $mode ) : ?>
		<?php if ( 'cover' === $layout && $image['id'] ) : ?>
			<div class="hero__bg" aria-hidden="true">
				<?php echo webni_hero_picture( $image['id'], $pos, '100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<canvas class="mist" aria-hidden="true"></canvas>
	<?php endif; ?>
	<div class="hero__grain"></div>
	<div class="hero__inner container">
		<div class="hero__copy">
			<p class="eyebrow"><?php echo esc_html( webni_option( 'hero_eyebrow' ) ); ?></p>
			<h1 class="hero__title"><?php echo $webni_words( $title_1, 0.15 ) . $webni_words( $title_2, 0.4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the closure. ?></h1>
			<p class="hero__sub"><?php echo esc_html( webni_option( 'hero_sub' ) ); ?></p>
			<?php if ( $first ) : ?>
				<p class="hero__cycle"><?php echo esc_html( webni_option( 'hero_cycle_prefix' ) ); ?> <span class="cycle-name" id="cycleName"><?php echo esc_html( $first['character'] ); ?></span></p>
			<?php endif; ?>
			<div class="hero__actions">
				<button type="button" class="btn btn--solid btn--lg" data-quiz><?php echo esc_html( webni_option( 'hero_cta1' ) ); ?></button>
				<a class="btn btn--ghost btn--lg" href="<?php echo esc_url( $cta2_url ? $cta2_url : webni_shop_url() ); ?>"><?php echo esc_html( webni_option( 'hero_cta2' ) ); ?></a>
			</div>
		</div>
		<?php if ( 'image' === $mode && 'split' === $layout ) : ?>
			<div class="hero__stage hero__stage--image">
				<?php echo webni_hero_picture( $image['id'], $pos, '(max-width: 900px) 100vw, 50vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php elseif ( 'animated' === $mode ) : ?>
			<div class="hero__stage">
				<?php if ( $first && $first['hero_id'] ) : ?>
					<div class="pack-stage" id="heroPack">
						<?php echo webni_perfume_image( $first, 'hero', array( 'class' => 'pack pack--hero is-on', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<img class="pack pack--hero" alt="" decoding="async" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( 'animated' === $mode && $perfumes ) : ?>
		<div class="hero__strip container" role="tablist" aria-label="<?php esc_attr_e( 'Osam karaktera', 'webni-custom-version-1' ); ?>">
			<?php foreach ( $perfumes as $i => $product ) : $d = webni_perfume( $product ); ?>
				<button type="button" class="strip__item<?php echo 0 === $i ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-i="<?php echo (int) $i; ?>">
					<span class="strip__bar"></span>
					<span class="strip__n"><?php echo esc_html( str_pad( (string) ( $d['n'] ? $d['n'] : $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<span class="strip__name"><?php echo esc_html( $d['name'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
