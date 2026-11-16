<?php
defined( 'ABSPATH' ) || exit;
$kk_b = '';
$kk_brands = kk_brands();
if ( $kk_brands ) {
	foreach ( $kk_brands as $kk_t ) {
		$kk_n  = get_term_meta( $kk_t->term_id, 'kk_name_en', true );
		$kk_b .= '<span class="latin">' . esc_html( $kk_n ? $kk_n : $kk_t->name ) . '</span>' . kk_ico( 'd-biscuit' );
	}
} else {
	foreach ( kk_demo_data()['brands'] as $kk_x ) {
		$kk_b .= '<span class="latin">' . esc_html( $kk_x['name_en'] ) . '</span>' . kk_ico( 'd-biscuit' );
	}
}
$kk_p = '';
for ( $i = 1; $i <= 4; $i++ ) {
	$kk_p .= '<span>' . esc_html( kk_optl( 'promise' . $i . '_t' ) ) . '</span>' . kk_ico( 'd-star' );
}
?>
<div class="ribbons" aria-hidden="true">
	<div class="ribbon ribbon-gold crimp-tb"><div class="ribbon-track"><?php echo str_repeat( $kk_b, 4 ); // phpcs:ignore ?></div></div>
	<div class="ribbon ribbon-red crimp-tb"><div class="ribbon-track"><?php echo str_repeat( $kk_p, 4 ); // phpcs:ignore ?></div></div>
</div>
