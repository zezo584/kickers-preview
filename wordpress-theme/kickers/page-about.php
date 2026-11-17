<?php
/**
 * Template Name: صفحة من نحن (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => kk_optl( 'tagline' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/p2/spread-1.webp' ) ) );
?>
<section class="section">
	<div class="wrap welcome-grid">
		<?php get_template_part( 'template-parts/girl-visual' ); ?>
		<div class="story-text"><p><?php echo esc_html( kk_optl( 'about_intro' ) ); ?></p><p><?php echo esc_html( kk_optl( 'about_history' ) ); ?></p><?php get_template_part( 'template-parts/stats' ); ?></div>
	</div>
</section>
<section class="section timeline-sec" style="padding-top:0">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_t( 'story' ) ); ?></h2></header>
		<ol class="timeline" data-stagger>
			<?php
			$kk_f = kk_factories();
			usort(
				$kk_f,
				function ( $a, $b ) {
					return (int) $a['since'] - (int) $b['since'];
				}
			);
			foreach ( $kk_f as $kk_x ) :
				?>
				<li data-reveal><b><?php echo esc_html( $kk_x['since'] ); ?></b><div><h3><?php echo esc_html( $kk_x['country'] ); ?></h3><p><?php echo esc_html( $kk_x['addr'] ); ?></p></div></li>
			<?php endforeach; ?>
			<li data-reveal><b><?php echo esc_html( gmdate( 'Y' ) ); ?></b><div><h3><?php echo esc_html( kk_optl( 'export_point1_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'export_text' ) ); ?></p></div></li>
		</ol>
	</div>
</section>
<section class="section" style="padding-top:0">
	<div class="wrap">
		<div class="vg-grid" data-stagger>
			<div class="vg vg-red" data-reveal><span class="vg-ico"><?php echo kk_ico( 'i-eye' ); // phpcs:ignore ?></span><h3><?php echo esc_html( kk_t( 'vision' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'about_vision' ) ); ?></p><span class="vg-bg" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span></div>
			<div class="vg vg-gold" data-reveal><span class="vg-ico"><?php echo kk_ico( 'i-target' ); // phpcs:ignore ?></span><h3><?php echo esc_html( kk_t( 'mission' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'about_mission' ) ); ?></p><span class="vg-bg" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></span></div>
		</div>
		<div class="values-box" data-reveal><h3><?php echo esc_html( kk_t( 'values' ) ); ?></h3><ul>
			<?php foreach ( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) kk_optl( 'about_values' ) ) ) ) as $kk_v ) : ?>
				<li><?php echo kk_ico( 'i-check' ); // phpcs:ignore ?><?php echo esc_html( $kk_v ); ?></li>
			<?php endforeach; ?>
		</ul></div>
		<div class="reach" data-reveal><b>?</b><div><h3><?php echo esc_html( kk_t( 'why' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'about_why' ) ); ?></p></div></div>
	</div>
</section>
<?php
get_template_part( 'template-parts/roll' );
get_template_part( 'template-parts/factories' );
get_template_part( 'template-parts/cta' );
get_footer();
