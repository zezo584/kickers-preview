<?php
/**
 * Export section (home + export page).
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_full  = ! empty( $args['full'] );
$kk_icons = array( 1 => 'globe', 2 => 'badge', 3 => 'smile', 4 => 'factory' );
$kk_paths = array( 'M 300 270 Q 200 80 90 170', 'M 300 270 Q 420 90 440 200', 'M 330 245 Q 300 420 140 380', 'M 330 245 Q 470 330 400 430' );
$kk_pk    = array( 'p12', 'p2', 'p16', 'p9' );
$kk_dots  = '';
for ( $y = 6; $y < 100; $y += 8 ) {
	for ( $x = 4; $x < 200; $x += 8 ) {
		if ( sin( $x * .09 + $y * .07 ) + cos( $x * .05 - $y * .11 ) > .35 ) {
			$kk_dots .= '<circle cx="' . $x . '" cy="' . $y . '" r="1.8"/>';
		}
	}
}
$kk_link = $kk_full ? '#export-form' : kk_page_url( 'export' );
?>
<section class="export crimp-tb">
	<span class="drift" data-speed=".6" style="left:4%;top:12%;--s:48px" aria-hidden="true"><?php echo kk_ico( 'd-wheat' ); // phpcs:ignore ?></span>
	<span class="drift" data-speed="1" style="right:46%;bottom:10%;--s:40px" aria-hidden="true"><?php echo kk_ico( 'd-biscuit' ); // phpcs:ignore ?></span>
	<div class="wrap export-grid">
		<div>
			<h2><?php echo esc_html( kk_optl( 'export_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( kk_optl( 'export_text' ) ); ?></p>
			<div class="ex-points" data-stagger>
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<div class="ex-point" data-reveal><?php echo kk_badge( 'i-' . $kk_icons[ $i ] ); // phpcs:ignore ?><div><h3><?php echo esc_html( kk_optl( 'export_point' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'export_point' . $i . '_d' ) ); ?></p></div></div>
				<?php endfor; ?>
			</div>
			<div class="ex-actions">
				<a class="btn btn-gold" href="<?php echo esc_url( $kk_link ); ?>"><?php echo esc_html( kk_optl( 'export_btn' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a>
				<a class="btn btn-ghost" href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?><?php echo esc_html( kk_t( 'chat' ) ); ?></a>
			</div>
		</div>
		<div class="globe" aria-hidden="true">
			<div class="globe-ring r2"></div><div class="globe-ring"></div>
			<div class="globe-core"><svg viewBox="0 0 200 100" preserveAspectRatio="none"><g fill="rgba(74,44,26,.45)"><?php echo $kk_dots; // phpcs:ignore ?></g></svg></div>
			<svg class="globe-arcs" viewBox="0 0 520 520">
				<?php foreach ( $kk_paths as $i => $d ) : $kk_dur = ( 4.6 + $i * .9 ) . 's'; $kk_beg = ( $i * 1.1 ) . 's'; ?>
					<path d="<?php echo esc_attr( $d ); ?>"/>
					<image href="<?php echo esc_url( kk_asset( 'img/products/' . $kk_pk[ $i ] . '.webp' ) ); ?>" width="50" height="50" x="-25" y="-25" opacity="0"><animateMotion dur="<?php echo esc_attr( $kk_dur ); ?>" begin="<?php echo esc_attr( $kk_beg ); ?>" repeatCount="indefinite" path="<?php echo esc_attr( $d ); ?>"/><animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.12;.85;1" dur="<?php echo esc_attr( $kk_dur ); ?>" begin="<?php echo esc_attr( $kk_beg ); ?>" repeatCount="indefinite"/></image>
				<?php endforeach; ?>
			</svg>
			<span class="globe-pin" style="left:57.7%;top:51.9%"><span><?php echo esc_html( kk_opt( kk_is_en() ? 'fac1_country_en' : 'fac1_country_ar' ) ); ?></span></span>
			<span class="globe-pin" style="left:63.5%;top:47.1%"><span><?php echo esc_html( kk_opt( kk_is_en() ? 'fac2_country_en' : 'fac2_country_ar' ) ); ?></span></span>
		</div>
	</div>
</section>
