<?php
/**
 * Template helpers: options, icons, perfume data, cards, URLs.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Options
 * ---------------------------------------------------------------------- */

/**
 * Theme option (Customizer) with default fallback.
 *
 * @param string $key     Option key without prefix.
 * @param mixed  $default Default value.
 * @return mixed
 */
function webni_option( $key, $default = '' ) {
	$value = get_theme_mod( 'webni_' . $key, null );
	if ( null === $value || '' === $value ) {
		$defaults = webni_option_defaults();
		return isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default;
	}
	return $value;
}

/* -------------------------------------------------------------------------
 * Icons (inline SVG, no icon font)
 * ---------------------------------------------------------------------- */

/**
 * Inline SVG icon.
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string
 */
function webni_icon( $name, $size = 20 ) {
	$paths = array(
		'cart'   => '<path d="M3 4h2l2.4 11.2a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 8H7"/><circle cx="9.5" cy="19.5" r="1.2"/><circle cx="16.5" cy="19.5" r="1.2"/>',
		'user'   => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/>',
		'search' => '<circle cx="11" cy="11" r="6.5"/><path d="M20 20l-4.2-4.2"/>',
		'close'  => '<path d="M6 6l12 12M18 6L6 18"/>',
		'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'minus'  => '<path d="M6 12h12"/>',
		'plus'   => '<path d="M12 6v12M6 12h12"/>',
		'check'  => '<path d="M5 12.5l4.5 4.5L19 7"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="icon icon--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$paths[ $name ] // Static markup defined above.
	);
}

/* -------------------------------------------------------------------------
 * Perfume data (WooCommerce products + "BP|DL karakter" meta)
 * ---------------------------------------------------------------------- */

/**
 * Product IDs of perfumes that carry character data, in menu order.
 *
 * @param int $limit Max number.
 * @return int[]
 */
function webni_get_perfume_ids( $limit = 12 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}
	static $cache = array();
	if ( isset( $cache[ $limit ] ) ) {
		return $cache[ $limit ];
	}
	$ids = get_posts( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'fields'         => 'ids',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array( 'key' => '_bpdl_character', 'value' => '', 'compare' => '!=' ),
		),
		'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array( 'taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => array( 'exclude-from-catalog' ), 'operator' => 'NOT IN' ),
		),
	) );
	$cache[ $limit ] = array_map( 'intval', $ids );
	return $cache[ $limit ];
}

/**
 * Perfume products (WC_Product objects) with character data.
 *
 * @param int $limit Max number.
 * @return WC_Product[]
 */
function webni_get_perfumes( $limit = 12 ) {
	$out = array();
	foreach ( webni_get_perfume_ids( $limit ) as $id ) {
		$product = wc_get_product( $id );
		if ( $product ) {
			$out[] = $product;
		}
	}
	return $out;
}

/**
 * Find a perfume product by its quiz key (meta) or slug.
 *
 * @param string $key Key.
 * @return WC_Product|null
 */
function webni_perfume_by_key( $key ) {
	$key = sanitize_title( $key );
	foreach ( webni_get_perfumes() as $p ) {
		if ( webni_perfume_key( $p ) === $key ) {
			return $p;
		}
	}
	return null;
}

/**
 * Quiz key of a product (meta, falls back to slug).
 *
 * @param WC_Product $product Product.
 * @return string
 */
function webni_perfume_key( $product ) {
	$key = $product->get_meta( '_bpdl_key', true );
	return $key ? sanitize_title( $key ) : $product->get_slug();
}

/**
 * Split a comma separated meta value into a trimmed list.
 *
 * @param string $value Raw value.
 * @return string[]
 */
function webni_list( $value ) {
	return array_values( array_filter( array_map( 'trim', explode( ',', (string) $value ) ) ) );
}

/**
 * Audience of a product from its categories: M (za njega), Ž (za nju), U (unisex).
 *
 * @param WC_Product $product Product.
 * @return string
 */
function webni_perfume_audience( $product ) {
	$slugs = (array) wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
	if ( in_array( 'unisex', $slugs, true ) ) {
		return 'U';
	}
	if ( in_array( 'za-nju', $slugs, true ) ) {
		return 'Ž';
	}
	if ( in_array( 'za-njega', $slugs, true ) ) {
		return 'M';
	}
	// No audience category (sets, accessories): perfumes with character data default to "for him".
	return '' !== (string) $product->get_meta( '_bpdl_character', true ) ? 'M' : '';
}

