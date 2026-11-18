<?php
/**
 * Kickers Foods theme bootstrap.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;

define( 'KK_VERSION', '2.2.0' );
define( 'KK_DIR', get_template_directory() );
define( 'KK_URI', get_template_directory_uri() );

// Language must be detected before WordPress parses the request.
require KK_DIR . '/inc/lang.php';

require KK_DIR . '/inc/defaults.php';
require KK_DIR . '/inc/helpers.php';
require KK_DIR . '/inc/setup.php';
require KK_DIR . '/inc/post-types.php';
require KK_DIR . '/inc/fields.php';
require KK_DIR . '/inc/customizer.php';
require KK_DIR . '/inc/seo.php';
require KK_DIR . '/inc/contact.php';

if ( is_admin() ) {
	require KK_DIR . '/inc/demo.php';
}
