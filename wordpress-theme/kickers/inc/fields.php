<?php
/**
 * Admin fields: product details, slide settings, English content for pages, term fields.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------ *
 * Admin assets
 * ------------------------------------------------------------------ */
add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		$types = array( 'kk_product', 'kk_slide', 'page', 'post' );
		$taxes = array( 'kk_product_cat', 'kk_brand' );
		if ( in_array( $screen->post_type, $types, true ) || in_array( $screen->taxonomy, $taxes, true ) || 'appearance_page_kk-setup' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'kk-admin', KK_URI . '/assets/css/admin.css', array(), KK_VERSION );
			wp_enqueue_script( 'kk-admin', KK_URI . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), KK_VERSION, true );
		}
	}
);

/* ------------------------------------------------------------------ *
 * Field renderers
 * ------------------------------------------------------------------ */
function kk_field_text( $name, $label, $value, $args = array() ) {
	$dir  = isset( $args['dir'] ) ? $args['dir'] : 'auto';
	$ph   = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
	$help = isset( $args['help'] ) ? '<span class="kk-help">' . esc_html( $args['help'] ) . '</span>' : '';
	printf(
		'<label class="kk-field"><span class="kk-label">%1$s</span><input type="text" name="%2$s" value="%3$s" dir="%4$s" placeholder="%5$s" class="widefat">%6$s</label>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_attr( $value ),
		esc_attr( $dir ),
		esc_attr( $ph ),
		$help // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
function kk_field_textarea( $name, $label, $value, $args = array() ) {
	$dir  = isset( $args['dir'] ) ? $args['dir'] : 'auto';
	$rows = isset( $args['rows'] ) ? (int) $args['rows'] : 3;
	printf(
		'<label class="kk-field"><span class="kk-label">%1$s</span><textarea name="%2$s" rows="%4$d" dir="%5$s" class="widefat">%3$s</textarea></label>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_textarea( $value ),
		absint( $rows ),
		esc_attr( $dir )
	);
}
function kk_field_color( $name, $label, $value, $default = '' ) {
	printf(
		'<label class="kk-field"><span class="kk-label">%1$s</span><input type="text" name="%2$s" value="%3$s" class="kk-color" data-default-color="%4$s"></label>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_attr( $value ),
		esc_attr( $default )
	);
}
function kk_field_select( $name, $label, $value, $options ) {
	echo '<label class="kk-field"><span class="kk-label">' . esc_html( $label ) . '</span><select name="' . esc_attr( $name ) . '" class="widefat">';
	foreach ( $options as $k => $v ) {
		echo '<option value="' . esc_attr( $k ) . '"' . selected( $value, $k, false ) . '>' . esc_html( $v ) . '</option>';
	}
	echo '</select></label>';
}
function kk_field_media( $name, $label, $value, $multiple = false, $help = '' ) {
	$ids  = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
	$prev = '';
	foreach ( $ids as $id ) {
		$prev .= wp_get_attachment_image( $id, 'thumbnail' );
	}
	printf(
		'<div class="kk-field kk-media" data-multiple="%5$s"><span class="kk-label">%1$s</span><input type="hidden" name="%2$s" value="%3$s"><div class="kk-media-prev">%4$s</div><p><button type="button" class="button kk-media-pick">%6$s</button> <button type="button" class="button-link-delete kk-media-clear">%7$s</button></p>%8$s</div>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_attr( implode( ',', $ids ) ),
		$prev, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$multiple ? '1' : '0',
		$multiple ? 'اختيار الصور' : 'اختيار صورة',
		'إزالة',
		$help ? '<span class="kk-help">' . esc_html( $help ) . '</span>' : ''
	);
}

function kk_save_text( $post_id, $keys, $allow_html = false ) {
	foreach ( $keys as $k ) {
		if ( ! isset( $_POST[ 'kk_' . $k ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			continue;
		}
		$raw = wp_unslash( $_POST[ 'kk_' . $k ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$val = $allow_html ? wp_kses_post( $raw ) : sanitize_textarea_field( $raw );
		if ( '' === $val ) {
			delete_post_meta( $post_id, '_kk_' . $k );
		} else {
			update_post_meta( $post_id, '_kk_' . $k, $val );
		}
	}
}

/* ------------------------------------------------------------------ *
 * Meta boxes
 * ------------------------------------------------------------------ */
add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'kk_product_box', 'تفاصيل المنتج — عربي / English', 'kk_product_box', 'kk_product', 'normal', 'high' );
		add_meta_box( 'kk_slide_box', 'إعدادات البانر', 'kk_slide_box', 'kk_slide', 'normal', 'high' );
		foreach ( array( 'page', 'post' ) as $pt ) {
			add_meta_box( 'kk_en_box', 'النسخة الإنجليزية — English version', 'kk_en_box', $pt, 'normal', 'default' );
		}
	}
);

function kk_product_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	$g = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_kk_' . $k, true );
	};
	echo '<p class="kk-intro">العنوان العربي فوق، والوصف المختصر العربي في خانة "مقتطف"، والوصف الطويل العربي في المحرر. الصورة الأساسية من "صورة المنتج" على الجنب.</p>';
	echo '<div class="kk-grid">';
	kk_field_text( 'kk_title_en', 'اسم المنتج بالإنجليزي', $g( 'title_en' ), array( 'dir' => 'ltr' ) );
	kk_field_text( 'kk_weight_ar', 'الوزن (عربي) — مثال: 65 جرام', $g( 'weight_ar' ) );
	kk_field_text( 'kk_weight_en', 'Weight (English) — e.g. 65 g', $g( 'weight_en' ), array( 'dir' => 'ltr' ) );
	kk_field_color( 'kk_tint', 'لون خلفية كارت المنتج', $g( 'tint' ), '#FDEBCB' );
	echo '</div>';
	kk_field_textarea( 'kk_excerpt_en', 'Short description (English)', $g( 'excerpt_en' ), array( 'dir' => 'ltr' ) );
	echo '<div class="kk-field"><span class="kk-label">Long description (English) — اختياري</span>';
	wp_editor( (string) $g( 'content_en' ), 'kk_content_en', array( 'textarea_name' => 'kk_content_en', 'textarea_rows' => 6, 'media_buttons' => false ) );
	echo '</div><div class="kk-grid">';
	kk_field_media( 'kk_gallery', 'صور إضافية للمنتج (اختياري)', $g( 'gallery' ), true, 'تظهر كصور مصغرة تحت الصورة الأساسية في صفحة المنتج.' );
	echo '<label class="kk-field kk-check"><input type="checkbox" name="kk_featured" value="1"' . checked( $g( 'featured' ), '1', false ) . '> اعرض المنتج في "منتجاتنا" بالصفحة الرئيسية</label>';
	echo '</div><p class="kk-help">الترتيب: من خانة "الترتيب" في "خصائص الصفحة" (الأقل يظهر الأول). التصنيف والعلامة التجارية من الصناديق على الجنب.</p>';
}

function kk_slide_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	$g = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_kk_' . $k, true );
	};
	$mode = $g( 'mode' ) ? $g( 'mode' ) : 'design';
	echo '<p class="kk-intro">العنوان العربي للبانر هو عنوان المنشور فوق. ترتيب البانرات من "الترتيب" في "خصائص الصفحة". لإخفاء بانر حوّله لمسودة.</p>';
	kk_field_select(
		'kk_mode',
		'نوع البانر',
		$mode,
		array(
			'design' => 'تصميم كيكرز المتحرك (نص + صور منتجات + زخارف)',
			'image'  => 'صورة بانر جاهزة (مصممة على فوتوشوب مثلًا)',
		)
	);
	echo '<div class="kk-grid">';
	kk_field_text( 'kk_label_ar', 'اسم قصير للبانر في شريط التنقل (عربي)', $g( 'label_ar' ) );
	kk_field_text( 'kk_label_en', 'Short tab label (English)', $g( 'label_en' ), array( 'dir' => 'ltr' ) );
	echo '</div>';
	echo '<div class="kk-panel" data-show-mode="design"><h4>تصميم كيكرز المتحرك</h4><div class="kk-grid">';
	kk_field_text( 'kk_title_en', 'Headline (English)', $g( 'title_en' ), array( 'dir' => 'ltr' ) );
	kk_field_text( 'kk_word', 'الكلمة الكبيرة في الخلفية (إنجليزي)', $g( 'word' ), array( 'dir' => 'ltr', 'placeholder' => 'Kickers' ) );
	kk_field_textarea( 'kk_sub_ar', 'الوصف (عربي)', $g( 'sub_ar' ) );
	kk_field_textarea( 'kk_sub_en', 'Description (English)', $g( 'sub_en' ), array( 'dir' => 'ltr' ) );
	kk_field_select(
		'kk_theme',
		'لون البانر',
		$g( 'theme' ) ? $g( 'theme' ) : 'red',
		array(
			'red'   => 'أحمر كيكرز',
			'cocoa' => 'بني شوكولاتة',
			'gold'  => 'أصفر ذهبي',
			'cream' => 'كريمي فاتح',
		)
	);
	kk_field_select(
		'kk_decor',
		'الزخارف الطايرة',
		$g( 'decor' ) ? $g( 'decor' ) : 'wheat',
		array(
			'wheat'     => 'سنابل قمح وبسكويت',
			'chocolate' => 'قطع شوكولاتة',
			'hazelnut'  => 'بندق وشوكولاتة',
			'biscuit'   => 'بسكويت وفتافيت',
			'none'      => 'بدون زخارف',
		)
	);
	echo '</div><div class="kk-grid kk-grid-3">';
	kk_field_media( 'kk_img1', 'صورة المنتج الرئيسية (في النص)', $g( 'img1' ), false, 'يفضل PNG أو WebP بخلفية شفافة.' );
	kk_field_media( 'kk_img2', 'صورة منتج 2 (فوق)', $g( 'img2' ) );
	kk_field_media( 'kk_img3', 'صورة منتج 3 (تحت)', $g( 'img3' ) );
	echo '</div><div class="kk-grid">';
	kk_field_text( 'kk_btn1_ar', 'نص الزرار الأساسي (عربي)', $g( 'btn1_ar' ), array( 'placeholder' => 'تصفح المنتجات' ) );
	kk_field_text( 'kk_btn1_en', 'Main button text (English)', $g( 'btn1_en' ), array( 'dir' => 'ltr', 'placeholder' => 'Browse products' ) );
	kk_field_text( 'kk_btn1_url', 'رابط الزرار الأساسي', $g( 'btn1_url' ), array( 'dir' => 'ltr', 'placeholder' => 'products', 'help' => 'اكتب: products أو whatsapp أو contact أو cat:slug (تصنيف) أو product:slug (منتج) أو أي رابط كامل.' ) );
	kk_field_text( 'kk_btn2_url', 'رابط الزرار الثاني', $g( 'btn2_url' ), array( 'dir' => 'ltr', 'placeholder' => 'whatsapp' ) );
	kk_field_text( 'kk_btn2_ar', 'نص الزرار الثاني (عربي) — فاضي = يختفي', $g( 'btn2_ar' ), array( 'placeholder' => 'كلّمنا على واتساب' ) );
	kk_field_text( 'kk_btn2_en', 'Second button text (English)', $g( 'btn2_en' ), array( 'dir' => 'ltr' ) );
	echo '</div><p class="kk-help">"صورة خلفية" على الجنب اختيارية: لو حطيت صورة هتظهر ورا البانر بلون البانر فوقها.</p></div>';

	echo '<div class="kk-panel" data-show-mode="image"><h4>صورة بانر جاهزة</h4><div class="kk-grid kk-grid-3">';
	kk_field_media( 'kk_banner', 'صورة الكمبيوتر (مقاس مقترح 1920×860)', $g( 'banner' ) );
	kk_field_media( 'kk_banner_m', 'صورة الموبايل (مقاس مقترح 900×1100) — اختياري', $g( 'banner_m' ) );
	kk_field_text( 'kk_banner_url', 'الرابط عند الضغط (اختياري)', $g( 'banner_url' ), array( 'dir' => 'ltr', 'placeholder' => 'products' ) );
	echo '</div></div>';
}

