<?php
/**
 * Products archive + category / brand archives.
 *
 * @package Kickers
 */

get_header();
$kk_term  = ( is_tax() ) ? get_queried_object() : null;
$kk_brand = $kk_term && 'kk_brand' === $kk_term->taxonomy;
if ( $kk_term ) {
	$kk_name  = kk_term_name( $kk_term );
	$kk_title = $kk_brand ? kk_t( 'brand_lead' ) . ' ' . ( get_term_meta( $kk_term->term_id, 'kk_name_en', true ) ? get_term_meta( $kk_term->term_id, 'kk_name_en', true ) : $kk_name ) : $kk_name;
	$kk_lead  = kk_term_desc( $kk_term );
	$kk_crumb = array( array( kk_t( 'products' ), kk_products_url() ), array( $kk_name, '' ) );
} else {
	$kk_title = kk_t( 'products' );
	$kk_lead  = kk_t( 'products_lead' );
	$kk_crumb = array( array( kk_t( 'products' ), '' ) );
}
$kk_img = '';
if ( have_posts() ) {
	$kk_first = $GLOBALS['wp_query']->posts[0];
	$kk_tid   = get_post_thumbnail_id( $kk_first );
	$kk_img   = $kk_tid ? wp_get_attachment_image_url( $kk_tid, 'kk-product' ) : '';
}
get_template_part( 'template-parts/page-hero', null, array( 'title' => $kk_title, 'lead' => $kk_lead, 'crumbs' => $kk_crumb, 'img' => $kk_img ) );
$kk_total = (int) wp_count_posts( 'kk_product' )->publish;
?>
<section class="section" style="padding-top:clamp(40px,5vw,60px)">
	<div class="wrap">
		<nav class="chips" aria-label="<?php echo esc_attr( kk_t( 'category' ) ); ?>">
			<a class="chip" href="<?php echo esc_url( kk_products_url() ); ?>"<?php echo ! $kk_term ? ' aria-current="page"' : ''; ?>><?php echo esc_html( kk_t( 'all' ) ); ?><span class="n"><?php echo (int) $kk_total; ?></span></a>
			<?php foreach ( kk_categories() as $kk_c ) : ?>
				<a class="chip" href="<?php echo esc_url( get_term_link( $kk_c ) ); ?>"<?php echo ( $kk_term && $kk_term->term_id === $kk_c->term_id ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( kk_term_name( $kk_c ) ); ?><span class="n"><?php echo (int) $kk_c->count; ?></span></a>
			<?php endforeach; ?>
		</nav>
		<?php if ( have_posts() ) : ?>
			<div class="p-grid">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/product-card' );
				}
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => kk_t( 'prev' ), 'next_text' => kk_t( 'next' ) ) ); ?>
		<?php else : ?>
			<p class="lead"><?php echo esc_html( kk_t( 'no_results' ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
