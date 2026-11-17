<?php
/**
 * Template helpers.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

function kk_opt( $key ) {
	$d = kk_defaults();
	return get_theme_mod( 'kk_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/** Language-aware option {key}_{lang} with fallback (fr → en → ar). */
function kk_optl( $key ) {
	foreach ( kk_lang_chain() as $l ) {
		$v = kk_opt( $key . '_' . $l );
		if ( '' !== trim( (string) $v ) ) {
			return $v;
		}
	}
	return '';
}

/** Language-aware post meta _kk_{key}_{lang} with fallback. */
function kk_meta( $post_id, $key ) {
	foreach ( kk_lang_chain() as $l ) {
		$v = get_post_meta( $post_id, '_kk_' . $key . '_' . $l, true );
		if ( '' !== trim( (string) $v ) ) {
			return $v;
		}
	}
	return '';
}

function kk_excerpt( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	foreach ( kk_lang_chain() as $l ) {
		if ( 'ar' === $l ) {
			break;
		}
		$v = get_post_meta( $post->ID, '_kk_excerpt_' . $l, true );
		if ( $v ) {
			return $v;
		}
	}
	if ( $post->post_excerpt ) {
		return $post->post_excerpt;
	}
	return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30 );
}

function kk_content( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$raw = $post->post_content;
	foreach ( kk_lang_chain() as $l ) {
		if ( 'ar' === $l ) {
			break;
		}
		$v = get_post_meta( $post->ID, '_kk_content_' . $l, true );
		if ( $v ) {
			$raw = $v;
			break;
		}
	}
	return apply_filters( 'the_content', $raw ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}

/* ---------- Terms ---------- */
function kk_term_l( $term, $key, $ar_fallback ) {
	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}
	foreach ( kk_lang_chain() as $l ) {
		if ( 'ar' === $l ) {
			break;
		}
		$v = get_term_meta( $term->term_id, 'kk_' . $key . '_' . $l, true );
		if ( '' !== (string) $v ) {
			return $v;
		}
	}
	return $ar_fallback;
}
function kk_term_name( $term ) {
	return $term ? kk_term_l( $term, 'name', $term->name ) : '';
}
function kk_term_desc( $term ) {
	return $term ? kk_term_l( $term, 'desc', $term->description ) : '';
}
function kk_term_meta( $term, $key, $fallback = '' ) {
	if ( ! $term || is_wp_error( $term ) ) {
		return $fallback;
	}
	$v = get_term_meta( $term->term_id, 'kk_' . $key, true );
	return '' !== (string) $v ? $v : $fallback;
}
/** Language field of a term with Arabic stored as kk_{key}_ar. */
function kk_term_ml( $term, $key ) {
	return kk_term_l( $term, $key, kk_term_meta( $term, $key . '_ar' ) );
}
function kk_brand_label( $brand ) {
	if ( ! $brand ) {
		return '';
	}
	$en = get_term_meta( $brand->term_id, 'kk_name_en', true );
	return $en ? $en : $brand->name;
}

