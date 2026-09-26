<?php

/**
 * How it works steps
 * 
 * The pnvr/how-it-works block: the pipeline steps from VR Content > How It Works
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// the steps
$pnvr_steps = PNVR_Post_Types::get_posts(PNVR_Post_Types::STEP);
?>
<?php if ($pnvr_steps) : ?>
    <ol class="pnvr-pipeline">
        <?php foreach ($pnvr_steps as $pnvr_i => $pnvr_step) :
            $pnvr_icon = (string) get_post_meta($pnvr_step->ID, '_pnvr_icon', true);
            $pnvr_output = (string) get_post_meta($pnvr_step->ID, '_pnvr_output', true);
        ?>
            <li class="pnvr-pipeline-step" data-scroll-reveal data-scroll-delay="<?php echo esc_attr(($pnvr_i % 4) * 120); ?>">
                <div class="pnvr-pipeline-head">
                    <span class="pnvr-pipeline-icon"><?php echo PNVR_Icons::svg($pnvr_icon); ?></span>
                    <span class="pnvr-pipeline-num"><?php echo esc_html(sprintf('%02d', $pnvr_i + 1)); ?></span>
                </div>
                <h3><?php echo esc_html(get_the_title($pnvr_step)); ?></h3>
                <p><?php echo esc_html($pnvr_step->post_excerpt); ?></p>
                <?php if ($pnvr_output) : ?>
                    <code class="pnvr-pipeline-output"><?php echo esc_html($pnvr_output); ?></code>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
<?php elseif (current_user_can('edit_posts')) : ?>
    <p class="pnvr-empty-notice"><?php esc_html_e('Add How It Works Step posts under VR Content > How It Works. Only editors see this notice.', 'pn-vr'); ?></p>
<?php endif; ?>