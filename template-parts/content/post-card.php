<?php
/**
 * Post card for blog listings and search results.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card reveal' ); ?>>
	<a class="post-card__visual" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
		<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); } ?>
	</a>
	<div class="post-card__body">
		<div class="entry-meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php if ( 'post' !== get_post_type() ) : ?><span><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span><?php endif; ?>
		</div>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
		<a class="btn btn--sm btn--ghost" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Pročitaj', 'webni-custom-version-1' ); ?></a>
	</div>
</article>
