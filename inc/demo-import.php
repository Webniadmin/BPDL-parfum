<?php
/**
 * Admin tools page: demo product import, store defaults, Serbian URLs, status checks.
 *
 * WooCommerce → BP|DL alati. Every action is a POST with a nonce and requires the
 * "manage_woocommerce" capability. The import is idempotent and non-destructive: products
 * are matched by slug, existing ones only get their empty fields filled, images are
 * uploaded once and reused.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the tools page under WooCommerce.
 */
function webni_tools_menu() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	add_submenu_page( 'woocommerce', 'BP|DL alati', 'BP|DL alati', 'manage_woocommerce', 'webni-tools', 'webni_tools_page' );
}
add_action( 'admin_menu', 'webni_tools_menu', 99 );

/**
 * Handle the tool actions (POST + nonce + capability).
 */
function webni_tools_handle() {
	if ( ! isset( $_POST['webni_tool'] ) || ! is_admin() ) {
		return;
	}
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'webni-custom-version-1' ) );
	}
	check_admin_referer( 'webni_tools', 'webni_tools_nonce' );

	$tool    = sanitize_key( wp_unslash( $_POST['webni_tool'] ) );
	$message = '';

	switch ( $tool ) {
		case 'import_products':
			$result  = webni_import_demo_products();
			$message = sprintf( 'Demo catalogue imported: %d products created, %d existing products kept (only empty fields filled), %d images linked (uploaded once, reused afterwards).', $result['created'], $result['updated'], $result['images'] );
			break;
		case 'store_defaults':
			webni_store_defaults( true );
			$message = 'Store defaults applied (RSD, Srbija, cash on delivery, Serbian e-mails, shipping zone).';
			break;
		case 'serbian_urls':
			webni_apply_serbian_urls( true );
			update_option( 'webni_flush_rewrite', 1 );
			webni_maybe_flush_rewrite();
			$message = get_option( 'permalink_structure' ) ? 'Serbian URL structure applied, rewrite rules flushed and .htaccess written.' : 'Could not write the rewrite block to .htaccess — pretty URLs stay off. See the notice for the block to paste.';
			break;
		case 'create_pages':
			$n = webni_create_pages();
			webni_create_menus();
			update_option( 'webni_flush_rewrite', 1 );
			$message = sprintf( 'Pages checked: %d created. Menus assigned where empty.', $n );
			break;
		case 'shipping':
			$r       = webni_default_shipping();
			$message = sprintf( 'Shipping zone for Serbia: %s; added: %s.', $r['zone'], $r['added'] ? implode( ', ', $r['added'] ) : 'nothing (rates were already there)' );
			break;
		case 'gf_forms':
			$r       = webni_gf_create_forms();
			$message = $r['error'] && ! $r['created'] ? 'Forms not created: ' . $r['error'] : sprintf( 'Gravity Forms: created %s; already set %s.', $r['created'] ? implode( ', ', $r['created'] ) : 'none', $r['kept'] ? implode( ', ', $r['kept'] ) : 'none' );
			break;
		case 'merchant_pages':
			$n       = webni_link_merchant_pages();
			$message = sprintf( 'Merchant placeholders linked on %d page(s). Values come from Customize → BP|DL Parfum → Podaci o trgovcu.', $n );
			break;
		default:
			return;
	}

	set_transient( 'webni_tools_message_' . get_current_user_id(), $message, 60 );
	wp_safe_redirect( add_query_arg( array( 'page' => 'webni-tools', 'done' => $tool ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_init', 'webni_tools_handle' );

/**
 * Render the tools page.
 */
function webni_tools_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}
	$message = get_transient( 'webni_tools_message_' . get_current_user_id() );
	if ( $message ) {
		delete_transient( 'webni_tools_message_' . get_current_user_id() );
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
	}

	$perfume_count = count( webni_get_perfume_ids( 50 ) );
	$gf            = webni_gf_active();
	$checks        = array(
		array( 'WooCommerce', class_exists( 'WooCommerce' ) ? 'active ' . WC()->version : 'missing', class_exists( 'WooCommerce' ) ),
		array( 'Gravity Forms', $gf ? 'active' : 'inactive — forms are hidden for visitors', $gf ),
		array( 'Newsletter form ID', (int) webni_option( 'newsletter_gf_id', 0 ) ?: 'not set', (bool) webni_option( 'newsletter_gf_id', 0 ) ),
		array( 'Quiz e-mail form ID', (int) webni_option( 'quiz_gf_id', 0 ) ?: 'not set (optional)', true ),
		array( 'Contact form ID', (int) webni_option( 'contact_gf_id', 0 ) ?: 'not set', (bool) webni_option( 'contact_gf_id', 0 ) ),
		array( 'Delivery in Serbia', ( function () {
			$f = webni_shipping_figures();
			return ( null === $f['cost'] ? 'no paid rate' : $f['cost'] . ' RSD' ) . ' · ' . ( null === $f['free'] ? 'no free shipping' : 'free from ' . $f['free'] . ' RSD' );
		} )(), null !== webni_shipping_figures()['cost'] ),
		array( 'Merchant data (naziv, adresa, PIB, MB)', webni_merchant_line() ? webni_merchant_line() : 'not filled in — legal pages show [placeholders]', '' !== webni_merchant( 'naziv' ) && '' !== webni_merchant( 'pib' ) && '' !== webni_merchant( 'mb' ) ),
		array( 'Card / 3-D Secure logos', absint( webni_option( 'payment_logos', 0 ) ) ? 'set' : 'not set (required by the bank for card payments)', (bool) absint( webni_option( 'payment_logos', 0 ) ) ),
		array( 'Perfume products with character data', $perfume_count, $perfume_count >= 8 ),
		array( 'Shop page', get_option( 'woocommerce_shop_page_id' ) ? get_permalink( (int) get_option( 'woocommerce_shop_page_id' ) ) : 'not set', (bool) get_option( 'woocommerce_shop_page_id' ) ),
		array( 'Currency', get_option( 'woocommerce_currency' ), 'RSD' === get_option( 'woocommerce_currency' ) ),
		array( 'Product base', ( (array) get_option( 'woocommerce_permalinks' ) )['product_base'] ?? '', '/parfem' === ( ( (array) get_option( 'woocommerce_permalinks' ) )['product_base'] ?? '' ) ),
		array( 'Discovery set product (Customizer)', (int) webni_option( 'discovery_product', 0 ) ?: 'not set', (bool) webni_option( 'discovery_product', 0 ) ),
		array( 'Store visibility', 'yes' === get_option( 'woocommerce_coming_soon' ) ? 'coming soon (visitors see a placeholder)' : 'live', 'yes' !== get_option( 'woocommerce_coming_soon' ) ),
		array( 'Pretty permalinks', get_option( 'permalink_structure' ) ? ( webni_pretty_permalinks_ready() ? 'on, rewrite rules in place' : 'on, but .htaccess has no WordPress block — pretty URLs and /wp-json/ will 404' ) : 'off (plain URLs) — see the notice above', (bool) get_option( 'permalink_structure' ) && webni_pretty_permalinks_ready() ),
	);
	?>
	<div class="wrap">
		<h1>BP|DL alati</h1>
		<p>Theme tools for the BP|DL Parfum store. Products, prices and stock are managed in WooCommerce; texts in <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=webni_panel' ) ); ?>">Appearance → Customize → BP|DL Parfum</a>.</p>

		<h2>Status</h2>
		<table class="widefat striped" style="max-width:820px">
			<tbody>
			<?php foreach ( $checks as $c ) : ?>
				<tr>
					<td style="width:45%"><?php echo esc_html( $c[0] ); ?></td>
					<td><?php echo $c[2] ? '<span style="color:#1a7f37">●</span> ' : '<span style="color:#b35900">●</span> '; ?><?php echo esc_html( (string) $c[1] ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2>Actions</h2>
		<form method="post" style="display:grid;gap:14px;max-width:820px">
			<?php wp_nonce_field( 'webni_tools', 'webni_tools_nonce' ); ?>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Učitaj demo proizvode</h3>
				<p>Creates the eight perfumes with their character data, palettes, notes, zodiac signs, images, categories (Za njega, Za nju, Unisex, Setovi), the Discovery set and the Par set. Products that already exist (same slug) are left as they are — your names, prices, texts, images and character data are never overwritten; only fields that are still empty get filled in. Nothing is deleted.</p>
				<button class="button button-primary" name="webni_tool" value="import_products">Učitaj demo proizvode</button>
			</div>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Store defaults</h3>
				<p>RSD with "5.900 RSD" format, Serbia as the only country, cash on delivery, Serbian e-mail subjects, one shipping zone (390 RSD, free above 9.000 RSD), reviews off, guest checkout on. Overwrites those options.</p>
				<button class="button" name="webni_tool" value="store_defaults">Apply store defaults</button>
			</div>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Serbian URLs</h3>
				<p>/parfem/, /kategorija-parfema/, /korpa/, /placanje/, /moj-nalog/porudzbine/ … Re-applies the Serbian bases and endpoints and flushes rewrite rules.</p>
				<button class="button" name="webni_tool" value="serbian_urls">Apply Serbian URLs</button>
			</div>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Pages &amp; menus</h3>
				<p>Creates any missing page (Početna, Parfemi, Korpa, Plaćanje, Moj nalog, Pomoć, O nama, Kontakt, legal drafts) and assigns menus to empty locations.</p>
				<button class="button" name="webni_tool" value="create_pages">Check pages &amp; menus</button>
			</div>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Dostava za Srbiju</h3>
				<p>Makes sure a shipping zone covers Serbia with "Kurirska dostava" 390 RSD and "Besplatna dostava" from 9.000 RSD. Only missing methods are added; existing rates (or a courier plugin's method) are kept. Prices can be changed later in WooCommerce → Settings → Shipping.</p>
				<button class="button" name="webni_tool" value="shipping">Podesi dostavu</button>
			</div>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Napravi forme (Gravity Forms)</h3>
				<p>Creates the Kontakt, Newsletter and Quiz e-mail forms in Gravity Forms (Serbian labels, honeypot, notification to the contact e-mail) and fills in their IDs in the Customizer. Forms that are already set and exist are kept. Requires Gravity Forms to be active.</p>
				<button class="button" name="webni_tool" value="gf_forms" <?php disabled( ! $gf ); ?>>Napravi forme</button>
			</div>
			<div class="card" style="max-width:none;padding:16px 20px">
				<h3 style="margin-top:0">Poveži podatke o trgovcu</h3>
				<p>Replaces the [placeholders] still left in Kontakt, Podaci o trgovcu, Uslovi kupovine, Politika privatnosti and Reklamacije with live values from Customize → BP|DL Parfum → Podaci o trgovcu (delivery price and threshold from the WooCommerce shipping zone). Text you already wrote is not touched. Runs once automatically after the 1.1.6 update.</p>
				<button class="button" name="webni_tool" value="merchant_pages">Poveži podatke</button>
			</div>
		</form>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Demo import
 * ---------------------------------------------------------------------- */

/**
 * Upload a theme image into the media library once (matched by a meta flag).
 *
 * @param string $file File name inside assets/images.
 * @param string $title Attachment title.
 * @param int    $parent Parent post.
 * @return int Attachment ID or 0.
 */
function webni_import_image( $file, $title, $parent = 0 ) {
	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_key'       => '_webni_demo_image', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'     => $file, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
	) );
	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = WEBNI_THEME_DIR . '/assets/images/' . $file;
	if ( ! file_exists( $source ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $file );
	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), $parent, $title );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}
	update_post_meta( $id, '_webni_demo_image', $file );
	update_post_meta( $id, '_wp_attachment_image_alt', $title );
	return (int) $id;
}

