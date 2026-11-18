<?php
/**
 * Product line card: main image, flavour swatches, NEW tag, quick view.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_p   = kk_product( isset( $args['post'] ) ? $args['post'] : get_post() );
$kk_bc  = $kk_p['brand'] ? kk_term_meta( $kk_p['brand'], 'color', '#D41325' ) : '#D41325';
$kk_bn  = kk_brand_label( $kk_p['brand'] );
$kk_ttl = wp_strip_all_tags( $kk_p['title'] );
$kk_vj  = array();
foreach ( $kk_p['variants'] as $kk_v ) {
	$kk_vj[] = array( kk_img_url( $kk_v['id'], 'large' ), $kk_v['name'] );
}
$kk_n   = count( $kk_p['variants'] );
$kk_flv = '';
// Single-flavour mode (home grid): one card per flavour.
if ( isset( $args['variant'] ) && isset( $kk_p['variants'][ (int) $args['variant'] ] ) ) {
	$kk_var      = $kk_p['variants'][ (int) $args['variant'] ];
	$kk_p['img'] = $kk_var['id'];
	$kk_flv      = $kk_var['name'];
	$kk_vj       = array( array( kk_img_url( $kk_var['id'], 'large' ), $kk_flv ) );
	$kk_n        = 0;
}
$kk_alt = $kk_flv ? $kk_ttl . ' – ' . $kk_flv : $kk_ttl;
?>
<article class="p-card<?php echo $kk_p['new'] ? ' is-new' : ''; ?>" data-tilt data-cat="<?php echo esc_attr( $kk_p['cat'] ? $kk_p['cat']->slug : '' ); ?>" style="--tint:<?php echo esc_attr( $kk_p['tint'] ); ?>">
	<?php if ( $kk_p['new'] ) : ?><span class="p-new"><?php echo esc_html( kk_t( 'newLabel' ) ); ?></span><?php endif; ?>
	<a class="p-link" href="<?php echo esc_url( $kk_p['url'] ); ?>">
		<div class="p-media"><span class="p-plate" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span><?php echo kk_img( $kk_p['img'], 'kk-product', array( 'alt' => $kk_alt, 'class' => 'p-main' ) ); // phpcs:ignore ?></div>
		<div class="p-body">
			<?php if ( $kk_bn ) : ?><span class="p-brand" style="--bc:<?php echo esc_attr( $kk_bc ); ?>"><?php echo esc_html( $kk_bn ); ?></span><?php endif; ?>
			<h3 class="p-title"><?php echo esc_html( $kk_ttl ); ?></h3>
			<?php if ( $kk_flv ) : ?><span class="p-flavor"><?php echo esc_html( $kk_flv ); ?></span><?php endif; ?>
			<div class="p-foot"><span class="p-tags"><?php echo $kk_p['weight'] ? '<span class="p-weight">' . esc_html( $kk_p['weight'] ) . '</span>' : ''; ?><?php echo $kk_n > 1 ? '<span class="p-weight">' . (int) $kk_n . ' ' . esc_html( kk_t( 'variantsN' ) ) . '</span>' : ''; ?></span><span class="p-go" aria-hidden="true"><?php echo kk_arrow(); // phpcs:ignore ?></span></div>
		</div>
	</a>
	<?php if ( $kk_n > 1 ) : ?>
		<div class="swatches" role="group" aria-label="<?php echo esc_attr( kk_t( 'flavors' ) ); ?>">
			<?php foreach ( $kk_p['variants'] as $kk_i => $kk_v ) : ?>
				<button type="button" class="sw<?php echo 0 === $kk_i ? ' is-on' : ''; ?>" data-sw="<?php echo esc_url( kk_img_url( $kk_v['id'] ) ); ?>" title="<?php echo esc_attr( $kk_v['name'] ); ?>" aria-label="<?php echo esc_attr( $kk_v['name'] ? $kk_v['name'] : $kk_ttl ); ?>"><?php echo kk_img( $kk_v['id'], 'thumbnail', array( 'alt' => '' ) ); // phpcs:ignore ?></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<button class="p-quick" type="button" aria-label="<?php echo esc_attr( kk_t( 'quickV' ) . ': ' . $kk_alt ); ?>" data-quick
		data-title="<?php echo esc_attr( $kk_alt ); ?>" data-img="<?php echo esc_url( kk_img_url( $kk_p['img'], 'large' ) ); ?>" data-desc="<?php echo esc_attr( kk_excerpt( $kk_p['id'] ) ); ?>"
		data-weight="<?php echo esc_attr( $kk_p['weight'] ); ?>" data-carton="<?php echo esc_attr( $kk_p['carton'] ); ?>" data-brand="<?php echo esc_attr( $kk_bn ); ?>" data-bc="<?php echo esc_attr( $kk_bc ); ?>"
		data-cat="<?php echo esc_attr( $kk_p['cat'] ? kk_term_name( $kk_p['cat'] ) : '' ); ?>" data-tint="<?php echo esc_attr( $kk_p['tint'] ); ?>"
		data-url="<?php echo esc_url( $kk_p['url'] ); ?>" data-more-label="<?php echo esc_attr( kk_t( 'details' ) ); ?>"
		data-wa="<?php echo esc_url( kk_wa_message_url( kk_t( 'order_msg' ) . ' ' . $kk_alt . ' — ' . $kk_p['url'] ) ); ?>" data-wa-label="<?php echo esc_attr( kk_t( 'orderWa' ) ); ?>"
		data-variants="<?php echo esc_attr( wp_json_encode( $kk_vj ) ); ?>"><?php echo kk_ico( 'i-eye' ); // phpcs:ignore ?></button>
</article>
