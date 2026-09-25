<?php
/**
 * Page hero
 * 
 * The inner page title band
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// an optional eyebrow and intro
$pnvr_eyebrow = $args['eyebrow'] ?? '';
$pnvr_intro = $args['intro'] ?? '';
?>
<header class="pnvr-page-hero">
    <div class="pnvr-hero-grid-lines" aria-hidden="true"></div>
    <div class="container container-2xl">
        <?php if( $pnvr_eyebrow ) : ?>
            <span class="pnvr-eyebrow"><?php echo esc_html( $pnvr_eyebrow ); ?></span>
        <?php endif; ?>
        <?php the_title( '<h2 class="pnvr-page-title">', '</h2>' ); ?>
        <?php if( $pnvr_intro ) : ?>
            <p class="pnvr-section-desc"><?php echo esc_html( $pnvr_intro ); ?></p>
        <?php endif; ?>
    </div>
</header>
