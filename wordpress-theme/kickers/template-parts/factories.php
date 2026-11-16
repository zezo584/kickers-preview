<?php
defined( 'ABSPATH' ) || exit;
$kk_f = kk_factories();
if ( ! $kk_f ) {
	return;
}
?>
<section class="section" style="padding-top:0">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_t( 'factories' ) ); ?></h2></header>
		<div class="fac-grid" data-stagger>
			<?php foreach ( $kk_f as $kk_x ) : ?>
				<div class="fac" data-reveal><?php echo kk_badge( 'i-factory' ); // phpcs:ignore ?><div><h3><?php echo esc_html( $kk_x['country'] ); ?></h3><p><?php echo esc_html( $kk_x['addr'] ); ?></p>
				<?php if ( $kk_x['map'] ) : ?><a class="text-link" href="<?php echo esc_url( $kk_x['map'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( kk_t( 'open_map' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a><?php endif; ?></div></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
