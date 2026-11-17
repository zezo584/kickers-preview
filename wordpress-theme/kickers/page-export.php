<?php
/**
 * Template Name: صفحة التصدير (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => kk_optl( 'export_text' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/p2/spread-1.webp' ) ) );
get_template_part( 'template-parts/export', null, array( 'full' => true ) );
get_template_part( 'template-parts/packing' );
?>
<section class="section" style="padding-top:0">
	<div class="wrap">
		<header class="sec-head"><h2><?php echo esc_html( kk_optl( 'export_steps_title' ) ); ?></h2></header>
		<div class="ex-steps" data-stagger>
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="ex-step" data-reveal><b><?php echo (int) $i; ?></b><h3><?php echo esc_html( kk_optl( 'export_step' . $i . '_t' ) ); ?></h3><p><?php echo esc_html( kk_optl( 'export_step' . $i . '_d' ) ); ?></p></div>
			<?php endfor; ?>
		</div>
	</div>
</section>
<section class="section" style="padding-top:0">
	<div class="wrap" style="max-width:820px">
		<?php
		$kk_subj = kk_lines( kk_optl( 'subjects' ) );
		get_template_part( 'template-parts/contact-form', null, array( 'first_subject' => isset( $kk_subj[1] ) ? $kk_subj[1] : '', 'id' => 'export-form' ) );
		?>
	</div>
</section>
<?php
get_footer();
