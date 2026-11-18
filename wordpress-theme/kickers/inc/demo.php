<?php
/**
 * One-click setup v2: types (with banners), 7 brands with logos, 14 product lines with all flavours,
 * reviews, pages (about, export, agent, reviews, contact), site icon = logo.
 * Appearance → "كيكرز: البداية السريعة".
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'كيكرز: البداية السريعة', 'كيكرز: البداية السريعة', 'manage_options', 'kk-setup', 'kk_setup_page' );
	}
);

add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) || 2 <= (int) get_option( 'kk_demo_done' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_kk-setup' === $screen->id ) {
			return;
		}
		echo '<div class="notice notice-info"><p><strong>قالب كيكرز فودز 2.0:</strong> اضغط "استيراد المحتوى" عشان المنتجات الجديدة والنكهات والبانرات والصفحات تتضاف. <a class="button button-primary" href="' . esc_url( admin_url( 'themes.php?page=kk-setup' ) ) . '">البداية السريعة</a></p></div>';
	}
);

function kk_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log = array();
	if ( isset( $_POST['kk_import'] ) && check_admin_referer( 'kk_import' ) ) {
		$keys = array( 'products', 'reviews', 'pages', 'icon', 'front', 'cleanup' );
		$o    = array();
		foreach ( $keys as $k ) {
			$o[ $k ] = ! empty( $_POST[ 'kk_' . $k ] );
		}
		$log = kk_run_import( $o );
	}
	echo '<div class="wrap kk-setup"><h1>كيكرز فودز — البداية السريعة</h1>';
	if ( $log ) {
		echo '<div class="notice notice-success"><ul style="list-style:disc;padding-inline-start:20px">';
		foreach ( $log as $l ) {
			echo '<li>' . esc_html( $l ) . '</li>';
		}
		echo '</ul><p><a class="button button-primary" href="' . esc_url( home_url( '/' ) ) . '" target="_blank">افتح الموقع</a></p></div>';
	}
	echo '<div class="card"><h2>استيراد محتوى الموقع</h2><p>آمن تضغطه أكتر من مرة: أي حاجة موجودة بنفس الرابط بتتساب زي ما هي.</p><form method="post">';
	wp_nonce_field( 'kk_import' );
	$checks = array(
		'kk_products' => '4 أنواع (سادة/محشو/مغطس/شيكولاتة سبريد) ببانراتها + 7 علامات بلوجوهاتها + 14 منتج بكل النكهات ومواصفات التعبئة',
		'kk_reviews'  => 'آراء العملاء الحالية',
		'kk_pages'    => 'صفحات: من نحن، التصدير، كن وكيلاً، آراء العملاء، تواصل معنا',
		'kk_icon'     => 'أيقونة الموقع = شعار كيكرز',
		'kk_front'    => 'الصفحة الرئيسية = تصميم القالب',
		'kk_cleanup'  => 'نقل منتجات وبانرات النسخة الأولى من القالب لسلة المهملات (لو كنت استوردتها قبل كده)',
	);
	echo '<ul class="kk-steps">';
	foreach ( $checks as $k => $label ) {
		echo '<li><label><input type="checkbox" name="' . esc_attr( $k ) . '" value="1" checked> ' . esc_html( $label ) . '</label></li>';
	}
	echo '</ul><p><button class="button button-primary button-hero" name="kk_import" value="1">استيراد المحتوى</button></p></form></div>';
	echo '<div class="card"><h2>هتعدّل منين؟</h2><ul style="list-style:disc;padding-inline-start:20px">';
	echo '<li><strong>منتج جديد:</strong> المنتجات ← أضف منتج ← اختار صور النكهات واكتب أسماءها، وعلّم "منتج جديد" → هيظهر عليه "جديد" ويتعمله بانر رئيسي متحرك لوحده.</li>';
	echo '<li><strong>نوع جديد (زي سادة/محشو):</strong> المنتجات ← الأنواع ← أضف — وعلّم "اعمل بانر رئيسي" واكتب عنوان البانر بكل لغة.</li>';
	echo '<li><strong>العلامات واللوجوهات:</strong> المنتجات ← العلامات التجارية.</li>';
	echo '<li><strong>بانرات زيادة (عروض/مناسبات):</strong> بانرات إضافية.</li>';
	echo '<li><strong>آراء العملاء:</strong> آراء العملاء — الآراء اللي بتيجي من الموقع بتستنى موافقتك.</li>';
	echo '<li><strong>كل النصوص بالأربع لغات، الأرقام، التواصل، SEO:</strong> المظهر ← تخصيص ← إعدادات كيكرز.</li>';
	echo '<li><strong>اللغات:</strong> عربي على الرابط الأساسي، و /en/ و /fr/ و /de/.</li>';
	echo '</ul></div></div>';
}

function kk_import_image( $file, $title ) {
	$map = get_option( 'kk_demo_media', array() );
	if ( ! empty( $map[ $file ] ) && get_post( $map[ $file ] ) ) {
		return (int) $map[ $file ];
	}
	$path = KK_DIR . '/assets/img/' . $file;
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	$upload = wp_upload_bits( 'kickers-' . str_replace( '/', '-', $file ), null, file_get_contents( $path ) ); // phpcs:ignore
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment( array( 'post_mime_type' => $type['type'], 'post_title' => $title, 'post_status' => 'inherit' ), $upload['file'] );
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $title );
	$map[ $file ] = $id;
	update_option( 'kk_demo_media', $map, false );
	return (int) $id;
}

function kk_product_exists( $slug ) {
	return (bool) get_posts( array( 'post_type' => 'kk_product', 'name' => $slug, 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'posts_per_page' => 1, 'fields' => 'ids' ) );
}

function kk_ensure_term( $tax, $slug, $name, $meta, $desc = '' ) {
	$t = get_term_by( 'slug', $slug, $tax );
	if ( $t ) {
		$id = (int) $t->term_id;
	} else {
		$r = wp_insert_term( $name, $tax, array( 'slug' => $slug, 'description' => $desc ) );
		if ( is_wp_error( $r ) ) {
			return 0;
		}
		$id = (int) $r['term_id'];
	}
	foreach ( $meta as $k => $v ) {
		if ( '' !== (string) $v && '' === (string) get_term_meta( $id, 'kk_' . $k, true ) ) {
			update_term_meta( $id, 'kk_' . $k, $v );
		}
	}
	return $id;
}

function kk_run_import( $o ) {
	$d   = kk_demo_data();
	$log = array();
	$l3  = array( 'en', 'fr', 'de' );

	if ( $o['cleanup'] ) {
		$old = array( 'rosyl-sity-tea-biscuits-65g', 'sybynzu-tea-biscuits-25g', 'rosyl-sity-vanilla-cream-52g', 'rosyl-sity-strawberry-cream-52g', 'rosyl-sity-chocolate-cream-52g', 'yunar-wan-chocolate-38g', 'yunar-wan-chocolate-orange-pack-38g', 'yunar-wan-vanilla', 'lilaf-wan-vanilla-14g', 'lilaf-wan-vanilla-purple-pack-14g', 'lilaf-wan-chocolate-14g', 'rosyl-tella-750g', 'rosyl-tella-350g', 'yunar-tella-40g', 'rosyl-tella-200g', 'yunar-tella-20g', 'rosyl-tella-20g' );
		$n   = 0;
		foreach ( $old as $slug ) {
			$p = get_posts( array( 'post_type' => 'kk_product', 'name' => $slug, 'posts_per_page' => 1 ) );
			$p = $p ? $p[0] : null;
			// v1 products have no packing data (_kk_units / _kk_carton_ar).
			if ( $p && '' === (string) get_post_meta( $p->ID, '_kk_carton_ar', true ) && '' === (string) get_post_meta( $p->ID, '_kk_gallery', true ) ) {
				wp_trash_post( $p->ID );
				++$n;
			}
		}
		foreach ( get_posts( array( 'post_type' => 'kk_slide', 'post_status' => 'any', 'posts_per_page' => 20, 'meta_key' => '_kk_word' ) ) as $s ) { // phpcs:ignore
			if ( in_array( get_post_meta( $s->ID, '_kk_word', true ), array( 'Kickers', 'Yunar', 'Tella', 'Rosyl' ), true ) ) {
				wp_trash_post( $s->ID );
				++$n;
			}
		}
		$t = get_term_by( 'slug', 'tella-spreads', 'kk_product_cat' );
		if ( $t ) {
			wp_update_term( $t->term_id, 'kk_product_cat', array( 'slug' => 'chocolate-spreads', 'name' => 'شيكولاتة سبريد' ) );
		}
		$log[] = 'عناصر قديمة اتنقلت للسلة: ' . $n;
	}

	if ( $o['products'] ) {
		$cat_ids = array();
		foreach ( $d['categories'] as $i => $c ) {
			$meta = array( 'color' => $c['color'], 'ink' => $c['ink'], 'order' => $i + 1, 'banner_show' => '1', 'banner_theme' => $c['theme'], 'banner_word' => $c['word'], 'banner_decor' => $c['decor'] );
			foreach ( $l3 as $l ) {
				$meta[ 'name_' . $l ] = $c['name'][ $l ];
				$meta[ 'desc_' . $l ] = $c['desc'][ $l ];
			}
			foreach ( array( 'ar', 'en', 'fr', 'de' ) as $l ) {
				$meta[ 'banner_title_' . $l ] = $c['banner_title'][ $l ];
				$meta[ 'banner_sub_' . $l ]   = $c['banner_sub'][ $l ];
			}
			$cat_ids[ $c['key'] ] = kk_ensure_term( 'kk_product_cat', $c['slug'], $c['name']['ar'], $meta, $c['desc']['ar'] );
		}
		$brand_ids = array();
		foreach ( $d['brands'] as $i => $b ) {
			$meta = array( 'name_en' => $b['name_en'], 'name_fr' => $b['name_en'], 'name_de' => $b['name_en'], 'color' => $b['color'], 'order' => $i + 1 );
			if ( $b['logo'] ) {
				$meta['logo'] = kk_import_image( 'brands/' . $b['logo'] . '.webp', $b['name_en'] );
			}
			$brand_ids[ $b['slug'] ] = kk_ensure_term( 'kk_brand', $b['slug'], $b['name_ar'], $meta );
		}
		$log[] = 'الأنواع: ' . count( $cat_ids ) . ' — العلامات: ' . count( $brand_ids );

		// v2.1: the cookie-shaped tub became "Meny Haifa chocolate cream 20 g".
		$kk_old = get_posts( array( 'post_type' => 'kk_product', 'name' => 'kickers-cookie-tub', 'post_status' => 'any', 'posts_per_page' => 1 ) );
		if ( $kk_old && ! kk_product_exists( 'meny-haifa-chocolate-cream-20g' ) ) {
			foreach ( $d['lines'] as $p ) {
				if ( 'meny-haifa-chocolate-cream-20g' !== $p['slug'] ) {
					continue;
				}
				$id = $kk_old[0]->ID;
				wp_update_post( array( 'ID' => $id, 'post_name' => $p['slug'], 'post_title' => $p['name']['ar'], 'post_excerpt' => $p['desc']['ar'] ) );
				foreach ( $l3 as $l ) {
					update_post_meta( $id, '_kk_title_' . $l, $p['name'][ $l ] );
					update_post_meta( $id, '_kk_excerpt_' . $l, $p['desc'][ $l ] );
				}
				foreach ( array( 'ar', 'en', 'fr', 'de' ) as $l ) {
					update_post_meta( $id, '_kk_weight_' . $l, $p['weight'][ $l ] );
				}
				if ( ! empty( $brand_ids[ $p['brand'] ] ) ) {
					wp_set_object_terms( $id, array( (int) $brand_ids[ $p['brand'] ] ), 'kk_brand' );
				}
				$log[] = 'تم تحويل "علبة كيكرز البسكويتة" إلى "' . $p['name']['ar'] . '".';
			}
		}

		$added = 0;
		foreach ( $d['lines'] as $i => $p ) {
			if ( kk_product_exists( $p['slug'] ) ) {
				continue;
			}
			$imgs    = array();
			$flavors = array();
			foreach ( $p['variants'] as $v ) {
				$imgs[] = kk_import_image( 'p2/' . $v['img'] . '.webp', $p['name']['ar'] . ( $v['flavor'] ? ' — ' . $v['flavor']['ar'] : '' ) );
				$flavors[] = $v['flavor'] ? implode( ' | ', array( $v['flavor']['ar'], $v['flavor']['en'], $v['flavor']['fr'], $v['flavor']['de'] ) ) : '';
			}
			$id = wp_insert_post( array( 'post_type' => 'kk_product', 'post_status' => 'publish', 'post_title' => $p['name']['ar'], 'post_name' => $p['slug'], 'post_excerpt' => $p['desc']['ar'], 'menu_order' => $i + 1 ) );
			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}
			$imgs = array_filter( $imgs );
			if ( $imgs ) {
				set_post_thumbnail( $id, reset( $imgs ) );
				update_post_meta( $id, '_kk_gallery', implode( ',', $imgs ) );
			}
			if ( array_filter( $flavors ) ) {
				update_post_meta( $id, '_kk_flavors', implode( "\n", $flavors ) );
			}
			foreach ( $l3 as $l ) {
				update_post_meta( $id, '_kk_title_' . $l, $p['name'][ $l ] );
				update_post_meta( $id, '_kk_excerpt_' . $l, $p['desc'][ $l ] );
			}
			foreach ( array( 'ar', 'en', 'fr', 'de' ) as $l ) {
				if ( $p['weight'][ $l ] ) {
					update_post_meta( $id, '_kk_weight_' . $l, $p['weight'][ $l ] );
				}
				if ( $p['carton'][ $l ] ) {
					update_post_meta( $id, '_kk_carton_' . $l, $p['carton'][ $l ] );
				}
			}
			if ( $p['units'] ) {
				update_post_meta( $id, '_kk_units', (string) $p['units'] );
			}
			update_post_meta( $id, '_kk_tint', $p['tint'] );
			if ( ! empty( $p['new'] ) ) {
				update_post_meta( $id, '_kk_new', '1' );
				update_post_meta( $id, '_kk_featured', '1' );
			}
			if ( ! empty( $cat_ids[ $p['cat'] ] ) ) {
				wp_set_object_terms( $id, array( (int) $cat_ids[ $p['cat'] ] ), 'kk_product_cat' );
			}
			if ( ! empty( $brand_ids[ $p['brand'] ] ) ) {
				wp_set_object_terms( $id, array( (int) $brand_ids[ $p['brand'] ] ), 'kk_brand' );
			}
			++$added;
		}
		$log[] = 'منتجات جديدة: ' . $added;
	}

	if ( $o['reviews'] && ! get_posts( array( 'post_type' => 'kk_review', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) ) ) {
		foreach ( $d['reviews'] as $i => $r ) {
			$id = wp_insert_post( array( 'post_type' => 'kk_review', 'post_status' => 'publish', 'post_title' => $r['name']['ar'], 'post_content' => $r['t']['ar'], 'menu_order' => $i ) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_kk_rating', '5' );
				update_post_meta( $id, '_kk_title_en', $r['name']['en'] );
				foreach ( $l3 as $l ) {
					update_post_meta( $id, '_kk_text_' . $l, $r['t'][ $l ] );
				}
			}
		}
		$log[] = 'آراء العملاء: ' . count( $d['reviews'] );
	}

	if ( $o['pages'] ) {
		$pages = array(
			'about'   => array( 'من نحن', 'About us', 'À propos', 'Über uns', 'page-about.php' ),
			'export'  => array( 'التصدير', 'Export', 'Export', 'Export', 'page-export.php' ),
			'agent'   => array( 'كن وكيلاً', 'Become an agent', 'Devenir agent', 'Partner werden', 'page-agent.php' ),
			'reviews' => array( 'آراء العملاء', 'Customer reviews', 'Avis clients', 'Kundenbewertungen', 'page-reviews.php' ),
			'contact' => array( 'تواصل معنا', 'Contact us', 'Contact', 'Kontakt', 'page-contact.php' ),
		);
		foreach ( $pages as $slug => $pg ) {
			$p  = get_page_by_path( $slug );
			$id = $p ? $p->ID : wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $pg[0], 'post_name' => $slug ) );
			if ( $p && 'publish' !== $p->post_status ) {
				wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
			}
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_wp_page_template', $pg[4] );
				update_post_meta( $id, '_kk_title_en', $pg[1] );
				update_post_meta( $id, '_kk_title_fr', $pg[2] );
				update_post_meta( $id, '_kk_title_de', $pg[3] );
			}
		}
		$log[] = 'الصفحات جاهزة: من نحن، التصدير، كن وكيلاً، آراء العملاء، تواصل معنا.';
	}

	if ( $o['icon'] ) {
		$icon = kk_import_image( 'brand/site-icon.png', 'Kickers logo icon' );
		if ( $icon ) {
			update_option( 'site_icon', $icon );
		}
		if ( ! get_theme_mod( 'custom_logo' ) ) {
			$logo = kk_import_image( 'brand/logo.png', 'Kickers logo' );
			if ( $logo ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}
		$log[] = 'أيقونة الموقع = الشعار.';
	}
	if ( $o['front'] ) {
		update_option( 'show_on_front', 'posts' );
	}
	update_option( 'kk_demo_done', 2 );
	flush_rewrite_rules();
	return $log;
}