function kk_en_box( $post ) {
	wp_nonce_field( 'kk_save_meta', 'kk_meta_nonce' );
	$g = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_kk_' . $k, true );
	};
	echo '<p class="kk-intro">النص اللي يظهر على النسخة الإنجليزية من الصفحة (/en/). لو سبته فاضي هيظهر النص العربي.</p>';
	kk_field_text( 'kk_title_en', 'Title (English)', $g( 'title_en' ), array( 'dir' => 'ltr' ) );
	kk_field_textarea( 'kk_excerpt_en', 'Short description / SEO description (English)', $g( 'excerpt_en' ), array( 'dir' => 'ltr' ) );
	echo '<div class="kk-field"><span class="kk-label">Content (English)</span>';
	wp_editor( (string) $g( 'content_en' ), 'kk_content_en', array( 'textarea_name' => 'kk_content_en', 'textarea_rows' => 8 ) );
	echo '</div>';
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
		if ( 'kk_product' === $post->post_type ) {
			kk_save_text( $post_id, array( 'title_en', 'weight_ar', 'weight_en', 'excerpt_en', 'gallery' ) );
			kk_save_text( $post_id, array( 'content_en' ), true );
			$tint = isset( $_POST['kk_tint'] ) ? sanitize_hex_color( wp_unslash( $_POST['kk_tint'] ) ) : '';
			$tint ? update_post_meta( $post_id, '_kk_tint', $tint ) : delete_post_meta( $post_id, '_kk_tint' );
			isset( $_POST['kk_featured'] ) ? update_post_meta( $post_id, '_kk_featured', '1' ) : delete_post_meta( $post_id, '_kk_featured' );
		} elseif ( 'kk_slide' === $post->post_type ) {
			kk_save_text( $post_id, array( 'mode', 'title_en', 'word', 'sub_ar', 'sub_en', 'label_ar', 'label_en', 'theme', 'decor', 'img1', 'img2', 'img3', 'btn1_ar', 'btn1_en', 'btn1_url', 'btn2_ar', 'btn2_en', 'btn2_url', 'banner', 'banner_m', 'banner_url' ) );
		} else {
			kk_save_text( $post_id, array( 'title_en', 'excerpt_en' ) );
			kk_save_text( $post_id, array( 'content_en' ), true );
		}
	},
	10,
	2
);

