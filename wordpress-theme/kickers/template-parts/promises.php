<?php
defined( 'ABSPATH' ) || exit;
$kk_icons = array( 1 => 'badge', 2 => 'smile', 3 => 'factory', 4 => 'truck' );
?>
<section class="section promises" aria-label="<?php echo esc_attr( kk_t( 'promise_label' ) ); ?>">
	<div class="wrap promise-row" data-stagger>
		<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
			<div class="promise" data-reveal><?php echo kk_badge( 'i-' . $kk_icons[ $i ] ); // phpcs:ignore ?><h3><?php echo esc_html( kk_optl( 'promise' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'promise' . $i . '_d' ) ); ?></p></div>
		<?php endfor; ?>
	</div>
</section>
