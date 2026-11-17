<?php
/**
 * Home page.
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/ribbons' );
$kk_sections = array(
	'welcome'   => 'welcome',
	'cats'      => 'categories',
	'showcase'  => 'showcase',
	'products'  => 'products-home',
	'roll'      => 'roll',
	'export'    => 'export',
	'brands'    => 'brands',
	'reviews'   => 'reviews',
	'factories' => 'factories',
);
foreach ( $kk_sections as $kk_key => $kk_part ) {
	if ( kk_opt( 'show_' . $kk_key ) ) {
		get_template_part( 'template-parts/' . $kk_part );
	}
}
get_template_part( 'template-parts/cta' );
get_footer();
