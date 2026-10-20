<?php
/**
 * "BP|DL karakter" meta box on WooCommerce products.
 *
 * Brand-specific data that WooCommerce has no field for (character, tagline, notes,
 * zodiac, palette, extra images). Prices, stock, images, categories stay in WooCommerce.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions.
 *
 * @return array[]
 */
function webni_character_fields() {
	return array(
		'character'   => array( 'label' => 'Karakter', 'type' => 'text', 'desc' => 'npr. Istraživač, Favorit, Dama' ),
		'tagline'     => array( 'label' => 'Tagline', 'type' => 'text', 'desc' => 'Jedna rečenica, npr. „Ceo svet u jednom udahu.“' ),
		'alt_tagline' => array( 'label' => 'Alternativni tagline', 'type' => 'text' ),
		'say'         => array( 'label' => 'Izgovor', 'type' => 'text', 'desc' => 'npr. „lemoré“' ),
		'words'       => array( 'label' => 'Pet reči', 'type' => 'text', 'desc' => 'Odvojene zarezom: citrusan, svež, čaj, svetao, dnevni' ),
		'who'         => array( 'label' => 'Ko ga nosi', 'type' => 'textarea' ),
		'occasion'    => array( 'label' => 'Prilika', 'type' => 'text', 'desc' => 'npr. dan, putovanje, leto' ),
		'season'      => array( 'label' => 'Sezona', 'type' => 'text' ),
		'time'        => array( 'label' => 'Dan / noć (filter)', 'type' => 'select', 'options' => array( 'dan' => 'Dan', 'noć' => 'Noć', 'dan i noć' => 'Dan i noć' ) ),
		'notes_top'   => array( 'label' => 'Note — vrh', 'type' => 'text', 'desc' => 'Odvojene zarezom' ),
		'notes_heart' => array( 'label' => 'Note — srce', 'type' => 'text' ),
		'notes_base'  => array( 'label' => 'Note — baza', 'type' => 'text' ),
		'zodiac'      => array( 'label' => 'Horoskopski znaci', 'type' => 'zodiac' ),
		'alt1'        => array( 'label' => 'Probaj i — 1', 'type' => 'product' ),
		'alt1_why'    => array( 'label' => 'Razlog 1', 'type' => 'text', 'desc' => 'npr. tiši, za veče' ),
		'alt2'        => array( 'label' => 'Probaj i — 2', 'type' => 'product' ),
		'alt2_why'    => array( 'label' => 'Razlog 2', 'type' => 'text' ),
		'c1'          => array( 'label' => 'Paleta — tamna', 'type' => 'color' ),
		'c2'          => array( 'label' => 'Paleta — srednja', 'type' => 'color' ),
		'c3'          => array( 'label' => 'Paleta — svetla / sjaj', 'type' => 'color' ),
		'accent'      => array( 'label' => 'Akcenat', 'type' => 'color' ),
		'light'       => array( 'label' => 'Svetla paleta (tamni tekst na heroju)', 'type' => 'checkbox' ),
		'font'        => array( 'label' => 'Tipografija imena', 'type' => 'select', 'options' => array( 'serif' => 'Serif', 'script' => 'Rukopis', 'caps' => 'Verzal' ) ),
		'hero_img'    => array( 'label' => 'Hero slika (velika, na stranici parfema i početnoj)', 'type' => 'image', 'desc' => 'Prazno = glavna slika proizvoda. Slika sa providnom pozadinom „lebdi“ na boji karaktera; fotografija se prikazuje uokvirena. Demo slika se automatski zamenjuje glavnom slikom čim je promeniš.' ),
		'bottle_img'  => array( 'label' => 'Mala slika (quiz, „Probaj i“, Par set)', 'type' => 'image', 'desc' => 'Prazno = hero slika.' ),
		'world'       => array( 'label' => 'Vizuelni svet (rečenica)', 'type' => 'text' ),
		'quiz_desc'   => array( 'label' => 'Opis u rezultatu quiza', 'type' => 'textarea' ),
		'key'         => array( 'label' => 'Ključ za quiz', 'type' => 'text', 'desc' => 'Ostaviti prazno da se koristi slug proizvoda (terros, secondo, luna, arden, ravon, orren, lemorae, deuxieme).' ),
	);
}

/**
 * Register the meta box.
 */
