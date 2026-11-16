<?php
/**
 * Rotating 3D showcase of all products.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_q = kk_query_products( array( 'posts_per_page' => 24 ) );
if ( count( $kk_q->posts ) < 3 ) {
	return;
}
?>
<section class="section showcase crimp-tb">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'showcase_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'showcase_text' ) ); ?></p></header>
		<div class="cf" data-cf tabindex="0" aria-roledescription="carousel" aria-label="<?php echo esc_attr( kk_optl( 'showcase_title' ) ); ?>">
			<div class="cf-stage">
				<?php
				foreach ( $kk_q->posts as $kk_post ) :
					$kk_p  = kk_product( $kk_post );
					$kk_bn = $kk_p['brand'] ? ( get_term_meta( $kk_p['brand']->term_id, 'kk_name_en', true ) ? get_term_meta( $kk_p['brand']->term_id, 'kk_name_en', true ) : $kk_p['brand']->name ) : '';
					?>
					<a class="cf-item" href="<?php echo esc_url( $kk_p['url'] ); ?>" data-title="<?php echo esc_attr( wp_strip_all_tags( $kk_p['title'] ) ); ?>" data-desc="<?php echo esc_attr( kk_excerpt( $kk_p['id'] ) ); ?>" data-weight="<?php echo esc_attr( $kk_p['weight'] ); ?>" data-brand="<?php echo esc_attr( $kk_bn ); ?>" data-bc="<?php echo esc_attr( $kk_p['brand'] ? kk_term_meta( $kk_p['brand'], 'color', '#D41325' ) : '#D41325' ); ?>" data-tint="<?php echo esc_attr( $kk_p['tint'] ); ?>">
						<span class="cf-plate" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span>
						<?php echo kk_img( $kk_p['img'], 'kk-product', array( 'alt' => wp_strip_all_tags( $kk_p['title'] ) ) ); // phpcs:ignore ?>
					</a>
				<?php endforeach; ?>
			</div>
			<div class="cf-bottom">
				<div class="cf-info" aria-live="polite"><span class="p-brand cf-brand"></span><h3 class="cf-title"></h3><p class="cf-desc"></p><div class="cf-row"><span class="p-weight cf-weight"></span><a class="btn btn-sm cf-link" href="<?php echo esc_url( kk_products_url() ); ?>"><?php echo esc_html( kk_t( 'details' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a></div></div>
				<div class="cf-ctrl">
					<button type="button" data-cf-prev aria-label="<?php echo esc_attr( kk_t( 'prev' ) ); ?>"><?php echo kk_ico( 'i-arrow', 'ico-dir prev-ico' ); // phpcs:ignore ?></button>
					<button type="button" data-cf-next aria-label="<?php echo esc_attr( kk_t( 'next' ) ); ?>"><?php echo kk_arrow(); // phpcs:ignore ?></button>
				</div>
			</div>
		</div>
	</div>
</section>
