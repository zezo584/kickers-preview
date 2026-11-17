<?php
/**
 * Packing & shipping table (carton contents, unit weight, optional container loads).
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_has20 = false;
$kk_rows  = array();
foreach ( kk_categories() as $kk_c ) {
	$kk_list = array();
	foreach ( kk_cat_products( $kk_c ) as $kk_post ) {
		$kk_p = kk_product( $kk_post );
		if ( $kk_p['cont20'] || $kk_p['cont40'] ) {
			$kk_has20 = true;
		}
		$kk_list[] = $kk_p;
	}
	if ( $kk_list ) {
		$kk_rows[] = array( $kk_c, $kk_list );
	}
}
if ( ! $kk_rows ) {
	return;
}
$kk_cols = 5 + ( $kk_has20 ? 2 : 0 );
?>
<section class="section" id="packing">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'export_packing_title' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'export_packing_text' ) ); ?></p></header>
		<div class="pk-wrap" data-reveal>
			<table class="pk-table">
				<thead><tr><th><?php echo esc_html( kk_t( 'product' ) ); ?></th><th><?php echo esc_html( kk_t( 'weight' ) ); ?></th><th><?php echo esc_html( kk_t( 'carton' ) ); ?></th><th><?php echo esc_html( kk_t( 'units' ) ); ?></th>
				<?php if ( $kk_has20 ) : ?><th><?php echo esc_html( kk_t( 'cont20' ) ); ?></th><th><?php echo esc_html( kk_t( 'cont40' ) ); ?></th><?php endif; ?>
				<th><?php echo esc_html( kk_t( 'flavors' ) ); ?></th></tr></thead>
				<?php foreach ( $kk_rows as $kk_r ) : ?>
					<tbody>
						<tr class="pk-cat"><th colspan="<?php echo (int) $kk_cols; ?>" style="--c:<?php echo esc_attr( kk_term_meta( $kk_r[0], 'color', '#FFC928' ) ); ?>"><?php echo esc_html( kk_term_name( $kk_r[0] ) ); ?></th></tr>
						<?php foreach ( $kk_r[1] as $kk_p ) : ?>
							<tr>
								<td><a class="pk-prod" href="<?php echo esc_url( $kk_p['url'] ); ?>"><?php echo kk_img( $kk_p['img'], 'thumbnail', array( 'alt' => '' ) ); // phpcs:ignore ?><span><?php echo esc_html( wp_strip_all_tags( $kk_p['title'] ) ); ?><?php echo $kk_p['new'] ? ' <em class="p-new sm">' . esc_html( kk_t( 'newLabel' ) ) . '</em>' : ''; ?></span></a></td>
								<td data-l="<?php echo esc_attr( kk_t( 'weight' ) ); ?>"><?php echo esc_html( $kk_p['weight'] ? $kk_p['weight'] : '—' ); ?></td>
								<td data-l="<?php echo esc_attr( kk_t( 'carton' ) ); ?>"><?php echo esc_html( $kk_p['carton'] ? $kk_p['carton'] : '—' ); ?></td>
								<td data-l="<?php echo esc_attr( kk_t( 'units' ) ); ?>"><?php echo $kk_p['units'] ? (int) $kk_p['units'] : '—'; ?></td>
								<?php if ( $kk_has20 ) : ?>
									<td data-l="<?php echo esc_attr( kk_t( 'cont20' ) ); ?>"><?php echo esc_html( $kk_p['cont20'] ? $kk_p['cont20'] : '—' ); ?></td>
									<td data-l="<?php echo esc_attr( kk_t( 'cont40' ) ); ?>"><?php echo esc_html( $kk_p['cont40'] ? $kk_p['cont40'] : '—' ); ?></td>
								<?php endif; ?>
								<td data-l="<?php echo esc_attr( kk_t( 'flavors' ) ); ?>"><?php echo esc_html( kk_flavor_list( $kk_p ) ? kk_flavor_list( $kk_p ) : '—' ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>
