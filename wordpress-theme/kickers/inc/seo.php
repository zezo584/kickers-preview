<?php
/**
 * SEO: titles, descriptions, canonical + hreflang (ar/en), Open Graph, JSON-LD, English sitemap, old-URL redirects.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

function kk_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || defined( 'SURERANK_VERSION' ) || class_exists( 'SureRank\\Inc\\Loader' );
}

/* ---------------- Document title ---------------- */
add_filter(
	'document_title_parts',
	function ( $parts ) {
		$parts['site'] = kk_site_name();
		if ( is_front_page() ) {
			$parts['title']   = kk_site_name();
			$parts['tagline'] = kk_optl( 'tagline' );
			unset( $parts['site'] );
		} elseif ( is_post_type_archive( 'kk_product' ) ) {
			$parts['title'] = kk_t( 'products' );
		} elseif ( is_tax( array( 'kk_product_cat', 'kk_brand' ) ) ) {
			$parts['title'] = kk_term_name( get_queried_object() );
		} elseif ( is_singular() ) {
			$parts['title'] = wp_strip_all_tags( get_the_title() );
		}
		return $parts;
	}
);
add_filter(
	'document_title_separator',
	function () {
		return '|';
	}
);

/* ---------------- Helpers ---------------- */
function kk_seo_description() {
	if ( is_front_page() ) {
		return kk_optl( 'meta_desc' );
	}
	if ( is_singular() ) {
		$d = kk_excerpt( get_queried_object() );
		return $d ? $d : kk_optl( 'meta_desc' );
	}
	if ( is_tax( array( 'kk_product_cat', 'kk_brand' ) ) ) {
		$d = kk_term_desc( get_queried_object() );
		return $d ? $d : kk_optl( 'meta_desc' );
	}
	if ( is_post_type_archive( 'kk_product' ) ) {
		return kk_t( 'products_lead' ) . ' ' . kk_optl( 'meta_desc' );
	}
	return kk_optl( 'meta_desc' );
}

function kk_canonical() {
	if ( is_404() || is_search() ) {
		return '';
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		$u    = get_permalink( get_queried_object_id() );
		$page = (int) get_query_var( 'page' );
		return $page > 1 ? trailingslashit( $u ) . $page . '/' : $u;
	}
	$u = '';
	if ( is_post_type_archive() ) {
		$u = get_post_type_archive_link( get_query_var( 'post_type' ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$u = get_term_link( get_queried_object() );
	} elseif ( is_home() ) {
		$u = get_permalink( get_option( 'page_for_posts' ) );
	}
	if ( ! $u || is_wp_error( $u ) ) {
		return '';
	}
	$paged = (int) get_query_var( 'paged' );
	return $paged > 1 ? trailingslashit( $u ) . 'page/' . $paged . '/' : $u;
}

function kk_og_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		if ( $src ) {
			return $src;
		}
	}
	$id = (int) kk_opt( 'og_image' );
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return array( kk_asset( 'img/brand/og-image.jpg' ), 1200, 630 );
}

/* ---------------- Head tags ---------------- */
remove_action( 'wp_head', 'rel_canonical' );

