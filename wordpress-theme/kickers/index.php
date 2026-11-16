<?php
/**
 * Fallback listing (blog, search, archives).
 *
 * @package Kickers
 */

get_header();
$kk_title = is_search() ? sprintf( kk_t( 'search_for' ), get_search_query() ) : ( is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : kk_t( 'news' ) );
get_template_part( 'template-parts/page-hero', null, array( 'title' => $kk_title, 'crumbs' => array( array( $kk_title, '' ) ) ) );
?>
<section class="section"><div class="wrap">
	<?php if ( have_posts() ) : ?>
		<div class="p-grid">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( 'kk_product' === get_post_type() ) {
				get_template_part( 'template-parts/product-card' );
				continue;
			}
			?>
			<article class="p-card"><a class="p-link" href="<?php the_permalink(); ?>">
				<div class="p-media crimp-b"><?php echo has_post_thumbnail() ? kk_img( get_post_thumbnail_id(), 'medium_large', array( 'alt' => '' ) ) : '<img src="' . esc_url( kk_asset( 'img/brand/logo.webp' ) ) . '" alt="">'; // phpcs:ignore ?></div>
				<div class="p-body"><h2 class="p-title"><?php the_title(); ?></h2><p><?php echo esc_html( wp_trim_words( kk_excerpt(), 18 ) ); ?></p></div>
			</a></article>
		<?php endwhile; ?>
		</div>
		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<p class="lead"><?php echo esc_html( kk_t( 'no_results' ) ); ?></p>
	<?php endif; ?>
</div></section>
<?php
get_footer();
