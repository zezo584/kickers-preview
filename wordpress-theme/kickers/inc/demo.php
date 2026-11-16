<?php
/**
 * One-click setup: imports categories, brands, 17 products with images, the 4 hero banners,
 * the About/Contact pages and sets the site icon to the logo.
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
		if ( ! current_user_can( 'manage_options' ) || get_option( 'kk_demo_done' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_kk-setup' === $screen->id ) {
			return;
		}
		echo '<div class="notice notice-info"><p><strong>قالب كيكرز فودز:</strong> اضغط "استيراد المحتوى" عشان المنتجات والبانرات والصفحات تتضاف تلقائيًا. <a class="button button-primary" href="' . esc_url( admin_url( 'themes.php?page=kk-setup' ) ) . '">البداية السريعة</a></p></div>';
	}
);

function kk_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log = array();
	if ( isset( $_POST['kk_import'] ) && check_admin_referer( 'kk_import' ) ) {
		$opts = array(
			'front'    => ! empty( $_POST['kk_front'] ),
			'icon'     => ! empty( $_POST['kk_icon'] ),
			'pages'    => ! empty( $_POST['kk_pages'] ),
			'products' => ! empty( $_POST['kk_products'] ),
			'slides'   => ! empty( $_POST['kk_slides'] ),
		);
		$log  = kk_run_import( $opts );
	}
	echo '<div class="wrap kk-setup"><h1>كيكرز فودز — البداية السريعة</h1>';
	if ( $log ) {
		echo '<div class="notice notice-success"><p><strong>تم الاستيراد:</strong></p><ul style="list-style:disc;padding-inline-start:20px">';
		foreach ( $log as $l ) {
			echo '<li>' . esc_html( $l ) . '</li>';
		}
		echo '</ul><p><a class="button button-primary" href="' . esc_url( home_url( '/' ) ) . '" target="_blank">افتح الموقع</a></p></div>';
	}
	echo '<div class="card"><h2>استيراد محتوى الموقع</h2><p>بيضيف محتوى كيكرز الجاهز (من الموقع القديم) مرة واحدة. لو حاجة موجودة بنفس الاسم بيسيبها زي ما هي، فممكن تضغط تاني بأمان.</p>';
	echo '<form method="post">';
	wp_nonce_field( 'kk_import' );
	$checks = array(
		'kk_products' => 'التصنيفات (4) والعلامات التجارية (6) والمنتجات (17) بصورها',
		'kk_slides'   => 'بانرات السلايدر الرئيسية (4)',
		'kk_pages'    => 'صفحات "من نحن" و"تواصل معنا" (لو موجودة بنفس الرابط: about و contact هيتطبق عليها تصميم القالب)',
		'kk_icon'     => 'أيقونة الموقع (Favicon) = شعار كيكرز',
		'kk_front'    => 'الصفحة الرئيسية = تصميم القالب (يلغي صفحة Elementor الرئيسية القديمة من العرض بدون ما يمسحها)',
	);
	echo '<ul class="kk-steps">';
	foreach ( $checks as $k => $label ) {
		echo '<li><label><input type="checkbox" name="' . esc_attr( $k ) . '" value="1" checked> ' . esc_html( $label ) . '</label></li>';
	}
	echo '</ul><p><button class="button button-primary button-hero" name="kk_import" value="1">استيراد المحتوى</button></p></form></div>';

	echo '<div class="card"><h2>هتعدّل منين؟</h2><ul style="list-style:disc;padding-inline-start:20px">';
	echo '<li><strong>المنتجات:</strong> القائمة الجانبية ← المنتجات (أضف / عدّل الاسم والصورة والوزن والنسخة الإنجليزية، وعلّم "اعرض في الرئيسية").</li>';
	echo '<li><strong>التصنيفات والعلامات:</strong> المنتجات ← التصنيفات / العلامات التجارية (الاسم الإنجليزي واللون والترتيب).</li>';
	echo '<li><strong>البانرات:</strong> القائمة الجانبية ← بانرات السلايدر (تصميم متحرك أو صورة جاهزة للكمبيوتر والموبايل).</li>';
	echo '<li><strong>كل النصوص وبيانات التواصل والسوشيال وSEO:</strong> المظهر ← تخصيص ← إعدادات كيكرز.</li>';
	echo '<li><strong>الشعار:</strong> المظهر ← تخصيص ← هوية الموقع.</li>';
	echo '<li><strong>رسائل نموذج التواصل:</strong> القائمة الجانبية ← رسائل التواصل (وبتوصل كمان على الإيميل).</li>';
	echo '<li><strong>النسخة الإنجليزية:</strong> نفس الروابط بعد ‎/en/‎ — مثال: ' . esc_html( kk_url_to_lang( home_url( '/' ), 'en' ) ) . '</li>';
	echo '</ul></div></div>';
}

/**
 * Copy an image from the theme into the Media Library (once).
 */
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
	$name   = 'kickers-' . basename( $file );
	$upload = wp_upload_bits( $name, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $title );
	$map[ $file ] = $id;
	update_option( 'kk_demo_media', $map, false );
	return (int) $id;
}