/**
 * Create one product from the demo data, or fill the gaps of an existing one.
 *
 * Existing products (matched by slug) are never overwritten: the shop's own name, prices,
 * descriptions, images, categories and character data stay as they are and only fields
 * that are still empty are filled in. New products get the complete demo record.
 *
 * @param array $data  Product data.
 * @param array $cats  Category term IDs by slug.
 * @param array $stats Counters (by reference).
 * @return int Product ID.
 */
function webni_import_product( $data, $cats, &$stats ) {
	$existing = get_page_by_path( $data['slug'], OBJECT, 'product' );
	$product  = $existing ? wc_get_product( $existing->ID ) : null;
	$is_new   = ! $product;

	if ( $is_new ) {
		$product = new WC_Product_Simple();
		$product->set_slug( $data['slug'] );
		$product->set_name( $data['name'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_featured( ! empty( $data['featured'] ) );
		$product->set_manage_stock( true );
		$product->set_stock_quantity( 50 );
		$product->set_stock_status( 'instock' );
		$product->set_sold_individually( false );
		$product->set_reviews_allowed( false );
		$stats['created']++;
	} else {
		$stats['updated']++;
	}

	// Fields filled only while empty (always empty on a new product).
	if ( '' === (string) $product->get_regular_price( 'edit' ) ) {
		$product->set_regular_price( (string) $data['price'] );
		$product->set_sale_price( ! empty( $data['sale'] ) ? (string) $data['sale'] : '' );
	}
	if ( '' === trim( wp_strip_all_tags( (string) $product->get_short_description( 'edit' ) ) ) ) {
		$product->set_short_description( $data['short'] );
	}
	if ( '' === trim( wp_strip_all_tags( (string) $product->get_description( 'edit' ) ) ) ) {
		$product->set_description( $data['long'] );
	}
	if ( ! $product->get_menu_order( 'edit' ) && (int) $data['n'] ) {
		$product->set_menu_order( (int) $data['n'] );
	}
	if ( ! $product->get_category_ids( 'edit' ) && isset( $cats[ $data['category'] ] ) ) {
		$product->set_category_ids( array( $cats[ $data['category'] ] ) );
	}
	if ( '' === (string) $product->get_weight( 'edit' ) ) {
		$product->set_weight( '0.25' );
	}
	if ( '' === (string) $product->get_sku( 'edit' ) ) {
		try {
			$product->set_sku( strtoupper( 'BPDL-' . str_replace( '-', '', substr( $data['slug'], 0, 8 ) ) ) );
		} catch ( WC_Data_Exception $e ) {
			// SKU already used by another product: leave it empty.
			unset( $e );
		}
	}

	$id = $product->save();

	// Images: the "set" image is the featured one, hero + bottle feed the character hero and the gallery.
	// Only the images that are still missing are linked, so pictures uploaded by the shop stay in place.
	$has_meta    = ! empty( $data['meta'] );
	$need_image  = ! $product->get_image_id( 'edit' );
	$need_hero   = $has_meta && ! metadata_exists( 'post', $id, '_bpdl_hero_img' );
	$need_bottle = $has_meta && ! metadata_exists( 'post', $id, '_bpdl_bottle_img' );
	$wanted      = array_filter( array(
		'set'    => $need_image,
		'hero'   => $need_hero || $is_new,
		'bottle' => $need_bottle || $is_new,
	) );
	$images      = array();
	foreach ( $data['images'] as $kind => $file ) {
		if ( ! isset( $wanted[ $kind ] ) ) {
			continue;
		}
		$img_id = webni_import_image( $file, $data['name'] . ' — ' . $kind, $id );
		if ( $img_id ) {
			$images[ $kind ] = $img_id;
			$stats['images']++;
		}
	}
	if ( $need_image && ! empty( $images['set'] ) ) {
		$product->set_image_id( $images['set'] );
	}
	if ( $is_new ) {
		$product->set_gallery_image_ids( array_values( array_filter( array( $images['hero'] ?? 0, $images['bottle'] ?? 0 ) ) ) );
	}
	$product->save();

	// Character meta: only keys that do not exist yet (the meta box edits are kept).
	if ( $has_meta ) {
		foreach ( $data['meta'] as $key => $value ) {
			if ( in_array( $key, array( 'alt1', 'alt2' ), true ) ) {
				continue; // Resolved after all products exist.
			}
			if ( ! metadata_exists( 'post', $id, '_bpdl_' . $key ) ) {
				update_post_meta( $id, '_bpdl_' . $key, $value );
			}
		}
		if ( $need_hero && ! empty( $images['hero'] ) ) {
			update_post_meta( $id, '_bpdl_hero_img', $images['hero'] );
		}
		if ( $need_bottle && ! empty( $images['bottle'] ) ) {
			update_post_meta( $id, '_bpdl_bottle_img', $images['bottle'] );
		}
	}
	return (int) $id;
}

/**
 * Whether a Customizer product picker points to an existing product.
 *
 * @param int $id Product ID.
 * @return bool
 */
function webni_pair_product_valid( $id ) {
	return $id > 0 && 'product' === get_post_type( $id ) && 'trash' !== get_post_status( $id );
}

/**
 * Add products to a product's cross-sells without removing the ones the shop already linked.
 *
 * @param int   $product_id Product.
 * @param int[] $add        Product IDs to add.
 */
function webni_add_cross_sells( $product_id, $add ) {
	$product = wc_get_product( $product_id );
	$add     = array_values( array_filter( array_map( 'intval', (array) $add ) ) );
	if ( ! $product || ! $add ) {
		return;
	}
	$current = array_map( 'intval', $product->get_cross_sell_ids( 'edit' ) );
	$merged  = array_values( array_unique( array_merge( $current, $add ) ) );
	if ( $merged !== $current ) {
		$product->set_cross_sell_ids( $merged );
		$product->save();
	}
}

/**
 * Import the demo catalogue.
 *
 * @return array{created:int,updated:int,images:int}
 */
function webni_import_demo_products() {
	$stats = array( 'created' => 0, 'updated' => 0, 'images' => 0 );
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $stats;
	}
	require_once WEBNI_THEME_DIR . '/inc/demo-data.php';

	// Categories.
	$cats = array();
	foreach ( webni_demo_categories() as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term ) {
			$inserted = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
			if ( is_wp_error( $inserted ) ) {
				continue;
			}
			$cats[ $slug ] = (int) $inserted['term_id'];
		} else {
			$cats[ $slug ] = (int) $term->term_id;
		}
	}

	// Perfumes.
	$ids = array();
	foreach ( webni_demo_perfumes() as $p ) {
		$ids[ $p['slug'] ] = webni_import_product( $p, $cats, $stats );
	}
	// "Probaj i" links need all IDs (kept when already chosen in the meta box).
	foreach ( webni_demo_perfumes() as $p ) {
		$id = $ids[ $p['slug'] ];
		foreach ( array( 'alt1', 'alt2' ) as $k ) {
			$slug = $p['meta'][ $k ] ?? '';
			if ( $slug && isset( $ids[ $slug ] ) && ! metadata_exists( 'post', $id, '_bpdl_' . $k ) ) {
				update_post_meta( $id, '_bpdl_' . $k, $ids[ $slug ] );
			}
		}
	}

	// Sets. The Customizer pickers are only set while they point to nothing.
	$pair_id      = 0;
	$pair_members = array();
	foreach ( webni_demo_sets() as $s ) {
		$id = webni_import_product( $s, $cats, $stats );
		if ( 'discovery' === $s['role'] ) {
			if ( ! webni_pair_product_valid( (int) webni_option( 'discovery_product', 0 ) ) ) {
				set_theme_mod( 'webni_discovery_product', $id );
			}
		} elseif ( 'pair' === $s['role'] ) {
			if ( ! webni_pair_product_valid( (int) webni_option( 'pair_product', 0 ) ) ) {
				set_theme_mod( 'webni_pair_product', $id );
			}
			$pair_id      = $id;
			$pair_members = array_values( array_intersect_key( $ids, array( 'secondo' => 1, 'deuxieme' => 1 ) ) );
			// The two perfumes are the set's linked products (cross-sells): the theme reads them from there.
			webni_add_cross_sells( $id, $pair_members );
		}
	}
	// Perfumes cross-sell the discovery set; the two perfumes of the pair also cross-sell the Par set.
	$discovery_id = (int) webni_option( 'discovery_product', 0 );
	foreach ( $ids as $pid ) {
		$extra = array();
		if ( $discovery_id ) {
			$extra[] = $discovery_id;
		}
		if ( $pair_id && in_array( $pid, $pair_members, true ) ) {
			$extra[] = $pair_id;
		}
		webni_add_cross_sells( $pid, $extra );
	}

	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients();
	}
	delete_transient( 'wc_products_onsale' );
	return $stats;
}