add_action(
	'wp_head',
	function () {
		$canonical = kk_canonical();
		$plugin    = kk_seo_plugin_active();

		if ( ! $plugin ) {
			$desc = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) kk_seo_description() ) ) );
			if ( $desc ) {
				echo '<meta name="description" content="' . esc_attr( wp_html_excerpt( $desc, 300, '…' ) ) . '">' . "\n";
			}
			if ( $canonical ) {
				echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
			}
			$gv = kk_opt( 'google_verification' );
			if ( $gv ) {
				echo '<meta name="google-site-verification" content="' . esc_attr( $gv ) . '">' . "\n";
			}
			// Open Graph / Twitter.
			$img   = kk_og_image();
			$title = wp_get_document_title();
			echo '<meta property="og:site_name" content="' . esc_attr( kk_site_name() ) . '">' . "\n";
			echo '<meta property="og:locale" content="' . ( kk_is_en() ? 'en_US' : 'ar_EG' ) . '">' . "\n";
			echo '<meta property="og:locale:alternate" content="' . ( kk_is_en() ? 'ar_EG' : 'en_US' ) . '">' . "\n";
			echo '<meta property="og:type" content="' . ( is_singular( 'kk_product' ) ? 'product' : ( is_singular( 'post' ) ? 'article' : 'website' ) ) . '">' . "\n";
			echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
			if ( $desc ) {
				echo '<meta property="og:description" content="' . esc_attr( wp_html_excerpt( $desc, 200, '…' ) ) . '">' . "\n";
			}
			echo '<meta property="og:url" content="' . esc_url( $canonical ? $canonical : kk_current_url() ) . '">' . "\n";
			echo '<meta property="og:image" content="' . esc_url( $img[0] ) . '">' . "\n";
			if ( ! empty( $img[1] ) ) {
				echo '<meta property="og:image:width" content="' . (int) $img[1] . '"><meta property="og:image:height" content="' . (int) $img[2] . '">' . "\n";
			}
			echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		} elseif ( $canonical && ( defined( 'SURERANK_VERSION' ) || class_exists( 'SureRank\\Inc\\Loader' ) ) && kk_is_en() ) {
			echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
		}

		// hreflang alternates (always — SEO plugins don't know about /en/).
		if ( $canonical ) {
			$ar = kk_url_to_lang( $canonical, 'ar' );
			$en = kk_url_to_lang( $canonical, 'en' );
			echo '<link rel="alternate" hreflang="ar" href="' . esc_url( $ar ) . '">' . "\n";
			echo '<link rel="alternate" hreflang="en" href="' . esc_url( $en ) . '">' . "\n";
			echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $ar ) . '">' . "\n";
		}

		kk_jsonld();
	},
	5
);

add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( is_search() || is_404() ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
		} else {
			$robots['max-image-preview'] = 'large';
		}
		return $robots;
	}
);

/* ---------------- Structured data ---------------- */
function kk_phone_intl( $p ) {
	$p = preg_replace( '/\D+/', '', (string) $p );
	if ( 0 === strpos( $p, '0' ) && 11 === strlen( $p ) ) {
		return '+2' . $p;
	}
	return $p ? '+' . ltrim( $p, '+' ) : '';
}

