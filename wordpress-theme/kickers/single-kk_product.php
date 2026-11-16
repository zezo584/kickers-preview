<?php
/**
 * Single product.
 *
 * @package Kickers
 */

get_header();
while ( have_posts() ) :
	the_post();
	$kk_p     = kk_product( get_post() );
	$kk_cat   = $kk_p['cat'];
	$kk_brand = $kk_p['brand'];
	$kk_bn    = $kk_brand ? ( get_term_meta( $kk_brand->term_id, 'kk_name_en', true ) ? get_term_meta( $kk_brand->term_id, 'kk_name_en', true ) : $kk_brand->name ) : '';
	$kk_decor = array( 'plain-biscuits' => array( 'd-wheat', 'd-biscuit' ), 'sandwich-biscuits' => array( 'd-biscuit', 'd-crumb' ), 'chocolate-coated-biscuits' => array( 'd-choc', 'd-choc' ), 'tella-spreads' => array( 'd-hazel', 'd-choc' ) );
	$kk_d     = ( $kk_cat && isset( $kk_decor[ $kk_cat->slug ] ) ) ? $kk_decor[ $kk_cat->slug ] : array( 'd-biscuit', 'd-wheat' );
	$kk_gal   = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( get_the_ID(), '_kk_gallery', true ) ) ) );
	if ( $kk_gal && $kk_p['img'] ) {
		array_unshift( $kk_gal, $kk_p['img'] );
	}
	$kk_crumbs = array( array( kk_t( 'products' ), kk_products_url() ) );
	if ( $kk_cat ) {
		$kk_crumbs[] = array( kk_term_name( $kk_cat ), get_term_link( $kk_cat ) );
	}
	$kk_crumbs[] = array( get_the_title(), '' );
	$kk_content  = trim( wp_strip_all_tags( kk_is_en() ? (string) get_post_meta( get_the_ID(), '_kk_content_en', true ) : get_the_content() ) );
	$kk_msg      = kk_t( 'order_msg' ) . ' ' . wp_strip_all_tags( get_the_title() ) . ' — ' . get_permalink();
	?>
	<section class="product-top">
		<div class="wrap">
			<?php echo kk_breadcrumbs( $kk_crumbs ); // phpcs:ignore ?>
			<div class="pd-grid">
				<div class="pd-stage" data-gallery>
					<div class="pd-media crimp-b" style="--tint:<?php echo esc_attr( $kk_p['tint'] ); ?>">
						<div class="plate-bg" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></div>
						<?php echo kk_img( $kk_p['img'], 'large', array( 'alt' => wp_strip_all_tags( get_the_title() ), 'loading' => 'eager', 'fetchpriority' => 'high', 'data-gallery-main' => '' ) ); // phpcs:ignore ?>
						<span class="sp-static" style="left:8%;top:10%" aria-hidden="true"><?php echo kk_ico( $kk_d[0] ); // phpcs:ignore ?></span>
						<span class="sp-static" style="right:9%;bottom:14%;width:40px;animation-delay:-3s" aria-hidden="true"><?php echo kk_ico( $kk_d[1] ); // phpcs:ignore ?></span>
						<span class="sp-static" style="right:12%;top:12%;width:26px;color:#FFC928" aria-hidden="true"><?php echo kk_ico( 'd-star' ); // phpcs:ignore ?></span>
					</div>
					<?php if ( count( $kk_gal ) > 1 ) : ?>
						<div class="pd-thumbs">
							<?php foreach ( $kk_gal as $kk_i => $kk_g ) : ?>
								<button type="button" data-src="<?php echo esc_url( wp_get_attachment_image_url( $kk_g, 'large' ) ); ?>" aria-pressed="<?php echo 0 === $kk_i ? 'true' : 'false'; ?>"><?php echo kk_img( $kk_g, 'thumbnail', array( 'alt' => '' ) ); // phpcs:ignore ?></button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="pd-info">
					<?php if ( $kk_brand ) : ?><a class="p-brand" href="<?php echo esc_url( get_term_link( $kk_brand ) ); ?>" style="--bc:<?php echo esc_attr( kk_term_meta( $kk_brand, 'color', '#D41325' ) ); ?>"><?php echo esc_html( $kk_bn ); ?></a><?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<p class="lead"><?php echo esc_html( kk_excerpt() ); ?></p>
					<?php if ( $kk_content ) : ?><div class="pd-content"><?php echo kk_content(); // phpcs:ignore ?></div><?php endif; ?>
					<dl class="specs">
						<?php if ( $kk_p['weight'] ) : ?><div><dt><?php echo esc_html( kk_t( 'weight' ) ); ?></dt><dd><?php echo esc_html( $kk_p['weight'] ); ?></dd></div><?php endif; ?>
						<?php if ( $kk_cat ) : ?><div><dt><?php echo esc_html( kk_t( 'category' ) ); ?></dt><dd><a href="<?php echo esc_url( get_term_link( $kk_cat ) ); ?>"><?php echo esc_html( kk_term_name( $kk_cat ) ); ?></a></dd></div><?php endif; ?>
						<?php if ( $kk_brand ) : ?><div><dt><?php echo esc_html( kk_t( 'brand' ) ); ?></dt><dd><a href="<?php echo esc_url( get_term_link( $kk_brand ) ); ?>"><?php echo esc_html( kk_is_en() ? $kk_bn : $kk_brand->name . ( $kk_bn !== $kk_brand->name ? ' · ' . $kk_bn : '' ) ); ?></a></dd></div><?php endif; ?>
					</dl>
					<div class="pd-cta">
						<a class="btn" href="<?php echo esc_url( kk_wa_message_url( $kk_msg ) ); ?>" target="_blank" rel="noopener"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?><?php echo esc_html( kk_t( 'order_wa' ) ); ?></a>
						<a class="btn btn-cream" href="<?php echo esc_url( kk_page_url( 'contact' ) ); ?>"><?php echo esc_html( kk_t( 'contact' ) ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	$kk_rel_args = array( 'posts_per_page' => 4, 'post__not_in' => array( get_the_ID() ) ); // phpcs:ignore
	if ( $kk_cat ) {
		$kk_rel_args['tax_query'] = array( array( 'taxonomy' => 'kk_product_cat', 'terms' => $kk_cat->term_id ) ); // phpcs:ignore
	}
	$kk_rel = kk_query_products( $kk_rel_args )->posts;
	if ( count( $kk_rel ) < 4 ) {
		$kk_more = kk_query_products( array( 'posts_per_page' => 4 - count( $kk_rel ), 'post__not_in' => array_merge( array( get_the_ID() ), wp_list_pluck( $kk_rel, 'ID' ) ) ) )->posts; // phpcs:ignore
		$kk_rel  = array_merge( $kk_rel, $kk_more );
	}
	if ( $kk_rel ) :
		?>
		<section class="section related">
			<div class="wrap">
				<header class="sec-head"><h2><?php echo esc_html( kk_t( 'related' ) ); ?></h2></header>
				<div class="p-grid"><?php foreach ( $kk_rel as $kk_r ) { get_template_part( 'template-parts/product-card', null, array( 'post' => $kk_r ) ); } ?></div>
			</div>
		</section>
		<?php
	endif;
endwhile;
get_footer();
