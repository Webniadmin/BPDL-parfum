<?php
/**
 * Home page: hero, marquee, collection, product rails, 30%, quiz, gift, story, newsletter.
 * Texts come from the Customizer (Appearance → Customize → BP|DL Parfum), products from WooCommerce.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

get_header();

$webni_perfumes = class_exists( 'WooCommerce' ) ? webni_get_perfumes() : array();

get_template_part( 'template-parts/home/hero', null, array( 'perfumes' => $webni_perfumes ) );
get_template_part( 'template-parts/home/marquee' );
get_template_part( 'template-parts/home/collection', null, array( 'perfumes' => $webni_perfumes ) );

if ( class_exists( 'WooCommerce' ) ) {
	foreach ( array( 'featured', 'new', 'best', 'sale', 'recommended' ) as $webni_rail ) {
		webni_render_rail( $webni_rail );
	}
}

get_template_part( 'template-parts/home/quality' );
get_template_part( 'template-parts/home/quiz-teaser' );
get_template_part( 'template-parts/home/gift' );
get_template_part( 'template-parts/home/story', null, array( 'perfumes' => $webni_perfumes ) );
get_template_part( 'template-parts/home/newsletter' );

get_footer();
