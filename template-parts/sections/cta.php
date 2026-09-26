<?php
/**
 * Call to action
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );
?>
<section id="cta" class="pnvr-section pnvr-cta" aria-labelledby="cta-heading">
    <div class="container container-2xl">
        <div class="pnvr-cta-inner" data-scroll-reveal>
            <span class="pnvr-eyebrow"><?php esc_html_e( 'Get Started', 'pn-vr' ); ?></span>
            <h2 id="cta-heading"><?php esc_html_e( 'Stop Eyeballing Staging.', 'pn-vr' ); ?></h2>
            <p class="pnvr-lead"><?php esc_html_e( 'Trial accounts are set up by hand. Tell us a little about your sites and we will get you comparing.', 'pn-vr' ); ?></p>
            <div class="pnvr-hero-ctas">
                <a href="<?php echo esc_url( pnvr_contact_url( 'trial' ) ); ?>" class="pnvr-btn-primary pnvr-btn-lg"><?php esc_html_e( 'Start a Free Trial', 'pn-vr' ); ?></a>
                <a href="<?php echo esc_url( pnvr_contact_url( 'signin' ) ); ?>" class="pnvr-btn-secondary pnvr-btn-lg"><?php esc_html_e( 'Sign In', 'pn-vr' ); ?></a>
            </div>
        </div>
    </div>
</section>
