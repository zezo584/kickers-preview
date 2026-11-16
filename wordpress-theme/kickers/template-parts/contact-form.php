<?php
/**
 * Contact form card.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_subjects = array_filter( array_map( 'trim', explode( ',', (string) kk_optl( 'subjects' ) ) ) );
if ( ! empty( $args['first_subject'] ) ) {
	array_unshift( $kk_subjects, $args['first_subject'] );
}
?>
<div class="form-card" data-reveal>
	<h2><?php echo esc_html( kk_t( 'form_title' ) ); ?></h2>
	<p><?php echo esc_html( kk_t( 'form_text' ) ); ?></p>
	<form data-kk-contact novalidate data-msg-required="<?php echo esc_attr( kk_t( 'required' ) ); ?>">
		<div class="f-row">
			<div class="field"><label for="f-name"><?php echo esc_html( kk_t( 'f_name' ) ); ?> <span class="req">*</span></label><input id="f-name" name="name" required autocomplete="name"></div>
			<div class="field"><label for="f-phone"><?php echo esc_html( kk_t( 'f_phone' ) ); ?> <span class="req">*</span></label><input id="f-phone" name="phone" type="tel" required autocomplete="tel" dir="ltr"></div>
		</div>
		<div class="f-row">
			<div class="field"><label for="f-email"><?php echo esc_html( kk_t( 'f_email' ) . ' ' . kk_t( 'f_opt' ) ); ?></label><input id="f-email" name="email" type="email" autocomplete="email" dir="ltr"></div>
			<div class="field"><label for="f-subject"><?php echo esc_html( kk_t( 'f_subject' ) ); ?></label><select id="f-subject" name="subject"><?php foreach ( $kk_subjects as $kk_s ) { echo '<option>' . esc_html( $kk_s ) . '</option>'; } ?></select></div>
		</div>
		<div class="field"><label for="f-msg"><?php echo esc_html( kk_t( 'f_msg' ) ); ?> <span class="req">*</span></label><textarea id="f-msg" name="message" required></textarea></div>
		<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
		<button class="btn" type="submit"><?php echo esc_html( kk_t( 'send' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></button>
		<p class="form-status" role="status" aria-live="polite"></p>
	</form>
</div>
