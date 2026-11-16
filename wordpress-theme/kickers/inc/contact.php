<?php
/**
 * Contact form: AJAX handler, email notification and a "رسائل التواصل" inbox in the dashboard.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'kk_message',
			array(
				'labels'          => array(
					'name'          => 'رسائل التواصل',
					'singular_name' => 'رسالة',
					'menu_name'     => 'رسائل التواصل',
					'all_items'     => 'كل الرسائل',
					'edit_item'     => 'الرسالة',
					'not_found'     => 'لا توجد رسائل بعد',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'menu_icon'       => 'dashicons-email-alt',
				'menu_position'   => 26,
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
			)
		);
	}
);

function kk_contact_handler() {
	if ( ! check_ajax_referer( 'kk_contact', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => kk_t( 'error' ) ), 403 );
	}
	// Honeypot.
	if ( ! empty( $_POST['website'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		wp_send_json_success( array( 'message' => kk_t( 'sent' ) ) );
	}
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
	$key = 'kk_rl_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 5 ) {
		wp_send_json_error( array( 'message' => kk_t( 'too_many' ) ), 429 );
	}
	set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $name || '' === $phone || '' === $message ) {
		wp_send_json_error( array( 'message' => kk_t( 'required' ) ), 400 );
	}

	$body  = "الاسم: {$name}\nالموبايل: {$phone}\n";
	$body .= $email ? "الإيميل: {$email}\n" : '';
	$body .= $subject ? "نوع الطلب: {$subject}\n" : '';
	$body .= 'اللغة: ' . ( kk_is_en() ? 'English' : 'عربي' ) . "\n\n{$message}\n";

	wp_insert_post(
		array(
			'post_type'    => 'kk_message',
			'post_status'  => 'private',
			'post_title'   => $name . ' — ' . $phone . ( $subject ? ' — ' . $subject : '' ),
			'post_content' => $body,
		)
	);

	$to      = kk_opt( 'form_email' ) ? kk_opt( 'form_email' ) : get_option( 'admin_email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}
	wp_mail( $to, '[' . kk_opt( 'site_name_ar' ) . '] رسالة جديدة من ' . $name, $body, $headers );

	wp_send_json_success( array( 'message' => kk_t( 'sent' ) ) );
}
add_action( 'wp_ajax_kk_contact', 'kk_contact_handler' );
add_action( 'wp_ajax_nopriv_kk_contact', 'kk_contact_handler' );

// Bubble with the number of messages received in the last 7 days.
add_action(
	'admin_menu',
	function () {
		global $menu;
		$q     = new WP_Query(
			array(
				'post_type'      => 'kk_message',
				'post_status'    => 'private',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'date_query'     => array( array( 'after' => '7 days ago' ) ),
			)
		);
		$count = (int) $q->found_posts;
		if ( ! $count ) {
			return;
		}
		foreach ( $menu as $i => $item ) {
			if ( isset( $item[2] ) && 'edit.php?post_type=kk_message' === $item[2] ) {
				$menu[ $i ][0] .= ' <span class="awaiting-mod">' . $count . '</span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}
	},
	99
);
