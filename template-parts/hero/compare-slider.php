<?php
/**
 * Hero before/after comparison slider
 * 
 * Uses the Customizer images when set, and the illustrated placeholders when not.
 * Wired up by the CompareSlider component.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the labels
$pnvr_label_before = pnvr_opt( 'hero_label_before', 'Baseline' );
$pnvr_label_after = pnvr_opt( 'hero_label_after', 'Candidate' );

// the images
$pnvr_sides = [
    'before' => [ absint( get_theme_mod( PNVR_Customizer::PREFIX . 'hero_image_before', 0 ) ), 'baseline', $pnvr_label_before ],
    'after' => [ absint( get_theme_mod( PNVR_Customizer::PREFIX . 'hero_image_after', 0 ) ), 'candidate', $pnvr_label_after ],
];
?>
<figure class="pnvr-compare-frame">
    <div class="pnvr-compare-bar" aria-hidden="true">
        <span class="pnvr-compare-dot"></span>
        <span class="pnvr-compare-dot"></span>
        <span class="pnvr-compare-dot"></span>
        <span class="pnvr-compare-title"><?php esc_html_e( 'visual regressor / compare', 'pn-vr' ); ?></span>
    </div>

    <div class="compare-slider" data-compare-slider data-start="50">
        <?php foreach( $pnvr_sides as $pnvr_side => [ $pnvr_image_id, $pnvr_placeholder, $pnvr_label ] ) : ?>
            <div class="compare-slider-<?php echo esc_attr( $pnvr_side ); ?>">
                <?php if( $pnvr_image_id && wp_attachment_is_image( $pnvr_image_id ) ) : ?>
                    <?php echo wp_get_attachment_image( $pnvr_image_id, 'full', false, [ 'class' => 'compare-slider-image', 'loading' => 'eager', 'alt' => $pnvr_label ] ); ?>
                <?php else : ?>
                    <?php get_template_part( 'template-parts/hero/placeholder', null, [ 'side' => $pnvr_placeholder ] ); ?>
                <?php endif; ?>
                <span class="compare-slider-label"><?php echo esc_html( $pnvr_label ); ?></span>
            </div>
        <?php endforeach; ?>
        <span class="compare-slider-handle" aria-hidden="true"></span>
        <input type="range" class="compare-slider-range" min="0" max="100" step="1" value="50" aria-label="<?php esc_attr_e( 'Drag to compare the baseline and candidate screenshots', 'pn-vr' ); ?>">
    </div>

    <div class="pnvr-compare-result" aria-hidden="true">
        <span class="pnvr-compare-metric"><?php esc_html_e( 'Pixel diff', 'pn-vr' ); ?> <strong>4.8%</strong></span>
        <span class="pnvr-compare-metric"><?php esc_html_e( 'Expected content', 'pn-vr' ); ?> <strong>5/5</strong></span>
        <span class="pnvr-compare-metric"><?php esc_html_e( 'AI verdict', 'pn-vr' ); ?> <strong class="pnvr-verdict pnvr-verdict--warn"><?php esc_html_e( 'Warn', 'pn-vr' ); ?></strong></span>
    </div>

    <figcaption class="screen-reader-text"><?php esc_html_e( 'A before and after comparison of the same page, with the differences outlined.', 'pn-vr' ); ?></figcaption>
</figure>
