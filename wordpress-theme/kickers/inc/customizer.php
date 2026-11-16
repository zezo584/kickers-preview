<?php
/**
 * Customizer: every text on the site in Arabic + English, contact details, sections on/off, SEO.
 * Appearance → Customize → "إعدادات كيكرز".
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field map: section => [ key => [label, type] ].
 * Types: text, textarea, url, email, number, checkbox, image, ar_en (adds _ar and _en), ar_en_area.
 */
function kk_customizer_map() {
	$map = array(
		'kk_identity' => array(
			'title'  => 'الاسم والشعار والنصوص العامة',
			'fields' => array(
				'site_name' => array( 'اسم الموقع', 'ar_en' ),
				'tagline'   => array( 'الوصف المختصر (يظهر في عنوان التبويب والفوتر)', 'ar_en' ),
				'footer'    => array( 'نبذة الفوتر', 'ar_en_area' ),
			),
		),
		'kk_contact'  => array(
			'title'  => 'بيانات التواصل والمصانع',
			'fields' => array(
				'phone1'          => array( 'رقم التليفون 1', 'text' ),
				'phone2'          => array( 'رقم التليفون 2', 'text' ),
				'email'           => array( 'الإيميل', 'email' ),
				'whatsapp_url'    => array( 'رابط واتساب (زرار "كلّمنا على واتساب")', 'url' ),
				'whatsapp_number' => array( 'رقم واتساب بالصيغة الدولية (اختياري، مثال 201020215649) — لو اتكتب، زرار "اطلب عن طريق واتساب" يفتح برسالة فيها اسم المنتج', 'text' ),
				'map_url'         => array( 'رابط خريطة جوجل', 'url' ),
				'map_embed'       => array( 'رابط تضمين الخريطة (Embed src) لصفحة التواصل — اختياري', 'url' ),
				'fac1_country'    => array( 'المصنع 1 — الدولة', 'ar_en' ),
				'fac1_addr'       => array( 'المصنع 1 — العنوان', 'ar_en' ),
				'fac1_map'        => array( 'المصنع 1 — رابط الخريطة', 'url' ),
				'fac2_country'    => array( 'المصنع 2 — الدولة', 'ar_en' ),
				'fac2_addr'       => array( 'المصنع 2 — العنوان', 'ar_en' ),
				'fac2_map'        => array( 'المصنع 2 — رابط الخريطة', 'url' ),
			),
		),
		'kk_social'   => array(
			'title'  => 'السوشيال ميديا',
			'fields' => array(
				'facebook'  => array( 'Facebook', 'url' ),
				'instagram' => array( 'Instagram', 'url' ),
				'tiktok'    => array( 'TikTok', 'url' ),
				'youtube'   => array( 'YouTube', 'url' ),
				'linkedin'  => array( 'LinkedIn', 'url' ),
				'x'         => array( 'X (Twitter)', 'url' ),
			),
		),
		'kk_slider'   => array(
			'title'       => 'السلايدر الرئيسي',
			'description' => 'البانرات نفسها من القائمة الجانبية "بانرات السلايدر".',
			'fields'      => array(
				'hero_autoplay' => array( 'تشغيل تلقائي', 'checkbox' ),
				'hero_duration' => array( 'مدة كل بانر بالثواني', 'number' ),
			),
		),
	);

	$home = array(
		'show_promises'  => array( 'إظهار قسم: وعد كيكرز (4 مميزات)', 'checkbox' ),
		'show_cats'      => array( 'إظهار قسم: التصنيفات', 'checkbox' ),
		'show_showcase'  => array( 'إظهار قسم: تشكيلة كيكرز (العرض الدوّار للمنتجات)', 'checkbox' ),
		'show_export'    => array( 'إظهار قسم: التصدير', 'checkbox' ),
		'show_products'  => array( 'إظهار قسم: منتجاتنا', 'checkbox' ),
		'show_roll'      => array( 'إظهار قسم: من حبة القمح لحد إيدك', 'checkbox' ),
		'show_about'     => array( 'إظهار قسم: من نحن + الأرقام', 'checkbox' ),
		'show_brands'    => array( 'إظهار قسم: العلامات التجارية', 'checkbox' ),
		'show_reviews'   => array( 'إظهار قسم: آراء العملاء', 'checkbox' ),
		'show_factories' => array( 'إظهار قسم: مصانعنا', 'checkbox' ),
		'show_cta'       => array( 'إظهار قسم: جاهز تكتشف طعم يفرق؟', 'checkbox' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$home[ 'promise' . $i . '_t' ] = array( 'ميزة ' . $i . ' — العنوان', 'ar_en' );
		$home[ 'promise' . $i . '_d' ] = array( 'ميزة ' . $i . ' — الوصف', 'ar_en_area' );
	}
	$home += array(
		'cats_title'     => array( 'التصنيفات — العنوان', 'ar_en' ),
		'cats_text'      => array( 'التصنيفات — الوصف', 'ar_en_area' ),
		'products_title' => array( 'منتجاتنا — العنوان', 'ar_en' ),
		'products_text'  => array( 'منتجاتنا — الوصف', 'ar_en_area' ),
		'products_count' => array( 'عدد المنتجات في الرئيسية (المنتجات المعلّم عليها "اعرض في الرئيسية")', 'number' ),
		'roll_title'     => array( 'المراحل — العنوان', 'ar_en' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$home[ 'step' . $i . '_t' ] = array( 'مرحلة ' . $i . ' — العنوان', 'ar_en' );
		$home[ 'step' . $i . '_d' ] = array( 'مرحلة ' . $i . ' — الوصف', 'ar_en_area' );
	}
	$home += array(
		'about_title'   => array( 'من نحن (الرئيسية) — العنوان', 'ar_en' ),
		'about_text'    => array( 'من نحن (الرئيسية) — النص', 'ar_en_area' ),
		'years'         => array( 'سنين الخبرة (رقم)', 'number' ),
		'factories'     => array( 'عدد المصانع (رقم)', 'number' ),
		'brands_title'  => array( 'العلامات — العنوان', 'ar_en' ),
		'brands_text'   => array( 'العلامات — الوصف', 'ar_en_area' ),
		'reviews_title' => array( 'آراء العملاء — العنوان', 'ar_en' ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		$home[ 'review' . $i . '_name' ] = array( 'رأي ' . $i . ' — الاسم (فاضي = يختفي)', 'ar_en' );
		$home[ 'review' . $i . '_t' ]    = array( 'رأي ' . $i . ' — النص', 'ar_en_area' );
	}
	$home += array(
		'cta_title' => array( 'البانر الأخير — العنوان', 'ar_en' ),
		'cta_text'  => array( 'البانر الأخير — النص', 'ar_en_area' ),
	);
	$map['kk_home'] = array( 'title' => 'الصفحة الرئيسية', 'fields' => $home );

	$exp = array(
		'export_nav'         => array( 'اسم القسم في القائمة', 'ar_en' ),
		'export_title'       => array( 'العنوان', 'ar_en' ),
		'export_text'        => array( 'النص', 'ar_en_area' ),
		'export_btn'         => array( 'نص الزرار', 'ar_en' ),
		'export_steps_title' => array( 'عنوان خطوات الاستيراد (صفحة التصدير)', 'ar_en' ),
		'showcase_title'     => array( 'تشكيلة كيكرز — العنوان', 'ar_en' ),
		'showcase_text'      => array( 'تشكيلة كيكرز — الوصف', 'ar_en' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$exp[ 'export_point' . $i . '_t' ] = array( 'ميزة تصدير ' . $i . ' — العنوان', 'ar_en' );
		$exp[ 'export_point' . $i . '_d' ] = array( 'ميزة تصدير ' . $i . ' — الوصف', 'ar_en_area' );
	}
	for ( $i = 1; $i <= 4; $i++ ) {
		$exp[ 'export_step' . $i . '_t' ] = array( 'خطوة ' . $i . ' — العنوان', 'ar_en' );
		$exp[ 'export_step' . $i . '_d' ] = array( 'خطوة ' . $i . ' — الوصف', 'ar_en_area' );
	}
	$map['kk_export'] = array( 'title' => 'التصدير وتشكيلة المنتجات', 'fields' => $exp );

	$map['kk_about'] = array(
		'title'       => 'صفحة من نحن',
		'description' => 'افصل بين الفقرات بسطر فاضي.',
		'fields'      => array(
			'about_hero' => array( 'الجملة تحت العنوان', 'ar_en_area' ),
			'story'      => array( 'حكاية الشركة', 'ar_en_area' ),
			'vision'     => array( 'رؤيتنا', 'ar_en_area' ),
			'goal'       => array( 'هدفنا', 'ar_en_area' ),
			'reach'      => array( 'انتشارنا', 'ar_en_area' ),
		),
	);
	$map['kk_contact_page'] = array(
		'title'  => 'صفحة تواصل معنا والنموذج',
		'fields' => array(
			'contact_lead' => array( 'الجملة تحت العنوان', 'ar_en' ),
			'form_email'   => array( 'الإيميل اللي بتوصله رسائل النموذج', 'email' ),
			'subjects'     => array( 'اختيارات "نوع الطلب" (افصل بفاصلة)', 'ar_en' ),
		),
	);
	$map['kk_seo'] = array(
		'title'       => 'محركات البحث (SEO)',
		'description' => 'لو مركّب إضافة SEO زي Yoast أو Rank Math، القالب بيوقف وسوم الوصف والمشاركة بتاعته تلقائيًا ويسيبها للإضافة.',
		'fields'      => array(
			'meta_desc'           => array( 'وصف الموقع لجوجل (الصفحة الرئيسية)', 'ar_en_area' ),
			'og_image'            => array( 'صورة المشاركة على السوشيال (1200×630)', 'image' ),
			'google_verification' => array( 'كود التحقق من Google Search Console (content فقط)', 'text' ),
		),
	);
	return $map;
}

add_action(
	'customize_register',
	function ( $wpc ) {
		$d = kk_defaults();
		$wpc->add_panel(
			'kk_panel',
			array(
				'title'    => 'إعدادات كيكرز',
				'priority' => 25,
			)
		);
		foreach ( kk_customizer_map() as $sid => $sec ) {
			$wpc->add_section(
				$sid,
				array(
					'title'       => $sec['title'],
					'panel'       => 'kk_panel',
					'description' => isset( $sec['description'] ) ? $sec['description'] : '',
				)
			);
			foreach ( $sec['fields'] as $key => $f ) {
				list( $label, $type ) = $f;
				if ( 'ar_en' === $type || 'ar_en_area' === $type ) {
					foreach ( array( 'ar' => 'عربي', 'en' => 'English' ) as $l => $ln ) {
						kk_add_control( $wpc, $sid, $key . '_' . $l, $label . ' (' . $ln . ')', 'ar_en_area' === $type ? 'textarea' : 'text', isset( $d[ $key . '_' . $l ] ) ? $d[ $key . '_' . $l ] : '' );
					}
				} else {
					kk_add_control( $wpc, $sid, $key, $label, $type, isset( $d[ $key ] ) ? $d[ $key ] : '' );
				}
			}
		}
	}
);

function kk_add_control( $wpc, $section, $key, $label, $type, $default ) {
	$san = 'sanitize_text_field';
	if ( 'textarea' === $type ) {
		$san = 'sanitize_textarea_field';
	} elseif ( 'url' === $type ) {
		$san = 'esc_url_raw';
	} elseif ( 'email' === $type ) {
		$san = 'sanitize_email';
	} elseif ( 'number' === $type ) {
		$san = 'absint';
	} elseif ( 'checkbox' === $type ) {
		$san = function ( $v ) {
			return $v ? 1 : 0;
		};
	} elseif ( 'image' === $type ) {
		$san = 'absint';
	}
	$wpc->add_setting(
		'kk_' . $key,
		array(
			'default'           => $default,
			'sanitize_callback' => $san,
			'transport'         => 'refresh',
		)
	);
	if ( 'image' === $type ) {
		$wpc->add_control(
			new WP_Customize_Media_Control(
				$wpc,
				'kk_' . $key,
				array(
					'label'     => $label,
					'section'   => $section,
					'mime_type' => 'image',
				)
			)
		);
		return;
	}
	$wpc->add_control(
		'kk_' . $key,
		array(
			'label'       => $label,
			'section'     => $section,
			'type'        => $type,
			'input_attrs' => ( false !== strpos( $key, '_en' ) && 'en' === substr( $key, -2 ) ) ? array( 'dir' => 'ltr' ) : array(),
		)
	);
}
