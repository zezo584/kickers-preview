<?php
/**
 * Admin fields: product lines (flavours, packing, NEW), banners, translations for pages/posts, reviews, term fields.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		$types = array( 'kk_product', 'kk_slide', 'kk_review', 'page', 'post' );
		$taxes = array( 'kk_product_cat', 'kk_brand' );
		if ( in_array( $screen->post_type, $types, true ) || in_array( $screen->taxonomy, $taxes, true ) || 'appearance_page_kk-setup' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'kk-admin', KK_URI . '/assets/css/admin.css', array(), KK_VERSION );
			wp_enqueue_script( 'kk-admin', KK_URI . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), KK_VERSION, true );
		}
	}
);

function kk_lang_labels() {
	return array( 'ar' => 'عربي', 'en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch' );
}

/* ---------- Field renderers ---------- */
function kk_field_text( $name, $label, $value, $args = array() ) {
	$dir  = isset( $args['dir'] ) ? $args['dir'] : 'auto';
	$ph   = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
	$type = isset( $args['type'] ) ? $args['type'] : 'text';
	$help = isset( $args['help'] ) ? '<span class="kk-help">' . esc_html( $args['help'] ) . '</span>' : '';
	printf( '<label class="kk-field"><span class="kk-label">%1$s</span><input type="%7$s" name="%2$s" value="%3$s" dir="%4$s" placeholder="%5$s" class="widefat">%6$s</label>', esc_html( $label ), esc_attr( $name ), esc_attr( $value ), esc_attr( $dir ), esc_attr( $ph ), $help, esc_attr( $type ) ); // phpcs:ignore
}
function kk_field_textarea( $name, $label, $value, $args = array() ) {
	$dir  = isset( $args['dir'] ) ? $args['dir'] : 'auto';
	$rows = isset( $args['rows'] ) ? (int) $args['rows'] : 3;
	$help = isset( $args['help'] ) ? '<span class="kk-help">' . esc_html( $args['help'] ) . '</span>' : '';
	printf( '<label class="kk-field"><span class="kk-label">%1$s</span><textarea name="%2$s" rows="%4$d" dir="%5$s" class="widefat">%3$s</textarea>%6$s</label>', esc_html( $label ), esc_attr( $name ), esc_textarea( $value ), absint( $rows ), esc_attr( $dir ), $help ); // phpcs:ignore
}
function kk_field_color( $name, $label, $value, $default = '' ) {
	printf( '<label class="kk-field"><span class="kk-label">%1$s</span><input type="text" name="%2$s" value="%3$s" class="kk-color" data-default-color="%4$s"></label>', esc_html( $label ), esc_attr( $name ), esc_attr( $value ), esc_attr( $default ) );
}
function kk_field_select( $name, $label, $value, $options ) {
	echo '<label class="kk-field"><span class="kk-label">' . esc_html( $label ) . '</span><select name="' . esc_attr( $name ) . '" class="widefat">';
	foreach ( $options as $k => $v ) {
		echo '<option value="' . esc_attr( $k ) . '"' . selected( (string) $value, (string) $k, false ) . '>' . esc_html( $v ) . '</option>';
	}
	echo '</select></label>';
}
function kk_field_check( $name, $label, $value ) {
	echo '<label class="kk-field kk-check"><input type="checkbox" name="' . esc_attr( $name ) . '" value="1"' . checked( (string) $value, '1', false ) . '> ' . esc_html( $label ) . '</label>';
}
function kk_field_media( $name, $label, $value, $multiple = false, $help = '' ) {
	$ids  = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
	$prev = '';
	foreach ( $ids as $id ) {
		$prev .= wp_get_attachment_image( $id, 'thumbnail' );
	}
	printf(
		'<div class="kk-field kk-media" data-multiple="%5$s"><span class="kk-label">%1$s</span><input type="hidden" name="%2$s" value="%3$s"><div class="kk-media-prev">%4$s</div><p><button type="button" class="button kk-media-pick">%6$s</button> <button type="button" class="button-link-delete kk-media-clear">إزالة</button></p>%7$s</div>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_attr( implode( ',', $ids ) ),
		$prev, // phpcs:ignore
		$multiple ? '1' : '0',
		$multiple ? 'اختيار / ترتيب الصور' : 'اختيار صورة',
		$help ? '<span class="kk-help">' . esc_html( $help ) . '</span>' : ''
	);
}
/** One block per language. $fields = [ key => [label, type] ] saved as kk_{key}_{lang}. */
function kk_lang_blocks( $get, $fields, $skip_ar = array() ) {
	echo '<div class="kk-langs">';
	foreach ( kk_lang_labels() as $l => $ln ) {
		echo '<details class="kk-lang"' . ( 'ar' === $l ? ' open' : '' ) . '><summary>' . esc_html( $ln ) . '</summary><div class="kk-grid">';
		foreach ( $fields as $k => $f ) {
			if ( 'ar' === $l && in_array( $k, $skip_ar, true ) ) {
				continue;
			}
			$dir = 'ar' === $l ? 'rtl' : 'ltr';
			if ( 'textarea' === $f[1] ) {
				kk_field_textarea( 'kk_' . $k . '_' . $l, $f[0], $get( $k . '_' . $l ), array( 'dir' => $dir ) );
			} else {
				kk_field_text( 'kk_' . $k . '_' . $l, $f[0], $get( $k . '_' . $l ), array( 'dir' => $dir ) );
			}
		}
		echo '</div></details>';
	}
	echo '</div>';
}
function kk_save_post_fields( $post_id, $keys, $html = array() ) {
	foreach ( $keys as $k ) {
		if ( ! isset( $_POST[ 'kk_' . $k ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			continue;
		}
		$raw = wp_unslash( $_POST[ 'kk_' . $k ] ); // phpcs:ignore
		$val = in_array( $k, $html, true ) ? wp_kses_post( $raw ) : sanitize_textarea_field( $raw );
		'' === $val ? delete_post_meta( $post_id, '_kk_' . $k ) : update_post_meta( $post_id, '_kk_' . $k, $val );
	}
}
function kk_ml_keys( $base, $langs = array( 'ar', 'en', 'fr', 'de' ) ) {
	$o = array();
	foreach ( (array) $base as $b ) {
		foreach ( $langs as $l ) {
			$o[] = $b . '_' . $l;
		}
	}
	return $o;
}

/* ---------- Meta boxes ---------- */
add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'kk_product_box', 'تفاصيل المنتج: النكهات والتعبئة واللغات', 'kk_product_box', 'kk_product', 'normal', 'high' );
		add_meta_box( 'kk_slide_box', 'إعدادات البانر', 'kk_slide_box', 'kk_slide', 'normal', 'high' );
		add_meta_box( 'kk_review_box', 'تفاصيل الرأي', 'kk_review_box', 'kk_review', 'normal', 'high' );
		foreach ( array( 'page', 'post' ) as $pt ) {
			add_meta_box( 'kk_ml_box', 'الترجمات — English / Français / Deutsch', 'kk_ml_box', $pt, 'normal', 'default' );
		}
	}
);

