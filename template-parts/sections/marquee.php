<?php
/**
 * Marquee strip
 * 
 * Items from VR Content > Marquee, falling back to a built-in set
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the items
$pnvr_items = wp_list_pluck( PNVR_Post_Types::get_posts( PNVR_Post_Types::MARQUEE ), 'post_title' );
if( empty( $pnvr_items ) ) {
    $pnvr_items = [
        __( 'Pixel-for-Pixel Diffs', 'pn-vr' ),
        __( 'Expected Content Checks', 'pn-vr' ),
        __( 'AI Verdicts: Pass · Warn · Fail', 'pn-vr' ),
        __( 'Anthropic or OpenAI-Compatible', 'pn-vr' ),
        __( 'Same-Host Spider', 'pn-vr' ),
        __( 'PDF Run Reports', 'pn-vr' ),
        __( 'Emailed Reports', 'pn-vr' ),
        __( 'Manual Pass with Note', 'pn-vr' ),
        __( 'Per-URL Basic Auth', 'pn-vr' ),
        __( 'Mandatory TOTP 2FA', 'pn-vr' ),
        __( 'Full Audit Log', 'pn-vr' ),
        __( 'Multi-Arch Container Images', 'pn-vr' ),
    ];
}
?>
<div class="pnvr-marquee" aria-hidden="true">
    <div class="pnvr-marquee-track">
        <?php for( $pnvr_pass = 0; $pnvr_pass < 2; $pnvr_pass++ ) : ?>
            <?php foreach( $pnvr_items as $pnvr_item ) : ?>
                <span class="pnvr-marquee-item"><span class="pnvr-marquee-dot"></span><?php echo esc_html( $pnvr_item ); ?></span>
            <?php endforeach; ?>
        <?php endfor; ?>
    </div>
</div>
