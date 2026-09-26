<?php
/**
 * Hero section
 * 
 * Copy and calls to action from the Customizer, with the before/after comparison slider
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the key facts under the calls to action
$pnvr_stats = [
    [ 'A vs B', __( 'Comparison', 'pn-vr' ) ],
    [ 'AI', __( 'Verdicts', 'pn-vr' ) ],
    [ '2FA', __( 'Mandatory', 'pn-vr' ) ],
    [ 'PDF', __( 'Reports', 'pn-vr' ) ],
];
?>
<section id="hero" class="pnvr-hero" aria-labelledby="hero-heading">
    <div class="pnvr-hero-glow" aria-hidden="true"></div>
    <div class="pnvr-hero-grid-lines" aria-hidden="true"></div>

    <div class="container container-2xl pnvr-hero-inner">

        <div class="pnvr-hero-content">
            <span class="pnvr-hero-badge"><?php echo esc_html( pnvr_opt( 'hero_badge_text', 'Pixel Diff &middot; Content Checks &middot; AI Verdicts' ) ); ?></span>
            <h2 class="pnvr-hero-title" id="hero-heading">
                <?php echo esc_html( pnvr_opt( 'hero_title_line1', 'Catch visual regressions' ) ); ?><br>
                <span class="pnvr-gradient-text"><?php echo esc_html( pnvr_opt( 'hero_title_line2', 'before your users do.' ) ); ?></span>
            </h2>
            <p class="pnvr-hero-desc"><?php echo esc_html( pnvr_opt( 'hero_description', 'Screenshot two URLs, compare them pixel for pixel, check the content you care about is on both, and let an AI judge whether what changed is a real regression or just a rotating advert.' ) ); ?></p>
            <div class="pnvr-hero-ctas">
                <a href="<?php echo esc_url( pnvr_contact_url( pnvr_opt( 'hero_cta_primary_plan', 'trial' ) ) ); ?>" class="pnvr-btn-primary"><?php echo esc_html( pnvr_opt( 'hero_cta_primary', 'Start a Free Trial' ) ); ?></a>
                <a href="<?php echo esc_url( pnvr_opt( 'hero_cta_secondary_url', '#how-it-works' ) ); ?>" class="pnvr-btn-secondary"><?php echo esc_html( pnvr_opt( 'hero_cta_secondary', 'See How It Works' ) ); ?></a>
            </div>
            <dl class="pnvr-hero-stats">
                <?php foreach( $pnvr_stats as [ $pnvr_value, $pnvr_label ] ) : ?>
                    <div class="pnvr-stat">
                        <dt class="pnvr-stat-label"><?php echo esc_html( $pnvr_label ); ?></dt>
                        <dd class="pnvr-stat-value"><?php echo esc_html( $pnvr_value ); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>

        <div class="pnvr-hero-visual">
            <?php get_template_part( 'template-parts/hero/compare-slider' ); ?>
        </div>

    </div>
</section>