/* ------------------------------------------------------------------ *
 * Term fields (categories & brands)
 * ------------------------------------------------------------------ */
function kk_term_fields_def( $tax ) {
	$f = array(
		'name_en' => array( 'Name (English)', 'text' ),
		'desc_en' => array( 'Description (English)', 'textarea' ),
		'color'   => array( 'cat' === $tax ? 'لون كارت التصنيف' : 'لون اسم العلامة', 'color' ),
	);
	if ( 'cat' === $tax ) {
		$f['ink'] = array( 'لون النص على الكارت', 'color' );
	}
	$f['image'] = array( 'cat' === $tax ? 'صورة الكارت (اختياري — الافتراضي أول منتجين)' : 'صورة العلامة (اختياري)', 'media' );
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
				kk_term_input( $k, $d, '' );
				echo '</div>';
			}
		}
	);
	add_action(
		$kk_tax . '_edit_form_fields',
		function ( $term ) use ( $kk_kind ) {
			wp_nonce_field( 'kk_term', 'kk_term_nonce' );
			foreach ( kk_term_fields_def( $kk_kind ) as $k => $d ) {
				echo '<tr class="form-field"><th scope="row">' . esc_html( $d[0] ) . '</th><td>';
				kk_term_input( $k, $d, get_term_meta( $term->term_id, 'kk_' . $k, true ), true );
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
	switch ( $d[1] ) {
		case 'textarea':
			kk_field_textarea( $name, $label, $val, array( 'dir' => 'ltr' ) );
			break;
		case 'color':
			kk_field_color( $name, $label, $val );
			break;
		case 'media':
			kk_field_media( $name, $label, $val );
			break;
		case 'number':
			printf( '<label class="kk-field">%s<input type="number" name="%s" value="%s" class="small-text"></label>', $label ? '<span class="kk-label">' . esc_html( $label ) . '</span>' : '', esc_attr( $name ), esc_attr( $val ) );
			break;
		default:
			kk_field_text( $name, $label, $val, array( 'dir' => 'ltr' ) );
	}
}
function kk_save_term( $term_id ) {
	if ( ! isset( $_POST['kk_term_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kk_term_nonce'] ) ), 'kk_term' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	foreach ( array( 'name_en', 'desc_en', 'color', 'ink', 'image', 'order' ) as $k ) {
		if ( ! isset( $_POST[ 'kk_term_' . $k ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ 'kk_term_' . $k ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( 'color' === $k || 'ink' === $k ) {
			$v = sanitize_hex_color( $raw );
		} elseif ( 'order' === $k || 'image' === $k ) {
			$v = '' === $raw ? '' : (string) absint( $raw );
		} else {
			$v = sanitize_textarea_field( $raw );
		}
		'' === (string) $v ? delete_term_meta( $term_id, 'kk_' . $k ) : update_term_meta( $term_id, 'kk_' . $k, $v );
	}
}

/* ------------------------------------------------------------------ *
 * Admin list columns
 * ------------------------------------------------------------------ */
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
				$new['kk_weight']   = 'الوزن';
				$new['kk_featured'] = 'في الرئيسية';
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
		} elseif ( 'kk_weight' === $col ) {
			echo esc_html( get_post_meta( $id, '_kk_weight_ar', true ) );
		} elseif ( 'kk_featured' === $col ) {
			echo get_post_meta( $id, '_kk_featured', true ) ? '★' : '—';
		}
	},
	10,
	2
);
add_filter(
	'manage_kk_slide_posts_columns',
	function ( $cols ) {
		return array(
			'cb'       => $cols['cb'],
			'kk_thumb' => 'معاينة',
			'title'    => 'العنوان',
			'kk_theme' => 'اللون / النوع',
			'kk_order' => 'الترتيب',
			'date'     => $cols['date'],
		);
	}
);
add_action(
	'manage_kk_slide_posts_custom_column',
	function ( $col, $id ) {
		if ( 'kk_thumb' === $col ) {
			$img = (int) get_post_meta( $id, 'image' === get_post_meta( $id, '_kk_mode', true ) ? '_kk_banner' : '_kk_img1', true );
			echo $img ? wp_get_attachment_image( $img, array( 80, 60 ) ) : '—';
		} elseif ( 'kk_theme' === $col ) {
			$mode = get_post_meta( $id, '_kk_mode', true );
			echo 'image' === $mode ? 'صورة جاهزة' : esc_html( get_post_meta( $id, '_kk_theme', true ) );
		} elseif ( 'kk_order' === $col ) {
			echo (int) get_post_field( 'menu_order', $id );
		}
	},
	10,
	2
);