/**
 * All character data of a perfume as one array.
 *
 * @param WC_Product|int $product Product or ID.
 * @return array|null
 */
function webni_perfume( $product ) {
	$product = is_numeric( $product ) ? wc_get_product( $product ) : $product;
	if ( ! $product ) {
		return null;
	}
	$id = $product->get_id();
	$m  = function ( $k, $d = '' ) use ( $product ) {
		$v = $product->get_meta( '_bpdl_' . $k, true );
		return ( '' === $v || null === $v ) ? $d : $v;
	};
	$audience = webni_perfume_audience( $product );
	$labels   = array( 'M' => __( 'Za njega', 'webni-custom-version-1' ), 'Ž' => __( 'Za nju', 'webni-custom-version-1' ), 'U' => __( 'Unisex', 'webni-custom-version-1' ) );
	if ( '' === $audience ) {
		$terms          = wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'names' ) );
		$labels['']     = ! is_wp_error( $terms ) && $terms ? $terms[0] : '';
	}
	$featured = (int) $product->get_image_id();
	$hero_id  = (int) $m( 'hero_img' );
	$bottle   = (int) $m( 'bottle_img' );
	// A picture the shop uploaded replaces the demo cutouts everywhere: the imported meta would
	// otherwise keep showing the demo bottle in the hero, the quiz result and the "Probaj i" cards.
	if ( $featured && ! webni_is_demo_image( $featured ) ) {
		if ( ! $hero_id || webni_is_demo_image( $hero_id ) ) {
			$hero_id = $featured;
		}
		if ( ! $bottle || webni_is_demo_image( $bottle ) ) {
			$bottle = $featured;
		}
	}
	$hero_id = $hero_id ?: $featured;
	$bottle  = $bottle ?: $hero_id;

	return array(
		'id'        => $id,
		'key'       => webni_perfume_key( $product ),
		'slug'      => $product->get_slug(),
		'n'         => (int) $product->get_menu_order(),
		'name'      => $product->get_name(),
		'url'       => get_permalink( $id ),
		'character' => $m( 'character' ),
		'tagline'   => $m( 'tagline' ),
		'alt'       => $m( 'alt_tagline' ),
		'say'       => $m( 'say' ),
		'words'     => webni_list( $m( 'words' ) ),
		'who'       => $m( 'who' ),
		'short'     => wp_strip_all_tags( $product->get_short_description() ),
		'long'      => wp_strip_all_tags( $product->get_description() ),
		'occasion'  => $m( 'occasion' ),
		'season'    => $m( 'season' ),
		'time'      => $m( 'time', '' !== $audience ? 'dan' : '' ),
		'notes'     => array( 'top' => webni_list( $m( 'notes_top' ) ), 'heart' => webni_list( $m( 'notes_heart' ) ), 'base' => webni_list( $m( 'notes_base' ) ) ),
		'zodiac'    => array_map( 'sanitize_text_field', (array) $m( 'zodiac', array() ) ),
		'alts'      => array_values( array_filter( array( (int) $m( 'alt1' ), (int) $m( 'alt2' ) ) ) ),
		'alts_why'  => array( $m( 'alt1_why' ), $m( 'alt2_why' ) ),
		'palette'   => array(
			'c1'     => sanitize_hex_color( $m( 'c1', '#0a0908' ) ) ?: '#0a0908',
			'c2'     => sanitize_hex_color( $m( 'c2', '#2b2312' ) ) ?: '#2b2312',
			'c3'     => sanitize_hex_color( $m( 'c3', '#d4af37' ) ) ?: '#d4af37',
			'accent' => sanitize_hex_color( $m( 'accent', '#d4af37' ) ) ?: '#d4af37',
			'light'  => (bool) $m( 'light', 0 ),
		),
		'font'      => $m( 'font', 'serif' ),
		'world'     => $m( 'world' ),
		'quiz_desc' => $m( 'quiz_desc' ),
		'cat'       => $audience,
		'cat_label' => $labels[ $audience ],
		'image_id'  => $featured,
		'hero_id'   => $hero_id,
		'bottle_id' => $bottle,
		'cutout'    => array(
			'card'   => webni_image_is_cutout( $featured ),
			'hero'   => webni_image_is_cutout( $hero_id ),
			'bottle' => webni_image_is_cutout( $bottle ),
		),
		'price'     => $product->get_price_html(),
		'in_stock'  => $product->is_in_stock(),
	);
}

