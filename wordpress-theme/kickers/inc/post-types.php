<?php
/**
 * Products, categories, brands, banners and reviews.
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
					'view_item'          => 'عرض المنتج',
					'search_items'       => 'بحث في المنتجات',
					'not_found'          => 'لا توجد منتجات',
					'all_items'          => 'كل المنتجات',
					'featured_image'     => 'الصورة الرئيسية',
					'set_featured_image' => 'اختيار الصورة الرئيسية',
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
				'labels'            => array( 'name' => 'أنواع المنتجات', 'singular_name' => 'نوع', 'menu_name' => 'الأنواع (سادة/محشو...)', 'add_new_item' => 'إضافة نوع', 'edit_item' => 'تعديل النوع' ),
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
				'labels'            => array( 'name' => 'العلامات التجارية', 'singular_name' => 'علامة تجارية', 'menu_name' => 'العلامات التجارية', 'add_new_item' => 'إضافة علامة', 'edit_item' => 'تعديل العلامة' ),
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
				'labels'              => array( 'name' => 'بانرات إضافية', 'singular_name' => 'بانر', 'menu_name' => 'بانرات إضافية', 'add_new' => 'أضف بانر', 'add_new_item' => 'إضافة بانر', 'edit_item' => 'تعديل البانر', 'all_items' => 'كل البانرات الإضافية', 'featured_image' => 'صورة خلفية (اختياري)', 'set_featured_image' => 'اختيار صورة خلفية' ),
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'           => 'dashicons-images-alt2',
				'menu_position'       => 6,
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
				'exclude_from_search' => true,
				'show_in_rest'        => false,
			)
		);
		register_post_type(
			'kk_review',
			array(
				'labels'          => array( 'name' => 'آراء العملاء', 'singular_name' => 'رأي', 'menu_name' => 'آراء العملاء', 'add_new' => 'أضف رأي', 'add_new_item' => 'إضافة رأي', 'edit_item' => 'تعديل الرأي', 'all_items' => 'كل الآراء' ),
				'public'          => false,
				'show_ui'         => true,
				'menu_icon'       => 'dashicons-star-filled',
				'menu_position'   => 7,
				'supports'        => array( 'title', 'editor', 'page-attributes' ),
				'show_in_rest'    => false,
			)
		);
	}
);

add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_post_type_archive( 'kk_product' ) || $q->is_tax( array( 'kk_product_cat', 'kk_brand' ) ) ) {
			$q->set( 'posts_per_page', 60 );
			$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		}
	}
);

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
 * Hero banners:
 * 1) every product marked "جديد" gets its own animated banner,
 * 2) every product type (category) gets a banner,
 * 3) then any extra banners added under "بانرات إضافية".
 */