function kk_product_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	$g = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_kk_' . $k, true );
	};
	echo '<p class="kk-intro">كل منتج هنا = نوع واحد (مثلاً "روسيل سيتي محشو مربع") ونكهاته جواه. الاسم العربي فوق، الوصف المختصر العربي في "مقتطف".</p>';
	echo '<div class="kk-grid">';
	kk_field_check( 'kk_new', 'منتج جديد ★ (يظهر عليه "جديد" ويتعمله بانر رئيسي متحرك تلقائي)', $g( 'new' ) );
	kk_field_check( 'kk_featured', 'اعرضه أول في الصفحة الرئيسية', $g( 'featured' ) );
	kk_field_color( 'kk_tint', 'لون خلفية الكارت', $g( 'tint' ), '#FDEBCB' );
	kk_field_select( 'kk_banner_theme', 'لون البانر لو المنتج جديد', $g( 'banner_theme' ), array( '' => 'تلقائي', 'red' => 'أحمر', 'gold' => 'أصفر', 'cocoa' => 'بني', 'cream' => 'كريمي' ) );
	echo '</div><h4>النكهات / الأشكال</h4><div class="kk-grid">';
	kk_field_media( 'kk_gallery', 'صور النكهات بالترتيب (أول صورة = الرئيسية)', $g( 'gallery' ), true, 'اختار صورة لكل نكهة. لو منتج بنكهة واحدة اختار صورة واحدة.' );
	kk_field_textarea( 'kk_flavors', 'أسماء النكهات بنفس ترتيب الصور — سطر لكل نكهة', $g( 'flavors' ), array( 'rows' => 5, 'help' => 'الشكل: عربي | English | Français | Deutsch — مثال: موز | Banana | Banane | Banane' ) );
	echo '</div><h4>التعبئة والشحن</h4><div class="kk-grid kk-grid-3">';
	kk_field_text( 'kk_units', 'إجمالي العبوات في الكرتونة (رقم)', $g( 'units' ), array( 'type' => 'number', 'dir' => 'ltr' ) );
	kk_field_text( 'kk_cont20', 'عدد الكراتين في حاوية 20 قدم (اختياري)', $g( 'cont20' ), array( 'dir' => 'ltr' ) );
	kk_field_text( 'kk_cont40', 'عدد الكراتين في حاوية 40 قدم (اختياري)', $g( 'cont40' ), array( 'dir' => 'ltr' ) );
	echo '</div>';
	kk_lang_blocks(
		$g,
		array(
			'title'   => array( 'الاسم', 'text' ),
			'excerpt' => array( 'وصف مختصر', 'textarea' ),
			'weight'  => array( 'وزن العبوة (مثال: 55 جم)', 'text' ),
			'carton'  => array( 'محتوى الكرتونة (مثال: 6 علب × 12 باكو)', 'text' ),
		),
		array( 'title', 'excerpt' )
	);
	echo '<p class="kk-help">النوع (سادة/محشو/...) والعلامة التجارية من الصناديق على الجنب. الترتيب من "خصائص الصفحة ← الترتيب".</p>';
}