/**
 * Perfume data in the shape the JavaScript expects (with image URLs).
 *
 * @param WC_Product $product Product.
 * @return array
 */
function webni_perfume_js( $product ) {
	$d = webni_perfume( $product );
	if ( ! $d ) {
		return array();
	}
	$img = function ( $id, $size ) {
		$src = $id ? wp_get_attachment_image_url( $id, $size ) : '';
		return $src ? $src : '';
	};
	return array(
		'id'        => $d['id'],
		'key'       => $d['key'],
		'slug'      => $d['slug'],
		'n'         => $d['n'],
		'name'      => $d['name'],
		'url'       => $d['url'],
		'character' => $d['character'],
		'tagline'   => $d['tagline'],
		'words'     => $d['words'],
		'cat'       => $d['cat'],
		'catLabel'  => $d['cat_label'],
		'time'      => $d['time'],
		'zodiac'    => $d['zodiac'],
		'alts'      => array_map( function ( $id ) { $p = wc_get_product( $id ); return $p ? webni_perfume_key( $p ) : ''; }, $d['alts'] ),
		'quizDesc'  => $d['quiz_desc'],
		'short'     => $d['short'],
		'palette'   => $d['palette'],
		'font'      => $d['font'],
		'images'    => array(
			'card'   => $img( $d['image_id'], 'webni-card' ),
			'hero'   => $img( $d['hero_id'], 'webni-hero' ),
			'bottle' => $img( $d['bottle_id'], 'webni-card' ),
		),
		'cutout'    => $d['cutout'],
	);
}

/**
 * Zodiac table: sign data plus the perfume for him / for her derived from product meta.
 *
 * @return array[]
 */
function webni_zodiac_table() {
	$signs = array(
		array( 'sign' => 'Ovan', 'dates' => '21.3 – 19.4', 'trait' => 'prvi kreće, ne čeka dozvolu', 'acc' => 'Ovna', 'rel' => 'koji prvi kreće' ),
		array( 'sign' => 'Bik', 'dates' => '20.4 – 20.5', 'trait' => 'uživa polako, voli kvalitet koji traje', 'acc' => 'Bika', 'rel' => 'koji uživa polako' ),
		array( 'sign' => 'Blizanci', 'dates' => '21.5 – 20.6', 'trait' => 'radoznao, dva raspoloženja dnevno', 'acc' => 'Blizance', 'rel' => 'koji imaju dva raspoloženja dnevno' ),
		array( 'sign' => 'Rak', 'dates' => '21.6 – 22.7', 'trait' => 'topao, veran, pamti sve', 'acc' => 'Raka', 'rel' => 'koji pamti sve' ),
		array( 'sign' => 'Lav', 'dates' => '23.7 – 22.8', 'trait' => 'voli scenu i zna da je zasluži', 'acc' => 'Lava', 'rel' => 'koji voli scenu' ),
		array( 'sign' => 'Devica', 'dates' => '23.8 – 22.9', 'trait' => 'precizan, čist, bez viška', 'acc' => 'Devicu', 'rel' => 'koja ne voli višak' ),
		array( 'sign' => 'Vaga', 'dates' => '23.9 – 22.10', 'trait' => 'bira lepo, uravnoteženo, šarmantno', 'acc' => 'Vagu', 'rel' => 'koja bira lepo' ),
		array( 'sign' => 'Škorpija', 'dates' => '23.10 – 21.11', 'trait' => 'intenzivan, magnetičan, ne zaboravlja', 'acc' => 'Škorpiju', 'rel' => 'koja ne zaboravlja' ),
		array( 'sign' => 'Strelac', 'dates' => '22.11 – 21.12', 'trait' => 'slobodan, putuje, optimista', 'acc' => 'Strelca', 'rel' => 'koji uvek putuje' ),
		array( 'sign' => 'Jarac', 'dates' => '22.12 – 19.1', 'trait' => 'disciplinovan, ambiciozan, klasičan', 'acc' => 'Jarca', 'rel' => 'koji drži red' ),
		array( 'sign' => 'Vodolija', 'dates' => '20.1 – 18.2', 'trait' => 'drugačiji namerno, moderan', 'acc' => 'Vodoliju', 'rel' => 'koja je drugačija namerno' ),
		array( 'sign' => 'Ribe', 'dates' => '19.2 – 20.3', 'trait' => 'sanjar, romantičan, intuitivan', 'acc' => 'Ribe', 'rel' => 'koje sanjaju' ),
	);
	$perfumes = array_map( 'webni_perfume', webni_get_perfumes() );
	foreach ( $signs as &$s ) {
		$s['him'] = '';
		$s['her'] = '';
		foreach ( $perfumes as $p ) {
			if ( ! in_array( $s['sign'], $p['zodiac'], true ) ) {
				continue;
			}
			if ( '' === $s['him'] && in_array( $p['cat'], array( 'M', 'U' ), true ) ) {
				$s['him'] = $p['key'];
			}
			if ( '' === $s['her'] && in_array( $p['cat'], array( 'Ž', 'U' ), true ) ) {
				$s['her'] = $p['key'];
			}
		}
	}
	return $signs;
}

