<?php
defined( 'ABSPATH' ) || exit;
$kk_id  = (int) kk_opt( 'welcome_image' );
$kk_src = $kk_id ? wp_get_attachment_image_url( $kk_id, 'large' ) : kk_asset( 'img/girl.webp' );
?>
<div class="welcome-visual" data-reveal="pop">
	<div class="wv-plate" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></div><div class="wv-ring" aria-hidden="true"></div>
	<img class="wv-girl" src="<?php echo esc_url( $kk_src ); ?>" alt="<?php echo esc_attr( kk_optl( 'welcome_title' ) ); ?>" loading="lazy" width="880" height="1100">
	<?php if ( ! empty( $args['chips'] ) ) : ?>
		<span class="wv-chip c1"><?php echo kk_ico( 'i-badge' ); // phpcs:ignore ?><?php echo (int) kk_years(); ?>+ <?php echo esc_html( kk_t( 'yearsL' ) ); ?></span>
		<span class="wv-chip c2"><?php echo kk_ico( 'i-globe' ); // phpcs:ignore ?><?php echo esc_html( kk_optl( 'export_point1_t' ) ); ?></span>
	<?php endif; ?>
	<span class="wv-s" style="left:4%;top:18%;width:46px" aria-hidden="true"><?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?></span>
	<span class="wv-s" style="right:6%;top:6%;width:30px;color:#FFC928" aria-hidden="true"><?php echo kk_ico( 'd-star' ); // phpcs:ignore ?></span>
</div>
