<?php
defined( 'ABSPATH' ) || exit;
if ( ! kk_opt( 'show_cta' ) ) {
	return;
}
$kk_a = kk_asset( 'img/products/p12.webp' );
$kk_b = kk_asset( 'img/products/p4.webp' );
?>
<section class="cta-band crimp-tb">
	<img class="float-p fp-1" src="<?php echo esc_url( $kk_a ); ?>" alt="" loading="lazy">
	<img class="float-p fp-2" src="<?php echo esc_url( $kk_b ); ?>" alt="" loading="lazy">
	<span class="float-s" style="left:9%;top:62%" aria-hidden="true"><?php echo kk_ico( 'd-wheat' ); // phpcs:ignore ?></span>
	<span class="float-s" style="right:12%;top:12%;width:38px" aria-hidden="true"><?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?></span>
	<div class="wrap">
		<h2><?php echo esc_html( kk_optl( 'cta_title' ) ); ?></h2>
		<p><?php echo esc_html( kk_optl( 'cta_text' ) ); ?></p>
		<div class="cta-actions">
			<a class="btn btn-cream" href="<?php echo esc_url( kk_products_url() ); ?>"><?php echo esc_html( kk_t( 'browse' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?><?php echo esc_html( kk_t( 'chat' ) ); ?></a>
		</div>
	</div>
</section>
