<?php

/**
 * Title: Features
 * Slug: pn-vr/features
 * Categories: pnvr
 * Description: The Capabilities section: a heading and the Features grid.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');
?>
<!-- wp:group {"tagName":"section","className":"pnvr-section"} -->
<section id="features" class="wp-block-group pnvr-section"><!-- wp:group {"className":"container container-2xl"} -->
    <div class="wp-block-group container container-2xl"><!-- wp:group {"tagName":"header","className":"pnvr-section-header pnvr-reveal"} -->
        <header class="wp-block-group pnvr-section-header pnvr-reveal"><!-- wp:paragraph {"className":"pnvr-eyebrow"} -->
            <p class="pnvr-eyebrow"><?php esc_html_e('Capabilities', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading -->
            <h2 class="wp-block-heading"><?php esc_html_e('Everything a Visual Regression Run Needs', 'pn-vr'); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"pnvr-section-desc"} -->
            <p class="pnvr-section-desc"><?php esc_html_e('Screenshots, diffs, content checks, an AI verdict, a crawl for broken links, and a report you can hand to a client. One container, one run.', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->
        </header>
        <!-- /wp:group -->

        <!-- wp:pnvr/features /-->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->