function kk_ensure_term( $tax, $slug, $name, $meta ) {
	$t = get_term_by( 'slug', $slug, $tax );
	if ( ! $t ) {
		$r = wp_insert_term( $name, $tax, array( 'slug' => $slug, 'description' => isset( $meta['desc_ar'] ) ? $meta['desc_ar'] : '' ) );
		if ( is_wp_error( $r ) ) {
			return 0;
		}
		$id = (int) $r['term_id'];
		foreach ( $meta as $k => $v ) {
			if ( 'desc_ar' !== $k && '' !== (string) $v ) {
				update_term_meta( $id, 'kk_' . $k, $v );
			}
		}
		return $id;
	}
	return (int) $t->term_id;
}

function kk_run_import( $o ) {
	$d   = kk_demo_data();
	$log = array();

	$img_ids = array();
	$names   = array();
	foreach ( $d['products'] as $p ) {
		$names[ $p['img'] ] = $p['name_en'];
	}

	if ( $o['products'] ) {
		$cat_ids = array();
		foreach ( $d['categories'] as $i => $c ) {
			$cat_ids[ $c['key'] ] = kk_ensure_term(
				'kk_product_cat',
				$c['slug'],
				$c['name_ar'],
				array(
					'name_en' => $c['name_en'],
					'desc_ar' => $c['desc_ar'],
					'desc_en' => $c['desc_en'],
					'color'   => $c['color'],
					'ink'     => $c['ink'],
					'order'   => $i + 1,
				)
			);
		}
		$brand_ids = array();
		foreach ( $d['brands'] as $i => $b ) {
			$brand_ids[ $b['slug'] ] = kk_ensure_term(
				'kk_brand',
				$b['slug'],
				$b['name_ar'],
				array(
					'name_en' => $b['name_en'],
					'color'   => $b['color'],
					'order'   => $i + 1,
				)
			);
		}
		$log[] = 'التصنيفات: ' . count( $cat_ids ) . ' — العلامات التجارية: ' . count( $brand_ids );

		$added = 0;
		foreach ( $d['products'] as $i => $p ) {
			$exists = get_page_by_path( $p['slug'], OBJECT, 'kk_product' );
			if ( $exists ) {
				continue;
			}
			$img = kk_import_image( 'products/' . $p['img'] . '.webp', $p['name_ar'] );
			$id  = wp_insert_post(
				array(
					'post_type'    => 'kk_product',
					'post_status'  => 'publish',
					'post_title'   => $p['name_ar'],
					'post_name'    => $p['slug'],
					'post_excerpt' => $p['desc_ar'],
					'post_content' => '',
					'menu_order'   => $i + 1,
				)
			);
			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
			update_post_meta( $id, '_kk_title_en', $p['name_en'] );
			update_post_meta( $id, '_kk_excerpt_en', $p['desc_en'] );
			if ( $p['weight_ar'] ) {
				update_post_meta( $id, '_kk_weight_ar', $p['weight_ar'] );
				update_post_meta( $id, '_kk_weight_en', $p['weight_en'] );
			}
			update_post_meta( $id, '_kk_tint', $p['tint'] );
			if ( $p['featured'] ) {
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

	if ( $o['slides'] ) {
		$have = get_posts( array( 'post_type' => 'kk_slide', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
		if ( $have ) {
			$log[] = 'البانرات موجودة بالفعل — اتسابت زي ما هي.';
		} else {
			foreach ( $d['slides'] as $i => $s ) {
				$id = wp_insert_post(
					array(
						'post_type'   => 'kk_slide',
						'post_status' => 'publish',
						'post_title'  => $s['title_ar'],
						'menu_order'  => $i + 1,
					)
				);
				if ( ! $id || is_wp_error( $id ) ) {
					continue;
				}
				$meta = array(
					'mode'     => 'design',
					'title_en' => $s['title_en'],
					'word'     => $s['word'],
					'sub_ar'   => $s['sub_ar'],
					'sub_en'   => $s['sub_en'],
					'label_ar' => $s['label_ar'],
					'label_en' => $s['label_en'],
					'theme'    => $s['theme'],
					'decor'    => $s['decor'],
					'btn1_ar'  => $s['btn1_ar'],
					'btn1_en'  => $s['btn1_en'],
					'btn1_url' => $s['btn1_url'],
					'btn2_ar'  => $s['btn2_ar'],
					'btn2_en'  => $s['btn2_en'],
					'btn2_url' => $s['btn2_url'],
				);
				foreach ( $s['images'] as $k => $im ) {
					$meta[ 'img' . ( $k + 1 ) ] = kk_import_image( 'products/' . $im . '.webp', isset( $names[ $im ] ) ? $names[ $im ] : $im );
				}
				foreach ( $meta as $k => $v ) {
					update_post_meta( $id, '_kk_' . $k, $v );
				}
			}
			$log[] = 'البانرات: ' . count( $d['slides'] );
		}
	}

	if ( $o['pages'] ) {
		$pages = array(
			'about'   => array( 'من نحن', 'About us', 'page-about.php' ),
			'contact' => array( 'تواصل معنا', 'Contact us', 'page-contact.php' ),
		);
		foreach ( $pages as $slug => $pg ) {
			$p = get_page_by_path( $slug );
			if ( $p ) {
				$id = $p->ID;
				if ( 'publish' !== $p->post_status ) {
					wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
				}
			} else {
				$id = wp_insert_post(
					array(
						'post_type'   => 'page',
						'post_status' => 'publish',
						'post_title'  => $pg[0],
						'post_name'   => $slug,
					)
				);
			}
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_wp_page_template', $pg[2] );
				update_post_meta( $id, '_kk_title_en', $pg[1] );
			}
		}
		$log[] = 'صفحات من نحن وتواصل معنا جاهزة.';
	}

	if ( $o['icon'] ) {
		$icon = kk_import_image( 'brand/icon-512.png', 'Kickers icon' );
		if ( $icon ) {
			update_option( 'site_icon', $icon );
			$log[] = 'أيقونة الموقع = الشعار.';
		}
		if ( ! get_theme_mod( 'custom_logo' ) ) {
			$logo = kk_import_image( 'brand/logo.png', 'Kickers logo' );
			if ( $logo ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}
	}

	if ( $o['front'] ) {
		update_option( 'show_on_front', 'posts' );
		$log[] = 'الصفحة الرئيسية بتعرض تصميم القالب.';
	}

	update_option( 'kk_demo_done', 1 );
	flush_rewrite_rules();
	return $log;
}
