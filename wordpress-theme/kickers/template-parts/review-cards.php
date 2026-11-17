<?php
defined( 'ABSPATH' ) || exit;
foreach ( kk_get_reviews( isset( $args['n'] ) ? (int) $args['n'] : 12 ) as $kk_r ) : ?>
	<figure class="review" data-reveal style="margin:0">
		<div class="stars" aria-label="<?php echo (int) $kk_r['rating']; ?>/5"><?php echo str_repeat( kk_ico( 'i-star' ), (int) $kk_r['rating'] ); // phpcs:ignore ?></div>
		<blockquote><?php echo esc_html( $kk_r['text'] ); ?></blockquote>
		<figcaption class="review-who"><span class="av" aria-hidden="true"><?php echo esc_html( mb_substr( $kk_r['name'], 0, 1 ) ); ?></span><span><?php echo esc_html( $kk_r['name'] ); ?><small><?php echo esc_html( $kk_r['city'] ? $kk_r['city'] : kk_t( 'customer' ) ); ?></small></span></figcaption>
	</figure>
<?php
endforeach;
