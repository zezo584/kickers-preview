<?php
defined( 'ABSPATH' ) || exit;
$kk_posts = kk_query_products( array( 'posts_per_page' => 40 ) )->posts;
if ( ! $kk_posts ) {
	return;
}
usort(
	$kk_posts,
	function ( $a, $b ) {
		return (int) ( '1' === get_post_meta( $b->ID, '_kk_featured', true ) ) - (int) ( '1' === get_post_meta( $a->ID, '_kk_featured', true ) );
	}
);
$kk_counts = array();
foreach ( $kk_posts as $kk_post ) {
	$kk_t = get_the_terms( $kk_post->ID, 'kk_product_cat' );
	if ( $kk_t && ! is_wp_error( $kk_t ) ) {
		$kk_counts[ $kk_t[0]->slug ] = isset( $kk_counts[ $kk_t[0]->slug ] ) ? $kk_counts[ $kk_t[0]->slug ] + 1 : 1;
	}
}
?>
<section class="section" id="home-products">
	<span class="drift" data-speed="1" style="right:2%;top:40%;--s:40px" aria-hidden="true"><?php echo kk_ico( 'd-choc' ); // phpcs:ignore ?></span>
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'products_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'products_text' ) ); ?></p></header>
		<div class="chips" data-filter="#home-grid" role="group" aria-label="<?php echo esc_attr( kk_t( 'category' ) ); ?>">
			<button class="chip" type="button" data-f="all" aria-pressed="true"><?php echo esc_html( kk_t( 'all' ) ); ?><span class="n"><?php echo (int) count( $kk_posts ); ?></span></button>
			<?php foreach ( kk_categories() as $kk_c ) : if ( empty( $kk_counts[ $kk_c->slug ] ) ) { continue; } ?>
				<button class="chip" type="button" data-f="<?php echo esc_attr( $kk_c->slug ); ?>" aria-pressed="false"><?php echo esc_html( kk_term_name( $kk_c ) ); ?><span class="n"><?php echo (int) $kk_counts[ $kk_c->slug ]; ?></span></button>
			<?php endforeach; ?>
		</div>
		<div class="p-grid" id="home-grid"><?php foreach ( $kk_posts as $kk_post ) { get_template_part( 'template-parts/product-card', null, array( 'post' => $kk_post ) ); } ?></div>
	</div>
</section>
