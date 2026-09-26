<?php

/**
 * Title: AI Judge
 * Slug: pn-vr/ai-judge
 * Categories: pnvr
 * Description: The AI Judge spotlight: a heading, the AI Judge points, and the review illustration.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');
?>
<!-- wp:group {"tagName":"section","className":"pnvr-section"} -->
<section id="ai-judge" class="wp-block-group pnvr-section"><!-- wp:group {"className":"container container-2xl pnvr-ai-inner"} -->
    <div class="wp-block-group container container-2xl pnvr-ai-inner"><!-- wp:group {"className":"pnvr-ai-content pnvr-reveal"} -->
        <div class="wp-block-group pnvr-ai-content pnvr-reveal"><!-- wp:paragraph {"className":"pnvr-eyebrow"} -->
            <p class="pnvr-eyebrow"><?php esc_html_e('AI Judge', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading -->
            <h2 class="wp-block-heading"><?php esc_html_e('Knows a rotating advert from a real regression.', 'pn-vr'); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"pnvr-lead"} -->
            <p class="pnvr-lead"><?php esc_html_e('A pixel diff tells you something changed. The AI judge tells you whether it matters, reading the screenshots, the diff, and the text of both pages the way a reviewer would.', 'pn-vr'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:pnvr/ai-judge-points /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"className":"pnvr-ai-visual pnvr-reveal pnvr-reveal-delay"} -->
        <div class="wp-block-group pnvr-ai-visual pnvr-reveal pnvr-reveal-delay"><!-- wp:pnvr/ai-review /--></div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->