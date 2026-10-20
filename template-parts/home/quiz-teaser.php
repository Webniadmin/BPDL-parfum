<?php
/**
 * Quiz teaser section.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$perfumes = class_exists( 'WooCommerce' ) ? webni_get_perfumes( 3 ) : array();
$orbs     = array( 'left:-10%;top:-10%', 'right:-10%;bottom:-20%;animation-delay:-6s', 'left:35%;bottom:-30%;animation-delay:-3s' );
?>
<section class="section section--quiz" id="quiz">
	<?php foreach ( $perfumes as $i => $product ) : $d = webni_perfume( $product ); ?>
		<div class="orb" style="<?php echo esc_attr( $orbs[ $i ] . ';background:' . ( $i < 2 ? $d['palette']['c3'] : $d['palette']['c2'] ) ); ?>"></div>
	<?php endforeach; ?>
	<div class="container quiz-teaser reveal">
		<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'quiz_eyebrow' ) ); ?></p>
		<h2 class="h-xl"><?php echo webni_text( webni_option( 'quiz_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<p class="lead"><?php echo esc_html( webni_option( 'quiz_text' ) ); ?></p>
		<button type="button" class="btn btn--solid btn--lg" data-quiz><?php echo esc_html( webni_option( 'quiz_cta' ) ); ?></button>
		<p class="tiny"><?php echo esc_html( webni_option( 'quiz_note' ) ); ?></p>
	</div>
</section>
