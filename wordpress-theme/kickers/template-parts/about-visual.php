<?php
defined( 'ABSPATH' ) || exit;
$kk_srcs = array();
foreach ( array( 'rosyl-tella-350g', 'rosyl-sity-tea-biscuits-65g', 'lilaf-wan-vanilla-14g' ) as $kk_i => $kk_slug ) {
	$kk_pp = get_page_by_path( $kk_slug, OBJECT, 'kk_product' );
	$kk_id = $kk_pp ? get_post_thumbnail_id( $kk_pp ) : 0;
	$kk_srcs[] = $kk_id ? wp_get_attachment_image_url( $kk_id, 'kk-product' ) : kk_asset( 'img/products/' . array( 'p16', 'p2', 'p9' )[ $kk_i ] . '.webp' );
}
?>
<div class="about-visual crimp-b" data-reveal="pop" aria-hidden="true">
	<div class="plate-static"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></div>
	<div class="av-word">Kickers</div>
	<img class="av-p av-p1" src="<?php echo esc_url( $kk_srcs[0] ); ?>" alt="" loading="lazy">
	<img class="av-p av-p2" src="<?php echo esc_url( $kk_srcs[1] ); ?>" alt="" loading="lazy">
	<img class="av-p av-p3" src="<?php echo esc_url( $kk_srcs[2] ); ?>" alt="" loading="lazy">
	<span class="av-s" style="left:6%;top:8%;width:42px"><?php echo kk_ico( 'd-wheat' ); // phpcs:ignore ?></span>
	<span class="av-s" style="left:82%;top:10%;width:50px"><?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?></span>
	<span class="av-s" style="left:86%;top:44%;width:38px"><?php echo kk_ico( 'd-hazel' ); // phpcs:ignore ?></span>
</div>
