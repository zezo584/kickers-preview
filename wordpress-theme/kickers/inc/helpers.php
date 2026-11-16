<?php
/**
 * Template helpers.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme option (Customizer) with built-in default.
 */
function kk_opt( $key ) {
	$d = kk_defaults();
	return get_theme_mod( 'kk_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/**
 * Language-aware option: reads {key}_en on /en/ (falls back to Arabic).
 */
function kk_optl( $key ) {
	if ( kk_is_en() ) {
		$v = kk_opt( $key . '_en' );
		if ( '' !== trim( (string) $v ) ) {
			return $v;
		}
	}
	return kk_opt( $key . '_ar' );
}

/**
 * Language-aware post meta: _kk_{key}_{lang}.
 */
function kk_meta( $post_id, $key ) {
	if ( kk_is_en() ) {
		$v = get_post_meta( $post_id, '_kk_' . $key . '_en', true );
		if ( '' !== trim( (string) $v ) ) {
			return $v;
		}
	}
	return get_post_meta( $post_id, '_kk_' . $key . '_ar', true );
}

/**
 * Short description of a post in the current language.
 */
function kk_excerpt( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	if ( kk_is_en() ) {
		$en = get_post_meta( $post->ID, '_kk_excerpt_en', true );
		if ( $en ) {
			return $en;
		}
	}
	if ( $post->post_excerpt ) {
		return $post->post_excerpt;
	}
	return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30 );
}

/**
 * Full content of a post in the current language (filtered).
 */
function kk_content( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$raw = $post->post_content;
	if ( kk_is_en() ) {
		$en = get_post_meta( $post->ID, '_kk_content_en', true );
		if ( $en ) {
			$raw = $en;
		}
	}
	return apply_filters( 'the_content', $raw ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}

/**
 * Term helpers (English name/description stored as term meta).
 */
function kk_term_name( $term ) {
	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}
	if ( kk_is_en() ) {
		$en = get_term_meta( $term->term_id, 'kk_name_en', true );
		if ( $en ) {
			return $en;
		}
	}
	return $term->name;
}
function kk_term_desc( $term ) {
	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}
	if ( kk_is_en() ) {
		$en = get_term_meta( $term->term_id, 'kk_desc_en', true );
		if ( $en ) {
			return $en;
		}
	}
	return $term->description;
}
function kk_term_meta( $term, $key, $fallback = '' ) {
	if ( ! $term || is_wp_error( $term ) ) {
		return $fallback;
	}
	$v = get_term_meta( $term->term_id, 'kk_' . $key, true );
	return '' !== (string) $v ? $v : $fallback;
}

/**
 * SVG sprite reference.
 */
function kk_ico( $id, $class = '' ) {
	return '<svg class="' . esc_attr( $class ) . '" aria-hidden="true" focusable="false"><use href="#' . esc_attr( $id ) . '"></use></svg>';
}
function kk_badge( $icon ) {
	return '<span class="badge">' . kk_ico( 'd-plate' ) . kk_ico( $icon, 'ico' ) . '</span>';
}
function kk_arrow() {
	return kk_ico( 'i-arrow', 'ico-dir' );
}

function kk_asset( $path ) {
	return KK_URI . '/assets/' . ltrim( $path, '/' );
}

function kk_logo_url() {
	$id = get_theme_mod( 'custom_logo' );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return kk_asset( 'img/brand/logo.webp' );
}

function kk_site_name() {
	return kk_optl( 'site_name' );
}

function kk_wa_url() {
	return kk_opt( 'whatsapp_url' );
}

/**
 * WhatsApp link with a prefilled message (needs the number in Customizer; otherwise the general link).
 */
function kk_wa_message_url( $text ) {
	$num = preg_replace( '/\D+/', '', (string) kk_opt( 'whatsapp_number' ) );
	if ( $num ) {
		return 'https://wa.me/' . $num . '?text=' . rawurlencode( $text );
	}
	return kk_wa_url();
}

function kk_tel( $phone ) {
	$p = preg_replace( '/[^\d+]/', '', (string) $phone );
	if ( 0 === strpos( $p, '0' ) && 11 === strlen( $p ) ) {
		$p = '+2' . $p; // Egyptian mobile.
	}
	return 'tel:' . $p;
}

function kk_products_url() {
	$u = get_post_type_archive_link( 'kk_product' );
	return $u ? $u : home_url( '/products/' );
}

function kk_page_url( $slug ) {
	$p = get_page_by_path( $slug );
	return $p ? get_permalink( $p ) : home_url( '/' . $slug . '/' );
}

/**
 * Resolve a link entered in the admin: "products", "whatsapp", "contact", "about", "cat:slug", "brand:slug", "product:slug" or any URL.
 */
function kk_resolve_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value || 'products' === $value ) {
		return kk_products_url();
	}
	if ( 'whatsapp' === $value ) {
		return kk_wa_url();
	}
	if ( 'contact' === $value || 'about' === $value ) {
		return kk_page_url( $value );
	}
	if ( preg_match( '/^(cat|brand|product):(.+)$/', $value, $m ) ) {
		if ( 'product' === $m[1] ) {
			$p = get_page_by_path( $m[2], OBJECT, 'kk_product' );
			return $p ? get_permalink( $p ) : kk_products_url();
		}
		$t = get_term_by( 'slug', $m[2], 'cat' === $m[1] ? 'kk_product_cat' : 'kk_brand' );
		$l = $t ? get_term_link( $t ) : '';
		return ( $l && ! is_wp_error( $l ) ) ? $l : kk_products_url();
	}
	if ( 0 === strpos( $value, '/' ) ) {
		return home_url( $value );
	}
	return $value;
}

