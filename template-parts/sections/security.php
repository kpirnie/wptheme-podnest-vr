<?php

/**
 * Security grid
 * 
 * The pnvr/security block: cards from VR Content > Security
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// the security items
$pnvr_security = PNVR_Post_Types::get_posts(PNVR_Post_Types::SECURITY);
?>
<?php if ($pnvr_security) : ?>
    <div class="pnvr-security-grid">
        <?php foreach ($pnvr_security as $pnvr_i => $pnvr_item) :
            $pnvr_icon = (string) get_post_meta($pnvr_item->ID, '_pnvr_icon', true);
        ?>
            <article class="pnvr-security-card" data-scroll-reveal data-scroll-delay="<?php echo esc_attr(($pnvr_i % 4) * 100); ?>">
                <?php if ($pnvr_icon) : ?>
                    <span class="pnvr-security-icon"><?php echo PNVR_Icons::svg($pnvr_icon); ?></span>
                <?php endif; ?>
                <h3><?php echo esc_html(get_the_title($pnvr_item)); ?></h3>
                <p><?php echo esc_html($pnvr_item->post_excerpt); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
<?php elseif (current_user_can('edit_posts')) : ?>
    <p class="pnvr-empty-notice"><?php esc_html_e('Add Security Item posts under VR Content > Security. Only editors see this notice.', 'pn-vr'); ?></p>
<?php endif; ?>