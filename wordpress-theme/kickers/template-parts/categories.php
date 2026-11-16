<?php
defined( 'ABSPATH' ) || exit;
$kk_cats = kk_categories();
if ( ! $kk_cats ) {
	return;
}
?>
<section class="section cats">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'cats_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'cats_text' ) ); ?></p></header>
		<div class="cat-grid" data-stagger>
			<?php
			foreach ( $kk_cats as $kk_c ) :
				$kk_imgs = array();
				$kk_own  = (int) kk_term_meta( $kk_c, 'image' );
				if ( $kk_own ) {
					$kk_imgs[] = $kk_own;
				}
				$kk_q = get_posts( array( 'post_type' => 'kk_product', 'posts_per_page' => 2, 'orderby' => 'menu_order', 'order' => 'ASC', 'fields' => 'ids', 'tax_query' => array( array( 'taxonomy' => 'kk_product_cat', 'terms' => $kk_c->term_id ) ) ) ); // phpcs:ignore
				foreach ( $kk_q as $kk_pid ) {
					if ( count( $kk_imgs ) < 2 && get_post_thumbnail_id( $kk_pid ) ) {
						$kk_imgs[] = get_post_thumbnail_id( $kk_pid );
					}
				}
				$kk_imgs = array_reverse( $kk_imgs );
				?>
				<a class="cat-tile" data-reveal href="<?php echo esc_url( get_term_link( $kk_c ) ); ?>" style="--tile:<?php echo esc_attr( kk_term_meta( $kk_c, 'color', '#FFC928' ) ); ?>;--ink:<?php echo esc_attr( kk_term_meta( $kk_c, 'ink', '#4A2C1A' ) ); ?>">
					<span class="cat-imgs" aria-hidden="true"><?php foreach ( $kk_imgs as $kk_i ) { echo kk_img( $kk_i, 'medium_large', array( 'alt' => '' ) ); } // phpcs:ignore ?></span>
					<h3><?php echo esc_html( kk_term_name( $kk_c ) ); ?></h3>
					<p><?php echo esc_html( kk_term_desc( $kk_c ) ); ?></p>
					<span class="cat-meta"><span class="cat-count"><?php echo esc_html( kk_count_label( $kk_c->count ) ); ?></span><span class="cat-go"><?php echo kk_arrow(); // phpcs:ignore ?></span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
