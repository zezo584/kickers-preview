<?php
/**
 * Customizer: every text in Arabic / English / Français / Deutsch, contact details, sections on/off, SEO.
 * Appearance → Customize → "إعدادات كيكرز".
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

function kk_customizer_map() {
	$rep = function ( $prefix, $label, $n, $area = true ) {
		$o = array();
		for ( $i = 1; $i <= $n; $i++ ) {
			$o[ $prefix . $i . '_t' ] = array( $label . ' ' . $i . ' — العنوان', 'ml' );
			$o[ $prefix . $i . '_d' ] = array( $label . ' ' . $i . ' — الوصف', $area ? 'ml_area' : 'ml' );
		}
		return $o;
	};
	return array(
		'kk_identity' => array( 'الاسم والنصوص العامة', array(
			'site_name' => array( 'اسم الموقع', 'ml' ),
			'tagline'   => array( 'الاسم الكامل للشركة', 'ml' ),
			'footer'    => array( 'نبذة الفوتر', 'ml_area' ),
		) ),
		'kk_contact' => array( 'التواصل والمصانع', array(
			'phone1'          => array( 'تليفون 1', 'text' ),
			'phone2'          => array( 'تليفون 2', 'text' ),
			'email'           => array( 'الإيميل', 'email' ),
			'whatsapp_url'    => array( 'رابط واتساب', 'url' ),
			'whatsapp_number' => array( 'رقم واتساب دولي (اختياري، مثال 201020215649) لرسائل جاهزة باسم المنتج', 'text' ),
			'map_url'         => array( 'رابط خريطة جوجل', 'url' ),
			'map_embed'       => array( 'رابط تضمين الخريطة لصفحة التواصل (اختياري)', 'url' ),
			'fac1_country'    => array( 'المصنع 1 — الدولة', 'ml' ),
			'fac1_addr'       => array( 'المصنع 1 — العنوان', 'ml' ),
			'fac1_since'      => array( 'المصنع 1 — سنة التأسيس', 'text' ),
			'fac1_map'        => array( 'المصنع 1 — رابط الخريطة', 'url' ),
			'fac2_country'    => array( 'المصنع 2 — الدولة', 'ml' ),
			'fac2_addr'       => array( 'المصنع 2 — العنوان', 'ml' ),
			'fac2_since'      => array( 'المصنع 2 — سنة التأسيس', 'text' ),
			'fac2_map'        => array( 'المصنع 2 — رابط الخريطة', 'url' ),
		) ),
		'kk_social' => array( 'السوشيال ميديا', array(
			'facebook' => array( 'Facebook', 'url' ), 'instagram' => array( 'Instagram', 'url' ), 'tiktok' => array( 'TikTok', 'url' ),
			'youtube' => array( 'YouTube', 'url' ), 'linkedin' => array( 'LinkedIn', 'url' ), 'x' => array( 'X', 'url' ),
		) ),
		'kk_slider' => array( 'البانرات الرئيسية', array(
			'hero_auto'     => array( 'بانر تلقائي لكل منتج "جديد" ولكل نوع منتجات', 'checkbox' ),
			'hero_autoplay' => array( 'تقليب تلقائي', 'checkbox' ),
			'hero_duration' => array( 'مدة كل بانر بالثواني', 'number' ),
		) ),
		'kk_home' => array( 'أقسام الصفحة الرئيسية', array_merge(
			array(
				'show_welcome' => array( 'إظهار: قسم الترحيب (صورة البنت)', 'checkbox' ),
				'show_cats' => array( 'إظهار: تشكيلة لكل مزاج', 'checkbox' ),
				'show_showcase' => array( 'إظهار: تشكيلة كيكرز الدوّارة', 'checkbox' ),
				'show_products' => array( 'إظهار: منتجاتنا', 'checkbox' ),
				'show_roll' => array( 'إظهار: من حبة القمح لحد إيدك', 'checkbox' ),
				'show_export' => array( 'إظهار: التصدير والخريطة', 'checkbox' ),
				'show_brands' => array( 'إظهار: العلامات التجارية', 'checkbox' ),
				'show_reviews' => array( 'إظهار: آراء العملاء', 'checkbox' ),
				'show_factories' => array( 'إظهار: مصانعنا', 'checkbox' ),
				'show_cta' => array( 'إظهار: البانر الأخير', 'checkbox' ),
				'welcome_image' => array( 'صورة قسم الترحيب (PNG شفاف)', 'image' ),
				'welcome_title' => array( 'الترحيب — العنوان', 'ml' ),
				'welcome_text' => array( 'الترحيب — النص', 'ml_area' ),
			),
			$rep( 'promise', 'ميزة', 4 ),
			array(
				'cats_title' => array( 'الأنواع — العنوان', 'ml' ), 'cats_text' => array( 'الأنواع — الوصف', 'ml' ),
				'showcase_title' => array( 'الدوّارة — العنوان', 'ml' ), 'showcase_text' => array( 'الدوّارة — الوصف', 'ml' ),
				'products_title' => array( 'منتجاتنا — العنوان', 'ml' ), 'products_text' => array( 'منتجاتنا — الوصف', 'ml' ),
				'roll_title' => array( 'المراحل — العنوان', 'ml' ),
			),
			$rep( 'step', 'مرحلة', 4 ),
			array(
				'brands_title' => array( 'العلامات — العنوان', 'ml' ), 'brands_text' => array( 'العلامات — الوصف', 'ml' ),
				'reviews_title' => array( 'الآراء — العنوان', 'ml' ),
				'cta_title' => array( 'البانر الأخير — العنوان', 'ml' ), 'cta_text' => array( 'البانر الأخير — النص', 'ml_area' ),
			)
		) ),
		'kk_stats' => array( 'الأرقام', array(
			'years_since'    => array( 'سنة بداية الشركة (الخبرة بتتحسب تلقائي)', 'number' ),
			'factories'      => array( 'عدد المصانع', 'number' ),
			'products_count' => array( 'عدد المنتجات', 'number' ),
			'brands_count'   => array( 'عدد العلامات التجارية', 'number' ),
		) ),
		'kk_about' => array( 'صفحة من نحن', array(
			'about_intro' => array( 'المقدمة', 'ml_area' ), 'about_history' => array( 'تاريخ الشركة', 'ml_area' ),
			'about_vision' => array( 'رؤيتنا', 'ml_area' ), 'about_mission' => array( 'رسالتنا', 'ml_area' ),
			'about_values' => array( 'قيمنا (سطر لكل قيمة)', 'ml_area' ), 'about_why' => array( 'ليه كيكرز؟', 'ml_area' ),
		) ),
		'kk_export' => array( 'التصدير', array_merge(
			array(
				'export_title' => array( 'العنوان', 'ml' ), 'export_text' => array( 'النص', 'ml_area' ), 'export_btn' => array( 'نص الزرار', 'ml' ),
				'export_map_title' => array( 'الخريطة — العنوان', 'ml' ), 'export_map_hint' => array( 'الخريطة — تعليمات', 'ml' ),
				'export_factories' => array( 'دول المصانع على الخريطة (أكواد مفصولة بفاصلة)', 'text' ),
				'export_regions' => array( 'أسواق العالم (مفصولة بفاصلة)', 'ml' ),
				'export_packing_title' => array( 'جدول التعبئة — العنوان', 'ml' ), 'export_packing_text' => array( 'جدول التعبئة — الوصف', 'ml' ),
				'export_steps_title' => array( 'خطوات الاستيراد — العنوان', 'ml' ),
			),
			$rep( 'export_point', 'ميزة', 4 ),
			$rep( 'export_step', 'خطوة', 4 )
		) ),
		'kk_agent' => array( 'صفحة كن وكيلاً', array_merge(
			array( 'agent_title' => array( 'العنوان', 'ml' ), 'agent_text' => array( 'النص', 'ml_area' ), 'agent_benefits_title' => array( 'عنوان المميزات', 'ml' ), 'agent_form_title' => array( 'عنوان النموذج', 'ml' ), 'activities' => array( 'أنواع النشاط (مفصولة بفاصلة)', 'ml' ) ),
			$rep( 'agent_benefit', 'ميزة', 4 )
		) ),
		'kk_contact_page' => array( 'صفحة التواصل والنماذج', array(
			'contact_lead' => array( 'الجملة تحت العنوان', 'ml' ),
			'form_email'   => array( 'الإيميل اللي بتوصله الرسائل', 'email' ),
			'subjects'     => array( 'اختيارات نوع الطلب (مفصولة بفاصلة)', 'ml' ),
		) ),
		'kk_seo' => array( 'محركات البحث (SEO)', array(
			'home_title'          => array( 'عنوان الصفحة الرئيسية في جوجل', 'ml' ),
			'meta_desc'           => array( 'وصف الموقع لجوجل', 'ml_area' ),
			'og_image'            => array( 'صورة المشاركة (1200×630)', 'image' ),
			'google_verification' => array( 'كود Google Search Console', 'text' ),
		) ),
	);
}

add_action(
	'customize_register',
	function ( $wpc ) {
		$d = kk_defaults();
		$wpc->add_panel( 'kk_panel', array( 'title' => 'إعدادات كيكرز', 'priority' => 25 ) );
		$ln = array( 'ar' => 'عربي', 'en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch' );
		foreach ( kk_customizer_map() as $sid => $sec ) {
			$wpc->add_section( $sid, array( 'title' => $sec[0], 'panel' => 'kk_panel' ) );
			foreach ( $sec[1] as $key => $f ) {
				if ( 'ml' === $f[1] || 'ml_area' === $f[1] ) {
					foreach ( $ln as $l => $n ) {
						kk_add_control( $wpc, $sid, $key . '_' . $l, $f[0] . ' (' . $n . ')', 'ml_area' === $f[1] ? 'textarea' : 'text', isset( $d[ $key . '_' . $l ] ) ? $d[ $key . '_' . $l ] : '', 'ar' !== $l );
					}
				} else {
					kk_add_control( $wpc, $sid, $key, $f[0], $f[1], isset( $d[ $key ] ) ? $d[ $key ] : '' );
				}
			}
		}
	}
);

function kk_add_control( $wpc, $section, $key, $label, $type, $default, $ltr = false ) {
	$san = array(
		'textarea' => 'sanitize_textarea_field',
		'url'      => 'esc_url_raw',
		'email'    => 'sanitize_email',
		'number'   => 'absint',
		'image'    => 'absint',
	);
	$cb = isset( $san[ $type ] ) ? $san[ $type ] : 'sanitize_text_field';
	if ( 'checkbox' === $type ) {
		$cb = function ( $v ) {
			return $v ? 1 : 0;
		};
	}
	$wpc->add_setting( 'kk_' . $key, array( 'default' => $default, 'sanitize_callback' => $cb, 'transport' => 'refresh' ) );
	if ( 'image' === $type ) {
		$wpc->add_control( new WP_Customize_Media_Control( $wpc, 'kk_' . $key, array( 'label' => $label, 'section' => $section, 'mime_type' => 'image' ) ) );
		return;
	}
	$wpc->add_control( 'kk_' . $key, array( 'label' => $label, 'section' => $section, 'type' => $type, 'input_attrs' => $ltr ? array( 'dir' => 'ltr' ) : array() ) );
}
