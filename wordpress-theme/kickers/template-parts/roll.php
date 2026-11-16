<?php defined( 'ABSPATH' ) || exit; ?>
<section class="section roll crimp-tb">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'roll_title' ) ); ?></h2></header>
		<div class="roll-track" data-roll>
			<div class="roll-line" aria-hidden="true"></div>
			<div class="roll-ball" aria-hidden="true"><?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?></div>
			<ol class="roll-steps">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<li class="roll-step"><span class="roll-num"><?php echo (int) $i; ?></span><h3><?php echo esc_html( kk_optl( 'step' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'step' . $i . '_d' ) ); ?></p></li>
				<?php endfor; ?>
			</ol>
		</div>
	</div>
</section>
