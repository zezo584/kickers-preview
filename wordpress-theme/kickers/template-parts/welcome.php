<?php
defined( 'ABSPATH' ) || exit;
$kk_icons = array( 1 => 'badge', 2 => 'smile', 3 => 'factory', 4 => 'globe' );
?>
<section class="section welcome">
	<span class="drift" data-speed=".8" style="right:3%;top:10%;--s:44px" aria-hidden="true"><?php echo kk_ico( 'd-wheat' ); // phpcs:ignore ?></span>
	<div class="wrap welcome-grid">
		<?php get_template_part( 'template-parts/girl-visual', null, array( 'chips' => true ) ); ?>
		<div class="welcome-copy">
			<span class="eyebrow-k"><?php echo esc_html( kk_optl( 'tagline' ) ); ?></span>
			<h2><?php echo esc_html( kk_optl( 'welcome_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( kk_optl( 'welcome_text' ) ); ?></p>
			<ul class="w-promises" data-stagger>
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<li data-reveal><?php echo kk_badge( 'i-' . $kk_icons[ $i ] ); // phpcs:ignore ?><div><h3><?php echo esc_html( kk_optl( 'promise' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'promise' . $i . '_d' ) ); ?></p></div></li>
				<?php endfor; ?>
			</ul>
			<a class="btn btn-cocoa" href="<?php echo esc_url( kk_page_url( 'about' ) ); ?>"><?php echo esc_html( kk_t( 'aboutMore' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a>
		</div>
	</div>
</section>
