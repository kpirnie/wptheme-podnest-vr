<?php
/**
 * Hero slider placeholder
 * 
 * An illustrated page for either side of the comparison slider, used until real screenshots are set.
 * The candidate side carries the changes, outlined the way the diff image marks them.
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// which side we're drawing
$pnvr_candidate = 'candidate' === ( $args['side'] ?? 'baseline' );
?>
<svg class="compare-slider-image" viewBox="0 0 800 520" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="<?php echo esc_attr( $pnvr_candidate ? __( 'Candidate page screenshot', 'pn-vr' ) : __( 'Baseline page screenshot', 'pn-vr' ) ); ?>" preserveAspectRatio="xMidYMid slice">
    <rect width="800" height="520" fill="#f4f6fb"/>

    <!-- nav -->
    <rect width="800" height="56" fill="#ffffff"/>
    <rect y="55" width="800" height="1" fill="#e2e8f0"/>
    <circle cx="40" cy="28" r="12" fill="#3b6ef5"/>
    <rect x="60" y="22" width="80" height="12" rx="3" fill="#1e293b"/>
    <rect x="520" y="24" width="44" height="8" rx="4" fill="#94a3b8"/>
    <rect x="584" y="24" width="44" height="8" rx="4" fill="#94a3b8"/>
    <rect x="648" y="24" width="44" height="8" rx="4" fill="#94a3b8"/>
    <rect x="712" y="18" width="56" height="20" rx="6" fill="#1e293b"/>

    <!-- hero copy -->
    <rect x="60" y="110" width="420" height="26" rx="4" fill="#1e293b"/>
    <rect x="60" y="146" width="<?php echo $pnvr_candidate ? 360 : 300; ?>" height="26" rx="4" fill="#1e293b"/>
    <rect x="60" y="196" width="380" height="10" rx="5" fill="#94a3b8"/>
    <rect x="60" y="214" width="340" height="10" rx="5" fill="#94a3b8"/>
    <rect x="60" y="232" width="360" height="10" rx="5" fill="#94a3b8"/>
    <rect x="60" y="266" width="<?php echo $pnvr_candidate ? 160 : 140; ?>" height="40" rx="8" fill="<?php echo $pnvr_candidate ? '#e2553b' : '#3b6ef5'; ?>"/>
    <rect x="88" y="282" width="<?php echo $pnvr_candidate ? 104 : 84; ?>" height="8" rx="4" fill="#ffffff"/>

    <!-- hero image -->
    <g transform="translate(0 <?php echo $pnvr_candidate ? 16 : 0; ?>)">
        <rect x="520" y="100" width="220" height="210" rx="12" fill="#dbe4ff"/>
        <circle cx="690" cy="148" r="18" fill="#ffffff" opacity="0.8"/>
        <path d="M532 298 L600 214 L648 262 L680 230 L728 298 Z" fill="#3b6ef5" opacity="0.55"/>
    </g>

    <!-- cards -->
    <?php foreach( [ 60, 300, 540 ] as $pnvr_i => $pnvr_x ) : ?>
        <rect x="<?php echo $pnvr_x; ?>" y="350" width="200" height="130" rx="10" fill="#ffffff" stroke="#e2e8f0"/>
        <rect x="<?php echo $pnvr_x + 16; ?>" y="366" width="168" height="50" rx="6" fill="<?php echo ( $pnvr_candidate && 1 === $pnvr_i ) ? '#fde2dc' : '#e2e8f0'; ?>"/>
        <rect x="<?php echo $pnvr_x + 16; ?>" y="430" width="120" height="10" rx="5" fill="#1e293b"/>
        <?php if( ! ( $pnvr_candidate && 2 === $pnvr_i ) ) : ?>
            <rect x="<?php echo $pnvr_x + 16; ?>" y="450" width="150" height="8" rx="4" fill="#94a3b8"/>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if( $pnvr_candidate ) : ?>
        <!-- the diff, outlined -->
        <g fill="none" stroke="#ff2fb3" stroke-width="2.5" stroke-dasharray="6 4">
            <rect x="54" y="140" width="372" height="38" rx="6"/>
            <rect x="54" y="260" width="172" height="52" rx="10"/>
            <rect x="512" y="92" width="236" height="242" rx="14"/>
            <rect x="308" y="358" width="184" height="66" rx="8"/>
            <rect x="548" y="442" width="184" height="26" rx="6"/>
        </g>
        <g font-family="JetBrains Mono, monospace" font-size="11" font-weight="700" fill="#ffffff">
            <?php foreach( [ [ 54, 140 ], [ 54, 260 ], [ 512, 92 ], [ 308, 358 ], [ 548, 442 ] ] as $pnvr_n => [ $pnvr_bx, $pnvr_by ] ) : ?>
                <rect x="<?php echo $pnvr_bx - 4; ?>" y="<?php echo $pnvr_by - 16; ?>" width="30" height="16" rx="4" fill="#ff2fb3"/>
                <text x="<?php echo $pnvr_bx + 1; ?>" y="<?php echo $pnvr_by - 4; ?>">&#916;<?php echo $pnvr_n + 1; ?></text>
            <?php endforeach; ?>
        </g>
    <?php endif; ?>
</svg>
