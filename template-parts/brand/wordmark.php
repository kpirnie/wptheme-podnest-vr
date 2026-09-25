<?php
/**
 * Brand wordmark
 * 
 * The PodNest logo, the POD/NEST two-tone mark, and the product name with its subline
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the tag the product name renders in
$pnvr_tag = $args['tag'] ?? 'h1';
$pnvr_tag = in_array( $pnvr_tag, [ 'h1', 'h2', 'p' ], true ) ? $pnvr_tag : 'h1';
?>
<div class="pnvr-brand">
    <img src="<?php echo esc_url( pnvr_logo_url( 'svg' ) ); ?>" alt="" width="80" height="80" class="pnvr-brand-logo" loading="eager" decoding="async">
    <div class="pnvr-brand-text">
        <span class="pnvr-brand-word" aria-hidden="true"><span class="pnvr-brand-pod">POD</span><span class="pnvr-brand-nest">NEST</span></span>
        <div class="pnvr-brand-product">
            <<?php echo $pnvr_tag; ?> class="pnvr-brand-title"><?php esc_html_e( 'Visual Regressor', 'pn-vr' ); ?></<?php echo $pnvr_tag; ?>>
            <span class="pnvr-brand-sub"><?php esc_html_e( 'Screenshot & Content Comparison', 'pn-vr' ); ?></span>
        </div>
    </div>
</div>
