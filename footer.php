<?php
/**
 * Site footer: brand, menus, contact, social, newsletter, legal, copyright.
 * Also carries the global components (cursor, toast, quiz modal).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$webni_social = array(
	'instagram' => 'Instagram',
	'tiktok'    => 'TikTok',
	'facebook'  => 'Facebook',
	'youtube'   => 'YouTube',
);
$webni_copy   = str_replace( '{year}', gmdate( 'Y' ), (string) webni_option( 'copyright' ) );
$webni_gf_nl  = (int) webni_option( 'newsletter_gf_id', 0 );
?>
</main>

<footer class="footer" id="footer">
	<div class="container">
		<div class="footer__grid">
			<div class="footer__brand">
				<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>">BP<i>|</i>DL <small>PARFUM</small></a>
				<p><?php echo esc_html( webni_option( 'footer_tagline' ) ); ?></p>
				<p class="tiny"><?php echo esc_html( webni_option( 'footer_note' ) ); ?></p>
				<?php if ( webni_option( 'contact_email' ) || webni_option( 'contact_phone' ) || webni_option( 'contact_address' ) ) : ?>
					<div class="footer__contact">
						<?php if ( webni_option( 'contact_email' ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( webni_option( 'contact_email' ) ) ); ?>"><?php echo esc_html( antispambot( webni_option( 'contact_email' ) ) ); ?></a><?php endif; ?>
						<?php if ( webni_option( 'contact_phone' ) ) : ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', webni_option( 'contact_phone' ) ) ); ?>"><?php echo esc_html( webni_option( 'contact_phone' ) ); ?></a><?php endif; ?>
						<?php if ( webni_option( 'contact_address' ) ) : ?><span><?php echo esc_html( webni_option( 'contact_address' ) ); ?></span><?php endif; ?>
					</div>
				<?php endif; ?>
				<?php
				$webni_links = array();
				foreach ( $webni_social as $webni_key => $webni_label ) {
					$webni_url = webni_option( 'social_' . $webni_key );
					if ( $webni_url ) {
						$webni_links[] = '<a href="' . esc_url( $webni_url ) . '" rel="noopener" target="_blank">' . esc_html( $webni_label ) . '</a>';
					}
				}
				if ( $webni_links ) {
					echo '<div class="footer__social">' . implode( '', $webni_links ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
				}
				?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Sajt', 'webni-custom-version-1' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer-site' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer-site', 'container' => false, 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false ) );
				} else {
					echo '<ul><li><a href="' . esc_url( webni_shop_url() ) . '">' . esc_html__( 'Parfemi', 'webni-custom-version-1' ) . '</a></li><li><a href="#" data-quiz>' . esc_html( webni_option( 'hero_cta1' ) ) . '</a></li><li><a href="' . esc_url( home_url( '/#poklon' ) ) . '">' . esc_html__( 'Poklon', 'webni-custom-version-1' ) . '</a></li></ul>';
				}
				?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Pomoć', 'webni-custom-version-1' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer-help' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer-help', 'container' => false, 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false ) );
				} else {
					echo '<ul><li><a href="' . esc_url( home_url( '/pomoc/' ) ) . '">' . esc_html__( 'Dostava i plaćanje', 'webni-custom-version-1' ) . '</a></li><li><a href="' . esc_url( home_url( '/kontakt/' ) ) . '">' . esc_html__( 'Kontakt', 'webni-custom-version-1' ) . '</a></li></ul>';
				}
				?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Pravno', 'webni-custom-version-1' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer-legal' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer-legal', 'container' => false, 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false ) );
				} else {
					echo '<ul><li><a href="' . esc_url( home_url( '/uslovi-kupovine/' ) ) . '">' . esc_html__( 'Uslovi kupovine', 'webni-custom-version-1' ) . '</a></li><li><a href="' . esc_url( get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/politika-privatnosti/' ) ) . '">' . esc_html__( 'Privatnost i kolačići', 'webni-custom-version-1' ) . '</a></li></ul>';
				}
				?>
			</div>
		</div>

		<?php if ( ! is_front_page() && $webni_gf_nl && webni_gf_active() ) : ?>
			<div class="footer__news">
				<div>
					<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'newsletter_eyebrow' ) ); ?></p>
					<h3><?php echo esc_html( webni_option( 'newsletter_title' ) ); ?></h3>
				</div>
				<div class="news-form"><?php echo webni_gravity_form( $webni_gf_nl, 'newsletter' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Gravity Forms markup. ?></div>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-extra' ) ) : ?>
			<div class="footer__widgets"><?php dynamic_sidebar( 'footer-extra' ); ?></div>
		<?php endif; ?>

		<?php
		$webni_logos    = webni_payment_logos( 'footer' );
		$webni_merchant = webni_merchant_line();
		?>
		<?php if ( $webni_logos || $webni_merchant ) : ?>
			<div class="footer__legal">
				<?php echo $webni_logos; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from wp_get_attachment_image(). ?>
				<?php if ( $webni_merchant ) : ?><p class="tiny"><?php echo esc_html( $webni_merchant ); ?></p><?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="footer__bottom">
			<span><?php echo esc_html( $webni_copy ); ?></span>
			<span><?php echo esc_html( webni_option( 'payment_line' ) ); ?></span>
			<span><?php esc_html_e( 'Sajt: Webni', 'webni-custom-version-1' ); ?></span>
		</div>
	</div>
</footer>

<div class="cursor" aria-hidden="true"><div class="cursor__dot"></div><div class="cursor__ring"></div></div>
<div class="toast" role="status" aria-live="polite"></div>

<div class="quiz" id="quizModal" hidden role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( webni_option( 'hero_cta1' ) ); ?>">
	<div class="quiz__panel"></div>
	<?php $webni_gf_quiz = (int) webni_option( 'quiz_gf_id', 0 ); ?>
	<div class="quiz__gf" hidden data-form-id="<?php echo esc_attr( $webni_gf_quiz ); ?>">
		<?php if ( $webni_gf_quiz && webni_gf_active() ) : ?>
			<div class="quiz__email-form"><?php echo webni_gravity_form( $webni_gf_quiz, 'quiz' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Gravity Forms markup. ?></div>
		<?php endif; ?>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
