<?php
/**
 * Archives (category, tag, date, author) for blog content.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="container">
	<?php get_template_part( 'template-parts/content/page-head', null, array( 'title' => get_the_archive_title(), 'lead' => get_the_archive_description(), 'eyebrow' => __( 'Dnevnik', 'webni-custom-version-1' ) ) ); ?>
	<?php if ( have_posts() ) : ?>
		<div class="grid--posts entry">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/post-card' );
			}
			?>
		</div>
		<?php the_posts_pagination( array( 'prev_text' => __( '← Prethodna', 'webni-custom-version-1' ), 'next_text' => __( 'Sledeća →', 'webni-custom-version-1' ), 'screen_reader_text' => __( 'Strane', 'webni-custom-version-1' ) ) ); ?>
	<?php else : ?>
		<p class="lead entry"><?php esc_html_e( 'Još nema tekstova.', 'webni-custom-version-1' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
