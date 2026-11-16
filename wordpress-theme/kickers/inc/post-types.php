<?php
/**
 * Products, categories, brands and hero slides.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'kk_product',
			array(
				'labels'        => array(
					'name'               => 'المنتجات',
					'singular_name'      => 'منتج',
					'menu_name'          => 'المنتجات',
					'add_new'            => 'أضف منتج',
					'add_new_item'       => 'إضافة منتج جديد',
					'edit_item'          => 'تعديل المنتج',
					'new_item'           => 'منتج جديد',
					'view_item'          => 'عرض المنتج',
					'search_items'       => 'بحث في المنتجات',
					'not_found'          => 'لا توجد منتجات',
					'all_items'          => 'كل المنتجات',
					'featured_image'     => 'صورة المنتج',
					'set_featured_image' => 'اختيار صورة المنتج',
				),
				'public'        => true,
				'has_archive'   => 'products',
				'rewrite'       => array( 'slug' => 'products', 'with_front' => false ),
				'menu_icon'     => 'dashicons-carrot',
				'menu_position' => 5,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
				'show_in_rest'  => false,
			)
		);

		register_taxonomy(
			'kk_product_cat',
			'kk_product',
			array(
				'labels'            => array(
					'name'          => 'تصنيفات المنتجات',
					'singular_name' => 'تصنيف',
					'menu_name'     => 'التصنيفات',
					'add_new_item'  => 'إضافة تصنيف',
					'edit_item'     => 'تعديل التصنيف',
					'search_items'  => 'بحث في التصنيفات',
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'product-category', 'with_front' => false ),
				'show_in_rest'      => true,
			)
		);

		register_taxonomy(
			'kk_brand',
			'kk_product',
			array(
				'labels'            => array(
					'name'          => 'العلامات التجارية',
					'singular_name' => 'علامة تجارية',
					'menu_name'     => 'العلامات التجارية',
					'add_new_item'  => 'إضافة علامة تجارية',
					'edit_item'     => 'تعديل العلامة',
					'search_items'  => 'بحث في العلامات',
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'brand', 'with_front' => false ),
				'show_in_rest'      => true,
			)
		);

		register_post_type(
			'kk_slide',
			array(
				'labels'              => array(
					'name'               => 'بانرات السلايدر',
					'singular_name'      => 'بانر',
					'menu_name'          => 'بانرات السلايدر',
					'add_new'            => 'أضف بانر',
					'add_new_item'       => 'إضافة بانر جديد',
					'edit_item'          => 'تعديل البانر',
					'all_items'          => 'كل البانرات',
					'not_found'          => 'لا توجد بانرات',
					'featured_image'     => 'صورة خلفية (اختياري)',
					'set_featured_image' => 'اختيار صورة خلفية',
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_icon'           => 'dashicons-images-alt2',
				'menu_position'       => 6,
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
				'exclude_from_search' => true,
				'show_in_rest'        => false,
			)
		);
	}
);

// Products archive: show everything, ordered like the admin (menu order).
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_post_type_archive( 'kk_product' ) || $q->is_tax( array( 'kk_product_cat', 'kk_brand' ) ) ) {
			$q->set( 'posts_per_page', 48 );
			$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		}
	}
);

// Flush rewrite rules once after the theme is (re)activated.
add_action(
	'after_switch_theme',
	function () {
		update_option( 'kk_flush_rewrite', 1 );
	}
);
add_action(
	'init',
	function () {
		if ( get_option( 'kk_flush_rewrite' ) ) {
			flush_rewrite_rules();
			delete_option( 'kk_flush_rewrite' );
		}
	},
	99
);

/**
 * Normalised slides for the hero (from "بانرات السلايدر"; built-in defaults until the first one is added).
 */
function kk_get_slides() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$posts = get_posts(
		array(
			'post_type'      => 'kk_slide',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		)
	);
	foreach ( $posts as $p ) {
		$m    = function ( $k ) use ( $p ) {
			return get_post_meta( $p->ID, '_kk_' . $k, true );
		};
		$lang = function ( $k ) use ( $p ) {
			return kk_meta( $p->ID, $k );
		};
		$imgs = array();
		foreach ( array( 'img1', 'img2', 'img3' ) as $k ) {
			$id = (int) $m( $k );
			if ( $id ) {
				$src = wp_get_attachment_image_url( $id, 'large' );
				if ( $src ) {
					$imgs[] = array( 'id' => $id, 'src' => $src );
				}
			}
		}
		$title    = kk_is_en() && $m( 'title_en' ) ? $m( 'title_en' ) : $p->post_title;
		$cache[] = array(
			'mode'       => $m( 'mode' ) ? $m( 'mode' ) : 'design',
			'theme'      => $m( 'theme' ) ? $m( 'theme' ) : 'red',
			'word'       => $m( 'word' ),
			'title'      => $title,
			'sub'        => $lang( 'sub' ),
			'btn1'       => $lang( 'btn1' ),
			'btn1_url'   => kk_resolve_link( $m( 'btn1_url' ) ),
			'btn2'       => $lang( 'btn2' ),
			'btn2_url'   => kk_resolve_link( $m( 'btn2_url' ) ? $m( 'btn2_url' ) : 'whatsapp' ),
			'images'     => $imgs,
			'decor'      => $m( 'decor' ) ? $m( 'decor' ) : 'wheat',
			'label'      => $lang( 'label' ) ? $lang( 'label' ) : $title,
			'photo'      => get_post_thumbnail_id( $p->ID ),
			'banner'     => (int) $m( 'banner' ),
			'banner_m'   => (int) $m( 'banner_m' ),
			'banner_url' => $m( 'banner_url' ) ? kk_resolve_link( $m( 'banner_url' ) ) : '',
			'banner_alt' => $title,
		);
	}
	if ( empty( $cache ) ) {
		$demo = kk_demo_data();
		$en   = kk_is_en();
		foreach ( $demo['slides'] as $s ) {
			$imgs = array();
			foreach ( $s['images'] as $im ) {
				$imgs[] = array( 'id' => 0, 'src' => kk_asset( 'img/products/' . $im . '.webp' ) );
			}
			$cache[] = array(
				'mode'       => 'design',
				'theme'      => $s['theme'],
				'word'       => $s['word'],
				'title'      => $en ? $s['title_en'] : $s['title_ar'],
				'sub'        => $en ? $s['sub_en'] : $s['sub_ar'],
				'btn1'       => $en ? $s['btn1_en'] : $s['btn1_ar'],
				'btn1_url'   => kk_resolve_link( $s['btn1_url'] ),
				'btn2'       => $en ? $s['btn2_en'] : $s['btn2_ar'],
				'btn2_url'   => kk_resolve_link( $s['btn2_url'] ),
				'images'     => $imgs,
				'decor'      => $s['decor'],
				'label'      => $en ? $s['label_en'] : $s['label_ar'],
				'photo'      => 0,
				'banner'     => 0,
				'banner_m'   => 0,
				'banner_url' => '',
				'banner_alt' => '',
			);
		}
	}
	return $cache;
}

function kk_first_slide_image() {
	$s = kk_get_slides();
	if ( empty( $s ) ) {
		return '';
	}
	if ( 'image' === $s[0]['mode'] && $s[0]['banner'] ) {
		return wp_get_attachment_image_url( $s[0]['banner'], 'full' );
	}
	return ! empty( $s[0]['images'][0]['src'] ) ? $s[0]['images'][0]['src'] : '';
}
