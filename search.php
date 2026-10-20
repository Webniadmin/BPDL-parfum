<?php
/**
 * Search results for posts and pages (product searches use the WooCommerce archive).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

get_header();
/* translators: %s search term */
$webni_title = sprintf( __( 'Rezultati za „%s“', 'webni-custom-version-1' ), get_search_query() );
?>
<div class="container">
	<?php get_template_part( 'template-parts/content/page-head', null, array( 'title' => $webni_title, 'eyebrow' => __( 'Pretraga', 'webni-custom-version-1' ) ) ); ?>
	<div class="entry" style="display:grid;gap:40px">
		<?php get_search_form(); ?>
		<?php if ( have_posts() ) : ?>
			<div class="grid--posts">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content/post-card' );
				}
				?>
			</div>
			<?php the_posts_pagination( array( 'prev_text' => __( '← Prethodna', 'webni-custom-version-1' ), 'next_text' => __( 'Sledeća →', 'webni-custom-version-1' ), 'screen_reader_text' => __( 'Strane', 'webni-custom-version-1' ) ) ); ?>
		<?php else : ?>
			<p class="lead"><?php esc_html_e( 'Ništa nije pronađeno. Probaj drugu reč ili pogledaj parfeme.', 'webni-custom-version-1' ); ?></p>
			<div><a class="btn btn--ghost" href="<?php echo esc_url( webni_shop_url() ); ?>"><?php esc_html_e( 'Svi parfemi', 'webni-custom-version-1' ); ?></a></div>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
