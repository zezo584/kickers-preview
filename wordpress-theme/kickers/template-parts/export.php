<?php
/**
 * Export section with the interactive country map.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_full  = ! empty( $args['full'] );
$kk_icons = array( 1 => 'globe', 2 => 'truck', 3 => 'badge', 4 => 'factory' );
$kk_li    = (int) array_search( kk_lang(), kk_lang_codes(), true ) + 1;
$kk_fac   = array_map( 'trim', explode( ',', strtoupper( (string) kk_opt( 'export_factories' ) ) ) );
$kk_form  = $kk_full ? '#export-form' : kk_page_url( 'export' ) . '#export-form';
$kk_pack  = $kk_full ? '#packing' : kk_page_url( 'export' ) . '#packing';
?>
<section class="export crimp-tb">
	<span class="drift" data-speed=".6" style="left:4%;top:12%;--s:48px" aria-hidden="true"><?php echo kk_ico( 'd-wheat' ); // phpcs:ignore ?></span>
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
				<a class="btn btn-gold" href="<?php echo esc_url( $kk_form ); ?>"><?php echo esc_html( kk_optl( 'export_btn' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a>
				<a class="btn btn-ghost" href="<?php echo esc_url( $kk_pack ); ?>"><?php echo esc_html( kk_t( 'packingAll' ) ); ?></a>
			</div>
		</div>
		<div class="tilemap" data-tilemap data-l-to="<?php echo esc_attr( kk_t( 'exportTo' ) ); ?>" data-l-fac="<?php echo esc_attr( kk_t( 'factoryHere' ) ); ?>" data-l-req="<?php echo esc_attr( kk_t( 'requestFor' ) ); ?>">
			<div class="tm-head"><h3><?php echo esc_html( kk_optl( 'export_map_title' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'export_map_hint' ) ); ?></p></div>
			<div class="tm-grid" role="group" aria-label="<?php echo esc_attr( kk_optl( 'export_map_title' ) ); ?>">
				<?php
				foreach ( kk_demo_data()['countries'] as $kk_c ) :
					$kk_is = in_array( $kk_c[0], $kk_fac, true );
					?>
					<button type="button" class="hex<?php echo $kk_is ? ' is-fac' : ''; ?>" style="--c:<?php echo (int) $kk_c[5]; ?>;--r:<?php echo (int) $kk_c[6]; ?>;--o:<?php echo $kk_c[6] % 2 ? '.5' : '0'; ?>" data-country="<?php echo esc_attr( $kk_c[ $kk_li ] ); ?>" data-fac="<?php echo $kk_is ? '1' : '0'; ?>"><span class="hex-code"><?php echo esc_html( $kk_c[0] ); ?></span><span class="hex-name"><?php echo esc_html( $kk_c[ $kk_li ] ); ?></span></button>
				<?php endforeach; ?>
			</div>
			<div class="tm-info" aria-live="polite">
				<div class="tm-flag"><?php echo kk_ico( 'i-globe' ); // phpcs:ignore ?></div>
				<div><small data-tm-label><?php echo esc_html( kk_t( 'exportTo' ) ); ?></small><strong data-tm-name><?php echo esc_html( kk_optl( 'export_point1_t' ) ); ?></strong></div>
				<a class="btn btn-gold btn-sm" data-tm-cta href="<?php echo esc_url( $kk_form ); ?>"><?php echo esc_html( kk_optl( 'export_btn' ) ); ?></a>
			</div>
			<div class="tm-regions"><span><?php echo esc_html( kk_t( 'andWorld' ) ); ?></span>
				<?php foreach ( kk_lines( kk_optl( 'export_regions' ) ) as $kk_r ) : ?>
					<button type="button" class="chip chip-dark" data-country="<?php echo esc_attr( $kk_r ); ?>"><?php echo esc_html( $kk_r ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
