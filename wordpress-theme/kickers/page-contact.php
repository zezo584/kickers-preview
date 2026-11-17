<?php
/**
 * Template Name: صفحة تواصل معنا (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => kk_optl( 'contact_lead' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/p2/spread-4.webp' ) ) );
?>
<section class="section">
	<div class="wrap contact-grid">
		<div class="c-list" data-stagger>
			<?php if ( kk_opt( 'phone1' ) || kk_opt( 'phone2' ) ) : ?>
			<div class="c-item" data-reveal><?php echo kk_badge( 'i-phone' ); // phpcs:ignore ?><div><small><?php echo esc_html( kk_t( 'call' ) ); ?></small><strong class="nums">
				<?php foreach ( array( kk_opt( 'phone1' ), kk_opt( 'phone2' ) ) as $kk_ph ) : if ( ! $kk_ph ) { continue; } ?><a href="<?php echo esc_url( kk_tel( $kk_ph ) ); ?>" dir="ltr"><?php echo esc_html( $kk_ph ); ?></a><?php endforeach; ?>
			</strong></div></div>
			<?php endif; ?>
			<a class="c-item" data-reveal href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener"><?php echo kk_badge( 'i-whatsapp' ); // phpcs:ignore ?><div><small><?php echo esc_html( kk_t( 'whatsapp' ) ); ?></small><strong><?php echo esc_html( kk_t( 'chat' ) ); ?></strong></div></a>
			<?php if ( kk_opt( 'email' ) ) : ?>
			<a class="c-item" data-reveal href="mailto:<?php echo esc_attr( antispambot( kk_opt( 'email' ) ) ); ?>"><?php echo kk_badge( 'i-mail' ); // phpcs:ignore ?><div><small><?php echo esc_html( kk_t( 'mail' ) ); ?></small><strong><?php echo esc_html( antispambot( kk_opt( 'email' ) ) ); ?></strong></div></a>
			<?php endif; ?>
			<?php foreach ( kk_factories() as $kk_f ) : ?>
				<div class="c-item" data-reveal><?php echo kk_badge( 'i-pin' ); // phpcs:ignore ?><div><small><?php echo esc_html( $kk_f['country'] . ( $kk_f['since'] ? ' · ' . kk_t( 'since' ) . ' ' . $kk_f['since'] : '' ) ); ?></small><strong><?php echo esc_html( $kk_f['addr'] ); ?></strong></div></div>
			<?php endforeach; ?>
			<?php if ( kk_opt( 'map_embed' ) ) : ?>
				<div class="map-embed" data-reveal><iframe src="<?php echo esc_url( kk_opt( 'map_embed' ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php echo esc_attr( kk_t( 'openMap' ) ); ?>"></iframe></div>
			<?php endif; ?>
		</div>
		<?php get_template_part( 'template-parts/contact-form' ); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/factories' );
get_footer();
