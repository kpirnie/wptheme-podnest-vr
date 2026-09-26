<?php

/**
 * Title: Call to Action
 * Slug: pn-vr/cta
 * Categories: pnvr
 * Description: The Get Started panel with the trial and sign in buttons.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');
?>
<!-- wp:group {"tagName":"section","className":"pnvr-section pnvr-cta"} -->
<section id="cta" class="wp-block-group pnvr-section pnvr-cta"><!-- wp:group {"className":"container container-2xl"} -->
    <div class="wp-block-group container container-2xl"><!-- wp:group {"className":"pnvr-cta-inner pnvr-reveal"} -->
        <div class="wp-block-group pnvr-cta-inner pnvr-reveal"><!-- wp:paragraph {"className":"pnvr-eyebrow"} -->
            <p class="pnvr-eyebrow"><?php esc_html_e('Get Started', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading -->
            <h2 class="wp-block-heading"><?php esc_html_e('Stop Eyeballing Staging.', 'pn-vr'); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"pnvr-lead"} -->
            <p class="pnvr-lead"><?php esc_html_e('Trial accounts are set up by hand. Tell us a little about your sites and we will get you comparing.', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"className":"pnvr-hero-ctas"} -->
            <div class="wp-block-buttons pnvr-hero-ctas"><!-- wp:button {"className":"is-style-pnvr-primary pnvr-block-btn-lg"} -->
                <div class="wp-block-button is-style-pnvr-primary pnvr-block-btn-lg"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url(pnvr_contact_url('trial')); ?>"><?php esc_html_e('Start a Free Trial', 'pn-vr'); ?></a></div>
                <!-- /wp:button -->

                <!-- wp:button {"className":"is-style-pnvr-secondary pnvr-block-btn-lg"} -->
                <div class="wp-block-button is-style-pnvr-secondary pnvr-block-btn-lg"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url(pnvr_contact_url('signin')); ?>"><?php esc_html_e('Sign In', 'pn-vr'); ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->