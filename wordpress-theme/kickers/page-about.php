<?php
/**
 * Template Name: صفحة من نحن (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => kk_optl( 'about_hero' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/products/p12.webp' ) ) );
?>
<section class="section">
	<div class="wrap story-grid">
		<div class="story-text"><?php echo kk_paragraphs( kk_optl( 'story' ) ); // phpcs:ignore ?></div>
		<?php get_template_part( 'template-parts/about-visual' ); ?>
	</div>
</section>
<section class="section" style="padding-top:0">
	<div class="wrap">
		<div class="vg-grid" data-stagger>
			<div class="vg vg-red" data-reveal><span class="vg-ico"><?php echo kk_ico( 'i-eye' ); // phpcs:ignore ?></span><h2 class="h3" style="font-size:clamp(1.6rem,2.6vw,2.2rem);font-weight:900;margin:18px 0 10px"><?php echo esc_html( kk_t( 'vision' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'vision' ) ); ?></p><span class="vg-bg" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span></div>
			<div class="vg vg-gold" data-reveal><span class="vg-ico"><?php echo kk_ico( 'i-target' ); // phpcs:ignore ?></span><h2 class="h3" style="font-size:clamp(1.6rem,2.6vw,2.2rem);font-weight:900;margin:18px 0 10px"><?php echo esc_html( kk_t( 'goal' ) ); ?></h2><p><?php echo esc_html( kk_optl( 'goal' ) ); ?></p><span class="vg-bg" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span></div>
		</div>
		<div class="reach" data-reveal><b><span data-count="<?php echo (int) kk_opt( 'years' ); ?>" data-suffix="+"><?php echo (int) kk_opt( 'years' ); ?>+</span></b><div><h3><?php echo esc_html( kk_t( 'reach_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'reach' ) ); ?></p></div></div>
	</div>
</section>
<?php
get_template_part( 'template-parts/roll' );
echo '<section class="section"><div class="wrap">';
get_template_part( 'template-parts/stats', null, array( 'wide' => true ) );
echo '</div></section>';
get_template_part( 'template-parts/factories' );
get_template_part( 'template-parts/cta' );
get_footer();
