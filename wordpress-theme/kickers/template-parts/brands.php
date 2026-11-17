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
		<div class="brand-grid b7" data-stagger>
			<?php
			foreach ( $kk_brands as $kk_b ) :
				$kk_logo  = (int) kk_term_meta( $kk_b, 'logo' );
				$kk_first = kk_query_products( array( 'posts_per_page' => 1, 'tax_query' => array( array( 'taxonomy' => 'kk_brand', 'terms' => $kk_b->term_id ) ) ) )->posts; // phpcs:ignore
				$kk_img   = $kk_first ? kk_product( $kk_first[0] )['img'] : 0;
				?>
				<a class="brand-card" data-reveal href="<?php echo esc_url( get_term_link( $kk_b ) ); ?>" style="--bc:<?php echo esc_attr( kk_term_meta( $kk_b, 'color', '#C4121F' ) ); ?>">
					<?php if ( $kk_logo ) : ?>
						<?php echo kk_img( $kk_logo, 'medium', array( 'class' => 'brand-logo', 'alt' => kk_brand_label( $kk_b ) ) ); // phpcs:ignore ?>
					<?php else : ?>
						<span class="brand-name"><?php echo esc_html( kk_brand_label( $kk_b ) ); ?></span>
					<?php endif; ?>
					<span class="brand-ar"><?php echo esc_html( kk_is_ar() ? $kk_b->name : kk_term_name( $kk_b ) ); ?></span>
					<span class="brand-count"><?php echo esc_html( kk_count_label( $kk_b->count ) ); ?></span>
					<?php echo kk_img( $kk_img, 'kk-product', array( 'class' => 'brand-prod', 'alt' => '' ) ); // phpcs:ignore ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
