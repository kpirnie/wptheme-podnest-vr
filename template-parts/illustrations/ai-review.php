<?php
/**
 * AI review illustration
 * 
 * An illustrated AI review panel: the diff with its changes pinned, the verdict, the confidence, and the reasoning
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the findings: category, text
$pnvr_findings = [
    [ __( 'Styling', 'pn-vr' ), __( 'The primary button changed from blue to orange and grew wider.', 'pn-vr' ) ],
    [ __( 'Layout', 'pn-vr' ), __( 'The hero image moved 16px down. Nothing overlaps.', 'pn-vr' ) ],
    [ __( 'Content', 'pn-vr' ), __( 'The second card\'s image was swapped. It looks like rotating promo content.', 'pn-vr' ) ],
];
?>
<figure class="pnvr-review" aria-labelledby="pnvr-review-caption">
    <div class="pnvr-review-bar" aria-hidden="true">
        <span class="pnvr-review-run">run #42</span>
        <span class="pnvr-review-path">/pricing</span>
        <span class="pnvr-review-model">ai review</span>
    </div>

    <div class="pnvr-review-diff">
        <svg viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="<?php esc_attr_e( 'The diff image, with three changed regions highlighted', 'pn-vr' ); ?>" preserveAspectRatio="xMidYMid slice">
            <rect width="800" height="400" fill="#0d1428"/>
            <g fill="#ffffff" opacity="0.08">
                <rect width="800" height="44"/>
                <rect x="60" y="90" width="420" height="22" rx="4"/>
                <rect x="60" y="122" width="300" height="22" rx="4"/>
                <rect x="60" y="164" width="380" height="9" rx="4"/>
                <rect x="60" y="180" width="340" height="9" rx="4"/>
                <rect x="520" y="80" width="220" height="180" rx="12"/>
                <rect x="60" y="290" width="200" height="96" rx="10"/>
                <rect x="300" y="290" width="200" height="96" rx="10"/>
                <rect x="540" y="290" width="200" height="96" rx="10"/>
            </g>
            <g fill="#ff2fb3">
                <rect x="60" y="212" width="160" height="36" rx="8" opacity="0.85"/>
                <rect x="520" y="80" width="220" height="12" opacity="0.7"/>
                <rect x="520" y="260" width="220" height="14" opacity="0.7"/>
                <rect x="316" y="304" width="168" height="40" rx="6" opacity="0.8"/>
            </g>
            <g font-family="JetBrains Mono, monospace" font-size="14" font-weight="700" text-anchor="middle">
                <?php foreach( [ [ 232, 212 ], [ 752, 80 ], [ 496, 304 ] ] as $pnvr_n => [ $pnvr_px, $pnvr_py ] ) : ?>
                    <circle cx="<?php echo $pnvr_px; ?>" cy="<?php echo $pnvr_py; ?>" r="14" fill="#2bff88"/>
                    <text x="<?php echo $pnvr_px; ?>" y="<?php echo $pnvr_py + 5; ?>" fill="#070e20"><?php echo $pnvr_n + 1; ?></text>
                <?php endforeach; ?>
            </g>
        </svg>
    </div>

    <div class="pnvr-review-body">
        <div class="pnvr-review-verdict">
            <span class="pnvr-verdict pnvr-verdict--warn"><?php esc_html_e( 'Warn', 'pn-vr' ); ?></span>
            <div class="pnvr-review-confidence">
                <span class="pnvr-review-confidence-label"><?php esc_html_e( 'Confidence', 'pn-vr' ); ?></span>
                <span class="pnvr-review-meter" aria-hidden="true"><span style="width: 87%"></span></span>
                <span class="pnvr-review-confidence-value">0.87</span>
            </div>
        </div>

        <p class="pnvr-review-summary"><?php esc_html_e( 'The page changed in styling and layout, and one image was swapped. All expected content is present. Nothing looks broken, but the button change needs a human to confirm it was intended.', 'pn-vr' ); ?></p>

        <ol class="pnvr-review-findings">
            <?php foreach( $pnvr_findings as [ $pnvr_cat, $pnvr_text ] ) : ?>
                <li><span class="pnvr-review-cat"><?php echo esc_html( $pnvr_cat ); ?></span> <?php echo esc_html( $pnvr_text ); ?></li>
            <?php endforeach; ?>
        </ol>
    </div>

    <figcaption id="pnvr-review-caption" class="screen-reader-text"><?php esc_html_e( 'An example AI review: a warn verdict at 0.87 confidence, with three findings.', 'pn-vr' ); ?></figcaption>
</figure>
