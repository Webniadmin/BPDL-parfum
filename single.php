<?php
/**
 * Single blog post.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$webni_cats = get_the_category_list( ', ' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry container container--narrow' ); ?>>
		<div class="page-head">
			<div class="entry-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php if ( $webni_cats ) : ?><span><?php echo wp_kses_post( $webni_cats ); ?></span><?php endif; ?>
			</div>
			<h1 class="h-l"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?><p class="lead"><?php echo wp_kses_post( get_the_excerpt() ); ?></p><?php endif; ?>
		</div>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
		<?php endif; ?>
		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
			?>
		</div>
		<?php the_tags( '<div class="entry-tags">', '', '</div>' ); ?>
		<?php
		$webni_prev = get_previous_post();
		$webni_next = get_next_post();
		if ( $webni_prev || $webni_next ) :
			?>
			<nav class="post-nav" aria-label="<?php esc_attr_e( 'Drugi tekstovi', 'webni-custom-version-1' ); ?>">
				<?php if ( $webni_prev ) : ?>
					<a href="<?php echo esc_url( get_permalink( $webni_prev ) ); ?>"><span class="tiny"><?php esc_html_e( '← Prethodni', 'webni-custom-version-1' ); ?></span><b><?php echo esc_html( get_the_title( $webni_prev ) ); ?></b></a>
				<?php else : ?><span></span><?php endif; ?>
				<?php if ( $webni_next ) : ?>
					<a href="<?php echo esc_url( get_permalink( $webni_next ) ); ?>"><span class="tiny"><?php esc_html_e( 'Sledeći →', 'webni-custom-version-1' ); ?></span><b><?php echo esc_html( get_the_title( $webni_next ) ); ?></b></a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
