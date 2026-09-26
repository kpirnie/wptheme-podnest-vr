<?php

/**
 * Title: Pricing
 * Slug: pn-vr/pricing
 * Categories: pnvr
 * Description: The Pricing section: a heading, the tier cards, and the Trial vs Paid table.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');
?>
<!-- wp:group {"tagName":"section","className":"pnvr-section"} -->
<section id="pricing" class="wp-block-group pnvr-section"><!-- wp:group {"className":"container container-2xl"} -->
    <div class="wp-block-group container container-2xl"><!-- wp:group {"tagName":"header","className":"pnvr-section-header pnvr-reveal"} -->
        <header class="wp-block-group pnvr-section-header pnvr-reveal"><!-- wp:paragraph {"className":"pnvr-eyebrow"} -->
            <p class="pnvr-eyebrow"><?php esc_html_e('Pricing', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading -->
            <h2 class="wp-block-heading"><?php esc_html_e('Start Free. Scale When It Earns Its Keep.', 'pn-vr'); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"pnvr-section-desc"} -->
            <p class="pnvr-section-desc"><?php esc_html_e('Try it on a trial account, run it hosted with every feature switched on, or buy the source and run it on your own servers.', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->
        </header>
        <!-- /wp:group -->

        <!-- wp:pnvr/pricing /-->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->