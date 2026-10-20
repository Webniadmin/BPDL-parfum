<?php
/**
 * Newsletter section (Gravity Forms; hidden for visitors when no form is configured).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$form_id = (int) webni_option( 'newsletter_gf_id', 0 );
$markup  = webni_gravity_form( $form_id, 'newsletter' );
if ( '' === $markup ) {
	return;
}
?>
<section class="section section--news" id="newsletter">
	<div class="container reveal">
		<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'newsletter_eyebrow' ) ); ?></p>
		<h2 class="h-l"><?php echo esc_html( webni_option( 'newsletter_title' ) ); ?></h2>
		<p class="lead"><?php echo esc_html( webni_option( 'newsletter_text' ) ); ?></p>
		<div class="news-form"><?php echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Gravity Forms markup. ?></div>
	</div>
</section>
