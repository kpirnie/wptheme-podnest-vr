<?php

/**
 * Title: Security
 * Slug: pn-vr/security
 * Categories: pnvr
 * Description: The Security section: a heading and the Security grid.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');
?>
<!-- wp:group {"tagName":"section","className":"pnvr-section pnvr-section-alt"} -->
<section id="security" class="wp-block-group pnvr-section pnvr-section-alt"><!-- wp:group {"className":"container container-2xl"} -->
    <div class="wp-block-group container container-2xl"><!-- wp:group {"tagName":"header","className":"pnvr-section-header pnvr-reveal"} -->
        <header class="wp-block-group pnvr-section-header pnvr-reveal"><!-- wp:paragraph {"className":"pnvr-eyebrow"} -->
            <p class="pnvr-eyebrow"><?php esc_html_e('Security', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading -->
            <h2 class="wp-block-heading"><?php esc_html_e('It Points a Real Browser at the Web. It Is Built Like It.', 'pn-vr'); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"pnvr-section-desc"} -->
            <p class="pnvr-section-desc"><?php esc_html_e('Accounts, secrets, screenshots, and the network the browser can reach are all locked down by default.', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->
        </header>
        <!-- /wp:group -->

        <!-- wp:pnvr/security /-->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->