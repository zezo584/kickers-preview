<?php
/**
 * Contact / export / agent form card.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_a     = wp_parse_args( $args, array( 'first_subject' => '', 'agent' => false, 'title' => '', 'id' => 'form' ) );
$kk_subj  = kk_lines( kk_optl( 'subjects' ) );
if ( $kk_a['first_subject'] ) {
	$kk_subj = array_values( array_diff( $kk_subj, array( $kk_a['first_subject'] ) ) );
	array_unshift( $kk_subj, $kk_a['first_subject'] );
}
$kk_field = function ( $id, $label, $req, $type = 'text', $extra = '' ) {
	return '<div class="field"><label for="f-' . esc_attr( $id ) . '">' . esc_html( $label ) . ( $req ? ' <span class="req">*</span>' : ' ' . esc_html( kk_t( 'fOpt' ) ) ) . '</label><input id="f-' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" type="' . esc_attr( $type ) . '"' . ( $req ? ' required' : '' ) . $extra . '></div>';
};
?>
<div class="form-card" data-reveal id="<?php echo esc_attr( $kk_a['id'] ); ?>">
	<h2><?php echo esc_html( $kk_a['title'] ? $kk_a['title'] : kk_t( 'formTitle' ) ); ?></h2>
	<p><?php echo esc_html( kk_t( 'formText' ) ); ?></p>
	<form data-kk-contact novalidate data-msg-required="<?php echo esc_attr( kk_t( 'required' ) ); ?>">
		<?php if ( $kk_a['agent'] ) : ?>
			<div class="f-row"><?php echo $kk_field( 'company', kk_t( 'fCompany' ), true ); // phpcs:ignore ?>
				<div class="field"><label for="f-activity"><?php echo esc_html( kk_t( 'fActivity' ) ); ?></label><select id="f-activity" name="activity"><?php foreach ( kk_lines( kk_optl( 'activities' ) ) as $kk_x ) { echo '<option>' . esc_html( $kk_x ) . '</option>'; } ?></select></div></div>
			<div class="f-row"><?php echo $kk_field( 'country', kk_t( 'fCountry' ), true ) . $kk_field( 'city', kk_t( 'fCity' ), false ); // phpcs:ignore ?></div>
		<?php endif; ?>
		<div class="f-row"><?php echo $kk_field( 'name', kk_t( 'fName' ), true, 'text', ' autocomplete="name"' ) . $kk_field( 'phone', kk_t( 'fPhone' ), true, 'tel', ' autocomplete="tel" dir="ltr"' ); // phpcs:ignore ?></div>
		<div class="f-row"><?php echo $kk_field( 'email', kk_t( 'fEmail' ), false, 'email', ' autocomplete="email" dir="ltr"' ); // phpcs:ignore ?>
			<?php if ( $kk_a['agent'] ) : ?>
				<input type="hidden" name="subject" value="<?php echo esc_attr( kk_t( 'agent' ) ); ?>">
			<?php else : ?>
				<div class="field"><label for="f-subject"><?php echo esc_html( kk_t( 'fSubject' ) ); ?></label><select id="f-subject" name="subject"><?php foreach ( $kk_subj as $kk_s ) { echo '<option>' . esc_html( $kk_s ) . '</option>'; } ?></select></div>
			<?php endif; ?>
		</div>
		<div class="field"><label for="f-msg"><?php echo esc_html( kk_t( 'fMsg' ) ); ?> <span class="req">*</span></label><textarea id="f-msg" name="message" required></textarea></div>
		<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
		<button class="btn" type="submit"><?php echo esc_html( kk_t( 'send' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></button>
		<p class="form-status" role="status" aria-live="polite"></p>
	</form>
</div>