function kk_slide_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	$g = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_kk_' . $k, true );
	};
	echo '<p class="kk-intro">بانرات المنتجات الجديدة وبانرات الأنواع بتتعمل تلقائي. هنا تقدر تضيف بانرات زيادة (عرض، مناسبة...). العنوان العربي = عنوان المنشور.</p>';
	kk_field_select( 'kk_mode', 'نوع البانر', $g( 'mode' ) ? $g( 'mode' ) : 'design', array( 'design' => 'تصميم كيكرز المتحرك', 'image' => 'صورة بانر جاهزة' ) );
	echo '<div class="kk-grid">';
	kk_field_check( 'kk_is_new', 'اعرض علامة "جديد"', $g( 'is_new' ) );
	kk_field_text( 'kk_word', 'الكلمة الكبيرة في الخلفية (إنجليزي)', $g( 'word' ), array( 'dir' => 'ltr' ) );
	kk_field_select( 'kk_theme', 'اللون', $g( 'theme' ) ? $g( 'theme' ) : 'red', array( 'red' => 'أحمر', 'cocoa' => 'بني', 'gold' => 'أصفر', 'cream' => 'كريمي' ) );
	kk_field_select( 'kk_decor', 'الزخارف', $g( 'decor' ) ? $g( 'decor' ) : 'wheat', array( 'wheat' => 'قمح وبسكويت', 'chocolate' => 'شوكولاتة', 'hazelnut' => 'بندق', 'biscuit' => 'بسكويت', 'none' => 'بدون' ) );
	echo '</div><div class="kk-panel" data-show-mode="design"><div class="kk-grid kk-grid-3">';
	kk_field_media( 'kk_img1', 'صورة المنتج الرئيسية', $g( 'img1' ) );
	kk_field_media( 'kk_img2', 'صورة 2', $g( 'img2' ) );
	kk_field_media( 'kk_img3', 'صورة 3', $g( 'img3' ) );
	echo '</div><div class="kk-grid">';
	kk_field_text( 'kk_btn1_url', 'رابط الزرار الأساسي', $g( 'btn1_url' ), array( 'dir' => 'ltr', 'placeholder' => 'products', 'help' => 'products / whatsapp / contact / export / agent / cat:slug / product:slug / أي رابط' ) );
	kk_field_text( 'kk_btn2_url', 'رابط الزرار الثاني', $g( 'btn2_url' ), array( 'dir' => 'ltr', 'placeholder' => 'whatsapp' ) );
	echo '</div></div><div class="kk-panel" data-show-mode="image"><div class="kk-grid kk-grid-3">';
	kk_field_media( 'kk_banner', 'صورة الكمبيوتر (1920×860)', $g( 'banner' ) );
	kk_field_media( 'kk_banner_m', 'صورة الموبايل (900×1100)', $g( 'banner_m' ) );
	kk_field_text( 'kk_banner_url', 'الرابط عند الضغط', $g( 'banner_url' ), array( 'dir' => 'ltr' ) );
	echo '</div></div>';
	kk_lang_blocks(
		$g,
		array(
			'title' => array( 'العنوان', 'text' ),
			'sub'   => array( 'الوصف', 'textarea' ),
			'btn1'  => array( 'نص الزرار الأساسي', 'text' ),
			'btn2'  => array( 'نص الزرار الثاني', 'text' ),
			'label' => array( 'اسم قصير في شريط البانرات', 'text' ),
		),
		array( 'title' )
	);
}

