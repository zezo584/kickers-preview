<?php
/**
 * Generic page.
 *
 * @package Kickers
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => has_excerpt() || get_post_meta( get_the_ID(), '_kk_excerpt_en', true ) ? kk_excerpt() : '', 'crumbs' => array( array( get_the_title(), '' ) ) ) );
	?>
	<section class="section"><div class="wrap"><div class="entry"><?php echo kk_content(); // phpcs:ignore ?></div></div></section>
	<?php
endwhile;
get_footer();
