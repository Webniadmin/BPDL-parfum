<?php
/**
 * Static pages (help, about, contact, legal) and the WooCommerce shortcode pages
 * (cart, checkout, my account), which get a wide layout.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

get_header();

$webni_wide = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( $webni_wide ? 'entry container shop-page' : 'entry container container--narrow' ); ?>>
		<?php get_template_part( 'template-parts/content/page-head', null, array( 'title' => get_the_title(), 'eyebrow' => $webni_wide ? __( 'BP|DL Parfum', 'webni-custom-version-1' ) : '' ) ); ?>
		<?php if ( ! $webni_wide && has_post_thumbnail() ) : ?>
			<figure class="entry-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
		<?php endif; ?>
		<div class="<?php echo $webni_wide ? 'shop-content' : 'entry-content'; ?>">
			<?php
			the_content();
			wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
			?>
		</div>
		<?php
		if ( ! $webni_wide && ( comments_open() || get_comments_number() ) ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