function kk_is_external( $url ) {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$h    = wp_parse_url( $url, PHP_URL_HOST );
	return $h && $h !== $host;
}

/**
 * Product data for templates.
 */
function kk_product( $post ) {
	$post   = get_post( $post );
	$id     = $post->ID;
	$cats   = get_the_terms( $id, 'kk_product_cat' );
	$brands = get_the_terms( $id, 'kk_brand' );
	$cat    = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
	$brand  = ( $brands && ! is_wp_error( $brands ) ) ? $brands[0] : null;
	$tint   = get_post_meta( $id, '_kk_tint', true );
	return array(
		'id'     => $id,
		'title'  => get_the_title( $id ),
		'url'    => get_permalink( $id ),
		'img'    => get_post_thumbnail_id( $id ),
		'tint'   => $tint ? $tint : '#FDEBCB',
		'weight' => kk_meta( $id, 'weight' ),
		'cat'    => $cat,
		'brand'  => $brand,
	);
}

/**
 * Image tag for an attachment, with a safe fallback.
 */
function kk_img( $id, $size = 'large', $attr = array() ) {
	if ( ! $id ) {
		return '';
	}
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	return wp_get_attachment_image( $id, $size, false, $attr );
}

/**
 * Decorative sprites for the hero/product stage.
 */
function kk_decor_set( $kind ) {
	$sets = array(
		'wheat'     => array( 'd-wheat', 'd-wheat', 'd-biscuit', 'd-star', 'd-crumb', 'd-star' ),
		'chocolate' => array( 'd-choc', 'd-choc', 'd-choc', 'd-star', 'd-choc', 'd-star' ),
		'hazelnut'  => array( 'd-hazel', 'd-hazel', 'd-choc', 'd-star', 'd-hazel', 'd-star' ),
		'biscuit'   => array( 'd-biscuit', 'd-biscuit', 'd-crumb', 'd-star', 'd-biscuit', 'd-star' ),
		'none'      => array(),
	);
	return isset( $sets[ $kind ] ) ? $sets[ $kind ] : $sets['wheat'];
}
function kk_sprites( $kind ) {
	$out = '';
	foreach ( kk_decor_set( $kind ) as $k => $s ) {
		$cls  = 'd-star' === $s ? ' sp-star' : ( 'd-wheat' === $s ? ' sp-wheat' : '' );
		$out .= '<div class="px sp sp-' . ( $k + 1 ) . $cls . '" style="--k:' . $k . ';--d:' . number_format( 1.2 + $k * 0.15, 2 ) . '" aria-hidden="true"><div class="sp-in">' . kk_ico( $s ) . '</div></div>';
	}
	return $out;
}

/**
 * Categories in display order.
 */
function kk_categories( $hide_empty = true ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'kk_product_cat',
			'hide_empty' => $hide_empty,
			'meta_key'   => 'kk_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		$terms = get_terms( array( 'taxonomy' => 'kk_product_cat', 'hide_empty' => $hide_empty ) );
	}
	return is_wp_error( $terms ) ? array() : $terms;
}
function kk_brands( $hide_empty = true ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'kk_brand',
			'hide_empty' => $hide_empty,
			'meta_key'   => 'kk_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		$terms = get_terms( array( 'taxonomy' => 'kk_brand', 'hide_empty' => $hide_empty ) );
	}
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Products query helper.
 */
function kk_query_products( $args = array() ) {
	return new WP_Query(
		wp_parse_args(
			$args,
			array(
				'post_type'           => 'kk_product',
				'posts_per_page'      => -1,
				'orderby'             => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			)
		)
	);
}

/**
 * Factories list from the Customizer.
 */
function kk_factories() {
	$out = array();
	for ( $i = 1; $i <= 2; $i++ ) {
		$c = kk_optl( 'fac' . $i . '_country' );
		if ( '' === trim( (string) $c ) ) {
			continue;
		}
		$out[] = array(
			'country' => $c,
			'addr'    => kk_optl( 'fac' . $i . '_addr' ),
			'map'     => kk_opt( 'fac' . $i . '_map' ),
		);
	}
	return $out;
}

function kk_socials() {
	$map = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'tiktok'    => 'TikTok',
		'youtube'   => 'YouTube',
		'linkedin'  => 'LinkedIn',
		'x'         => 'X',
	);
	$out = array();
	foreach ( $map as $k => $label ) {
		$u = kk_opt( $k );
		if ( $u ) {
			$out[ $k ] = array( 'url' => $u, 'label' => $label );
		}
	}
	return $out;
}

/**
 * Breadcrumb trail markup (also used for JSON-LD).
 */
function kk_breadcrumbs( $items ) {
	$html = '<nav class="crumbs" aria-label="' . esc_attr( kk_t( 'breadcrumb' ) ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( kk_t( 'home' ) ) . '</a>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $it ) {
		$html .= kk_ico( 'i-chev', 'ico-dir' );
		if ( $i === $last || empty( $it[1] ) ) {
			$html .= '<span aria-current="page">' . esc_html( $it[0] ) . '</span>';
		} else {
			$html .= '<a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a>';
		}
	}
	$html .= '</nav>';
	$GLOBALS['kk_crumbs'] = $items;
	return $html;
}

/**
 * Split stored paragraphs (blank-line separated) into <p>.
 */
function kk_paragraphs( $text ) {
	$parts = preg_split( "/\R\s*\R/", trim( (string) $text ) );
	$out   = '';
	foreach ( $parts as $p ) {
		if ( '' !== trim( $p ) ) {
			$out .= '<p>' . esc_html( trim( $p ) ) . '</p>';
		}
	}
	return $out;
}
