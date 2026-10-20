<?php
/**
 * WooCommerce integration: layout hooks, loop, single product, cart, checkout, account.
 *
 * WooCommerce stays the source of truth for products, prices, stock, cart, checkout
 * and orders. The theme changes presentation only, through hooks where possible and
 * through a small set of template overrides in /woocommerce.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/* -------------------------------------------------------------------------
 * Wrappers, sidebar, scripts
 * ---------------------------------------------------------------------- */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Cart fragments keep the header cart count in sync after AJAX add-to-cart; the
 * block styles are not needed (classic shortcode cart/checkout).
 */
function webni_wc_scripts() {
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'wc-blocks-vendors-style' );
	if ( ! is_cart() && ! is_checkout() ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
add_action( 'wp_enqueue_scripts', 'webni_wc_scripts', 30 );

/**
 * Header cart count (and mini cart) refreshed by WooCommerce fragments.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function webni_wc_fragments( $fragments ) {
	$fragments['.webni-cart-count'] = webni_cart_count_html();
	ob_start();
	webni_mini_cart_html();
	$fragments['.minicart__body'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'webni_wc_fragments' );

/**
 * Cart count badge markup.
 *
 * @return string
 */
function webni_cart_count_html() {
	$count = WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
	return sprintf( '<span class="webni-cart-count%s" aria-hidden="true">%d</span>', $count ? ' is-on' : '', $count );
}

/**
 * Mini cart body markup (used in the header drawer and refreshed by fragments).
 */
function webni_mini_cart_html() {
	echo '<div class="minicart__body">';
	if ( WC()->cart && ! WC()->cart->is_empty() ) {
		echo '<ul class="minicart__items">';
		foreach ( WC()->cart->get_cart() as $key => $item ) {
			$product = $item['data'];
			if ( ! $product || ! $product->exists() || $item['quantity'] <= 0 ) {
				continue;
			}
			$remove = apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>', esc_url( wc_get_cart_remove_url( $key ) ), esc_attr__( 'Ukloni', 'webni-custom-version-1' ), esc_attr( $item['product_id'] ), esc_attr( $key ), esc_attr( $product->get_sku() ), webni_icon( 'close', 14 ) ), $key );
			echo '<li class="minicart__item">';
			echo '<a class="minicart__thumb" href="' . esc_url( $product->get_permalink( $item ) ) . '">' . $product->get_image( 'webni-thumb' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce image markup.
			echo '<div class="minicart__meta"><a href="' . esc_url( $product->get_permalink( $item ) ) . '">' . wp_kses_post( $product->get_name() ) . '</a>';
			echo '<span class="minicart__qty">' . sprintf( '%s × %s', (int) $item['quantity'], WC()->cart->get_product_price( $product ) ) . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- price HTML from WooCommerce.
			echo $remove; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above with escaped parts.
			echo '</li>';
		}
		echo '</ul>';
		echo '<p class="minicart__total"><span>' . esc_html__( 'Međuzbir', 'webni-custom-version-1' ) . '</span><b>' . WC()->cart->get_cart_subtotal() . '</b></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="minicart__actions"><a class="btn btn--ghost" href="' . esc_url( wc_get_cart_url() ) . '">' . esc_html__( 'Vidi korpu', 'webni-custom-version-1' ) . '</a><a class="btn btn--solid" href="' . esc_url( wc_get_checkout_url() ) . '">' . esc_html__( 'Plaćanje', 'webni-custom-version-1' ) . '</a></div>';
	} else {
		echo '<p class="minicart__empty">' . esc_html__( 'Korpa je prazna.', 'webni-custom-version-1' ) . '</p>';
		echo '<a class="btn btn--ghost" href="' . esc_url( webni_shop_url() ) . '">' . esc_html__( 'Pogledaj parfeme', 'webni-custom-version-1' ) . '</a>';
	}
	echo '</div>';
}

/* -------------------------------------------------------------------------
 * Catalog loop
 * ---------------------------------------------------------------------- */

/**
 * Archive wrapper: container + breadcrumb before the WooCommerce products header.
 */
function webni_wc_archive_open() {
	if ( is_product() ) {
		return;
	}
	echo '<div class="shop container">';
	woocommerce_breadcrumb();
}
add_action( 'woocommerce_before_main_content', 'webni_wc_archive_open', 5 );

/**
 * Archive wrapper end.
 */
function webni_wc_archive_close() {
	if ( is_product() ) {
		return;
	}
	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'webni_wc_archive_close', 50 );

/**
 * Shop intro from the Customizer when the shop page itself has no content.
 */
function webni_wc_shop_intro() {
	if ( ! is_shop() ) {
		return;
	}
	$page = get_post( wc_get_page_id( 'shop' ) );
	if ( $page && trim( (string) $page->post_content ) ) {
		return;
	}
	$intro = webni_option( 'shop_intro' );
	if ( $intro ) {
		echo '<p class="lead shop__intro">' . esc_html( $intro ) . '</p>';
	}
}
add_action( 'woocommerce_archive_description', 'webni_wc_shop_intro', 5 );

/**
 * Filter chips above the loop (client-side, perfumes only) and a wrapper for count + ordering.
 */
function webni_wc_loop_tools_open() {
	echo '<div class="shop__tools">';
	if ( is_shop() || is_product_category() ) {
		get_template_part( 'template-parts/content/filters' );
	}
	echo '<div class="shop__sort">';
}
add_action( 'woocommerce_before_shop_loop', 'webni_wc_loop_tools_open', 15 );

/**
 * Close the loop tools wrapper.
 */
function webni_wc_loop_tools_close() {
	echo '</div></div>';
}
add_action( 'woocommerce_before_shop_loop', 'webni_wc_loop_tools_close', 35 );

/**
 * Empty-filter message after the loop.
 */
function webni_wc_loop_empty_filter() {
	if ( is_shop() || is_product_category() ) {
		echo '<p class="lead filters__empty" hidden>' . esc_html__( 'Nema parfema za ovaj izbor.', 'webni-custom-version-1' ) . '</p>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'webni_wc_loop_empty_filter', 5 );

add_filter( 'loop_shop_per_page', function () {
	return 24;
} );

add_filter( 'loop_shop_columns', function () {
	return 4;
} );

add_filter( 'woocommerce_product_loop_start', function () {
	return '<ul class="products grid--cards">';
} );

add_filter( 'woocommerce_product_loop_end', function () {
	return '</ul>';
} );

add_filter( 'woocommerce_pagination_args', function ( $args ) {
	$args['prev_text'] = __( '← Prethodna', 'webni-custom-version-1' );
	$args['next_text'] = __( 'Sledeća →', 'webni-custom-version-1' );
	return $args;
} );

add_filter( 'woocommerce_sale_flash', function () {
	return '<span class="card__badge">' . esc_html__( 'Akcija', 'webni-custom-version-1' ) . '</span>';
} );

add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		return __( 'U korpu', 'webni-custom-version-1' );
	}
	return $text;
}, 10, 2 );

add_filter( 'woocommerce_product_single_add_to_cart_text', function ( $text, $product ) {
	/* translators: %s product name */
	return $product ? sprintf( __( 'Uzmi %s', 'webni-custom-version-1' ), $product->get_name() ) : __( 'Dodaj u korpu', 'webni-custom-version-1' );
}, 10, 2 );

/**
 * Shop page title from the Customizer; categories keep their names.
 *
 * @param string $title Title.
 * @return string
 */
function webni_wc_page_title( $title ) {
	if ( is_shop() && ! is_search() ) {
		return webni_option( 'shop_title', __( 'Parfemi', 'webni-custom-version-1' ) );
	}
	return $title;
}
add_filter( 'woocommerce_page_title', 'webni_wc_page_title' );

/**
 * Breadcrumb: "Početna" and theme markup.
 *
 * @param array $defaults Breadcrumb args.
 * @return array
 */
function webni_wc_breadcrumb_defaults( $defaults ) {
	$defaults['delimiter']   = '<span class="crumbs__sep" aria-hidden="true">/</span>';
	$defaults['wrap_before'] = '<nav class="crumbs" aria-label="' . esc_attr__( 'Putanja', 'webni-custom-version-1' ) . '">';
	$defaults['wrap_after']  = '</nav>';
	$defaults['before']      = '<span class="crumbs__item">';
	$defaults['after']       = '</span>';
	$defaults['home']        = __( 'Početna', 'webni-custom-version-1' );
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'webni_wc_breadcrumb_defaults' );

/**
 * Shop link in the breadcrumb uses the Customizer title.
 *
 * @param array $crumbs Crumbs.
 * @return array
 */
function webni_wc_breadcrumb_shop_label( $crumbs ) {
	foreach ( $crumbs as $i => $crumb ) {
		if ( isset( $crumb[1] ) && $crumb[1] === wc_get_page_permalink( 'shop' ) ) {
			$crumbs[ $i ][0] = webni_option( 'shop_title', __( 'Parfemi', 'webni-custom-version-1' ) );
		}
	}
	return $crumbs;
}
add_filter( 'woocommerce_get_breadcrumb', 'webni_wc_breadcrumb_shop_label' );

/* -------------------------------------------------------------------------
 * Single product
 * ---------------------------------------------------------------------- */

// The theme renders its own hero, gallery, character sections and "Probaj i" list.
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Perfumes render title, price, excerpt and the add-to-cart form inside the hero
 * (template-parts/product/perfume.php); only structured data and plugin additions stay
 * on the summary hook. Other products keep the default summary.
 */
function webni_single_summary_layout() {
	if ( ! is_product() ) {
		return;
	}
	$d = webni_perfume( get_queried_object_id() );
	if ( $d && $d['character'] ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}
}
add_action( 'wp', 'webni_single_summary_layout' );

/**
 * "Probaj i" products for a product: the two alternatives from the character meta,
 * otherwise WooCommerce related products.
 *
 * @param WC_Product $product Product.
 * @param int        $limit   Max number.
 * @return WC_Product[]
 */
function webni_related_products( $product, $limit = 2 ) {
	$d   = webni_perfume( $product );
	$ids = $d ? $d['alts'] : array();
	if ( ! $ids ) {
		$ids = wc_get_related_products( $product->get_id(), $limit );
	}
	$out = array();
	foreach ( array_slice( $ids, 0, $limit ) as $id ) {
		$p = wc_get_product( $id );
		if ( $p && 'publish' === $p->get_status() ) {
			$out[] = $p;
		}
	}
	return $out;
}

/**
 * Previous / next perfume (by menu order) for the single product navigation.
 *
 * @param WC_Product $product Product.
 * @return array{prev: ?WC_Product, next: ?WC_Product}
 */
function webni_adjacent_perfumes( $product ) {
	$list = webni_get_perfumes();
	$n    = count( $list );
	foreach ( $list as $i => $p ) {
		if ( $p->get_id() === $product->get_id() ) {
			return array(
				'prev' => $n > 1 ? $list[ ( $i - 1 + $n ) % $n ] : null,
				'next' => $n > 1 ? $list[ ( $i + 1 ) % $n ] : null,
			);
		}
	}
	return array( 'prev' => null, 'next' => null );
}

/**
 * Quantity input without the default WooCommerce label markup around it.
 *
 * @param array $args Args.
 * @return array
 */
function webni_wc_quantity_args( $args ) {
	$args['classes'] = array( 'input-text', 'qty', 'text' );
	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'webni_wc_quantity_args' );

/* -------------------------------------------------------------------------
 * Cart & checkout
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_order_button_text', function () {
	return __( 'Poruči', 'webni-custom-version-1' );
} );

add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

/**
 * Checkout fields: Serbian labels, no company/state, phone and email first.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function webni_wc_checkout_fields( $fields ) {
	foreach ( array( 'billing', 'shipping' ) as $group ) {
		if ( empty( $fields[ $group ] ) ) {
			continue;
		}
		unset( $fields[ $group ][ $group . '_company' ], $fields[ $group ][ $group . '_state' ] );
		$order = array( 'first_name' => 10, 'last_name' => 20, 'phone' => 30, 'email' => 40, 'address_1' => 50, 'address_2' => 55, 'city' => 60, 'postcode' => 70, 'country' => 80 );
		foreach ( $order as $key => $priority ) {
			if ( isset( $fields[ $group ][ $group . '_' . $key ] ) ) {
				$fields[ $group ][ $group . '_' . $key ]['priority'] = $priority;
			}
		}
		$half = array( 'first_name', 'last_name', 'phone', 'email', 'city', 'postcode' );
		foreach ( $half as $i => $key ) {
			if ( isset( $fields[ $group ][ $group . '_' . $key ] ) ) {
				$fields[ $group ][ $group . '_' . $key ]['class'] = array( $i % 2 ? 'form-row-last' : 'form-row-first' );
			}
		}
		foreach ( array( 'address_1', 'address_2', 'country' ) as $key ) {
			if ( isset( $fields[ $group ][ $group . '_' . $key ] ) ) {
				$fields[ $group ][ $group . '_' . $key ]['class'] = array( 'form-row-wide' );
			}
		}
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['label']       = __( 'Telefon', 'webni-custom-version-1' );
		$fields['billing']['billing_phone']['placeholder'] = __( 'za kurira, npr. 06x…', 'webni-custom-version-1' );
	}
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['label']       = __( 'E-mail', 'webni-custom-version-1' );
		$fields['billing']['billing_email']['placeholder'] = __( 'za potvrdu porudžbine', 'webni-custom-version-1' );
	}
	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['label']       = __( 'Napomena', 'webni-custom-version-1' );
		$fields['order']['order_comments']['placeholder'] = __( 'Sprat, interfon, kad te ima kod kuće, poruka za poklon…', 'webni-custom-version-1' );
	}
	if ( isset( $fields['account']['account_password'] ) ) {
		$fields['account']['account_password']['label']       = __( 'Lozinka za nalog', 'webni-custom-version-1' );
		$fields['account']['account_password']['placeholder'] = __( 'Lozinka', 'webni-custom-version-1' );
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'webni_wc_checkout_fields', 20 );

/**
 * Serbian "thank you" line.
 *
 * @param string   $text  Text.
 * @param WC_Order $order Order.
 * @return string
 */
function webni_wc_thankyou_text( $text, $order ) {
	if ( ! $order ) {
		return $text;
	}
	$line = trim( (string) webni_option( 'shop_delivery_line', __( 'Dostava za 1–3 radna dana.', 'webni-custom-version-1' ) ) );
	if ( '' !== $line ) {
		$line = mb_strtoupper( mb_substr( $line, 0, 1 ) ) . mb_substr( $line, 1 );
		$line = rtrim( $line, '.' ) . '.';
	}
	return __( 'Hvala. Tvoja porudžbina je primljena — potvrda stiže na e-mail.', 'webni-custom-version-1' ) . ' ' . $line;
}
add_filter( 'woocommerce_thankyou_order_received_text', 'webni_wc_thankyou_text', 10, 2 );

/**
 * Cart page layout: notices + wrapper so the form and totals sit side by side,
 * cross-sells moved below the cart (full width).
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 20 );

/**
 * Cross-sells on the cart page: at most two, and "Probaj i" as the heading.
 */
add_filter( 'woocommerce_cross_sells_total', function () {
	return 2;
} );
add_filter( 'woocommerce_cross_sells_columns', function () {
	return 2;
} );
add_filter( 'woocommerce_product_cross_sells_products_heading', function () {
	return __( 'Probaj i', 'webni-custom-version-1' );
} );

/* -------------------------------------------------------------------------
 * Product sections for the home page rails
 * ---------------------------------------------------------------------- */

/**
 * Products for a home-page rail.
 *
 * @param string $kind featured|new|best|sale|recommended.
 * @param int    $limit Max.
 * @return WC_Product[]
 */
function webni_rail_products( $kind, $limit = 4 ) {
	$args = array(
		'status'     => 'publish',
		'limit'      => $limit,
		'visibility' => 'catalog',
		'orderby'    => 'menu_order title',
		'order'      => 'ASC',
	);
	switch ( $kind ) {
		case 'featured':
			$args['featured'] = true;
			break;
		case 'new':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;
		case 'best':
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$args['order']    = 'DESC';
			break;
		case 'sale':
			$ids = wc_get_product_ids_on_sale();
			if ( ! $ids ) {
				return array();
			}
			$args['include'] = $ids;
			break;
		case 'recommended':
			$ids = array_filter( array_map( 'absint', explode( ',', (string) webni_option( 'rail_recommended_ids', '' ) ) ) );
			if ( ! $ids ) {
				return array();
			}
			$args['include'] = $ids;
			$args['orderby'] = 'include';
			break;
		default:
			return array();
	}
	$products = wc_get_products( $args );
	if ( 'best' === $kind ) {
		$products = array_values( array_filter( $products, function ( $p ) {
			return (int) $p->get_total_sales() > 0;
		} ) );
	}
	return $products;
}

/**
 * Render one rail (section with a product grid) if enabled and not empty.
 *
 * @param string $kind Rail kind.
 */
function webni_render_rail( $kind ) {
	if ( ! webni_option( 'rail_' . $kind . '_on', 1 ) ) {
		return;
	}
	$products = webni_rail_products( $kind );
	if ( ! $products ) {
		return;
	}
	$title = webni_option( 'rail_' . $kind . '_title', '' );
	get_template_part( 'template-parts/home/rail', null, array( 'kind' => $kind, 'title' => $title, 'products' => $products ) );
}

/**
 * When free shipping applies, hide the paid rates so the customer is not asked to choose.
 *
 * @param array $rates   Shipping rates.
 * @param array $package Package.
 * @return array
 */
function webni_wc_hide_paid_rates_when_free( $rates, $package ) {
	$free = array();
	foreach ( $rates as $id => $rate ) {
		if ( 'free_shipping' === $rate->get_method_id() ) {
			$free[ $id ] = $rate;
		}
	}
	return $free ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'webni_wc_hide_paid_rates_when_free', 10, 2 );
