<?php
/**
 * Admin tools page: demo product import, store defaults, Serbian URLs, status checks.
 *
 * WooCommerce → BP|DL alati. Every action is a POST with a nonce and requires the
 * "manage_woocommerce" capability. The import is idempotent: products are matched by
 * slug and updated in place, images are uploaded once and reused.
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
			$message = sprintf( 'Demo catalogue imported: %d products created, %d updated, %d images linked (uploaded once, reused afterwards).', $result['created'], $result['updated'], $result['images'] );
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
				<p>Creates (or updates) the eight perfumes with their character data, palettes, notes, zodiac signs, images, categories (Za njega, Za nju, Unisex, Setovi), the Discovery set and the Par set. Existing products with the same slugs are updated, nothing is deleted.</p>
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
 * Create or update one product from the demo data.
 *
 * @param array $data Product data.
 * @param array $cats Category term IDs by slug.
 * @param array $stats Counters (by reference).
 * @return int Product ID.
 */
function webni_import_product( $data, $cats, &$stats ) {
	$existing = get_page_by_path( $data['slug'], OBJECT, 'product' );
	$product  = $existing ? wc_get_product( $existing->ID ) : null;
	if ( ! $product ) {
		$product = new WC_Product_Simple();
		$product->set_slug( $data['slug'] );
		$stats['created']++;
	} else {
		$stats['updated']++;
	}

	$product->set_name( $data['name'] );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_featured( ! empty( $data['featured'] ) );
	$product->set_menu_order( (int) $data['n'] );
	$product->set_regular_price( (string) $data['price'] );
	$product->set_sale_price( ! empty( $data['sale'] ) ? (string) $data['sale'] : '' );
	$product->set_short_description( $data['short'] );
	$product->set_description( $data['long'] );
	$product->set_manage_stock( true );
	$product->set_stock_quantity( 50 );
	$product->set_stock_status( 'instock' );
	$product->set_sold_individually( false );
	$product->set_reviews_allowed( false );
	$product->set_sku( strtoupper( 'BPDL-' . str_replace( '-', '', substr( $data['slug'], 0, 8 ) ) ) );
	if ( isset( $cats[ $data['category'] ] ) ) {
		$product->set_category_ids( array( $cats[ $data['category'] ] ) );
	}
	$product->set_weight( '0.25' );

	$id = $product->save();

	// Images: the "set" image is the featured one, hero + bottle go to the gallery and the meta.
	$images = array();
	foreach ( $data['images'] as $kind => $file ) {
		$img_id = webni_import_image( $file, $data['name'] . ' — ' . $kind, $id );
		if ( $img_id ) {
			$images[ $kind ] = $img_id;
			$stats['images']++;
		}
	}
	if ( ! empty( $images['set'] ) ) {
		$product->set_image_id( $images['set'] );
	}
	$gallery = array_values( array_filter( array( $images['hero'] ?? 0, $images['bottle'] ?? 0 ) ) );
	$product->set_gallery_image_ids( $gallery );
	$product->save();

	// Character meta.
	if ( ! empty( $data['meta'] ) ) {
		foreach ( $data['meta'] as $key => $value ) {
			if ( in_array( $key, array( 'alt1', 'alt2' ), true ) ) {
				continue; // Resolved after all products exist.
			}
			update_post_meta( $id, '_bpdl_' . $key, $value );
		}
		if ( ! empty( $images['hero'] ) ) {
			update_post_meta( $id, '_bpdl_hero_img', $images['hero'] );
		}
		if ( ! empty( $images['bottle'] ) ) {
			update_post_meta( $id, '_bpdl_bottle_img', $images['bottle'] );
		}
	}
	return (int) $id;
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
	// "Probaj i" links need all IDs.
	foreach ( webni_demo_perfumes() as $p ) {
		$id = $ids[ $p['slug'] ];
		foreach ( array( 'alt1', 'alt2' ) as $k ) {
			$slug = $p['meta'][ $k ] ?? '';
			if ( $slug && isset( $ids[ $slug ] ) ) {
				update_post_meta( $id, '_bpdl_' . $k, $ids[ $slug ] );
			}
		}
	}

	// Sets.
	foreach ( webni_demo_sets() as $s ) {
		$id = webni_import_product( $s, $cats, $stats );
		if ( 'discovery' === $s['role'] ) {
			set_theme_mod( 'webni_discovery_product', $id );
		} elseif ( 'pair' === $s['role'] ) {
			set_theme_mod( 'webni_pair_product', $id );
			// The pair set cross-sells on the cart page.
			$pair = wc_get_product( $id );
			if ( $pair ) {
				$pair->set_cross_sell_ids( array_values( array_intersect_key( $ids, array( 'secondo' => 1, 'deuxieme' => 1 ) ) ) );
				$pair->save();
			}
		}
	}
	// Perfumes cross-sell the discovery set.
	if ( ! empty( $ids ) && webni_option( 'discovery_product', 0 ) ) {
		foreach ( $ids as $pid ) {
			$product = wc_get_product( $pid );
			if ( $product ) {
				$product->set_cross_sell_ids( array( (int) webni_option( 'discovery_product', 0 ) ) );
				$product->save();
			}
		}
	}

	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients();
	}
	delete_transient( 'wc_products_onsale' );
	return $stats;
}
