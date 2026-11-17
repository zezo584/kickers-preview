<?php
/**
 * Footer.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_name = kk_site_name();
$kk_loc  = 'footer_none';
?>
</main>
<footer class="site-footer crimp-t">
	<div class="wrap">
		<div class="f-grid">
			<div>
				<a class="f-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( kk_logo_url() ); ?>" alt="<?php echo esc_attr( $kk_name ); ?>" width="241" height="120" loading="lazy"></a>
				<p class="f-about"><?php echo esc_html( kk_optl( 'footer' ) ); ?></p>
				<div class="socials">
					<a href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?></a>
					<?php foreach ( kk_socials() as $kk_k => $kk_s ) : ?>
						<a href="<?php echo esc_url( $kk_s['url'] ); ?>" target="_blank" rel="noopener me" aria-label="<?php echo esc_attr( $kk_s['label'] ); ?>"><?php echo kk_ico( 'i-' . $kk_k ); // phpcs:ignore ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div>
				<h2 class="f-title"><?php echo esc_html( kk_t( 'quick' ) ); ?></h2>
				<?php
				if ( has_nav_menu( $kk_loc ) ) {
					wp_nav_menu( array( 'theme_location' => $kk_loc, 'container' => false, 'menu_class' => 'f-links', 'depth' => 1 ) );
				} else {
					echo '<ul class="f-links">';
					foreach ( kk_nav_items() as $kk_it ) {
						echo '<li><a href="' . esc_url( $kk_it['url'] ) . '">' . esc_html( $kk_it['label'] ) . '</a></li>';
					}
					echo '</ul>';
				}
				?>
			</div>
			<div>
				<h2 class="f-title"><?php echo esc_html( kk_t( 'products' ) ); ?></h2>
				<ul class="f-links">
					<?php foreach ( kk_categories() as $kk_c ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $kk_c ) ); ?>"><?php echo esc_html( kk_term_name( $kk_c ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div>
				<h2 class="f-title"><?php echo esc_html( kk_t( 'reach' ) ); ?></h2>
				<ul class="f-contact">
					<?php if ( kk_opt( 'phone1' ) || kk_opt( 'phone2' ) ) : ?>
					<li><?php echo kk_ico( 'i-phone' ); // phpcs:ignore ?><span>
						<?php foreach ( array( kk_opt( 'phone1' ), kk_opt( 'phone2' ) ) as $kk_i => $kk_p ) : if ( ! $kk_p ) { continue; } ?>
							<?php echo $kk_i ? '<br>' : ''; ?><a href="<?php echo esc_url( kk_tel( $kk_p ) ); ?>" dir="ltr"><?php echo esc_html( $kk_p ); ?></a>
						<?php endforeach; ?>
					</span></li>
					<?php endif; ?>
					<?php if ( kk_opt( 'email' ) ) : ?>
					<li><?php echo kk_ico( 'i-mail' ); // phpcs:ignore ?><a href="mailto:<?php echo esc_attr( antispambot( kk_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( kk_opt( 'email' ) ) ); ?></a></li>
					<?php endif; ?>
					<?php foreach ( kk_factories() as $kk_f ) : ?>
					<li><?php echo kk_ico( 'i-pin' ); // phpcs:ignore ?><span><b><?php echo esc_html( $kk_f['country'] ); ?>:</b> <?php echo esc_html( $kk_f['addr'] ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<div class="f-bottom">
			<span>© <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( kk_optl( 'tagline' ) ); ?>. <?php echo esc_html( kk_t( 'rights' ) ); ?></span>
			<span class="f-langs"><?php foreach ( kk_langs() as $kk_c => $kk_l ) : ?><a href="<?php echo esc_url( kk_switch_url( $kk_c ) ); ?>" hreflang="<?php echo esc_attr( $kk_c ); ?>"><?php echo esc_html( $kk_l['name'] ); ?></a> <?php endforeach; ?></span>
		</div>
	</div>
</footer>
<a class="fab" href="<?php echo esc_url( kk_wa_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( kk_t( 'chat' ) ); ?>"><?php echo kk_ico( 'i-whatsapp' ); // phpcs:ignore ?></a>
<?php wp_footer(); ?>
</body>
</html>