function webni_character_meta_box() {
	add_meta_box( 'webni-character', 'BP|DL karakter', 'webni_character_meta_box_html', 'product', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'webni_character_meta_box' );

/**
 * Admin assets for the meta box (color picker, media).
 *
 * @param string $hook Current admin page.
 */
function webni_character_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || 'product' !== get_post_type() ) {
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_media();
	wp_enqueue_script( 'webni-admin', WEBNI_THEME_URI . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), WEBNI_THEME_VERSION, true );
	wp_add_inline_style( 'wp-color-picker', '.webni-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 24px}.webni-field{display:grid;gap:4px}.webni-field label{font-weight:600}.webni-field .description{margin:0;color:#646970}.webni-field input[type=text],.webni-field textarea,.webni-field select{width:100%}.webni-field--wide{grid-column:1/-1}.webni-zodiac{display:flex;flex-wrap:wrap;gap:6px 14px}.webni-image{display:flex;gap:10px;align-items:center}.webni-image img{width:60px;height:70px;object-fit:contain;background:#111;border-radius:4px}' );
}
add_action( 'admin_enqueue_scripts', 'webni_character_admin_assets' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Product post.
 */
function webni_character_meta_box_html( $post ) {
	wp_nonce_field( 'webni_character_save', 'webni_character_nonce' );
	$perfumes = get_posts( array( 'post_type' => 'product', 'posts_per_page' => 50, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => array( 'publish', 'draft' ) ) );
	echo '<p class="description">Podaci koje WooCommerce nema: karakter, tagline, note, znaci, paleta. Cena, slike, kategorije i zalihe su u WooCommerce poljima iznad.</p>';
	echo '<div class="webni-fields">';
	foreach ( webni_character_fields() as $key => $f ) {
		$value = get_post_meta( $post->ID, '_bpdl_' . $key, true );
		$id    = 'webni_' . $key;
		$name  = 'webni_character[' . $key . ']';
		$wide  = in_array( $f['type'], array( 'textarea', 'zodiac' ), true ) ? ' webni-field--wide' : '';
		echo '<div class="webni-field' . esc_attr( $wide ) . '">';
		echo '<label for="' . esc_attr( $id ) . '">' . esc_html( $f['label'] ) . '</label>';
		switch ( $f['type'] ) {
			case 'textarea':
				printf( '<textarea id="%s" name="%s" rows="3">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( $value ) );
				break;
			case 'select':
				printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $f['options'] as $ov => $ol ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $ov ), selected( $value, $ov, false ), esc_html( $ol ) );
				}
				echo '</select>';
				break;
			case 'checkbox':
				printf( '<label><input type="checkbox" id="%s" name="%s" value="1"%s> Da</label>', esc_attr( $id ), esc_attr( $name ), checked( (bool) $value, true, false ) );
				break;
			case 'color':
				printf( '<input type="text" class="webni-color" id="%s" name="%s" value="%s" data-default-color="#0a0908">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ) );
				break;
			case 'zodiac':
				echo '<div class="webni-zodiac">';
				$value = (array) $value;
				foreach ( webni_zodiac_signs() as $sign ) {
					printf( '<label><input type="checkbox" name="%s[]" value="%s"%s> %s</label>', esc_attr( $name ), esc_attr( $sign ), checked( in_array( $sign, $value, true ), true, false ), esc_html( $sign ) );
				}
				echo '</div>';
				break;
			case 'product':
				printf( '<select id="%s" name="%s"><option value="">—</option>', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $perfumes as $pp ) {
					if ( $pp->ID === $post->ID ) {
						continue;
					}
					printf( '<option value="%d"%s>%s</option>', (int) $pp->ID, selected( (int) $value, $pp->ID, false ), esc_html( $pp->post_title ) );
				}
				echo '</select>';
				break;
			case 'image':
				$src = $value ? wp_get_attachment_image_url( (int) $value, 'thumbnail' ) : '';
				printf(
					'<div class="webni-image"><img src="%s" alt="" style="%s"><input type="hidden" id="%s" name="%s" value="%d"><button type="button" class="button webni-image-pick">Izaberi</button><button type="button" class="button-link webni-image-clear">Ukloni</button></div>',
					esc_url( $src ),
					$src ? '' : 'display:none',
					esc_attr( $id ),
					esc_attr( $name ),
					(int) $value
				);
				break;
			default:
				printf( '<input type="text" id="%s" name="%s" value="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ) );
		}
		if ( ! empty( $f['desc'] ) ) {
			echo '<p class="description">' . esc_html( $f['desc'] ) . '</p>';
		}
		echo '</div>';
	}
	echo '</div>';
}

/**
 * Save the meta box.
 *
 * @param int $post_id Product ID.
 */
function webni_character_save( $post_id ) {
	if ( ! isset( $_POST['webni_character_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['webni_character_nonce'] ), 'webni_character_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$data = isset( $_POST['webni_character'] ) ? (array) wp_unslash( $_POST['webni_character'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per field below.
	foreach ( webni_character_fields() as $key => $f ) {
		$raw = isset( $data[ $key ] ) ? $data[ $key ] : '';
		switch ( $f['type'] ) {
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				break;
			case 'checkbox':
				$val = $raw ? 1 : 0;
				break;
			case 'color':
				$val = sanitize_hex_color( $raw );
				break;
			case 'zodiac':
				$val = array_values( array_intersect( array_map( 'sanitize_text_field', (array) $raw ), webni_zodiac_signs() ) );
				break;
			case 'product':
			case 'image':
				$val = absint( $raw );
				break;
			case 'select':
				$val = array_key_exists( $raw, $f['options'] ) ? $raw : '';
				break;
			default:
				$val = sanitize_text_field( $raw );
		}
		if ( '' === $val || array() === $val || ( 0 === $val && in_array( $f['type'], array( 'product', 'image' ), true ) ) ) {
			delete_post_meta( $post_id, '_bpdl_' . $key );
		} else {
			update_post_meta( $post_id, '_bpdl_' . $key, $val );
		}
	}
}
add_action( 'save_post_product', 'webni_character_save' );
