<?php
/**
 * Site header: logo, navigation, search, account, cart, quiz button, mobile menu.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

$webni_has_wc    = class_exists( 'WooCommerce' );
$webni_cart_url  = $webni_has_wc ? wc_get_cart_url() : '';
$webni_acct_url  = $webni_has_wc ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$webni_shop_url  = webni_shop_url();
$webni_cart_count = $webni_has_wc && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#0a0908">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Pređi na sadržaj', 'webni-custom-version-1' ); ?></a>

<header class="nav" id="siteHeader">
	<div class="container nav__inner">
		<?php if ( has_custom_logo() ) : ?>
			<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php echo wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'medium', false, array( 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager' ) ); ?>
			</a>
		<?php else : ?>
			<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">BP<i>|</i>DL <small>PARFUM</small></a>
		<?php endif; ?>

		<nav class="nav__links" aria-label="<?php esc_attr_e( 'Glavna navigacija', 'webni-custom-version-1' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false ) );
			} else {
				echo '<a href="' . esc_url( $webni_shop_url ) . '">' . esc_html__( 'Parfemi', 'webni-custom-version-1' ) . '</a>';
				echo '<a href="' . esc_url( home_url( '/#kvalitet' ) ) . '">' . esc_html__( 'Zašto 30%', 'webni-custom-version-1' ) . '</a>';
				echo '<a href="' . esc_url( home_url( '/#poklon' ) ) . '">' . esc_html__( 'Poklon', 'webni-custom-version-1' ) . '</a>';
				echo '<a href="' . esc_url( home_url( '/#prica' ) ) . '">' . esc_html__( 'Priča', 'webni-custom-version-1' ) . '</a>';
			}
			?>
		</nav>

		<div class="nav__actions">
			<button type="button" class="btn btn--ghost btn--nav" data-quiz><?php echo esc_html( webni_option( 'hero_cta1' ) ); ?></button>
			<button type="button" class="nav__icon" data-open-search aria-label="<?php esc_attr_e( 'Pretraga', 'webni-custom-version-1' ); ?>"><?php echo webni_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			<a class="nav__icon" href="<?php echo esc_url( $webni_acct_url ); ?>" aria-label="<?php esc_attr_e( 'Moj nalog', 'webni-custom-version-1' ); ?>"><?php echo webni_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php if ( $webni_has_wc ) : ?>
				<a class="nav__icon" href="<?php echo esc_url( $webni_cart_url ); ?>" data-open-cart aria-label="<?php esc_attr_e( 'Korpa', 'webni-custom-version-1' ); ?>"><?php echo webni_icon( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo webni_cart_count_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
			<button type="button" class="burger" aria-label="<?php esc_attr_e( 'Meni', 'webni-custom-version-1' ); ?>" aria-expanded="false" aria-controls="mobileMenu"><span></span><span></span><span></span></button>
		</div>
	</div>
</header>

<nav class="menu" id="mobileMenu" aria-label="<?php esc_attr_e( 'Meni', 'webni-custom-version-1' ); ?>">
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false ) );
	} else {
		echo '<a href="' . esc_url( $webni_shop_url ) . '">' . esc_html__( 'Parfemi', 'webni-custom-version-1' ) . '</a>';
		echo '<a href="' . esc_url( home_url( '/#poklon' ) ) . '">' . esc_html__( 'Poklon', 'webni-custom-version-1' ) . '</a>';
		echo '<a href="' . esc_url( home_url( '/#prica' ) ) . '">' . esc_html__( 'Priča', 'webni-custom-version-1' ) . '</a>';
	}
	?>
	<a href="#" data-quiz><?php echo esc_html( webni_option( 'hero_cta1' ) ); ?></a>
	<div class="menu__small">
		<a href="<?php echo esc_url( $webni_acct_url ); ?>"><?php esc_html_e( 'Moj nalog', 'webni-custom-version-1' ); ?></a>
		<?php if ( $webni_has_wc ) : ?><a href="<?php echo esc_url( $webni_cart_url ); ?>"><?php esc_html_e( 'Korpa', 'webni-custom-version-1' ); ?></a><?php endif; ?>
		<a href="#" data-open-search><?php esc_html_e( 'Pretraga', 'webni-custom-version-1' ); ?></a>
	</div>
	<p class="tiny"><?php echo esc_html( get_bloginfo( 'name' ) . ' · ' . webni_option( 'footer_tagline' ) ); ?></p>
</nav>

<div class="search-overlay" id="searchOverlay" hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Pretraga', 'webni-custom-version-1' ); ?>">
	<div class="search-overlay__panel">
		<button type="button" class="search-overlay__close" data-close-overlay aria-label="<?php esc_attr_e( 'Zatvori', 'webni-custom-version-1' ); ?>"><?php echo webni_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		<?php get_search_form(); ?>
		<?php if ( $webni_has_wc ) : ?>
			<div class="search-overlay__quick">
				<?php
				$webni_quick = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 6 ) );
				if ( ! is_wp_error( $webni_quick ) ) {
					foreach ( $webni_quick as $webni_term ) {
						echo '<a href="' . esc_url( get_term_link( $webni_term ) ) . '">' . esc_html( $webni_term->name ) . '</a>';
					}
				}
				?>
				<a href="<?php echo esc_url( $webni_shop_url ); ?>"><?php esc_html_e( 'Svi parfemi', 'webni-custom-version-1' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php if ( $webni_has_wc ) : ?>
<div class="minicart" id="minicart" hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Korpa', 'webni-custom-version-1' ); ?>">
	<div class="minicart__panel">
		<div class="minicart__head">
			<h2><?php esc_html_e( 'Korpa', 'webni-custom-version-1' ); ?></h2>
			<button type="button" class="minicart__close" data-close-overlay aria-label="<?php esc_attr_e( 'Zatvori', 'webni-custom-version-1' ); ?>"><?php echo webni_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
		<?php webni_mini_cart_html(); ?>
	</div>
</div>
<?php endif; ?>

<main id="main" class="site-main">
