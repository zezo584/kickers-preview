<?php
defined( 'ABSPATH' ) || exit;
$kk_brands = kk_brands();
if ( ! $kk_brands ) {
	return;
}
?>
<section class="section brands">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'brands_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'brands_text' ) ); ?></p></header>
		<div class="brand-grid" data-stagger>
			<?php
			foreach ( $kk_brands as $kk_b ) :
				$kk_en  = get_term_meta( $kk_b->term_id, 'kk_name_en', true );
				$kk_img = (int) kk_term_meta( $kk_b, 'image' );
				if ( ! $kk_img ) {
					$kk_first = get_posts( array( 'post_type' => 'kk_product', 'posts_per_page' => 1, 'orderby' => 'menu_order', 'order' => 'ASC', 'fields' => 'ids', 'tax_query' => array( array( 'taxonomy' => 'kk_brand', 'terms' => $kk_b->term_id ) ) ) ); // phpcs:ignore
					$kk_img   = $kk_first ? get_post_thumbnail_id( $kk_first[0] ) : 0;
				}
				?>
				<a class="brand-card" data-reveal href="<?php echo esc_url( get_term_link( $kk_b ) ); ?>" style="--bc:<?php echo esc_attr( kk_term_meta( $kk_b, 'color', '#ED1C2E' ) ); ?>">
					<span><span class="brand-name"><?php echo esc_html( $kk_en ? $kk_en : $kk_b->name ); ?></span><?php if ( ! kk_is_en() && $kk_en ) : ?><span class="brand-ar" style="display:block"><?php echo esc_html( $kk_b->name ); ?></span><?php endif; ?></span>
					<span class="brand-count"><?php echo esc_html( kk_count_label( $kk_b->count ) ); ?></span>
					<?php echo kk_img( $kk_img, 'medium_large', array( 'alt' => '' ) ); // phpcs:ignore ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
