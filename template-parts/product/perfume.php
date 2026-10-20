<?php
/**
 * Perfume landing layout: hero with mist, "Ko ga nosi", facts, notes, 30%, gift + "Probaj i", CTA band.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

global $product;

$d        = $args['d'];
$notices  = isset( $args['notices'] ) ? $args['notices'] : '';
$count    = count( webni_get_perfume_ids() );
$adjacent = webni_adjacent_perfumes( $product );
$related  = webni_related_products( $product, 2 );
$light    = $d['palette']['light'];
$n        = str_pad( (string) ( $d['n'] ? $d['n'] : 1 ), 2, '0', STR_PAD_LEFT );
$of       = str_pad( (string) max( $count, 1 ), 2, '0', STR_PAD_LEFT );
$notes    = $d['notes'];
?>
<section class="hero hero--single<?php echo $light ? ' is-light' : ''; ?>" id="hero" style="<?php echo esc_attr( webni_palette_style( $d ) ); ?>" data-c1="<?php echo esc_attr( $d['palette']['c1'] ); ?>" data-c2="<?php echo esc_attr( $d['palette']['c2'] ); ?>" data-c3="<?php echo esc_attr( $d['palette']['c3'] ); ?>">
	<canvas class="mist" aria-hidden="true"></canvas><div class="hero__grain"></div>
	<?php woocommerce_breadcrumb(); ?>
	<div class="hero__inner container">
		<div class="hero__copy summary entry-summary">
			<p class="eyebrow">
				<?php
				/* translators: 1: number, 2: total, 3: character, 4: pronunciation */
				echo esc_html( sprintf( __( 'Karakter %1$s od %2$s · %3$s', 'webni-custom-version-1' ), $n, $of, $d['character'] ) );
				if ( $d['say'] ) {
					/* translators: %s pronunciation */
					echo esc_html( ' · ' . sprintf( __( 'izgovara se „%s“', 'webni-custom-version-1' ), $d['say'] ) );
				}
				?>
			</p>
			<h1 class="hero__name font-<?php echo esc_attr( $d['font'] ); ?> product_title entry-title"><?php echo esc_html( $d['name'] ); ?></h1>
			<?php if ( $d['tagline'] ) : ?><p class="hero__tagline"><?php echo webni_text( $d['tagline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p><?php endif; ?>
			<?php if ( $d['words'] ) : ?>
				<ul class="chips"><?php foreach ( $d['words'] as $w ) : ?><li><?php echo esc_html( $w ); ?></li><?php endforeach; ?></ul>
			<?php endif; ?>
			<div class="buy">
				<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<span><?php echo esc_html( webni_option( 'badge3_value' ) ); ?></span>
				<span class="badge"><?php echo esc_html( webni_option( 'badge1_value' ) . ' ' . webni_option( 'badge1_label' ) ); ?></span>
			</div>
			<?php echo $notices; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce notices. ?>
			<div class="hero__actions">
				<?php woocommerce_template_single_add_to_cart(); ?>
				<a class="btn btn--ghost btn--lg" href="<?php echo esc_url( webni_discovery_url() ); ?>"><?php esc_html_e( 'Probaj u discovery setu', 'webni-custom-version-1' ); ?></a>
			</div>
			<p class="tiny"><?php echo esc_html( $d['cat_label'] . ' · ' . webni_option( 'shop_delivery_line' ) . ' · ' . webni_option( 'free_shipping_text' ) ); ?></p>
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
		</div>
		<div class="hero__stage">
			<?php echo webni_perfume_image( $d, 'hero', array( 'class' => 'pack pack--hero pack--float', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
	<?php if ( $adjacent['next'] ) : ?>
		<a class="hero__next" href="<?php echo esc_url( $adjacent['next']->get_permalink() ); ?>"><?php /* translators: %s next perfume name */ echo esc_html( sprintf( __( 'Sledeći karakter: %s →', 'webni-custom-version-1' ), $adjacent['next']->get_name() ) ); ?></a>
	<?php endif; ?>
</section>

<section class="section">
	<div class="container split">
		<div class="reveal">
			<p class="eyebrow eyebrow--gold"><?php esc_html_e( 'Ko ga nosi', 'webni-custom-version-1' ); ?></p>
			<h2 class="h-l"><?php echo webni_text( $d['who'] ? $d['who'] : $d['tagline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<div class="lead woocommerce-product-details__description"><?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?></div>
		</div>
		<div class="reveal hero__stage">
			<?php echo webni_perfume_image( $d, 'card', array( 'class' => 'pack pack--set' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>

<?php if ( $d['occasion'] || $d['season'] ) : ?>
<section class="section section--tight">
	<div class="container">
		<div class="facts reveal">
			<?php if ( $d['occasion'] ) : ?><div class="fact"><h3><?php esc_html_e( 'Prilika', 'webni-custom-version-1' ); ?></h3><p><?php echo webni_text( $d['occasion'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div><?php endif; ?>
			<?php if ( $d['season'] ) : ?><div class="fact"><h3><?php esc_html_e( 'Sezona', 'webni-custom-version-1' ); ?></h3><p><?php echo webni_text( $d['season'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div><?php endif; ?>
			<div class="fact"><h3><?php esc_html_e( 'Kako traje', 'webni-custom-version-1' ); ?></h3><p><?php echo webni_text( webni_option( 'product_how_long' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $notes['top'] || $notes['heart'] || $notes['base'] ) : ?>
<section class="section">
	<div class="container">
		<div class="section__head reveal">
			<div><p class="eyebrow"><?php esc_html_e( 'Note', 'webni-custom-version-1' ); ?></p><h2 class="h-l"><?php esc_html_e( 'Kako se otvara.', 'webni-custom-version-1' ); ?></h2></div>
			<p class="lead" style="max-width:40ch"><?php echo esc_html( webni_option( 'product_notes_lead' ) ); ?></p>
		</div>
		<div class="note-grid reveal">
			<?php
			$webni_note_rows = array(
				array( 'top', __( 'Prvih 15 minuta', 'webni-custom-version-1' ), __( 'Vrh', 'webni-custom-version-1' ), '.35' ),
				array( 'heart', __( '1–4 sata', 'webni-custom-version-1' ), __( 'Srce', 'webni-custom-version-1' ), '.65' ),
				array( 'base', __( 'Do večeri', 'webni-custom-version-1' ), __( 'Baza', 'webni-custom-version-1' ), '1' ),
			);
			foreach ( $webni_note_rows as $row ) :
				if ( ! $notes[ $row[0] ] ) {
					continue;
				}
				?>
				<div class="note" style="--o:<?php echo esc_attr( $row[3] ); ?>">
					<p class="tiny"><?php echo esc_html( $row[1] ); ?></p>
					<h3><?php echo esc_html( $row[2] ); ?></h3>
					<ul><?php foreach ( $notes[ $row[0] ] as $note ) : ?><li><?php echo esc_html( $note ); ?></li><?php endforeach; ?></ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/home/quality', null, array( 'compact' => true, 'name' => $d['name'] ) ); ?>

<section class="section">
	<div class="container split">
		<div class="reveal">
			<p class="eyebrow eyebrow--gold"><?php esc_html_e( 'Poklon', 'webni-custom-version-1' ); ?></p>
			<?php $webni_acc = webni_zodiac_accusative( $d['zodiac'] ); ?>
			<h2 class="h-l"><?php echo esc_html( $webni_acc ? sprintf( /* translators: %s zodiac signs */ __( 'Idealan za %s.', 'webni-custom-version-1' ), $webni_acc ) : __( 'Idealan poklon.', 'webni-custom-version-1' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( sprintf( /* translators: %s character */ __( 'Kartica karaktera je u pakovanju: piše „Ti si %s“, pa poklon ima priču pre nego što se otvori.', 'webni-custom-version-1' ), $d['character'] ) ); ?></p>
			<div class="hero__actions"><button type="button" class="btn btn--gold" data-gift><?php esc_html_e( 'Pronađi njihov miris', 'webni-custom-version-1' ); ?></button></div>
		</div>
		<?php if ( $related ) : ?>
			<div class="reveal">
				<p class="eyebrow"><?php esc_html_e( 'Ako ti se dopada, probaj i', 'webni-custom-version-1' ); ?></p>
				<div class="pair-cards">
					<?php foreach ( $related as $i => $alt ) : $ad = webni_perfume( $alt ); ?>
						<a class="pair-card" href="<?php echo esc_url( $ad['url'] ); ?>" style="<?php echo esc_attr( webni_palette_style( $ad ) ); ?>">
							<div class="pair-card__visual"><?php echo webni_perfume_image( $ad, 'bottle', array( 'class' => 'pack pack--bottle' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<div>
								<h3><?php echo esc_html( $ad['name'] ); ?></h3>
								<?php if ( $ad['tagline'] ) : ?><p class="ital"><?php echo webni_text( $ad['tagline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p><?php endif; ?>
								<?php if ( ! empty( $d['alts_why'][ $i ] ) ) : ?><p class="tiny"><?php echo esc_html( $d['alts_why'][ $i ] ); ?></p><?php endif; ?>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="cta-band container reveal">
	<p class="eyebrow eyebrow--gold"><?php esc_html_e( 'Nije to?', 'webni-custom-version-1' ); ?></p>
	<h2 class="h-l"><?php echo webni_text( webni_option( 'quiz_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
	<p class="lead"><?php esc_html_e( 'Pet pitanja, 40 sekundi, i znaš koji si karakter.', 'webni-custom-version-1' ); ?></p>
	<div class="hero__actions">
		<button type="button" class="btn btn--solid btn--lg" data-quiz><?php echo esc_html( webni_option( 'hero_cta1' ) ); ?></button>
		<?php if ( $adjacent['prev'] ) : ?><a class="btn btn--ghost" href="<?php echo esc_url( $adjacent['prev']->get_permalink() ); ?>">← <?php echo esc_html( $adjacent['prev']->get_name() ); ?></a><?php endif; ?>
		<?php if ( $adjacent['next'] ) : ?><a class="btn btn--ghost" href="<?php echo esc_url( $adjacent['next']->get_permalink() ); ?>"><?php echo esc_html( $adjacent['next']->get_name() ); ?> →</a><?php endif; ?>
	</div>
</section>
