<?php
defined( 'ABSPATH' ) || exit;
$kk_items = array(
	array( kk_years(), '+', kk_t( 'yearsL' ) ),
	array( (int) kk_opt( 'factories' ), '', kk_t( 'facL' ) ),
	array( (int) kk_opt( 'products_count' ), '', kk_t( 'prodL' ) ),
	array( (int) kk_opt( 'brands_count' ), '', kk_t( 'brandL' ) ),
);
?>
<div class="stats" data-stagger>
	<?php foreach ( $kk_items as $kk_s ) : ?>
		<div class="stat" data-reveal><b data-count="<?php echo (int) $kk_s[0]; ?>" data-suffix="<?php echo esc_attr( $kk_s[1] ); ?>"><?php echo (int) $kk_s[0] . esc_html( $kk_s[1] ); ?></b><span><?php echo esc_html( $kk_s[2] ); ?></span></div>
	<?php endforeach; ?>
</div>
