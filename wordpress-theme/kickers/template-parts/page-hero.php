<?php
defined( 'ABSPATH' ) || exit;
$kk_args = wp_parse_args( $args, array( 'title' => '', 'lead' => '', 'crumbs' => array(), 'img' => '' ) );
?>
<section class="page-hero crimp-b">
	<div class="wrap">
		<?php echo kk_breadcrumbs( $kk_args['crumbs'] ); // phpcs:ignore ?>
		<h1><?php echo esc_html( $kk_args['title'] ); ?></h1>
		<?php if ( $kk_args['lead'] ) : ?><p class="lead"><?php echo esc_html( $kk_args['lead'] ); ?></p><?php endif; ?>
	</div>
	<div class="ph-deco" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?><?php if ( $kk_args['img'] ) : ?><img src="<?php echo esc_url( $kk_args['img'] ); ?>" alt=""><?php endif; ?></div>
</section>
