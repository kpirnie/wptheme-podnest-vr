<?php

/**
 * Pricing
 * 
 * The pnvr/pricing block: tier cards from VR Content > Pricing, the monthly / annual switch, and the Trial vs Paid comparison table
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// the tiers
$pnvr_tiers = PNVR_Post_Types::get_posts(PNVR_Post_Types::PRICING);

// does any tier have an annual price
$pnvr_has_annual = false;
foreach ($pnvr_tiers as $pnvr_tier) {
    if ('' !== trim((string) get_post_meta($pnvr_tier->ID, '_pnvr_price_annual', true))) {
        $pnvr_has_annual = true;
        break;
    }
}

// the Trial vs Paid rows: feature, trial, paid. true/false render as a check or a cross
$pnvr_rows = [
    [__('Projects', 'pn-vr'), '1', __('Unlimited', 'pn-vr')],
    [__('Comparisons', 'pn-vr'), '1', __('Unlimited', 'pn-vr')],
    [__('Runs per day', 'pn-vr'), '5', __('Unlimited', 'pn-vr')],
    [__('Pixel diff & expected content checks', 'pn-vr'), true, true],
    [__('Per-URL HTTP basic auth', 'pn-vr'), true, true],
    [__('Manual pass with note', 'pn-vr'), true, true],
    [__('AI verdicts', 'pn-vr'), false, true],
    [__('Spider for 4xx / 5xx responses', 'pn-vr'), false, true],
    [__('PDF run reports', 'pn-vr'), false, true],
    [__('Emailed run reports', 'pn-vr'), false, true],
    [__('Team users & project assignment', 'pn-vr'), false, true],
    [__('Mandatory TOTP 2FA', 'pn-vr'), true, true],
];

// renders a table cell's value
$pnvr_cell = static function ($value): string {

    // a check or a cross
    if (is_bool($value)) {
        return sprintf(
            '<span class="%1$s" aria-hidden="true">%2$s</span><span class="screen-reader-text">%3$s</span>',
            $value ? 'pnvr-check' : 'pnvr-cross',
            $value ? '&#10003;' : '&#10007;',
            $value ? esc_html__('Included', 'pn-vr') : esc_html__('Not included', 'pn-vr')
        );
    }

    // plain text
    return esc_html($value);
};

// renders a price
$pnvr_price = static function (string $amount, string $unit): string {

    // blank is custom, zero is free
    if ('' === trim($amount)) {
        return sprintf('<span class="pnvr-price-amount">%s</span>', esc_html__('Custom', 'pn-vr'));
    }
    if (0.0 === (float) $amount) {
        return sprintf('<span class="pnvr-price-amount">%s</span>', esc_html__('Free', 'pn-vr'));
    }

    // the amount and its unit
    return sprintf(
        '<span class="pnvr-price-amount">$%1$s</span><span class="pnvr-price-unit">%2$s</span>',
        esc_html($amount),
        esc_html($unit)
    );
};
?>
<?php if ($pnvr_tiers) : ?>
    <div class="pnvr-pricing">

        <?php if ($pnvr_has_annual) : ?>
            <fieldset class="pnvr-billing-switch">
                <legend class="screen-reader-text"><?php esc_html_e('Billing period', 'pn-vr'); ?></legend>
                <input type="radio" name="pnvr-billing" id="pnvr-billing-monthly" value="monthly" checked>
                <label for="pnvr-billing-monthly"><?php esc_html_e('Monthly', 'pn-vr'); ?></label>
                <input type="radio" name="pnvr-billing" id="pnvr-billing-annual" value="annual">
                <label for="pnvr-billing-annual"><?php esc_html_e('Annual', 'pn-vr'); ?></label>
            </fieldset>
        <?php endif; ?>

        <div class="pnvr-pricing-grid">
            <?php foreach ($pnvr_tiers as $pnvr_i => $pnvr_tier) :
                $pnvr_meta = static fn(string $key): string => (string) get_post_meta($pnvr_tier->ID, $key, true);
                $pnvr_featured = (bool) $pnvr_meta('_pnvr_is_featured');
                $pnvr_annual = $pnvr_meta('_pnvr_price_annual');
                $pnvr_list = array_filter(array_map('trim', explode("\n", $pnvr_meta('_pnvr_features_list'))));
                $pnvr_desc = wp_strip_all_tags(apply_filters('the_content', $pnvr_tier->post_content));
            ?>
                <article class="pnvr-pricing-card<?php echo $pnvr_featured ? ' is-featured' : ''; ?>" data-scroll-reveal data-scroll-delay="<?php echo esc_attr($pnvr_i * 100); ?>">

                    <?php if ($pnvr_featured && $pnvr_meta('_pnvr_badge_text')) : ?>
                        <span class="pnvr-pricing-badge"><?php echo esc_html($pnvr_meta('_pnvr_badge_text')); ?></span>
                    <?php endif; ?>

                    <?php if ($pnvr_meta('_pnvr_tier_label')) : ?>
                        <span class="pnvr-pricing-tier"><?php echo esc_html($pnvr_meta('_pnvr_tier_label')); ?></span>
                    <?php endif; ?>

                    <h3 class="pnvr-pricing-name"><?php echo esc_html(get_the_title($pnvr_tier)); ?></h3>

                    <div class="pnvr-pricing-price pnvr-price--monthly">
                        <?php echo $pnvr_price($pnvr_meta('_pnvr_price'), $pnvr_meta('_pnvr_price_unit') ?: __('/ month', 'pn-vr')); ?>
                    </div>
                    <?php if ($pnvr_has_annual) : ?>
                        <div class="pnvr-pricing-price pnvr-price--annual">
                            <?php
                            echo '' !== trim($pnvr_annual)
                                ? $pnvr_price($pnvr_annual, $pnvr_meta('_pnvr_price_annual_unit') ?: __('/ year', 'pn-vr'))
                                : $pnvr_price($pnvr_meta('_pnvr_price'), $pnvr_meta('_pnvr_price_unit') ?: __('/ month', 'pn-vr'));
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($pnvr_desc) : ?>
                        <p class="pnvr-pricing-desc"><?php echo esc_html(wp_trim_words($pnvr_desc, 30, '&hellip;')); ?></p>
                    <?php endif; ?>

                    <?php if ($pnvr_list) : ?>
                        <ul class="pnvr-pricing-features">
                            <?php foreach ($pnvr_list as $pnvr_line) :
                                $pnvr_out = str_starts_with($pnvr_line, 'x:');
                            ?>
                                <li class="<?php echo $pnvr_out ? 'is-excluded' : ''; ?>">
                                    <?php echo $pnvr_cell(! $pnvr_out); ?>
                                    <span><?php echo esc_html($pnvr_out ? trim(substr($pnvr_line, 2)) : $pnvr_line); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <a href="<?php echo esc_url(pnvr_contact_url($pnvr_meta('_pnvr_cta_plan'))); ?>" class="<?php echo $pnvr_featured ? 'pnvr-btn-primary' : 'pnvr-btn-secondary'; ?>"><?php echo esc_html($pnvr_meta('_pnvr_cta_text') ?: __('Get in Touch', 'pn-vr')); ?></a>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
<?php elseif (current_user_can('edit_posts')) : ?>
    <p class="pnvr-empty-notice"><?php esc_html_e('Add Pricing Tier posts under VR Content > Pricing. Only editors see this notice.', 'pn-vr'); ?></p>
<?php endif; ?>

<div class="pnvr-compare-plans" data-scroll-reveal>
    <h3 class="pnvr-compare-plans-title"><?php esc_html_e('Trial vs Paid', 'pn-vr'); ?></h3>
    <div class="pnvr-table-wrap">
        <table class="pnvr-plan-table">
            <caption class="screen-reader-text"><?php esc_html_e('What a trial account includes compared to a paid account', 'pn-vr'); ?></caption>
            <thead>
                <tr>
                    <th scope="col"><?php esc_html_e('Feature', 'pn-vr'); ?></th>
                    <th scope="col"><?php esc_html_e('Trial', 'pn-vr'); ?></th>
                    <th scope="col" class="is-paid"><?php esc_html_e('Paid', 'pn-vr'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pnvr_rows as [$pnvr_label, $pnvr_trial, $pnvr_paid]) : ?>
                    <tr>
                        <th scope="row"><?php echo esc_html($pnvr_label); ?></th>
                        <td><?php echo $pnvr_cell($pnvr_trial); ?></td>
                        <td class="is-paid"><?php echo $pnvr_cell($pnvr_paid); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="pnvr-compare-plans-note">
        <?php
        printf(
            /* translators: %s: link to support.podnest.us */
            esc_html__('Source Code buyers get the private repository and every update pushed to it, with generic support through GitHub Issues. Trial and Paid customers get support at %s.', 'pn-vr'),
            sprintf('<a href="%1$s" rel="noopener">%2$s</a>', esc_url(pnvr_opt('support_url', 'https://support.podnest.us/')), esc_html(wp_parse_url(pnvr_opt('support_url', 'https://support.podnest.us/'), PHP_URL_HOST) ?: 'support.podnest.us'))
        );
        ?>
    </p>
</div>