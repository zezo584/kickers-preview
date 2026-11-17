<?php
defined( 'ABSPATH' ) || exit;
if ( ! kk_get_reviews( 4 ) ) {
	return;
}
?>
<section class="section">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'reviews_title' ) ); ?></h2><p><a class="text-link" href="<?php echo esc_url( kk_page_url( 'reviews' ) ); ?>"><?php echo esc_html( kk_t( 'reviews' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a></p></header>
		<div class="reviews-grid" data-stagger><?php get_template_part( 'template-parts/review-cards', null, array( 'n' => 4 ) ); ?></div>
	</div>
</section>