function kk_review_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	$g = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_kk_' . $k, true );
	};
	echo '<p class="kk-intro">اسم العميل = العنوان، والرأي بالعربي في المحرر. الآراء اللي بتيجي من الموقع بتوصل هنا "قيد المراجعة" — انشرها عشان تظهر.</p><div class="kk-grid kk-grid-3">';
	kk_field_select( 'kk_rating', 'التقييم', $g( 'rating' ) ? $g( 'rating' ) : '5', array( '5' => '★★★★★', '4' => '★★★★', '3' => '★★★', '2' => '★★', '1' => '★' ) );
	kk_field_text( 'kk_city', 'المدينة / الدولة', $g( 'city' ) );
	echo '</div><div class="kk-grid kk-grid-3">';
	foreach ( array( 'en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch' ) as $l => $n ) {
		kk_field_textarea( 'kk_text_' . $l, 'الرأي — ' . $n, $g( 'text_' . $l ), array( 'dir' => 'ltr' ) );
	}
	echo '</div>';
}

function kk_ml_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	echo '<p class="kk-intro">النص اللي يظهر على /en/ و /fr/ و /de/. اللي يتساب فاضي يظهر بالإنجليزي ثم العربي.</p>';
	foreach ( array( 'en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch' ) as $l => $n ) {
		echo '<details class="kk-lang"><summary>' . esc_html( $n ) . '</summary>';
		kk_field_text( 'kk_title_' . $l, 'Title', get_post_meta( $post->ID, '_kk_title_' . $l, true ), array( 'dir' => 'ltr' ) );
		kk_field_textarea( 'kk_excerpt_' . $l, 'Short / SEO description', get_post_meta( $post->ID, '_kk_excerpt_' . $l, true ), array( 'dir' => 'ltr' ) );
		wp_editor( (string) get_post_meta( $post->ID, '_kk_content_' . $l, true ), 'kk_content_' . $l, array( 'textarea_name' => 'kk_content_' . $l, 'textarea_rows' => 6 ) );
		echo '</details>';
	}
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ! isset( $_POST['kk_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kk_meta_nonce'] ) ), 'kk_save_meta' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		$checks = array();
		if ( 'kk_product' === $post->post_type ) {
			kk_save_post_fields( $post_id, array_merge( array( 'gallery', 'flavors', 'units', 'cont20', 'cont40', 'banner_theme' ), kk_ml_keys( array( 'title', 'excerpt' ), array( 'en', 'fr', 'de' ) ), kk_ml_keys( array( 'weight', 'carton' ) ) ) );
			$tint = isset( $_POST['kk_tint'] ) ? sanitize_hex_color( wp_unslash( $_POST['kk_tint'] ) ) : '';
			$tint ? update_post_meta( $post_id, '_kk_tint', $tint ) : delete_post_meta( $post_id, '_kk_tint' );
			$checks = array( 'new', 'featured' );
			// Keep the featured image in sync with the first flavour.
			$ids = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $post_id, '_kk_gallery', true ) ) ) );
			if ( $ids && ! has_post_thumbnail( $post_id ) ) {
				set_post_thumbnail( $post_id, reset( $ids ) );
			}
		} elseif ( 'kk_slide' === $post->post_type ) {
			kk_save_post_fields( $post_id, array_merge( array( 'mode', 'word', 'theme', 'decor', 'img1', 'img2', 'img3', 'btn1_url', 'btn2_url', 'banner', 'banner_m', 'banner_url' ), kk_ml_keys( array( 'title' ), array( 'en', 'fr', 'de' ) ), kk_ml_keys( array( 'sub', 'btn1', 'btn2', 'label' ) ) ) );
			$checks = array( 'is_new' );
		} elseif ( 'kk_review' === $post->post_type ) {
			kk_save_post_fields( $post_id, array( 'rating', 'city', 'text_en', 'text_fr', 'text_de' ) );
		} else {
			kk_save_post_fields( $post_id, kk_ml_keys( array( 'title', 'excerpt', 'content' ), array( 'en', 'fr', 'de' ) ), kk_ml_keys( array( 'content' ), array( 'en', 'fr', 'de' ) ) );
		}
		foreach ( $checks as $c ) {
			isset( $_POST[ 'kk_' . $c ] ) ? update_post_meta( $post_id, '_kk_' . $c, '1' ) : delete_post_meta( $post_id, '_kk_' . $c );
		}
	},
	10,
	2
);

