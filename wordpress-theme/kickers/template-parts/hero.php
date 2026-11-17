<?php
/**
 * Hero slider.
 *
 * @package Kickers
 */

defined( 'ABSPATH' ) || exit;
$kk_slides = kk_get_slides();
if ( ! $kk_slides ) {
	return;
}
$kk_n     = count( $kk_slides );
$kk_sprinkles = '<div class="sprinkles" aria-hidden="true">';
$kk_cols      = array( '#FFC928', '#fff', '#FF8FA0', '#7A4A2D', '#FFE27F' );
for ( $kk_j = 0; $kk_j < 16; $kk_j++ ) {
	$kk_sprinkles .= '<i style="--x:' . ( ( $kk_j * 37 ) % 100 ) . '%;--c:' . $kk_cols[ $kk_j % 5 ] . ';--t:' . ( 7 + ( $kk_j % 5 ) * 1.6 ) . 's;--dl:' . ( -$kk_j * 1.1 ) . 's"></i>';
}
$kk_sprinkles .= '</div>';
$kk_waves = '<div class="slide-bg" aria-hidden="true"><svg viewBox="0 0 1440 500" preserveAspectRatio="none"><path class="wave-a" d="M0,170 C240,90 420,260 720,170 C1000,85 1180,230 1440,140 L1440,500 L0,500 Z"/><path class="wave-b" d="M0,280 C300,200 520,360 860,270 C1100,205 1280,320 1440,250 L1440,500 L0,500 Z"/></svg></div>';
?>
<section class="hero" data-hero data-duration="<?php echo esc_attr( max( 3, (int) kk_opt( 'hero_duration' ) ) ); ?>" data-autoplay="<?php echo kk_opt( 'hero_autoplay' ) ? '1' : '0'; ?>" aria-roledescription="carousel" aria-label="<?php echo esc_attr( kk_t( 'heroLabel' ) ); ?>">
	<h1 class="sr-only"><?php echo esc_html( kk_t( 'h1' ) ); ?></h1>
	<div class="hero-stage crimp-b">
		<?php
		foreach ( $kk_slides as $kk_i => $kk_s ) :
			$kk_label = ( $kk_i + 1 ) . ' / ' . $kk_n;
			if ( 'image' === $kk_s['mode'] && $kk_s['banner'] ) :
				$kk_desk = wp_get_attachment_image_url( $kk_s['banner'], 'full' );
				$kk_mob  = $kk_s['banner_m'] ? wp_get_attachment_image_url( $kk_s['banner_m'], 'full' ) : '';
				$kk_alt  = get_post_meta( $kk_s['banner'], '_wp_attachment_image_alt', true );
				$kk_s['banner_alt'] = $kk_s['title'];
				?>
				<div class="slide slide-image" data-theme="<?php echo esc_attr( $kk_s['theme'] ); ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( $kk_label ); ?>">
					<?php if ( $kk_s['banner_url'] ) : ?><a href="<?php echo esc_url( $kk_s['banner_url'] ); ?>" class="slide-banner-link"><?php endif; ?>
					<picture>
						<?php if ( $kk_mob ) : ?><source media="(max-width: 900px)" srcset="<?php echo esc_url( $kk_mob ); ?>"><?php endif; ?>
						<img class="slide-banner" src="<?php echo esc_url( $kk_desk ); ?>" alt="<?php echo esc_attr( $kk_alt ? $kk_alt : $kk_s['banner_alt'] ); ?>" <?php echo 0 === $kk_i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
					</picture>
					<?php if ( $kk_s['banner_url'] ) : ?></a><?php endif; ?>
				</div>
				<?php
			else :
				$kk_btn2_ext = kk_is_external( $kk_s['btn2_url'] );
				?>
				<div class="slide<?php echo $kk_s['photo'] ? ' has-photo' : ''; ?><?php echo ! empty( $kk_s['new'] ) ? ' is-new' : ''; ?>" data-theme="<?php echo esc_attr( $kk_s['theme'] ); ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( $kk_label ); ?>">
					<?php
					if ( $kk_s['photo'] ) {
						echo wp_get_attachment_image( $kk_s['photo'], 'full', false, array( 'class' => 'slide-photo', 'alt' => '', 'loading' => 0 === $kk_i ? 'eager' : 'lazy' ) );
					}
					echo $kk_waves . $kk_sprinkles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					if ( $kk_s['word'] ) {
						echo '<div class="slide-word" aria-hidden="true">' . esc_html( $kk_s['word'] ) . '</div>';
					}
					?>
					<div class="wrap slide-grid">
						<div class="slide-copy">
							<?php if ( ! empty( $kk_s['new'] ) ) : ?><span class="new-pill"><?php echo kk_ico( 'd-star' ); // phpcs:ignore ?><?php echo esc_html( kk_t( 'newLabel' ) ); ?></span><?php endif; ?>
							<h2 class="slide-title"><?php echo esc_html( $kk_s['title'] ); ?></h2>
							<?php if ( $kk_s['sub'] ) : ?><p class="slide-sub"><?php echo esc_html( $kk_s['sub'] ); ?></p><?php endif; ?>
							<div class="slide-cta">
								<?php if ( $kk_s['btn1'] ) : ?>
									<a class="btn btn-main" href="<?php echo esc_url( $kk_s['btn1_url'] ); ?>"><?php echo esc_html( $kk_s['btn1'] ); ?><?php echo kk_arrow(); // phpcs:ignore ?></a>
								<?php endif; ?>
								<?php if ( $kk_s['btn2'] ) : ?>
									<a class="btn btn-ghost" href="<?php echo esc_url( $kk_s['btn2_url'] ); ?>"<?php echo $kk_btn2_ext ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo false !== strpos( $kk_s['btn2_url'], 'wa.me' ) || false !== strpos( $kk_s['btn2_url'], 'whatsapp' ) ? kk_ico( 'i-whatsapp' ) : ''; // phpcs:ignore ?><?php echo esc_html( $kk_s['btn2'] ); ?></a>
								<?php endif; ?>
							</div>
						</div>
						<div class="slide-visual">
							<div class="plate" aria-hidden="true"><?php echo kk_ico( 'd-plate' ); // phpcs:ignore ?></div>
							<?php foreach ( $kk_s['images'] as $kk_k => $kk_im ) : ?>
								<div class="px pk pk-<?php echo (int) $kk_k + 1; ?>"><div class="pk-in"><img src="<?php echo esc_url( $kk_im['src'] ); ?>" alt="<?php echo $kk_im['id'] ? esc_attr( get_post_meta( $kk_im['id'], '_wp_attachment_image_alt', true ) ) : ''; ?>" <?php echo ( 0 === $kk_i && 0 === $kk_k ) ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async"></div></div>
							<?php endforeach; ?>
							<?php echo kk_sprites( $kk_s['decor'] ); // phpcs:ignore ?>
							<?php
							if ( ! empty( $kk_s['new'] ) ) :
								$kk_w = esc_html( kk_t( 'newLabel' ) );
								?>
								<div class="new-sticker" aria-hidden="true"><svg viewBox="0 0 120 120"><defs><path id="nc<?php echo (int) $kk_i; ?>" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs><text><textPath href="#nc<?php echo (int) $kk_i; ?>"><?php echo str_repeat( $kk_w . ' ★ ', 4 ); // phpcs:ignore ?></textPath></text></svg><b><?php echo $kk_w; // phpcs:ignore ?></b></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
			endif;
		endforeach;
		?>
		<?php if ( $kk_n > 1 ) : ?>
		<div class="hero-nav wrap">
			<div class="hero-tabs">
				<?php
				foreach ( $kk_slides as $kk_s ) :
					$kk_thumb = '';
					if ( 'image' === $kk_s['mode'] && $kk_s['banner'] ) {
						$kk_thumb = wp_get_attachment_image_url( $kk_s['banner'], 'thumbnail' );
					} elseif ( ! empty( $kk_s['images'][0]['src'] ) ) {
						$kk_thumb = $kk_s['images'][0]['id'] ? wp_get_attachment_image_url( $kk_s['images'][0]['id'], 'thumbnail' ) : $kk_s['images'][0]['src'];
					}
					?>
					<button class="hero-tab" type="button" aria-label="<?php echo esc_attr( $kk_s['label'] ); ?>">
						<?php if ( $kk_thumb ) : ?><img src="<?php echo esc_url( $kk_thumb ); ?>" alt="" loading="lazy"><?php endif; ?>
						<span><?php echo ! empty( $kk_s['new'] ) ? '<em>' . esc_html( kk_t( 'newLabel' ) ) . '</em> ' : ''; ?><?php echo esc_html( $kk_s['label'] ); ?></span><span class="bar"><i></i></span>
					</button>
				<?php endforeach; ?>
			</div>
			<div class="hero-ctrl">
				<button class="hero-btn" type="button" data-hero-prev aria-label="<?php echo esc_attr( kk_t( 'prev' ) ); ?>"><?php echo kk_ico( 'i-arrow', 'ico-dir prev-ico' ); // phpcs:ignore ?></button>
				<button class="hero-btn" type="button" data-hero-toggle aria-pressed="false" aria-label="<?php echo esc_attr( kk_t( 'pause' ) ); ?>" data-label-pause="<?php echo esc_attr( kk_t( 'pause' ) ); ?>" data-label-play="<?php echo esc_attr( kk_t( 'play' ) ); ?>"><?php echo kk_ico( 'i-pause', 'i-pause' ) . kk_ico( 'i-play', 'i-play' ); // phpcs:ignore ?></button>
				<button class="hero-btn" type="button" data-hero-next aria-label="<?php echo esc_attr( kk_t( 'next' ) ); ?>"><?php echo kk_arrow(); // phpcs:ignore ?></button>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>
