<?php
/**
 * Built-in multilingual engine: Arabic at the root, English /en/, French /fr/, German /de/.
 * No plugin needed — the prefix is stripped before WordPress parses the request and added back to every front-end link.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

function kk_langs() {
	return array(
		'ar' => array( 'name' => 'العربية', 'locale' => 'ar', 'og' => 'ar_EG', 'dir' => 'rtl' ),
		'en' => array( 'name' => 'English', 'locale' => 'en_US', 'og' => 'en_US', 'dir' => 'ltr' ),
		'fr' => array( 'name' => 'Français', 'locale' => 'fr_FR', 'og' => 'fr_FR', 'dir' => 'ltr' ),
		'de' => array( 'name' => 'Deutsch', 'locale' => 'de_DE', 'og' => 'de_DE', 'dir' => 'ltr' ),
	);
}
function kk_lang_codes() {
	return array_keys( kk_langs() );
}

function kk_detect_lang() {
	if ( defined( 'KK_LANG' ) ) {
		return;
	}
	$lang    = 'ar';
	$is_back = is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI );
	if ( $is_back ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$req = isset( $_REQUEST['kk_lang'] ) ? sanitize_key( wp_unslash( $_REQUEST['kk_lang'] ) ) : '';
		if ( in_array( $req, array( 'en', 'fr', 'de' ), true ) ) {
			$lang = $req;
		}
		define( 'KK_LANG', $lang );
		return;
	}
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore
	$base = rtrim( (string) wp_parse_url( get_option( 'home' ), PHP_URL_PATH ), '/' );
	$re   = '#^' . preg_quote( $base, '#' ) . '/(en|fr|de)(?=/|\?|$)#i';
	if ( preg_match( $re, $uri, $m ) ) {
		$lang                       = strtolower( $m[1] );
		$GLOBALS['kk_original_uri'] = $uri;
		$new                        = preg_replace( $re, $base, $uri, 1 );
		if ( '' === $new || '/' !== $new[0] ) {
			$new = '/' . ltrim( $new, '/' );
		}
		$_SERVER['REQUEST_URI'] = $new;
	}
	define( 'KK_LANG', $lang );
}
kk_detect_lang();

function kk_lang() {
	return defined( 'KK_LANG' ) ? KK_LANG : 'ar';
}
function kk_is_ar() {
	return 'ar' === kk_lang();
}
/** True on any non-Arabic version (kept for compatibility). */
function kk_is_en() {
	return ! kk_is_ar();
}
/** Fallback chain for the current language, e.g. fr → fr, en, ar. */
function kk_lang_chain() {
	$l = kk_lang();
	if ( 'ar' === $l ) {
		return array( 'ar' );
	}
	if ( 'en' === $l ) {
		return array( 'en', 'ar' );
	}
	return array( $l, 'en', 'ar' );
}

function kk_url_to_lang( $url, $lang ) {
	$home = untrailingslashit( preg_replace( '#^https?:#i', '', (string) get_option( 'home' ) ) );
	$u    = preg_replace( '#^https?:#i', '', (string) $url );
	if ( '' === $home || 0 !== stripos( $u, $home ) ) {
		return $url;
	}
	$scheme = preg_match( '#^(https?:)#i', (string) $url, $m ) ? $m[1] : '';
	$rest   = (string) substr( $u, strlen( $home ) );
	$rest   = preg_replace( '#^/(en|fr|de)(?=/|\?|\#|$)#i', '', $rest );
	if ( 'ar' !== $lang ) {
		if ( '' === $rest ) {
			$rest = '/';
		} elseif ( '/' !== $rest[0] ) {
			$rest = '/' . $rest;
		}
		$rest = '/' . $lang . $rest;
	}
	return $scheme . $home . $rest;
}

function kk_home_url_filter( $url, $path, $scheme ) {
	if ( kk_is_ar() || is_admin() ) {
		return $url;
	}
	if ( in_array( $scheme, array( 'rest', 'login', 'login_post', 'admin' ), true ) ) {
		return $url;
	}
	$p = ltrim( (string) $path, '/' );
	if ( preg_match( '#^(wp-json|wp-admin|wp-login|wp-content|wp-includes|xmlrpc|wp-sitemap|robots\.txt|favicon\.ico|\?rest_route)#i', $p ) ) {
		return $url;
	}
	return kk_url_to_lang( $url, kk_lang() );
}
add_filter( 'home_url', 'kk_home_url_filter', 20, 3 );

add_filter(
	'redirect_canonical',
	function ( $redirect ) {
		return kk_is_ar() ? $redirect : false;
	}
);

add_action(
	'after_setup_theme',
	function () {
		if ( kk_is_ar() || is_admin() || ! function_exists( 'switch_to_locale' ) ) {
			return;
		}
		$langs = kk_langs();
		if ( ! switch_to_locale( $langs[ kk_lang() ]['locale'] ) ) {
			switch_to_locale( 'en_US' );
		}
	},
	1
);

add_filter(
	'language_attributes',
	function ( $output ) {
		if ( is_admin() ) {
			return $output;
		}
		$l = kk_langs();
		return 'lang="' . esc_attr( kk_lang() ) . '" dir="' . esc_attr( $l[ kk_lang() ]['dir'] ) . '"';
	},
	20
);

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'lang-' . kk_lang();
		return $classes;
	}
);

add_filter(
	'the_title',
	function ( $title, $id = 0 ) {
		if ( kk_is_ar() || is_admin() || ! $id ) {
			return $title;
		}
		foreach ( kk_lang_chain() as $l ) {
			if ( 'ar' === $l ) {
				break;
			}
			$v = get_post_meta( $id, '_kk_title_' . $l, true );
			if ( $v ) {
				return $v;
			}
		}
		return $title;
	},
	10,
	2
);

function kk_current_url() {
	$origin = preg_replace( '#^(https?://[^/]+).*$#i', '$1', (string) get_option( 'home' ) );
	$uri    = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore
	return $origin . $uri;
}
function kk_switch_url( $lang ) {
	$url = ( is_singular() && get_query_var( 'paged' ) < 2 ) ? get_permalink() : kk_current_url();
	return kk_url_to_lang( $url, $lang );
}

/**
 * Translate a UI string (falls back to English).
 */
function kk_t( $key ) {
	$s = kk_strings();
	if ( ! isset( $s[ $key ] ) ) {
		return $key;
	}
	$i = (int) array_search( kk_lang(), kk_lang_codes(), true );
	$v = isset( $s[ $key ][ $i ] ) ? $s[ $key ][ $i ] : '';
	return '' !== $v ? $v : $s[ $key ][1];
}

function kk_count_label( $n ) {
	$n = (int) $n;
	switch ( kk_lang() ) {
		case 'en':
			return $n . ( 1 === $n ? ' product' : ' products' );
		case 'fr':
			return $n . ( 1 === $n ? ' produit' : ' produits' );
		case 'de':
			return $n . ( 1 === $n ? ' Produkt' : ' Produkte' );
	}
	if ( 1 === $n ) {
		return 'منتج واحد';
	}
	if ( 2 === $n ) {
		return 'منتجين';
	}
	return $n . ( $n <= 10 ? ' منتجات' : ' منتج' );
}