/**
 * All zodiac sign names.
 *
 * @return string[]
 */
function webni_zodiac_signs() {
	return array_column( webni_zodiac_table(), 'sign' );
}

/* -------------------------------------------------------------------------
 * URLs
 * ---------------------------------------------------------------------- */

/**
 * Shop (Parfemi) URL.
 *
 * @return string
 */
function webni_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( 'shop' );
		if ( $url ) {
			return $url;
		}
	}
	return home_url( '/parfemi/' );
}

/**
 * URL of the discovery set product (Customizer), falls back to the shop.
 *
 * @return string
 */
function webni_discovery_url() {
	$id = (int) webni_option( 'discovery_product', 0 );
	if ( $id && 'publish' === get_post_status( $id ) ) {
		return get_permalink( $id );
	}
	return webni_shop_url();
}

/**
 * Home hero mode: 'image' (one large static picture) or 'animated' (WebGL mist cycling the characters).
 *
 * @return string
 */
function webni_hero_mode() {
	return 'animated' === webni_option( 'hero_mode', 'image' ) ? 'animated' : 'image';
}

/**
 * Attachment used by the static hero: the Customizer image, otherwise the first perfume's featured image.
 *
 * @return array{id:int,fallback:bool,cutout:bool}
 */
function webni_hero_image() {
	$id = (int) webni_option( 'hero_image', 0 );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return array( 'id' => $id, 'fallback' => false, 'cutout' => webni_image_is_cutout( $id ) );
	}
	$ids = function_exists( 'wc_get_product' ) ? webni_get_perfume_ids() : array();
	foreach ( $ids as $pid ) {
		$d = webni_perfume( $pid );
		if ( $d && $d['hero_id'] ) {
			return array( 'id' => (int) $d['hero_id'], 'fallback' => true, 'cutout' => (bool) $d['cutout']['hero'] );
		}
	}
	return array( 'id' => 0, 'fallback' => true, 'cutout' => false );
}

/**
 * Hero picture markup: the large image, with the optional portrait image served to phones.
 *
 * @param int    $id    Attachment ID.
 * @param string $pos   object-position keyword.
 * @param string $sizes Sizes attribute for the large image.
 * @return string
 */
function webni_hero_picture( $id, $pos = 'center', $sizes = '100vw' ) {
	$img = wp_get_attachment_image( $id, 'full', false, array(
		'class'         => 'hero__img',
		'style'         => 'object-position:' . $pos,
		'loading'       => 'eager',
		'fetchpriority' => 'high',
		'decoding'      => 'async',
		'sizes'         => $sizes,
	) );
	if ( ! $img ) {
		return '';
	}
	$mobile = (int) webni_option( 'hero_image_mobile', 0 );
	if ( ! $mobile || ! wp_attachment_is_image( $mobile ) ) {
		return $img;
	}
	$srcset = wp_get_attachment_image_srcset( $mobile, 'full' );
	$src    = wp_get_attachment_image_url( $mobile, 'full' );
	if ( ! $src ) {
		return $img;
	}
	return '<picture><source media="(max-width: 900px)" srcset="' . esc_attr( $srcset ? $srcset : $src ) . '" sizes="100vw">' . $img . '</picture>';
}

/**
 * The Par set product (Customizer → Poklon), only when published and purchasable.
 *
 * @return WC_Product|null
 */
function webni_pair_product() {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}
	$id = (int) webni_option( 'pair_product', 0 );
	if ( ! $id || 'publish' !== get_post_status( $id ) ) {
		return null;
	}
	$product = wc_get_product( $id );
	return $product ? $product : null;
}

