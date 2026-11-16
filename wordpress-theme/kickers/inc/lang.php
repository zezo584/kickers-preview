<?php
/**
 * Built-in bilingual engine.
 * Arabic is the main language at the site root; English lives under /en/ (e.g. /en/products/rosyl-tella-350g/).
 * No plugin needed: the /en prefix is stripped before WordPress parses the request and added back to every front-end link.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

/**
 * Detect the language from the request and normalise REQUEST_URI.
 */
function kk_detect_lang() {
	if ( defined( 'KK_LANG' ) ) {
		return;
	}
	$lang = 'ar';
	$is_back = is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI );
	if ( $is_back ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['kk_lang'] ) && 'en' === $_REQUEST['kk_lang'] ) {
			$lang = 'en';
		}
		define( 'KK_LANG', $lang );
		return;
	}
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$base = rtrim( (string) wp_parse_url( get_option( 'home' ), PHP_URL_PATH ), '/' );
	$re   = '#^' . preg_quote( $base, '#' ) . '/en(?=/|\?|$)#i';
	if ( preg_match( $re, $uri ) ) {
		$lang                       = 'en';
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
function kk_is_en() {
	return 'en' === kk_lang();
}

/**
 * Convert any front-end URL of this site to the given language.
 */
function kk_url_to_lang( $url, $lang ) {
	$home = untrailingslashit( preg_replace( '#^https?:#i', '', (string) get_option( 'home' ) ) );
	$u    = preg_replace( '#^https?:#i', '', (string) $url );
	if ( '' === $home || 0 !== stripos( $u, $home ) ) {
		return $url;
	}
	$scheme = preg_match( '#^(https?:)#i', (string) $url, $m ) ? $m[1] : '';
	$rest   = (string) substr( $u, strlen( $home ) );
	$rest   = preg_replace( '#^/en(?=/|\?|\#|$)#i', '', $rest );
	if ( 'en' === $lang ) {
		if ( '' === $rest ) {
			$rest = '/';
		} elseif ( '/' !== $rest[0] ) {
			$rest = '/' . $rest;
		}
		$rest = '/en' . $rest;
	}
	return $scheme . $home . $rest;
}

/**
 * Add /en to every front-end link while browsing in English.
 */
function kk_home_url_filter( $url, $path, $scheme ) {
	if ( ! kk_is_en() || is_admin() ) {
		return $url;
	}
	if ( in_array( $scheme, array( 'rest', 'login', 'login_post', 'admin' ), true ) ) {
		return $url;
	}
	$p = ltrim( (string) $path, '/' );
	if ( preg_match( '#^(wp-json|wp-admin|wp-login|wp-content|wp-includes|xmlrpc|wp-sitemap|robots\.txt|favicon\.ico|\?rest_route)#i', $p ) ) {
		return $url;
	}
	return kk_url_to_lang( $url, 'en' );
}
add_filter( 'home_url', 'kk_home_url_filter', 20, 3 );

// The stripped /en prefix would otherwise make WordPress "fix" the URL and loop.
add_filter(
	'redirect_canonical',
	function ( $redirect ) {
		return kk_is_en() ? false : $redirect;
	}
);

// Core strings (dates, search, etc.) in English on /en/.
add_action(
	'after_setup_theme',
	function () {
		if ( kk_is_en() && ! is_admin() && function_exists( 'switch_to_locale' ) ) {
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
		return kk_is_en() ? 'lang="en" dir="ltr"' : 'lang="ar" dir="rtl"';
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

/**
 * The English title of any post (falls back to Arabic).
 */
add_filter(
	'the_title',
	function ( $title, $id = 0 ) {
		if ( ! kk_is_en() || is_admin() || ! $id ) {
			return $title;
		}
		$en = get_post_meta( $id, '_kk_title_en', true );
		return $en ? $en : $title;
	},
	10,
	2
);

/**
 * URL of the current page in the other (or given) language.
 */
function kk_current_url() {
	$origin = preg_replace( '#^(https?://[^/]+).*$#i', '$1', (string) get_option( 'home' ) );
	$uri    = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore
	return $origin . $uri;
}
function kk_switch_url( $lang = null ) {
	if ( null === $lang ) {
		$lang = kk_is_en() ? 'ar' : 'en';
	}
	if ( is_singular() ) {
		$url = get_permalink();
		if ( get_query_var( 'paged' ) > 1 ) {
			$url = kk_current_url();
		}
	} else {
		$url = kk_current_url();
	}
	return kk_url_to_lang( $url, $lang );
}

/**
 * Interface strings.
 */
function kk_strings() {
	static $s = null;
	if ( null !== $s ) {
		return $s;
	}
	$s = array(
		'skip'          => array( 'تخطي إلى المحتوى', 'Skip to content' ),
		'home'          => array( 'الرئيسية', 'Home' ),
		'about'         => array( 'من نحن', 'About us' ),
		'products'      => array( 'منتجاتنا', 'Products' ),
		'brands'        => array( 'علاماتنا', 'Brands' ),
		'contact'       => array( 'تواصل معنا', 'Contact' ),
		'all'           => array( 'الكل', 'All' ),
		'whatsapp'      => array( 'واتساب', 'WhatsApp' ),
		'chat'          => array( 'كلّمنا على واتساب', 'Chat on WhatsApp' ),
		'browse'        => array( 'تصفح المنتجات', 'Browse products' ),
		'view_all'      => array( 'شوف كل المنتجات', 'See all products' ),
		'weight'        => array( 'الوزن', 'Weight' ),
		'category'      => array( 'التصنيف', 'Category' ),
		'brand'         => array( 'العلامة التجارية', 'Brand' ),
		'order_wa'      => array( 'اطلب عن طريق واتساب', 'Order on WhatsApp' ),
		'order_msg'     => array( 'مرحبًا، عايز أستفسر عن المنتج:', 'Hello, I would like to ask about:' ),
		'related'       => array( 'منتجات ممكن تعجبك', 'You may also like' ),
		'factories'     => array( 'مصانعنا', 'Our factories' ),
		'open_map'      => array( 'افتح الخريطة', 'Open the map' ),
		'next'          => array( 'التالي', 'Next' ),
		'prev'          => array( 'السابق', 'Previous' ),
		'pause'         => array( 'إيقاف التشغيل التلقائي', 'Pause autoplay' ),
		'play'          => array( 'تشغيل تلقائي', 'Play autoplay' ),
		'menu'          => array( 'افتح القائمة', 'Open menu' ),
		'close'         => array( 'إغلاق القائمة', 'Close menu' ),
		'main_menu'     => array( 'القائمة الرئيسية', 'Main menu' ),
		'other_lang'    => array( 'English', 'العربية' ),
		'other_code'    => array( 'EN', 'ع' ),
		'quick'         => array( 'روابط سريعة', 'Quick links' ),
		'reach'         => array( 'تواصل', 'Get in touch' ),
		'rights'        => array( 'جميع الحقوق محفوظة.', 'All rights reserved.' ),
		'call'          => array( 'اتصل بنا', 'Call us' ),
		'mail'          => array( 'راسلنا على الإيميل', 'Email us' ),
		'wa_direct'     => array( 'واتساب مباشر', 'WhatsApp' ),
		'f_name'        => array( 'الاسم', 'Name' ),
		'f_phone'       => array( 'رقم الموبايل', 'Mobile number' ),
		'f_email'       => array( 'البريد الإلكتروني', 'Email' ),
		'f_opt'         => array( '(اختياري)', '(optional)' ),
		'f_subject'     => array( 'نوع الطلب', 'Request type' ),
		'f_msg'         => array( 'رسالتك', 'Your message' ),
		'send'          => array( 'إرسال الرسالة', 'Send message' ),
		'form_title'    => array( 'ابعتلنا رسالة', 'Send us a message' ),
		'form_text'     => array( 'للطلبات والتوزيع والاستفسارات، هنرد عليك في أقرب وقت.', 'Orders, distribution or questions: we reply as soon as we can.' ),
		'required'      => array( 'من فضلك اكمل الحقول المطلوبة.', 'Please fill in the required fields.' ),
		'sent'          => array( 'وصلتنا رسالتك، هنكلمك قريب.', 'Thanks! We got your message and will get back to you soon.' ),
		'error'         => array( 'حصلت مشكلة في الإرسال، جرّب تاني أو كلّمنا على واتساب.', 'Sending failed. Please try again or message us on WhatsApp.' ),
		'too_many'      => array( 'بعت رسايل كتير في وقت قصير، استنى شوية وجرّب تاني.', 'Too many messages in a short time. Please wait a bit and try again.' ),
		'nf_title'      => array( 'البسكويتة دي حد كلها!', 'Someone ate this biscuit!' ),
		'nf_text'       => array( 'الصفحة اللي بتدور عليها مش موجودة أو اتنقلت.', 'The page you are looking for doesn\'t exist or has moved.' ),
		'back_home'     => array( 'ارجع للرئيسية', 'Back to home' ),
		'years_l'       => array( 'سنة خبرة في الصناعات الغذائية', 'years in the food industry' ),
		'fac_l'         => array( 'مصنع في مصر وسوريا', 'factories in Egypt and Syria' ),
		'prod_l'        => array( 'منتج في تشكيلتنا', 'products in our range' ),
		'brand_l'       => array( 'علامات تجارية', 'brands' ),
		'vision'        => array( 'رؤيتنا', 'Our vision' ),
		'goal'          => array( 'هدفنا', 'Our goal' ),
		'reach_t'       => array( 'انتشارنا', 'Our reach' ),
		'about_more'    => array( 'اعرف أكتر عننا', 'More about us' ),
		'customer'      => array( 'من عملائنا', 'Customer' ),
		'products_lead' => array( 'كل منتجات كيكرز في مكان واحد. اختار التصنيف اللي يناسبك.', 'Every Kickers product in one place. Pick the category you like.' ),
		'brand_lead'    => array( 'منتجات العلامة', 'Products by' ),
		'hero_label'    => array( 'عروض كيكرز', 'Kickers highlights' ),
		'slide_of'      => array( '%1$s من %2$s', '%1$s of %2$s' ),
		'h1'            => array( 'كيكرز فودز للصناعات الغذائية: بسكويت وكريمة شوكولاتة بالبندق', 'Kickers Foods: biscuits and chocolate hazelnut spread' ),
		'promise_label' => array( 'وعد كيكرز', 'The Kickers promise' ),
		'search'        => array( 'بحث', 'Search' ),
		'search_for'    => array( 'نتائج البحث عن: %s', 'Search results for: %s' ),
		'no_results'    => array( 'مفيش نتائج مطابقة. جرّب كلمة تانية أو تصفح المنتجات.', 'Nothing matched. Try another word or browse the products.' ),
		'read_more'     => array( 'اقرأ المزيد', 'Read more' ),
		'breadcrumb'    => array( 'مسار التنقل', 'Breadcrumb' ),
		'news'          => array( 'الأخبار', 'News' ),
	);
	return $s;
}

/**
 * Translate a UI string.
 */
function kk_t( $key ) {
	$s = kk_strings();
	if ( ! isset( $s[ $key ] ) ) {
		return $key;
	}
	return kk_is_en() ? $s[ $key ][1] : $s[ $key ][0];
}

function kk_count_label( $n ) {
	$n = (int) $n;
	if ( kk_is_en() ) {
		return $n . ( 1 === $n ? ' product' : ' products' );
	}
	if ( 1 === $n ) {
		return 'منتج واحد';
	}
	if ( 2 === $n ) {
		return 'منتجين';
	}
	if ( $n >= 3 && $n <= 10 ) {
		return $n . ' منتجات';
	}
	return $n . ' منتج';
}
