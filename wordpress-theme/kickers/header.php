<?php
/**
 * Header.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_name = kk_site_name();
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="progress" data-progress aria-hidden="true"></div>
<a class="skip-link" href="#main"><?php echo esc_html( kk_t( 'skip' ) ); ?></a>
<?php if ( is_front_page() ) : ?>
<div class="intro crimp-b" data-intro aria-hidden="true"><div class="intro-logo-wrap"><img class="intro-logo" src="<?php echo esc_url( kk_logo_url() ); ?>" alt="" width="241" height="120"></div></div>
<?php endif; ?>
<header class="site-header" data-header>
	<div class="wrap header-in">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $kk_name ); ?>">
			<img src="<?php echo esc_url( kk_logo_url() ); ?>" alt="<?php echo esc_attr( $kk_name ); ?>" width="241" height="120">
		</a>
		<nav class="main-nav" aria-label="<?php echo esc_attr( kk_t( 'main_menu' ) ); ?>">
			<?php kk_render_nav(); ?>
		</nav>
		<div class="header-actions">
			<?php kk_lang_menu(); ?>
			<a class="btn btn-sm btn-wa-h" href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( kk_t( 'whatsapp' ) ); ?>"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?></a>
			<button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="drawer" aria-label="<?php echo esc_attr( kk_t( 'menu' ) ); ?>"><?php echo kk_ico( 'i-menu' ); // phpcs:ignore ?></button>
		</div>
	</div>
</header>
<div class="drawer" id="drawer" data-drawer role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( kk_t( 'main_menu' ) ); ?>">
	<div class="drawer-top">
		<img src="<?php echo esc_url( kk_logo_url() ); ?>" alt="" width="241" height="120">
		<button class="drawer-close" type="button" data-drawer-close aria-label="<?php echo esc_attr( kk_t( 'close' ) ); ?>"><?php echo kk_ico( 'i-close' ); // phpcs:ignore ?></button>
	</div>
	<nav class="drawer-nav"><?php kk_render_nav( true ); ?></nav>
	<div class="drawer-langs"><?php foreach ( kk_langs() as $kk_c => $kk_l ) : ?><a href="<?php echo esc_url( kk_switch_url( $kk_c ) ); ?>" hreflang="<?php echo esc_attr( $kk_c ); ?>"<?php echo kk_lang() === $kk_c ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $kk_l['name'] ); ?></a><?php endforeach; ?></div>
	<div class="drawer-foot">
		<a class="btn btn-cream" href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?><?php echo esc_html( kk_t( 'chat' ) ); ?></a>
	</div>
</div>
<main id="main" tabindex="-1">
