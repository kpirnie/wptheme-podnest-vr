<?php
/**
 * How it works
 * 
 * The capture, compare, judge, report pipeline
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the steps: icon, title, description, output
$pnvr_steps = [
    [ 'camera', __( 'Capture', 'pn-vr' ), __( 'Headless Chromium loads both URLs, with per-URL basic auth, frozen animations, and masked selectors for sliders and cookie banners, then screenshots them.', 'pn-vr' ), 'baseline.png · candidate.png' ],
    [ 'pixels', __( 'Compare', 'pn-vr' ), __( 'A pixel-for-pixel diff with a tolerance and threshold, plus a check that every expected string is in the rendered text of both pages.', 'pn-vr' ), 'diff.png · 4.8% · 5/5' ],
    [ 'ai', __( 'Judge', 'pn-vr' ), __( 'The screenshots, the diff, the measurements, and both pages\' text go to your AI model, which returns a verdict, a confidence, a summary, and its findings.', 'pn-vr' ), 'verdict: warn · 0.87' ],
    [ 'report', __( 'Report', 'pn-vr' ), __( 'Results land as each comparison finishes. Download the run as a PDF, email it to the project\'s recipients, or mark a known change as a manual pass.', 'pn-vr' ), 'run-42.pdf · emailed' ],
];
?>
<section id="how-it-works" class="pnvr-section pnvr-section-alt" aria-labelledby="how-heading">
    <div class="container container-2xl">

        <header class="pnvr-section-header" data-scroll-reveal>
            <span class="pnvr-eyebrow"><?php esc_html_e( 'How It Works', 'pn-vr' ); ?></span>
            <h2 id="how-heading"><?php esc_html_e( 'Capture. Compare. Judge. Report.', 'pn-vr' ); ?></h2>
            <p class="pnvr-section-desc"><?php esc_html_e( 'Every run walks the same four steps for every comparison in the project, and the results show up while the run is still going.', 'pn-vr' ); ?></p>
        </header>

        <ol class="pnvr-pipeline">
            <?php foreach( $pnvr_steps as $pnvr_i => [ $pnvr_icon, $pnvr_title, $pnvr_desc, $pnvr_output ] ) : ?>
                <li class="pnvr-pipeline-step" data-scroll-reveal data-scroll-delay="<?php echo esc_attr( $pnvr_i * 120 ); ?>">
                    <div class="pnvr-pipeline-head">
                        <span class="pnvr-pipeline-icon"><?php echo PNVR_Icons::svg( $pnvr_icon ); ?></span>
                        <span class="pnvr-pipeline-num"><?php echo esc_html( sprintf( '%02d', $pnvr_i + 1 ) ); ?></span>
                    </div>
                    <h3><?php echo esc_html( $pnvr_title ); ?></h3>
                    <p><?php echo esc_html( $pnvr_desc ); ?></p>
                    <code class="pnvr-pipeline-output"><?php echo esc_html( $pnvr_output ); ?></code>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