/* ---------- Icons, assets, links ---------- */
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
function kk_wa_message_url( $text ) {
	$num = preg_replace( '/\D+/', '', (string) kk_opt( 'whatsapp_number' ) );
	return $num ? 'https://wa.me/' . $num . '?text=' . rawurlencode( $text ) : kk_wa_url();
}
function kk_tel( $phone ) {
	$p = preg_replace( '/[^\d+]/', '', (string) $phone );
	if ( 0 === strpos( $p, '0' ) && 11 === strlen( $p ) ) {
		$p = '+2' . $p;
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
function kk_resolve_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value || 'products' === $value ) {
		return kk_products_url();
	}
	if ( 'whatsapp' === $value ) {
		return kk_wa_url();
	}
	if ( in_array( $value, array( 'contact', 'about', 'export', 'agent', 'reviews' ), true ) ) {
		return kk_page_url( $value );
	}
	if ( preg_match( '/^(cat|brand|product):(.+)$/', $value, $m ) ) {
		if ( 'product' === $m[1] ) {
			$p = get_posts( array( 'post_type' => 'kk_product', 'name' => $m[2], 'posts_per_page' => 1 ) );
			return $p ? get_permalink( $p[0] ) : kk_products_url();
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

/* ---------- Products (each post = a product line with flavour variants) ---------- */
function kk_variants( $id ) {
	$ids  = array_values( array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $id, '_kk_gallery', true ) ) ) ) );
	$main = (int) get_post_thumbnail_id( $id );
	if ( ! $ids && $main ) {
		$ids = array( $main );
	}
	$lines = preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $id, '_kk_flavors', true ) );
	$i     = (int) array_search( kk_lang(), kk_lang_codes(), true );
	$out   = array();
	foreach ( $ids as $k => $img ) {
		$name = '';
		if ( isset( $lines[ $k ] ) && '' !== trim( $lines[ $k ] ) ) {
			$parts = array_map( 'trim', explode( '|', $lines[ $k ] ) );
			$name  = isset( $parts[ $i ] ) && '' !== $parts[ $i ] ? $parts[ $i ] : ( isset( $parts[1] ) && '' !== $parts[1] && ! kk_is_ar() ? $parts[1] : $parts[0] );
		}
		$out[] = array( 'id' => $img, 'name' => $name );
	}
	return $out;
}
function kk_product( $post ) {
	$post   = get_post( $post );
	$id     = $post->ID;
	$cats   = get_the_terms( $id, 'kk_product_cat' );
	$brands = get_the_terms( $id, 'kk_brand' );
	$tint   = get_post_meta( $id, '_kk_tint', true );
	$vars   = kk_variants( $id );
	return array(
		'id'       => $id,
		'title'    => get_the_title( $id ),
		'url'      => get_permalink( $id ),
		'img'      => $vars ? $vars[0]['id'] : (int) get_post_thumbnail_id( $id ),
		'variants' => $vars,
		'tint'     => $tint ? $tint : '#FDEBCB',
		'weight'   => kk_meta( $id, 'weight' ),
		'carton'   => kk_meta( $id, 'carton' ),
		'units'    => (int) get_post_meta( $id, '_kk_units', true ),
		'cont20'   => get_post_meta( $id, '_kk_cont20', true ),
		'cont40'   => get_post_meta( $id, '_kk_cont40', true ),
		'new'      => '1' === get_post_meta( $id, '_kk_new', true ),
		'cat'      => ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null,
		'brand'    => ( $brands && ! is_wp_error( $brands ) ) ? $brands[0] : null,
	);
}
function kk_flavor_list( $p ) {
	$n = array();
	foreach ( $p['variants'] as $v ) {
		if ( $v['name'] ) {
			$n[] = $v['name'];
		}
	}
	return implode( kk_is_ar() ? '، ' : ', ', $n );
}
function kk_img( $id, $size = 'large', $attr = array() ) {
	if ( ! $id ) {
		return '';
	}
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	return wp_get_attachment_image( $id, $size, false, $attr );
}
function kk_img_url( $id, $size = 'kk-product' ) {
	$u = $id ? wp_get_attachment_image_url( $id, $size ) : '';
	return $u ? $u : '';
}

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

function kk_terms_ordered( $tax, $hide_empty = true ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $tax,
			'hide_empty' => $hide_empty,
			'meta_key'   => 'kk_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		$terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => $hide_empty ) );
	}
	return is_wp_error( $terms ) ? array() : $terms;
}
function kk_categories( $hide_empty = true ) {
	return kk_terms_ordered( 'kk_product_cat', $hide_empty );
}
function kk_brands( $hide_empty = true ) {
	return kk_terms_ordered( 'kk_brand', $hide_empty );
}
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
function kk_cat_products( $term, $n = -1 ) {
	return kk_query_products( array( 'posts_per_page' => $n, 'tax_query' => array( array( 'taxonomy' => 'kk_product_cat', 'terms' => $term->term_id ) ) ) )->posts; // phpcs:ignore
}

function kk_factories() {
	$out = array();
	for ( $i = 1; $i <= 2; $i++ ) {
		$c = kk_optl( 'fac' . $i . '_country' );
		if ( '' === trim( (string) $c ) ) {
			continue;
		}
		$out[] = array( 'country' => $c, 'addr' => kk_optl( 'fac' . $i . '_addr' ), 'map' => kk_opt( 'fac' . $i . '_map' ), 'since' => kk_opt( 'fac' . $i . '_since' ) );
	}
	return $out;
}
function kk_socials() {
	$map = array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'x' => 'X' );
	$out = array();
	foreach ( $map as $k => $label ) {
		$u = kk_opt( $k );
		if ( $u ) {
			$out[ $k ] = array( 'url' => $u, 'label' => $label );
		}
	}
	return $out;
}
function kk_years() {
	return max( 1, (int) gmdate( 'Y' ) - (int) kk_opt( 'years_since' ) );
}
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
	return $html . '</nav>';
}
function kk_paragraphs( $text ) {
	$out = '';
	foreach ( preg_split( "/\R\s*\R/u", trim( (string) $text ) ) as $p ) {
		if ( '' !== trim( $p ) ) {
			$out .= '<p>' . esc_html( trim( $p ) ) . '</p>';
		}
	}
	return $out;
}
function kk_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R|,|،/u', (string) $text ) ) ) );
}
