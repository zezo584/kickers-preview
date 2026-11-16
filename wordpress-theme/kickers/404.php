<?php
/**
 * 404.
 *
 * @package Kickers
 */

get_header();
?>
<section class="nf"><div class="wrap">
	<div class="nf-num" aria-hidden="true">4<?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?>4</div>
	<h1><?php echo esc_html( kk_t( 'nf_title' ) ); ?></h1>
	<p><?php echo esc_html( kk_t( 'nf_text' ) ); ?></p>
	<div class="cta-actions"><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( kk_t( 'back_home' ) ); ?></a><a class="btn btn-cream" href="<?php echo esc_url( kk_products_url() ); ?>"><?php echo esc_html( kk_t( 'browse' ) ); ?></a></div>
</div></section>
<?php
get_footer();
