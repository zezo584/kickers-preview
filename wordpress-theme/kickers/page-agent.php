<?php
/**
 * Template Name: صفحة كن وكيلاً (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => kk_optl( 'agent_title' ), 'lead' => kk_optl( 'agent_text' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/p2/dipped-5.webp' ) ) );
$kk_icons = array( 1 => 'badge', 2 => 'smile', 3 => 'truck', 4 => 'globe' );
?>
<section class="section">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'agent_benefits_title' ) ); ?></h2></header>
		<div class="agent-grid" data-stagger>
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="agent-card" data-reveal><?php echo kk_badge( 'i-' . $kk_icons[ $i ] ); // phpcs:ignore ?><h3><?php echo esc_html( kk_optl( 'agent_benefit' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'agent_benefit' . $i . '_d' ) ); ?></p></div>
			<?php endfor; ?>
		</div>
	</div>
</section>
<section class="section roll crimp-tb">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_t( 'agentSteps' ) ); ?></h2></header>
		<div class="ex-steps" data-stagger>
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="ex-step" data-reveal><b><?php echo (int) $i; ?></b><h3><?php echo esc_html( kk_optl( 'export_step' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'export_step' . $i . '_d' ) ); ?></p></div>
			<?php endfor; ?>
		</div>
	</div>
</section>
<section class="section">
	<div class="wrap agent-form-grid">
		<div class="agent-side" data-reveal><?php get_template_part( 'template-parts/girl-visual' ); ?><?php get_template_part( 'template-parts/stats' ); ?></div>
		<?php get_template_part( 'template-parts/contact-form', null, array( 'agent' => true, 'title' => kk_optl( 'agent_form_title' ), 'id' => 'agent-form' ) ); ?>
	</div>
</section>
<?php
get_footer();