function kk_jsonld() {
	$home  = home_url( '/' );
	$org   = $home . '#organization';
	$graph = array();

	$same = array();
	foreach ( kk_socials() as $s ) {
		$same[] = $s['url'];
	}
	$addresses = array();
	foreach ( kk_factories() as $f ) {
		$addresses[] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $f['addr'],
			'addressCountry'  => ( false !== stripos( $f['country'], 'syri' ) || false !== strpos( $f['country'], 'سوري' ) ) ? 'SY' : 'EG',
		);
	}
	$contacts = array();
	foreach ( array( kk_opt( 'phone1' ), kk_opt( 'phone2' ) ) as $ph ) {
		if ( $ph ) {
			$contacts[] = array(
				'@type'             => 'ContactPoint',
				'telephone'         => kk_phone_intl( $ph ),
				'contactType'       => 'customer service',
				'availableLanguage' => array( 'Arabic', 'English' ),
			);
		}
	}
	$graph[] = array_filter(
		array(
			'@type'         => 'Organization',
			'@id'           => $org,
			'name'          => kk_site_name(),
			'alternateName' => kk_is_en() ? kk_opt( 'site_name_ar' ) : kk_opt( 'site_name_en' ),
			'legalName'     => kk_optl( 'tagline' ),
			'url'           => $home,
			'logo'          => array(
				'@type' => 'ImageObject',
				'url'   => kk_logo_url(),
			),
			'email'         => kk_opt( 'email' ),
			'address'       => $addresses,
			'contactPoint'  => $contacts,
			'sameAs'        => $same,
		)
	);
	$graph[] = array(
		'@type'      => 'WebSite',
		'@id'        => $home . '#website',
		'url'        => $home,
		'name'       => kk_site_name(),
		'inLanguage' => kk_is_en() ? 'en' : 'ar',
		'publisher'  => array( '@id' => $org ),
	);

	if ( is_singular( 'kk_product' ) ) {
		$p       = kk_product( get_queried_object() );
		$img     = $p['img'] ? wp_get_attachment_image_url( $p['img'], 'large' ) : '';
		$product = array(
			'@type'        => 'Product',
			'@id'          => $p['url'] . '#product',
			'name'         => wp_strip_all_tags( $p['title'] ),
			'url'          => $p['url'],
			'description'  => wp_strip_all_tags( kk_excerpt( $p['id'] ) ),
			'sku'          => get_post_field( 'post_name', $p['id'] ),
			'manufacturer' => array( '@id' => $org ),
		);
		if ( $img ) {
			$product['image'] = $img;
		}
		if ( $p['brand'] ) {
			$product['brand'] = array(
				'@type' => 'Brand',
				'name'  => get_term_meta( $p['brand']->term_id, 'kk_name_en', true ) ? get_term_meta( $p['brand']->term_id, 'kk_name_en', true ) : $p['brand']->name,
			);
		}
		if ( $p['cat'] ) {
			$product['category'] = kk_term_name( $p['cat'] );
		}
		$w = get_post_meta( $p['id'], '_kk_weight_en', true );
		if ( $w && preg_match( '/([\d.]+)\s*(kg|g)/i', $w, $m ) ) {
			$product['weight'] = array(
				'@type'    => 'QuantitativeValue',
				'value'    => (float) $m[1],
				'unitCode' => 'kg' === strtolower( $m[2] ) ? 'KGM' : 'GRM',
			);
		}
		$graph[] = $product;
	}

	if ( ! empty( $GLOBALS['kk_crumbs'] ) || is_singular() || is_archive() ) {
		$items = kk_crumb_items();
		if ( count( $items ) > 1 ) {
			$list = array();
			foreach ( $items as $i => $it ) {
				$list[] = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => $it[0],
					'item'     => $it[1],
				);
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $list,
			);
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	) . '</script>' . "\n";
}

/**
 * Breadcrumb items for JSON-LD (computed in the head, before templates run).
 */
function kk_crumb_items() {
	$items = array( array( kk_t( 'home' ), home_url( '/' ) ) );
	if ( is_front_page() ) {
		return $items;
	}
	if ( is_singular( 'kk_product' ) ) {
		$items[] = array( kk_t( 'products' ), kk_products_url() );
		$p       = kk_product( get_queried_object() );
		if ( $p['cat'] ) {
			$items[] = array( kk_term_name( $p['cat'] ), get_term_link( $p['cat'] ) );
		}
		$items[] = array( wp_strip_all_tags( $p['title'] ), $p['url'] );
	} elseif ( is_tax( array( 'kk_product_cat', 'kk_brand' ) ) ) {
		$items[] = array( kk_t( 'products' ), kk_products_url() );
		$items[] = array( kk_term_name( get_queried_object() ), get_term_link( get_queried_object() ) );
	} elseif ( is_post_type_archive( 'kk_product' ) ) {
		$items[] = array( kk_t( 'products' ), kk_products_url() );
	} elseif ( is_singular() ) {
		$items[] = array( wp_strip_all_tags( get_the_title( get_queried_object_id() ) ), get_permalink( get_queried_object_id() ) );
	}
	return $items;
}

/* ---------------- Sitemaps ---------------- */
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);
add_filter(
	'wp_sitemaps_post_types',
	function ( $types ) {
		unset( $types['kk_slide'] );
		return $types;
	}
);

