<?php

/**
 * Features grid
 * 
 * The pnvr/features block: cards from VR Content > Features
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// the features
$pnvr_features = PNVR_Post_Types::get_posts(PNVR_Post_Types::FEATURE);
?>
<?php if ($pnvr_features) : ?>
    <div class="pnvr-features-grid">
        <?php foreach ($pnvr_features as $pnvr_i => $pnvr_feature) :
            $pnvr_icon = (string) get_post_meta($pnvr_feature->ID, '_pnvr_icon', true);
            $pnvr_more = (string) get_post_meta($pnvr_feature->ID, '_pnvr_learn_more_url', true);
            $pnvr_desc = $pnvr_feature->post_excerpt ?: wp_trim_words(wp_strip_all_tags($pnvr_feature->post_content), 24, '&hellip;');
        ?>
            <article class="pnvr-feature-card" data-scroll-reveal data-scroll-delay="<?php echo esc_attr(($pnvr_i % 3) * 100); ?>">
                <?php if ($pnvr_icon) : ?>
                    <span class="pnvr-feature-icon"><?php echo PNVR_Icons::svg($pnvr_icon); ?></span>
                <?php endif; ?>
                <h3><?php echo esc_html(get_the_title($pnvr_feature)); ?></h3>
                <p><?php echo esc_html($pnvr_desc); ?></p>
                <?php if ($pnvr_more) : ?>
                    <a href="<?php echo esc_url($pnvr_more); ?>" class="pnvr-link-arrow"><?php esc_html_e('Learn more', 'pn-vr'); ?> <span aria-hidden="true">&rarr;</span></a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php elseif (current_user_can('edit_posts')) : ?>
    <p class="pnvr-empty-notice"><?php esc_html_e('Add Feature posts under VR Content > Features. Only editors see this notice.', 'pn-vr'); ?></p>
<?php endif; ?>