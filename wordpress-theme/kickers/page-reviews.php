<?php
/**
 * Template Name: صفحة آراء العملاء (كيكرز)
 *
 * @package Kickers
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => get_the_title(), 'lead' => kk_t( 'reviewsLead' ), 'crumbs' => array( array( get_the_title(), '' ) ), 'img' => kk_asset( 'img/p2/filled-11.webp' ) ) );
?>
<section class="section"><div class="wrap"><div class="reviews-grid" data-stagger><?php get_template_part( 'template-parts/review-cards', null, array( 'n' => 60 ) ); ?></div></div></section>
<section class="section" style="padding-top:0">
	<div class="wrap" style="max-width:820px">
		<div class="form-card" data-reveal>
			<h2><?php echo esc_html( kk_t( 'shareReview' ) ); ?></h2>
			<p><?php echo esc_html( kk_t( 'shareReviewText' ) ); ?></p>
			<form data-kk-contact novalidate data-msg-required="<?php echo esc_attr( kk_t( 'required' ) ); ?>">
				<input type="hidden" name="kind" value="review"><input type="hidden" name="phone" value="-">
				<div class="f-row">
					<div class="field"><label for="rv-name"><?php echo esc_html( kk_t( 'fName' ) ); ?> <span class="req">*</span></label><input id="rv-name" name="name" required></div>
					<div class="field"><label for="rv-city"><?php echo esc_html( kk_t( 'fCity' ) . ' ' . kk_t( 'fOpt' ) ); ?></label><input id="rv-city" name="city"></div>
				</div>
				<div class="field"><span class="flabel"><?php echo esc_html( kk_t( 'rating' ) ); ?></span><div class="rate">
					<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
						<input type="radio" name="rating" id="r<?php echo (int) $i; ?>" value="<?php echo (int) $i; ?>"<?php checked( 5, $i ); ?>><label for="r<?php echo (int) $i; ?>" aria-label="<?php echo (int) $i; ?>"><?php echo kk_ico( 'i-star' ); // phpcs:ignore ?></label>
					<?php endfor; ?>
				</div></div>
				<div class="field"><label for="rv-msg"><?php echo esc_html( kk_t( 'yourReview' ) ); ?> <span class="req">*</span></label><textarea id="rv-msg" name="message" required></textarea></div>
				<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
				<button class="btn" type="submit"><?php echo esc_html( kk_t( 'send' ) ); ?><?php echo kk_arrow(); // phpcs:ignore ?></button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