function kk_get_slides() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$wa    = kk_wa_url();

	if ( kk_opt( 'hero_auto' ) ) {
		$new = kk_query_products( array( 'posts_per_page' => 6, 'meta_key' => '_kk_new', 'meta_value' => '1' ) )->posts; // phpcs:ignore
		$i   = 0;
		foreach ( $new as $post ) {
			$p    = kk_product( $post );
			$imgs = array();
			foreach ( array_slice( $p['variants'], 0, 3 ) as $v ) {
				$imgs[] = array( 'id' => $v['id'], 'src' => kk_img_url( $v['id'], 'large' ) );
			}
			$theme   = get_post_meta( $p['id'], '_kk_banner_theme', true );
			$cache[] = array(
				'mode'   => 'design',
				'new'    => true,
				'theme'  => $theme ? $theme : ( $i % 2 ? 'red' : 'gold' ),
				'word'   => 'NEW',
				'title'  => kk_t( 'newPrefix' ) . wp_strip_all_tags( $p['title'] ),
				'sub'    => kk_excerpt( $p['id'] ),
				'btn1'   => kk_t( 'details' ),
				'btn1_url' => $p['url'],
				'btn2'   => kk_t( 'chat' ),
				'btn2_url' => $wa,
				'images' => $imgs,
				'decor'  => $p['cat'] ? kk_term_meta( $p['cat'], 'banner_decor', 'biscuit' ) : 'biscuit',
				'label'  => wp_strip_all_tags( $p['title'] ),
				'photo'  => 0,
			);
			++$i;
		}
		foreach ( kk_categories() as $c ) {
			if ( '0' === (string) kk_term_meta( $c, 'banner_show', '1' ) ) {
				continue;
			}
			$imgs  = array();
			$posts = kk_cat_products( $c, 6 );
			foreach ( $posts as $post ) {
				$v = kk_variants( $post->ID );
				if ( $v && count( $imgs ) < 3 ) {
					$imgs[] = array( 'id' => $v[0]['id'], 'src' => kk_img_url( $v[0]['id'], 'large' ) );
				}
			}
			foreach ( $posts as $post ) {
				foreach ( array_slice( kk_variants( $post->ID ), 1 ) as $v ) {
					if ( count( $imgs ) < 3 ) {
						$imgs[] = array( 'id' => $v['id'], 'src' => kk_img_url( $v['id'], 'large' ) );
					}
				}
			}
			$title   = kk_term_ml( $c, 'banner_title' );
			$cache[] = array(
				'mode'     => 'design',
				'new'      => false,
				'theme'    => kk_term_meta( $c, 'banner_theme', 'red' ),
				'word'     => kk_term_meta( $c, 'banner_word', '' ),
				'title'    => $title ? $title : kk_term_name( $c ),
				'sub'      => kk_term_ml( $c, 'banner_sub' ) ? kk_term_ml( $c, 'banner_sub' ) : kk_term_desc( $c ),
				'btn1'     => kk_t( 'browse' ),
				'btn1_url' => get_term_link( $c ),
				'btn2'     => kk_t( 'chat' ),
				'btn2_url' => $wa,
				'images'   => $imgs,
				'decor'    => kk_term_meta( $c, 'banner_decor', 'wheat' ),
				'label'    => kk_term_name( $c ),
				'photo'    => (int) kk_term_meta( $c, 'banner_photo', 0 ),
			);
		}
	}

	$posts = get_posts( array( 'post_type' => 'kk_slide', 'post_status' => 'publish', 'posts_per_page' => 12, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	foreach ( $posts as $p ) {
		$m    = function ( $k ) use ( $p ) {
			return get_post_meta( $p->ID, '_kk_' . $k, true );
		};
		$imgs = array();
		foreach ( array( 'img1', 'img2', 'img3' ) as $k ) {
			$id = (int) $m( $k );
			if ( $id && kk_img_url( $id, 'large' ) ) {
				$imgs[] = array( 'id' => $id, 'src' => kk_img_url( $id, 'large' ) );
			}
		}
		$title   = get_the_title( $p );
		$cache[] = array(
			'mode'       => $m( 'mode' ) ? $m( 'mode' ) : 'design',
			'new'        => '1' === $m( 'is_new' ),
			'theme'      => $m( 'theme' ) ? $m( 'theme' ) : 'red',
			'word'       => $m( 'word' ),
			'title'      => $title,
			'sub'        => kk_meta( $p->ID, 'sub' ),
			'btn1'       => kk_meta( $p->ID, 'btn1' ),
			'btn1_url'   => kk_resolve_link( $m( 'btn1_url' ) ),
			'btn2'       => kk_meta( $p->ID, 'btn2' ),
			'btn2_url'   => kk_resolve_link( $m( 'btn2_url' ) ? $m( 'btn2_url' ) : 'whatsapp' ),
			'images'     => $imgs,
			'decor'      => $m( 'decor' ) ? $m( 'decor' ) : 'wheat',
			'label'      => kk_meta( $p->ID, 'label' ) ? kk_meta( $p->ID, 'label' ) : $title,
			'photo'      => get_post_thumbnail_id( $p->ID ),
			'banner'     => (int) $m( 'banner' ),
			'banner_m'   => (int) $m( 'banner_m' ),
			'banner_url' => $m( 'banner_url' ) ? kk_resolve_link( $m( 'banner_url' ) ) : '',
		);
	}

	if ( empty( $cache ) ) {
		$cache = kk_fallback_slides();
	}
	return $cache;
}

/** Built-in banners shown before the demo content is imported. */
function kk_fallback_slides() {
	$d   = kk_demo_data();
	$out = array();
	$l   = kk_lang();
	$pick = function ( $t ) use ( $l ) {
		return isset( $t[ $l ] ) && '' !== $t[ $l ] ? $t[ $l ] : $t['en'];
	};
	$i = 0;
	foreach ( $d['lines'] as $line ) {
		if ( empty( $line['new'] ) ) {
			continue;
		}
		$imgs = array();
		foreach ( array_slice( $line['variants'], 0, 3 ) as $v ) {
			$imgs[] = array( 'id' => 0, 'src' => kk_asset( 'img/p2/' . $v['img'] . '.webp' ) );
		}
		$out[] = array( 'mode' => 'design', 'new' => true, 'theme' => $i++ % 2 ? 'red' : 'gold', 'word' => 'NEW', 'title' => kk_t( 'newPrefix' ) . $pick( $line['name'] ), 'sub' => $pick( $line['desc'] ), 'btn1' => kk_t( 'browse' ), 'btn1_url' => kk_products_url(), 'btn2' => kk_t( 'chat' ), 'btn2_url' => kk_wa_url(), 'images' => $imgs, 'decor' => 'biscuit', 'label' => $pick( $line['name'] ), 'photo' => 0 );
	}
	foreach ( $d['categories'] as $c ) {
		$imgs = array();
		foreach ( $d['lines'] as $line ) {
			if ( $line['cat'] === $c['key'] && count( $imgs ) < 3 ) {
				$imgs[] = array( 'id' => 0, 'src' => kk_asset( 'img/p2/' . $line['variants'][0]['img'] . '.webp' ) );
			}
		}
		$out[] = array( 'mode' => 'design', 'new' => false, 'theme' => $c['theme'], 'word' => $c['word'], 'title' => $pick( $c['banner_title'] ), 'sub' => $pick( $c['banner_sub'] ), 'btn1' => kk_t( 'browse' ), 'btn1_url' => kk_products_url(), 'btn2' => kk_t( 'chat' ), 'btn2_url' => kk_wa_url(), 'images' => $imgs, 'decor' => $c['decor'], 'label' => $pick( $c['name'] ), 'photo' => 0 );
	}
	return $out;
}

function kk_first_slide_image() {
	$s = kk_get_slides();
	if ( empty( $s ) ) {
		return '';
	}
	if ( 'image' === $s[0]['mode'] && ! empty( $s[0]['banner'] ) ) {
		return wp_get_attachment_image_url( $s[0]['banner'], 'full' );
	}
	return ! empty( $s[0]['images'][0]['src'] ) ? $s[0]['images'][0]['src'] : '';
}

/** Published reviews (CPT) with a fallback to the built-in ones. */
function kk_get_reviews( $n = 12 ) {
	$out   = array();
	$posts = get_posts( array( 'post_type' => 'kk_review', 'post_status' => 'publish', 'posts_per_page' => $n, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	foreach ( $posts as $p ) {
		$text = $p->post_content;
		foreach ( kk_lang_chain() as $l ) {
			if ( 'ar' === $l ) {
				break;
			}
			$v = get_post_meta( $p->ID, '_kk_text_' . $l, true );
			if ( $v ) {
				$text = $v;
				break;
			}
		}
		$out[] = array( 'name' => get_the_title( $p ), 'text' => wp_strip_all_tags( $text ), 'city' => get_post_meta( $p->ID, '_kk_city', true ), 'rating' => max( 1, min( 5, (int) get_post_meta( $p->ID, '_kk_rating', true ) ? (int) get_post_meta( $p->ID, '_kk_rating', true ) : 5 ) ) );
	}
	if ( ! $out && ! get_option( 'kk_demo_done' ) ) {
		$l = kk_lang();
		foreach ( kk_demo_data()['reviews'] as $r ) {
			$out[] = array( 'name' => isset( $r['name'][ $l ] ) && $r['name'][ $l ] ? $r['name'][ $l ] : $r['name']['en'], 'text' => isset( $r['t'][ $l ] ) && $r['t'][ $l ] ? $r['t'][ $l ] : $r['t']['en'], 'city' => '', 'rating' => 5 );
		}
	}
	return $out;
}
