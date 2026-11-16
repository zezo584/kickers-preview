<?php
/**
 * Product card (v2) with quick view.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_p   = kk_product( isset( $args['post'] ) ? $args['post'] : get_post() );
$kk_bc  = $kk_p['brand'] ? kk_term_meta( $kk_p['brand'], 'color', '#D41325' ) : '#D41325';
$kk_bn  = $kk_p['brand'] ? ( get_term_meta( $kk_p['brand']->term_id, 'kk_name_en', true ) ? get_term_meta( $kk_p['brand']->term_id, 'kk_name_en', true ) : $kk_p['brand']->name ) : '';
$kk_ttl = wp_strip_all_tags( $kk_p['title'] );
$kk_src = $kk_p['img'] ? wp_get_attachment_image_url( $kk_p['img'], 'large' ) : '';
$kk_msg = kk_t( 'order_msg' ) . ' ' . $kk_ttl . ' — ' . $kk_p['url'];
?>
<article class="p-card" data-tilt data-cat="<?php echo esc_attr( $kk_p['cat'] ? $kk_p['cat']->slug : '' ); ?>" style="--tint:<?php echo esc_attr( $kk_p['tint'] ); ?>">
	<a class="p-link" href="<?php echo esc_url( $kk_p['url'] ); ?>">
		<div class="p-media"><span class="p-plate" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span><?php echo kk_img( $kk_p['img'], 'kk-product', array( 'alt' => $kk_ttl ) ); // phpcs:ignore ?></div>
		<div class="p-body">
			<?php if ( $kk_bn ) : ?><span class="p-brand" style="--bc:<?php echo esc_attr( $kk_bc ); ?>"><?php echo esc_html( $kk_bn ); ?></span><?php endif; ?>
			<h3 class="p-title"><?php echo esc_html( $kk_p['title'] ); ?></h3>
			<div class="p-foot"><?php echo $kk_p['weight'] ? '<span class="p-weight">' . esc_html( $kk_p['weight'] ) . '</span>' : '<span></span>'; ?><span class="p-go" aria-hidden="true"><?php echo kk_arrow(); // phpcs:ignore ?></span></div>
		</div>
	</a>
	<button class="p-quick" type="button" aria-label="<?php echo esc_attr( kk_t( 'quick' ) . ': ' . $kk_ttl ); ?>" data-quick
		data-title="<?php echo esc_attr( $kk_ttl ); ?>" data-img="<?php echo esc_url( $kk_src ); ?>" data-desc="<?php echo esc_attr( kk_excerpt( $kk_p['id'] ) ); ?>"
		data-weight="<?php echo esc_attr( $kk_p['weight'] ); ?>" data-brand="<?php echo esc_attr( $kk_bn ); ?>" data-bc="<?php echo esc_attr( $kk_bc ); ?>"
		data-cat="<?php echo esc_attr( $kk_p['cat'] ? kk_term_name( $kk_p['cat'] ) : '' ); ?>" data-tint="<?php echo esc_attr( $kk_p['tint'] ); ?>"
		data-url="<?php echo esc_url( $kk_p['url'] ); ?>" data-more-label="<?php echo esc_attr( kk_t( 'details' ) ); ?>"
		data-wa="<?php echo esc_url( kk_wa_message_url( $kk_msg ) ); ?>" data-wa-label="<?php echo esc_attr( kk_t( 'order_wa' ) ); ?>"><?php echo kk_ico( 'i-eye' ); // phpcs:ignore ?></button>
</article>
