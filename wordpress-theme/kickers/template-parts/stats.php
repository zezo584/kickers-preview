<?php
defined( 'ABSPATH' ) || exit;
$kk_pc = (int) wp_count_posts( 'kk_product' )->publish;
$kk_bc = count( kk_brands() );
$kk_items = array(
	array( (int) kk_opt( 'years' ), '+', kk_t( 'years_l' ) ),
	array( (int) kk_opt( 'factories' ), '', kk_t( 'fac_l' ) ),
	array( $kk_pc ? $kk_pc : 17, '', kk_t( 'prod_l' ) ),
	array( $kk_bc ? $kk_bc : 6, '', kk_t( 'brand_l' ) ),
);
?>
<div class="stats" data-stagger<?php echo ! empty( $args['wide'] ) ? ' style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))"' : ''; ?>>
	<?php foreach ( $kk_items as $kk_s ) : ?>
		<div class="stat" data-reveal><b data-count="<?php echo (int) $kk_s[0]; ?>" data-suffix="<?php echo esc_attr( $kk_s[1] ); ?>"><?php echo (int) $kk_s[0] . esc_html( $kk_s[1] ); ?></b><span><?php echo esc_html( $kk_s[2] ); ?></span></div>
	<?php endforeach; ?>
</div>
