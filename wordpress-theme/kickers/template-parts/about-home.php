<?php defined( 'ABSPATH' ) || exit; ?>
<section class="section">
	<div class="wrap about-split">
		<?php get_template_part( 'template-parts/about-visual' ); ?>
		<div class="about-copy">
			<h2><?php echo esc_html( kk_optl( 'about_title' ) ); ?></h2>
			<p><?php echo esc_html( kk_optl( 'about_text' ) ); ?></p>
			<?php get_template_part( 'template-parts/stats' ); ?>
			<a class="btn btn-cocoa" href="<?php echo esc_url( kk_page_url( 'about' ) ); ?>"><?php echo esc_html( kk_t( 'about_more' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a>
		</div>
	</div>
</section>
