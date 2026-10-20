<?php
/**
 * 404 page.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="notfound container">
	<div>
		<p class="h-xl">404</p>
		<h1 class="h-l"><?php esc_html_e( 'Ovaj miris ne postoji.', 'webni-custom-version-1' ); ?></h1>
		<p class="lead" style="text-align:center"><?php esc_html_e( 'Stranica je premeštena ili nikad nije postojala. Osam karaktera i dalje čeka.', 'webni-custom-version-1' ); ?></p>
		<div class="hero__actions" style="justify-content:center">
			<a class="btn btn--solid" href="<?php echo esc_url( webni_shop_url() ); ?>"><?php esc_html_e( 'Pogledaj parfeme', 'webni-custom-version-1' ); ?></a>
			<button type="button" class="btn btn--ghost" data-quiz><?php echo esc_html( webni_option( 'hero_cta1' ) ); ?></button>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Početna', 'webni-custom-version-1' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
