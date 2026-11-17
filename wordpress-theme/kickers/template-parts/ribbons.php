<?php
defined( 'ABSPATH' ) || exit;
$kk_a = '';
foreach ( kk_query_products( array( 'posts_per_page' => 30 ) )->posts as $kk_p ) {
	$kk_a .= '<span>' . esc_html( wp_strip_all_tags( get_the_title( $kk_p ) ) ) . '</span>' . kk_ico( 'd-biscuit' );
}
$kk_b = '';
foreach ( kk_categories() as $kk_c ) {
	$kk_b .= '<span>' . esc_html( kk_term_name( $kk_c ) ) . '</span>' . kk_ico( 'd-star' );
}
if ( '' === $kk_a ) {
	return;
}
?>
<div class="ribbons" aria-hidden="true">
	<div class="ribbon ribbon-gold crimp-tb"><div class="ribbon-track"><?php echo $kk_a . $kk_a; // phpcs:ignore ?></div></div>
	<div class="ribbon ribbon-red crimp-tb"><div class="ribbon-track"><?php echo str_repeat( $kk_b, 6 ); // phpcs:ignore ?></div></div>
</div>