/**
 * The perfumes that make up the Par set: its WooCommerce cross-sells (Products → Linked products).
 *
 * @return WC_Product[]
 */
function webni_pair_members() {
	$pair = webni_pair_product();
	if ( ! $pair ) {
		return array();
	}
	$members = array();
	foreach ( $pair->get_cross_sell_ids() as $id ) {
		$product = wc_get_product( $id );
		if ( $product && 'publish' === $product->get_status() ) {
			$members[] = $product;
		}
	}
	return $members;
}

/**
 * Whether a product is one of the perfumes sold in the Par set.
 *
 * @param int $product_id Product ID.
 * @return bool
 */
function webni_is_pair_member( $product_id ) {
	foreach ( webni_pair_members() as $member ) {
		if ( (int) $member->get_id() === (int) $product_id ) {
			return true;
		}
	}
	return false;
}

/* -------------------------------------------------------------------------
 * Output helpers
 * ---------------------------------------------------------------------- */

/**
 * Product card used on the home page and in the shop loop.
 *
 * @param WC_Product $product Product.
 * @param int        $index   Position (for reveal delay).
 * @param bool       $tag     Wrap in <li> (WooCommerce loop) instead of <div>.
 */
function webni_product_card( $product, $index = 0, $tag = false ) {
	$d     = webni_perfume( $product );
	$key   = $d['character'] ? $d['key'] : '';
	$el    = $tag ? 'li' : 'div';
	$style = sprintf( '--pc1:%s;--pc2:%s;--pc3:%s;--pacc:%s;transition-delay:%dms', $d['palette']['c1'], $d['palette']['c2'], $d['palette']['c3'], $d['palette']['accent'], ( $index % 8 ) * 70 );
	$img   = $d['image_id'] ? wp_get_attachment_image( $d['image_id'], 'webni-card', false, array( 'class' => 'pack pack--set', 'loading' => $index < 4 ? 'eager' : 'lazy' ) ) : '';
	?>
	<<?php echo $el; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="card reveal product<?php echo $d['in_stock'] ? '' : ' is-out'; ?>" data-cat="<?php echo esc_attr( $d['cat'] ); ?>" data-time="<?php echo esc_attr( $d['time'] ); ?>" style="<?php echo esc_attr( $style ); ?>">
		<a class="card__link" href="<?php echo esc_url( $d['url'] ); ?>">
			<div class="card__visual"><div><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php if ( $product->is_on_sale() ) : ?><span class="card__badge"><?php esc_html_e( 'Akcija', 'webni-custom-version-1' ); ?></span><?php endif; ?>
				<?php if ( ! $d['in_stock'] ) : ?><span class="card__badge card__badge--out"><?php esc_html_e( 'Rasprodato', 'webni-custom-version-1' ); ?></span><?php endif; ?>
			</div>
			<div class="card__body">
				<?php if ( $d['n'] ) : ?><span class="card__n"><?php echo esc_html( str_pad( (string) $d['n'], 2, '0', STR_PAD_LEFT ) ); ?></span><?php endif; ?>
				<h3 class="card__title"><?php echo webni_text( $d['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				<?php if ( $d['character'] ) : ?><p class="card__char"><?php echo esc_html( $d['character'] ); ?></p><?php endif; ?>
				<?php if ( $d['tagline'] ) : ?><p class="card__tag"><?php echo webni_text( $d['tagline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p><?php endif; ?>
				<?php if ( $d['words'] ) : ?><p class="card__words"><?php echo esc_html( implode( ' · ', $d['words'] ) ); ?></p><?php endif; ?>
				<p class="card__price"><b><?php echo wp_kses_post( $d['price'] ); ?></b><span><?php echo esc_html( $d['cat_label'] ); ?></span></p>
			</div>
		</a>
		<?php if ( $product->is_purchasable() && $product->is_in_stock() && $product->is_type( 'simple' ) ) : ?>
			<div class="card__add"><?php webni_add_to_cart_button( $product, 'btn btn--sm btn--solid' ); ?></div>
		<?php endif; ?>
	</<?php echo $el; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
}

/**
 * WooCommerce AJAX "U korpu" button for a simple product, usable outside the shop loop
 * (home collection, rails, Par set band). WooCommerce reads the global $product, so it is
 * set for the call and restored afterwards.
 *
 * @param WC_Product $product Product.
 * @param string     $class   Button classes.
 */
function webni_add_to_cart_button( $product, $class = 'btn btn--solid' ) {
	if ( ! function_exists( 'woocommerce_template_loop_add_to_cart' ) || ! $product instanceof WC_Product ) {
		return;
	}
	$previous           = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
	$GLOBALS['product'] = $product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	woocommerce_template_loop_add_to_cart( array( 'class' => $class . ' add_to_cart_button ajax_add_to_cart product_type_simple' ) );
	$GLOBALS['product'] = $previous; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

/**
 * Inline style string with a perfume palette.
 *
 * @param array $d Perfume data.
 * @return string
 */
function webni_palette_style( $d ) {
	return sprintf( '--pc1:%s;--pc2:%s;--pc3:%s;--pacc:%s', $d['palette']['c1'], $d['palette']['c2'], $d['palette']['c3'], $d['palette']['accent'] );
}

/**
 * Zodiac sign names in the accusative joined with "i".
 *
 * @param string[] $signs Signs.
 * @return string
 */
function webni_zodiac_accusative( $signs ) {
	$table = array_column( webni_zodiac_table(), 'acc', 'sign' );
	$acc   = array();
	foreach ( $signs as $s ) {
		if ( isset( $table[ $s ] ) ) {
			$acc[] = $table[ $s ];
		}
	}
	if ( ! $acc ) {
		return '';
	}
	if ( 1 === count( $acc ) ) {
		return $acc[0];
	}
	$last = array_pop( $acc );
	return implode( ', ', $acc ) . ' i ' . $last;
}

/**
 * Hero image markup for a perfume (bottle on the tube).
 *
 * @param array  $d     Perfume data.
 * @param string $kind  hero|bottle|card.
 * @param array  $attrs Extra attributes.
 * @return string
 */
function webni_perfume_image( $d, $kind = 'hero', $attrs = array() ) {
	$map  = array( 'hero' => array( $d['hero_id'], 'webni-hero' ), 'bottle' => array( $d['bottle_id'], 'webni-card' ), 'card' => array( $d['image_id'], 'webni-card' ) );
	$pick = $map[ $kind ];
	if ( ! $pick[0] ) {
		return '';
	}
	$attrs = wp_parse_args( $attrs, array( 'class' => 'pack pack--' . $kind, 'loading' => 'lazy' ) );
	if ( empty( $d['cutout'][ $kind ] ) ) {
		$attrs['class'] .= ' pack--photo'; // Rectangular photo: framed and cropped instead of floating.
	}
	return wp_get_attachment_image( $pick[0], $pick[1], false, $attrs );
}

/**
 * Whether an attachment is one of the theme's demo pictures (imported from assets/images).
 *
 * @param int $id Attachment ID.
 * @return bool
 */
function webni_is_demo_image( $id ) {
	return $id > 0 && metadata_exists( 'post', (int) $id, '_webni_demo_image' );
}

/**
 * Whether an image has a transparent background (a cutout that can float on the palette) or is a
 * rectangular photo. Decided once per attachment from the alpha of its corner pixels and cached in
 * attachment meta; the demo cutouts are known. Filter: webni_image_is_cutout.
 *
 * @param int $id Attachment ID.
 * @return bool
 */
function webni_image_is_cutout( $id ) {
	$id = (int) $id;
	if ( ! $id ) {
		return false;
	}
	$cached = get_post_meta( $id, '_webni_cutout', true );
	if ( '' !== $cached && null !== $cached ) {
		return (bool) apply_filters( 'webni_image_is_cutout', (bool) (int) $cached, $id );
	}
	$cutout = webni_is_demo_image( $id ) ? true : webni_detect_cutout( $id );
	update_post_meta( $id, '_webni_cutout', $cutout ? 1 : 0 );
	return (bool) apply_filters( 'webni_image_is_cutout', $cutout, $id );
}

/**
 * Corner-pixel alpha test on the smallest generated size (GD); falls back to the file header.
 *
 * @param int $id Attachment ID.
 * @return bool
 */
function webni_detect_cutout( $id ) {
	$mime = get_post_mime_type( $id );
	if ( ! in_array( $mime, array( 'image/png', 'image/webp', 'image/gif' ), true ) ) {
		return false; // JPEG and the rest have no alpha channel.
	}
	$file = get_attached_file( $id );
	if ( ! $file || ! is_readable( $file ) ) {
		return false;
	}
	// Prefer the thumbnail: same transparency, a fraction of the memory.
	$meta = wp_get_attachment_metadata( $id );
	if ( ! empty( $meta['sizes']['thumbnail']['file'] ) ) {
		$thumb = path_join( dirname( $file ), $meta['sizes']['thumbnail']['file'] );
		if ( is_readable( $thumb ) && get_post_mime_type( $id ) === ( $meta['sizes']['thumbnail']['mime-type'] ?? $mime ) ) {
			$file = $thumb;
		}
	}
	if ( function_exists( 'imagecreatefrompng' ) ) {
		$im = false;
		if ( 'image/png' === $mime ) {
			$im = @imagecreatefrompng( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		} elseif ( 'image/webp' === $mime && function_exists( 'imagecreatefromwebp' ) ) {
			$im = @imagecreatefromwebp( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		} elseif ( 'image/gif' === $mime && function_exists( 'imagecreatefromgif' ) ) {
			$im = @imagecreatefromgif( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		if ( $im ) {
			$w = imagesx( $im );
			$h = imagesy( $im );
			$transparent = 0;
			foreach ( array( array( 1, 1 ), array( $w - 2, 1 ), array( 1, $h - 2 ), array( $w - 2, $h - 2 ) ) as $pt ) {
				$x     = max( 0, min( $w - 1, $pt[0] ) );
				$y     = max( 0, min( $h - 1, $pt[1] ) );
				$alpha = ( imagecolorat( $im, $x, $y ) >> 24 ) & 0x7F; // 0 opaque … 127 transparent.
				if ( $alpha > 64 ) {
					$transparent++;
				}
			}
			imagedestroy( $im );
			return $transparent >= 3;
		}
	}
	// No GD: trust the header (PNG colour type with alpha, WebP alpha flag).
	$fh = fopen( $file, 'rb' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	if ( ! $fh ) {
		return false;
	}
	$head = (string) fread( $fh, 64 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fread
	fclose( $fh ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	if ( 0 === strpos( $head, "\x89PNG" ) && strlen( $head ) > 25 ) {
		return in_array( ord( $head[25] ), array( 4, 6 ), true );
	}
	if ( 'RIFF' === substr( $head, 0, 4 ) && 'WEBP' === substr( $head, 8, 4 ) ) {
		$chunk = substr( $head, 12, 4 );
		if ( 'VP8X' === $chunk ) {
			return (bool) ( ord( $head[20] ) & 0x10 );
		}
		if ( 'VP8L' === $chunk && strlen( $head ) > 25 ) {
			$bits = ord( $head[22] ) | ( ord( $head[23] ) << 8 ) | ( ord( $head[24] ) << 16 ) | ( ord( $head[25] ) << 24 );
			return (bool) ( ( $bits >> 28 ) & 1 );
		}
	}
	return false;
}

/**
 * Single comment markup (wp_list_comments callback).
 *
 * @param WP_Comment $comment Comment.
 * @param array      $args    Args.
 * @param int        $depth   Depth.
 */
function webni_comment_html( $comment, $args, $depth ) {
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
		<div class="comment-body">
			<div class="comment-meta">
				<b><?php echo esc_html( get_comment_author( $comment ) ); ?></b>
				<time datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>"><?php echo esc_html( get_comment_date( '', $comment ) ); ?></time>
				<?php if ( '0' === $comment->comment_approved ) : ?><span><?php esc_html_e( 'Čeka odobrenje', 'webni-custom-version-1' ); ?></span><?php endif; ?>
			</div>
			<div class="comment-content"><?php comment_text( $comment ); ?></div>
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __( 'Odgovori', 'webni-custom-version-1' ) ) ), $comment ); ?>
		</div>
	<?php
}

/**
 * Escape text for output and wrap dashes so they render in the body font
 * (the display font's hairline dashes disappear at large sizes).
 *
 * @param string $text Plain text.
 * @return string HTML.
 */
function webni_text( $text ) {
	return webni_dashes( esc_html( (string) $text ) );
}

/**
 * Wrap em/en dashes in already-escaped HTML.
 *
 * @param string $html Escaped HTML.
 * @return string
 */
function webni_dashes( $html ) {
	static $map = null;
	if ( null === $map ) {
		$map = array();
		foreach ( array( '—', '–', '+', '×', '−', '=' ) as $ch ) {
			$map[ $ch ] = '<span class="dash">' . $ch . '</span>';
		}
	}
	return strtr( (string) $html, $map );
}
