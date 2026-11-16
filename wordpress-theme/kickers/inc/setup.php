<?php
/**
 * Theme setup, assets and head output.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 240,
				'width'       => 480,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		register_nav_menus(
			array(
				'primary'    => 'القائمة الرئيسية (عربي)',
				'primary_en' => 'Main menu (English)',
				'footer'     => 'روابط الفوتر (عربي)',
				'footer_en'  => 'Footer links (English)',
			)
		);
		add_image_size( 'kk-product', 640, 640, false );
	}
);

/**
 * Styles & scripts.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$css = KK_DIR . '/assets/css/main.css';
		$js  = KK_DIR . '/assets/js/main.js';
		wp_enqueue_style( 'kk-fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Montserrat:wght@500;600;700;800;900&display=swap', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'kk-main', KK_URI . '/assets/css/main.css', array(), KK_VERSION . '.' . ( file_exists( $css ) ? filemtime( $css ) : '' ) );
		wp_enqueue_script( 'kk-main', KK_URI . '/assets/js/main.js', array(), KK_VERSION . '.' . ( file_exists( $js ) ? filemtime( $js ) : '' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script(
			'kk-main',
			'KK_DATA',
			array(
				'ajax'  => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'kk_contact' ),
				'lang'  => kk_lang(),
				'i18n'  => array(
					'required' => kk_t( 'required' ),
					'sent'     => kk_t( 'sent' ),
					'error'    => kk_t( 'error' ),
				),
			)
		);
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

add_filter(
	'wp_resource_hints',
	function ( $urls, $type ) {
		if ( 'preconnect' === $type ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		}
		return $urls;
	},
	10,
	2
);

// Swap no-js → js as early as possible (prevents flashes of hidden content).
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.className=document.documentElement.className.replace(/\\bno-js\\b/,'js');</script>\n";
		echo '<meta name="theme-color" content="#ED1C2E">' . "\n";
	},
	0
);

// Lighter front end.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/**
 * Favicon = brand logo (until a Site Icon is chosen in Customizer → Site Identity).
 */
add_action(
	'wp_head',
	function () {
		if ( has_site_icon() ) {
			return;
		}
		echo '<link rel="icon" href="' . esc_url( kk_asset( 'img/brand/favicon.ico' ) ) . '" sizes="any">' . "\n";
		echo '<link rel="icon" type="image/png" sizes="192x192" href="' . esc_url( kk_asset( 'img/brand/icon-192.png' ) ) . '">' . "\n";
		echo '<link rel="apple-touch-icon" href="' . esc_url( kk_asset( 'img/brand/apple-touch-icon.png' ) ) . '">' . "\n";
	},
	3
);

/**
 * Preload the first hero banner image (faster LCP).
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		$src = kk_first_slide_image();
		if ( $src ) {
			echo '<link rel="preload" as="image" href="' . esc_url( $src ) . '" fetchpriority="high">' . "\n";
		}
	},
	2
);

/**
 * Inline SVG sprite right after <body>.
 */
add_action(
	'wp_body_open',
	function () {
		$f = KK_DIR . '/template-parts/sprite.svg';
		if ( file_exists( $f ) ) {
			echo file_get_contents( $f ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		}
	},
	1
);

/**
 * Body class helper: pages without the hero always show the WhatsApp button.
 */
add_filter(
	'body_class',
	function ( $c ) {
		if ( ! is_front_page() ) {
			$c[] = 'no-hero';
		}
		return $c;
	}
);

/**
 * Header navigation: uses the assigned WordPress menu for the current language, or a smart default.
 */
function kk_nav_items() {
	$items  = array();
	$cats   = kk_categories();
	$sub    = array();
	foreach ( $cats as $c ) {
		$sub[] = array(
			'label' => kk_term_name( $c ),
			'url'   => get_term_link( $c ),
			'color' => kk_term_meta( $c, 'color', '#ED1C2E' ),
		);
	}
	$items[] = array( 'label' => kk_t( 'home' ), 'url' => home_url( '/' ), 'current' => is_front_page() );
	$items[] = array( 'label' => kk_t( 'about' ), 'url' => kk_page_url( 'about' ), 'current' => is_page( 'about' ) );
	$items[] = array(
		'label'   => kk_t( 'products' ),
		'url'     => kk_products_url(),
		'current' => is_post_type_archive( 'kk_product' ) || is_singular( 'kk_product' ) || is_tax( array( 'kk_product_cat', 'kk_brand' ) ),
		'sub'     => $sub,
	);
	$items[] = array( 'label' => kk_t( 'contact' ), 'url' => kk_page_url( 'contact' ), 'current' => is_page( 'contact' ) );
	return $items;
}

function kk_menu_location() {
	return kk_is_en() ? 'primary_en' : 'primary';
}

function kk_render_nav( $drawer = false ) {
	$loc = kk_menu_location();
	if ( has_nav_menu( $loc ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $loc,
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => false,
				'menu_class'     => $drawer ? '' : 'menu',
			)
		);
		return;
	}
	echo '<ul>';
	foreach ( kk_nav_items() as $it ) {
		$cur = ! empty( $it['current'] ) ? ' aria-current="page"' : '';
		if ( ! $drawer && ! empty( $it['sub'] ) ) {
			echo '<li class="has-sub"><a href="' . esc_url( $it['url'] ) . '"' . $cur . '>' . esc_html( $it['label'] ) . '</a><ul class="sub-menu">'; // phpcs:ignore
			foreach ( $it['sub'] as $s ) {
				if ( is_wp_error( $s['url'] ) ) {
					continue;
				}
				echo '<li><a href="' . esc_url( $s['url'] ) . '"><span class="dot" style="--c:' . esc_attr( $s['color'] ) . '"></span>' . esc_html( $s['label'] ) . '</a></li>';
			}
			echo '<li><a href="' . esc_url( kk_products_url() ) . '"><span class="dot" style="--c:#4A2C1A"></span>' . esc_html( kk_t( 'view_all' ) ) . '</a></li></ul></li>';
		} else {
			echo '<li><a href="' . esc_url( $it['url'] ) . '"' . $cur . '>' . esc_html( $it['label'] ) . '</a></li>'; // phpcs:ignore
		}
	}
	echo '</ul>';
}
