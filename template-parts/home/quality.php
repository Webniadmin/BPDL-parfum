<?php
/**
 * "Zašto 30%" section (badges + comparison bars). Reused on the product page with $args['compact'].
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$compact = ! empty( $args['compact'] );
$name    = isset( $args['name'] ) ? $args['name'] : '';
$text    = webni_option( 'quality_text' );
if ( $compact && $name ) {
	/* translators: %s product name */
	$text = sprintf( __( 'Većina toaletnih voda ima 8–15%% parfemskog ulja. %s ima 30%%. Tri spreja ujutru i ništa do večeri — zato 50 ml traje duže nego što misliš.', 'webni-custom-version-1' ), $name );
}
?>
<section class="section section--quality" id="kvalitet">
	<div class="container split">
		<div class="reveal">
			<p class="eyebrow eyebrow--gold"><?php echo esc_html( webni_option( 'quality_eyebrow' ) ); ?></p>
			<h2 class="h-l"><?php echo webni_text( webni_option( 'quality_title_1' ) ); ?><br><?php echo webni_text( webni_option( 'quality_title_2' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p class="lead"><?php echo esc_html( $text ); ?></p>
			<?php if ( $compact ) : ?>
				<div><a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/#kvalitet' ) ); ?>"><?php esc_html_e( 'Kako radi 30%', 'webni-custom-version-1' ); ?></a></div>
			<?php else : ?>
				<ul class="badges">
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
						<li><strong><?php echo webni_text( webni_option( 'badge' . $i . '_value' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><span><?php echo esc_html( webni_option( 'badge' . $i . '_label' ) ); ?></span></li>
					<?php endfor; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php if ( $compact ) : ?>
			<div class="reveal">
				<ul class="badges">
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
						<li><strong><?php echo webni_text( webni_option( 'badge' . $i . '_value' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><span><?php echo esc_html( webni_option( 'badge' . $i . '_label' ) ); ?></span></li>
					<?php endfor; ?>
				</ul>
			</div>
		<?php else : ?>
			<div class="bars reveal">
				<?php for ( $i = 1; $i <= 3; $i++ ) : $pct = max( 0, min( 100, (int) webni_option( 'bar' . $i . '_pct' ) ) ); ?>
					<div class="bar<?php echo 3 === $i ? ' bar--us' : ''; ?>">
						<span class="bar__label"><?php echo esc_html( webni_option( 'bar' . $i . '_label' ) ); ?></span>
						<span class="bar__val"><?php echo webni_text( webni_option( 'bar' . $i . '_value' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="bar__track"><i style="--w:<?php echo (int) $pct; ?>%;--delay:<?php echo esc_attr( ( $i - 1 ) * 0.15 ); ?>s"></i></span>
					</div>
				<?php endfor; ?>
				<p class="tiny"><?php echo esc_html( webni_option( 'quality_note' ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
