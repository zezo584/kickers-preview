<?php
/**
 * Template Name: صفحة تواصل معنا (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => kk_optl( 'contact_lead' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/products/p16.webp' ) ) );
$kk_subjects = array_filter( array_map( 'trim', explode( ',', (string) kk_optl( 'subjects' ) ) ) );
?>
<section class="section">
	<div class="wrap contact-grid">
		<div class="c-list" data-stagger>
			<?php if ( kk_opt( 'phone1' ) || kk_opt( 'phone2' ) ) : ?>
			<div class="c-item" data-reveal><?php echo kk_badge( 'i-phone' ); // phpcs:ignore ?><div><small><?php echo esc_html( kk_t( 'call' ) ); ?></small><strong class="nums">
				<?php foreach ( array( kk_opt( 'phone1' ), kk_opt( 'phone2' ) ) as $kk_ph ) : if ( ! $kk_ph ) { continue; } ?><a href="<?php echo esc_url( kk_tel( $kk_ph ) ); ?>" dir="ltr"><?php echo esc_html( $kk_ph ); ?></a><?php endforeach; ?>
			</strong></div></div>
			<?php endif; ?>
			<a class="c-item" data-reveal href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener"><?php echo kk_badge( 'i-whatsapp' ); // phpcs:ignore ?><div><small><?php echo esc_html( kk_t( 'wa_direct' ) ); ?></small><strong><?php echo esc_html( kk_t( 'chat' ) ); ?></strong></div></a>
			<?php if ( kk_opt( 'email' ) ) : ?>
			<a class="c-item" data-reveal href="mailto:<?php echo esc_attr( antispambot( kk_opt( 'email' ) ) ); ?>"><?php echo kk_badge( 'i-mail' ); // phpcs:ignore ?><div><small><?php echo esc_html( kk_t( 'mail' ) ); ?></small><strong><?php echo esc_html( antispambot( kk_opt( 'email' ) ) ); ?></strong></div></a>
			<?php endif; ?>
			<?php foreach ( kk_factories() as $kk_f ) : ?>
				<div class="c-item" data-reveal><?php echo kk_badge( 'i-pin' ); // phpcs:ignore ?><div><small><?php echo esc_html( $kk_f['country'] ); ?></small><strong><?php echo esc_html( $kk_f['addr'] ); ?></strong></div></div>
			<?php endforeach; ?>
			<?php if ( kk_opt( 'map_embed' ) ) : ?>
				<div class="map-embed" data-reveal><iframe src="<?php echo esc_url( kk_opt( 'map_embed' ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php echo esc_attr( kk_t( 'open_map' ) ); ?>"></iframe></div>
			<?php endif; ?>
		</div>
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
	</div>
</section>
<?php
get_template_part( 'template-parts/factories' );
get_footer();