/* ---------- Term fields ---------- */
function kk_term_fields_def( $kind ) {
	$f = array();
	foreach ( array( 'en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch' ) as $l => $n ) {
		$f[ 'name_' . $l ] = array( 'الاسم — ' . $n, 'text' );
		if ( 'cat' === $kind ) {
			$f[ 'desc_' . $l ] = array( 'الوصف — ' . $n, 'textarea' );
		}
	}
	$f['color'] = array( 'cat' === $kind ? 'لون كارت النوع' : 'لون اسم العلامة', 'color' );
	if ( 'cat' === $kind ) {
		$f['ink']          = array( 'لون النص على الكارت', 'color' );
		$f['banner_show']  = array( 'اعمل بانر رئيسي متحرك للنوع ده', 'check' );
		$f['banner_theme'] = array( 'لون البانر', 'theme' );
		$f['banner_word']  = array( 'الكلمة الكبيرة في خلفية البانر (إنجليزي)', 'text' );
		$f['banner_decor'] = array( 'زخارف البانر', 'decor' );
		$f['banner_photo'] = array( 'صورة خلفية للبانر (اختياري)', 'media' );
		foreach ( kk_lang_labels() as $l => $n ) {
			$f[ 'banner_title_' . $l ] = array( 'عنوان البانر — ' . $n, 'text' );
			$f[ 'banner_sub_' . $l ]   = array( 'وصف البانر — ' . $n, 'textarea' );
		}
	} else {
		$f['logo'] = array( 'لوجو العلامة (PNG/WebP شفاف)', 'media' );
	}
	$f['order'] = array( 'الترتيب (رقم)', 'number' );
	return $f;
}
foreach ( array( 'kk_product_cat' => 'cat', 'kk_brand' => 'brand' ) as $kk_tax => $kk_kind ) {
	add_action(
		$kk_tax . '_add_form_fields',
		function () use ( $kk_kind ) {
			wp_nonce_field( 'kk_term', 'kk_term_nonce' );
			foreach ( kk_term_fields_def( $kk_kind ) as $k => $d ) {
				echo '<div class="form-field">';
				kk_term_input( $k, $d, 'banner_show' === $k ? '1' : '' );
				echo '</div>';
			}
		}
	);
	add_action(
		$kk_tax . '_edit_form_fields',
		function ( $term ) use ( $kk_kind ) {
			wp_nonce_field( 'kk_term', 'kk_term_nonce' );
			foreach ( kk_term_fields_def( $kk_kind ) as $k => $d ) {
				$v = get_term_meta( $term->term_id, 'kk_' . $k, true );
				if ( 'banner_show' === $k && '' === $v ) {
					$v = '1';
				}
				echo '<tr class="form-field"><th scope="row">' . esc_html( $d[0] ) . '</th><td>';
				kk_term_input( $k, $d, $v, true );
				echo '</td></tr>';
			}
		}
	);
	add_action( 'created_' . $kk_tax, 'kk_save_term' );
	add_action( 'edited_' . $kk_tax, 'kk_save_term' );
}
function kk_term_input( $k, $d, $val, $table = false ) {
	$name  = 'kk_term_' . $k;
	$label = $table ? '' : $d[0];
	$dir   = preg_match( '/_(en|fr|de)$/', $k ) || in_array( $k, array( 'banner_word' ), true ) ? 'ltr' : 'auto';
	switch ( $d[1] ) {
		case 'textarea':
			kk_field_textarea( $name, $label, $val, array( 'dir' => $dir ) );
			break;
		case 'color':
			kk_field_color( $name, $label, $val );
			break;
		case 'media':
			kk_field_media( $name, $label, $val );
			break;
		case 'check':
			echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="0">';
			kk_field_check( $name, $table ? 'نعم' : $label, $val );
			break;
		case 'theme':
			kk_field_select( $name, $label, $val, array( 'red' => 'أحمر', 'cocoa' => 'بني', 'gold' => 'أصفر', 'cream' => 'كريمي' ) );
			break;
		case 'decor':
			kk_field_select( $name, $label, $val, array( 'wheat' => 'قمح وبسكويت', 'chocolate' => 'شوكولاتة', 'hazelnut' => 'بندق', 'biscuit' => 'بسكويت', 'none' => 'بدون' ) );
			break;
		case 'number':
			printf( '<label class="kk-field">%s<input type="number" name="%s" value="%s" class="small-text"></label>', $label ? '<span class="kk-label">' . esc_html( $label ) . '</span>' : '', esc_attr( $name ), esc_attr( $val ) ); // phpcs:ignore
			break;
		default:
			kk_field_text( $name, $label, $val, array( 'dir' => $dir ) );
	}
}
function kk_save_term( $term_id ) {
	if ( ! isset( $_POST['kk_term_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kk_term_nonce'] ) ), 'kk_term' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	foreach ( $_POST as $key => $raw ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( 0 !== strpos( $key, 'kk_term_' ) || 'kk_term_nonce' === $key ) {
			continue;
		}
		$k   = substr( $key, 8 );
		$raw = wp_unslash( $raw );
		if ( in_array( $k, array( 'color', 'ink' ), true ) ) {
			$v = sanitize_hex_color( $raw );
		} elseif ( in_array( $k, array( 'order', 'image', 'logo', 'banner_photo', 'banner_show' ), true ) ) {
			$v = '' === $raw ? '' : (string) absint( $raw );
		} else {
			$v = sanitize_textarea_field( $raw );
		}
		'' === (string) $v ? delete_term_meta( $term_id, 'kk_' . $k ) : update_term_meta( $term_id, 'kk_' . $k, $v );
	}
}

/* ---------- Admin columns ---------- */
add_filter(
	'manage_kk_product_posts_columns',
	function ( $cols ) {
		$new = array();
		foreach ( $cols as $k => $v ) {
			if ( 'title' === $k ) {
				$new['kk_thumb'] = 'الصورة';
			}
			$new[ $k ] = $v;
			if ( 'title' === $k ) {
				$new['kk_flavors'] = 'النكهات';
				$new['kk_new']     = 'جديد';
			}
		}
		return $new;
	}
);
add_action(
	'manage_kk_product_posts_custom_column',
	function ( $col, $id ) {
		if ( 'kk_thumb' === $col ) {
			echo get_the_post_thumbnail( $id, array( 56, 56 ) );
		} elseif ( 'kk_flavors' === $col ) {
			echo (int) count( kk_variants( $id ) );
		} elseif ( 'kk_new' === $col ) {
			echo get_post_meta( $id, '_kk_new', true ) ? '★' : '—';
		}
	},
	10,
	2
);
