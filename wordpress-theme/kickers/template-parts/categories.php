<?php
defined( 'ABSPATH' ) || exit;
$kk_cats = kk_categories();
if ( ! $kk_cats ) {
	return;
}
?>
<section class="section cats">
	<span class="drift" data-speed="1.3" style="right:3%;top:12%;--s:52px" aria-hidden="true"><?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?></span>
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'cats_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'cats_text' ) ); ?></p></header>
		<div class="cat-grid" data-stagger>
			<?php
			foreach ( $kk_cats as $kk_c ) :
				$kk_posts = kk_cat_products( $kk_c );
				$kk_imgs  = array();
				$kk_vars  = 0;
				foreach ( $kk_posts as $kk_p ) {
					$kk_v     = kk_variants( $kk_p->ID );
					$kk_vars += count( $kk_v );
					if ( $kk_v && count( $kk_imgs ) < 2 ) {
						$kk_imgs[] = $kk_v[0]['id'];
					}
				}
				$kk_own = (int) kk_term_meta( $kk_c, 'image' );
				if ( $kk_own ) {
					array_unshift( $kk_imgs, $kk_own );
					$kk_imgs = array_slice( $kk_imgs, 0, 2 );
				}
				$kk_imgs = array_reverse( $kk_imgs );
				?>
				<a class="cat-tile" data-reveal href="<?php echo esc_url( get_term_link( $kk_c ) ); ?>" style="--tile:<?php echo esc_attr( kk_term_meta( $kk_c, 'color', '#FFC928' ) ); ?>;--ink:<?php echo esc_attr( kk_term_meta( $kk_c, 'ink', '#4A2C1A' ) ); ?>">
					<span class="cat-imgs" aria-hidden="true"><?php foreach ( $kk_imgs as $kk_i ) { echo kk_img( $kk_i, 'kk-product', array( 'alt' => '' ) ); } // phpcs:ignore ?></span>
					<h3><?php echo esc_html( kk_term_name( $kk_c ) ); ?></h3>
					<p><?php echo esc_html( kk_term_desc( $kk_c ) ); ?></p>
					<span class="cat-meta"><span class="cat-count"><?php
					if ( $kk_vars <= 1 ) {
						echo esc_html( kk_count_label( 1 ) );
					} else {
						echo (int) count( $kk_posts ) . ' ' . esc_html( kk_t( 'typesL' ) ) . ( $kk_vars > count( $kk_posts ) ? ' · ' . (int) $kk_vars . ' ' . esc_html( kk_t( 'variantsN' ) ) : '' );
					}
					?></span><span class="cat-go"><?php echo kk_arrow(); // phpcs:ignore ?></span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
