<?php
/**
 * Comments (blog posts only; product reviews are disabled by the theme defaults).
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section class="comments" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2>
			<?php
			$webni_n = get_comments_number();
			/* translators: %s number of comments */
			echo esc_html( sprintf( _n( '%s komentar', '%s komentara', $webni_n, 'webni-custom-version-1' ), number_format_i18n( $webni_n ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 0,
				'callback'    => 'webni_comment_html',
			) );
			?>
		</ol>
		<?php the_comments_navigation( array( 'prev_text' => __( '← Stariji', 'webni-custom-version-1' ), 'next_text' => __( 'Noviji →', 'webni-custom-version-1' ) ) ); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="tiny"><?php esc_html_e( 'Komentari su zatvoreni.', 'webni-custom-version-1' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form( array(
		'title_reply'          => __( 'Ostavi komentar', 'webni-custom-version-1' ),
		'title_reply_to'       => __( 'Odgovori: %s', 'webni-custom-version-1' ),
		'cancel_reply_link'    => __( 'Odustani', 'webni-custom-version-1' ),
		'label_submit'         => __( 'Pošalji', 'webni-custom-version-1' ),
		'class_submit'         => 'btn btn--solid',
		'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'E-mail adresa se ne objavljuje.', 'webni-custom-version-1' ) . '</p>',
		'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Komentar', 'webni-custom-version-1' ) . '</label><textarea id="comment" name="comment" rows="5" required></textarea></p>',
		'fields'               => array(
			'author' => '<p class="comment-form-author"><label for="author">' . esc_html__( 'Ime', 'webni-custom-version-1' ) . '</label><input id="author" name="author" type="text" value="" required></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'E-mail', 'webni-custom-version-1' ) . '</label><input id="email" name="email" type="email" value="" required></p>',
			'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"><label for="wp-comment-cookies-consent">' . esc_html__( 'Zapamti ime i e-mail za sledeći put.', 'webni-custom-version-1' ) . '</label></p>',
		),
	) );
	?>
</section>
