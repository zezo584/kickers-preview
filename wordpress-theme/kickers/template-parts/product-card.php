<?php
defined( 'ABSPATH' ) || exit;
$kk_p  = kk_product( isset( $args['post'] ) ? $args['post'] : get_post() );
$kk_bc = $kk_p['brand'] ? kk_term_meta( $kk_p['brand'], 'color', '#D41325' ) : '#D41325';
$kk_bn = $kk_p['brand'] ? ( get_term_meta( $kk_p['brand']->term_id, 'kk_name_en', true ) ? get_term_meta( $kk_p['brand']->term_id, 'kk_name_en', true ) : $kk_p['brand']->name ) : '';
?>
<article class="p-card" data-cat="<?php echo esc_attr( $kk_p['cat'] ? $kk_p['cat']->slug : '' ); ?>" style="--tint:<?php echo esc_attr( $kk_p['tint'] ); ?>">
	<a class="p-link" href="<?php echo esc_url( $kk_p['url'] ); ?>">
		<div class="p-media crimp-b"><?php echo kk_img( $kk_p['img'], 'kk-product', array( 'alt' => wp_strip_all_tags( $kk_p['title'] ) ) ); // phpcs:ignore ?></div>
		<div class="p-body">
			<?php if ( $kk_bn ) : ?><span class="p-brand" style="--bc:<?php echo esc_attr( $kk_bc ); ?>"><?php echo esc_html( $kk_bn ); ?></span><?php endif; ?>
			<h3 class="p-title"><?php echo esc_html( $kk_p['title'] ); ?></h3>
			<div class="p-foot"><?php echo $kk_p['weight'] ? '<span class="p-weight">' . esc_html( $kk_p['weight'] ) . '</span>' : '<span></span>'; ?><span class="p-go" aria-hidden="true"><?php echo kk_arrow(); // phpcs:ignore ?></span></div>
		</div>
	</a>
</article>