add_action(
	'init',
	function () {
		if ( ! class_exists( 'WP_Sitemaps_Provider' ) || ! function_exists( 'wp_register_sitemap_provider' ) ) {
			return;
		}
		if ( ! class_exists( 'KK_Sitemap_English' ) ) {
			/**
			 * /wp-sitemap-en-1.xml — every English URL.
			 */
			class KK_Sitemap_English extends WP_Sitemaps_Provider { // phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound,Generic.Classes.OpeningBraceSameLine.ContentAfterBrace
				public function __construct() {
					$this->name        = 'en';
					$this->object_type = 'en';
				}
				public function get_url_list( $page_num, $object_subtype = '' ) {
					$urls   = array( array( 'loc' => kk_url_to_lang( home_url( '/' ), 'en' ) ) );
					$urls[] = array( 'loc' => kk_url_to_lang( kk_products_url(), 'en' ) );
					$posts  = get_posts(
						array(
							'post_type'      => array( 'page', 'kk_product' ),
							'post_status'    => 'publish',
							'posts_per_page' => 1500,
							'orderby'        => 'menu_order',
							'order'          => 'ASC',
						)
					);
					foreach ( $posts as $p ) {
						if ( (int) get_option( 'page_on_front' ) === $p->ID ) {
							continue;
						}
						$urls[] = array(
							'loc'     => kk_url_to_lang( get_permalink( $p ), 'en' ),
							'lastmod' => get_post_modified_time( 'c', true, $p ),
						);
					}
					foreach ( array( 'kk_product_cat', 'kk_brand' ) as $tax ) {
						$terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => true ) );
						if ( is_wp_error( $terms ) ) {
							continue;
						}
						foreach ( $terms as $t ) {
							$l = get_term_link( $t );
							if ( ! is_wp_error( $l ) ) {
								$urls[] = array( 'loc' => kk_url_to_lang( $l, 'en' ) );
							}
						}
					}
					return $urls;
				}
				public function get_max_num_pages( $object_subtype = '' ) {
					return 1;
				}
			}
		}
		wp_register_sitemap_provider( 'en', new KK_Sitemap_English() );
	}
);

/* ---------------- Old URLs → new URLs (keeps Google rankings) ---------------- */
function kk_old_url_map() {
	return apply_filters(
		'kk_old_url_map',
		array(
			'services'    => array( 'archive' ),
			'projects'    => array( 'archive' ),
			'بسكويت-محشو' => array( 'cat', 'sandwich-biscuits' ),
			'بسكويت-مغطس' => array( 'cat', 'chocolate-coated-biscuits' ),
			'بسكويت-سادة' => array( 'cat', 'plain-biscuits' ),
			'تيلا'        => array( 'cat', 'tella-spreads' ),
			'home'        => array( 'home' ),
		)
	);
}
add_action(
	'template_redirect',
	function () {
		if ( is_admin() ) {
			return;
		}
		$base = rtrim( (string) wp_parse_url( get_option( 'home' ), PHP_URL_PATH ), '/' );
		$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore
		$path = rawurldecode( $path );
		if ( $base && 0 === strpos( $path, $base ) ) {
			$path = substr( $path, strlen( $base ) );
		}
		$slug = trim( $path, '/' );
		$map  = kk_old_url_map();
		if ( '' === $slug || ! isset( $map[ $slug ] ) ) {
			return;
		}
		$to = $map[ $slug ];
		$target = '';
		if ( 'archive' === $to[0] ) {
			$target = kk_products_url();
		} elseif ( 'home' === $to[0] ) {
			$target = home_url( '/' );
		} elseif ( 'cat' === $to[0] ) {
			$t = get_term_by( 'slug', $to[1], 'kk_product_cat' );
			$l = $t ? get_term_link( $t ) : '';
			$target = ( $l && ! is_wp_error( $l ) ) ? $l : kk_products_url();
		}
		if ( $target ) {
			wp_safe_redirect( $target, 301 );
			exit;
		}
	},
	1
);
