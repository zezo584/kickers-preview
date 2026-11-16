<?php
defined( 'ABSPATH' ) || exit;
$kk_r = array();
for ( $i = 1; $i <= 3; $i++ ) {
	$kk_n = kk_optl( 'review' . $i . '_name' );
	$kk_t = kk_optl( 'review' . $i . '_t' );
	if ( trim( (string) $kk_n ) && trim( (string) $kk_t ) ) {
		$kk_r[] = array( $kk_n, $kk_t );
	}
}
if ( ! $kk_r ) {
	return;
}
?>
<section class="section">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'reviews_title' ) ); ?></h2></header>
		<div class="reviews-grid" data-stagger>
			<?php foreach ( $kk_r as $kk_x ) : ?>
				<figure class="review" data-reveal style="margin:0">
					<div class="stars" aria-label="5/5"><?php echo str_repeat( kk_ico( 'i-star' ), 5 ); // phpcs:ignore ?></div>
					<blockquote><?php echo esc_html( $kk_x[1] ); ?></blockquote>
					<figcaption class="review-who"><span class="av" aria-hidden="true"><?php echo esc_html( mb_substr( $kk_x[0], 0, 1 ) ); ?></span><span><?php echo esc_html( $kk_x[0] ); ?><small><?php echo esc_html( kk_t( 'customer' ) ); ?></small></span></figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
