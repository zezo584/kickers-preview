<?php
defined( 'ABSPATH' ) || exit;
$kk_count = max( 1, (int) kk_opt( 'products_count' ) );
$kk_q     = kk_query_products( array( 'posts_per_page' => $kk_count, 'meta_key' => '_kk_featured', 'meta_value' => '1' ) ); // phpcs:ignore
if ( ! $kk_q->have_posts() ) {
	$kk_q = kk_query_products( array( 'posts_per_page' => $kk_count ) );
}
if ( ! $kk_q->have_posts() ) {
	return;
}
$kk_counts = array();
foreach ( $kk_q->posts as $kk_post ) {
	$kk_d = kk_product( $kk_post );
	if ( $kk_d['cat'] ) {
		$kk_counts[ $kk_d['cat']->slug ] = isset( $kk_counts[ $kk_d['cat']->slug ] ) ? $kk_counts[ $kk_d['cat']->slug ] + 1 : 1;
	}
}
?>
<section class="section" id="home-products">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'products_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'products_text' ) ); ?></p></header>
		<div class="chips" data-filter="#home-grid" role="group" aria-label="<?php echo esc_attr( kk_t( 'category' ) ); ?>">
			<button class="chip" type="button" data-f="all" aria-pressed="true"><?php echo esc_html( kk_t( 'all' ) ); ?><span class="n"><?php echo (int) $kk_q->post_count; ?></span></button>
			<?php foreach ( kk_categories() as $kk_c ) : if ( empty( $kk_counts[ $kk_c->slug ] ) ) { continue; } ?>
				<button class="chip" type="button" data-f="<?php echo esc_attr( $kk_c->slug ); ?>" aria-pressed="false"><?php echo esc_html( kk_term_name( $kk_c ) ); ?><span class="n"><?php echo (int) $kk_counts[ $kk_c->slug ]; ?></span></button>
			<?php endforeach; ?>
		</div>
		<div class="p-grid" id="home-grid">
			<?php foreach ( $kk_q->posts as $kk_post ) { get_template_part( 'template-parts/product-card', null, array( 'post' => $kk_post ) ); } ?>
		</div>
		<div class="more-row"><a class="btn" href="<?php echo esc_url( kk_products_url() ); ?>"><?php echo esc_html( kk_t( 'view_all' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a></div>
	</div>
</section>
