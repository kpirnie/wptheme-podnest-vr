<?php
/**
 * AI judge spotlight
 * 
 * What the AI review adds, with the illustrated review panel
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the points: icon, title, description
$pnvr_points = [
    [ 'ai', __( 'Verdicts you can act on', 'pn-vr' ), __( 'Pass, warn, or fail, with a confidence, a one-line summary, and the findings behind it.', 'pn-vr' ) ],
    [ 'content', __( 'Expectations are absolute', 'pn-vr' ), __( 'A missing expected string fails the comparison, whatever the model says.', 'pn-vr' ) ],
    [ 'shield', __( 'Never a silent pass', 'pn-vr' ), __( 'If the AI call fails, the comparison degrades to warn and says why. It never quietly passes.', 'pn-vr' ) ],
    [ 'key', __( 'Your model, your key', 'pn-vr' ), __( 'Anthropic or any OpenAI-compatible endpoint, with prompt caching to keep repeat runs cheap.', 'pn-vr' ) ],
];

// the verdict legend: modifier, label, meaning
$pnvr_verdicts = [
    [ 'pass', __( 'Pass', 'pn-vr' ), __( 'Nothing that matters changed.', 'pn-vr' ) ],
    [ 'warn', __( 'Warn', 'pn-vr' ), __( 'Something changed. Worth a human look.', 'pn-vr' ) ],
    [ 'fail', __( 'Fail', 'pn-vr' ), __( 'A real regression, or missing content.', 'pn-vr' ) ],
];
?>
<section id="ai-judge" class="pnvr-section" aria-labelledby="ai-heading">
    <div class="container container-2xl pnvr-ai-inner">

        <div class="pnvr-ai-content" data-scroll-reveal>
            <span class="pnvr-eyebrow"><?php esc_html_e( 'AI Judge', 'pn-vr' ); ?></span>
            <h2 id="ai-heading"><?php esc_html_e( 'Knows a rotating advert from a real regression.', 'pn-vr' ); ?></h2>
            <p class="pnvr-lead"><?php esc_html_e( 'A pixel diff tells you something changed. The AI judge tells you whether it matters, reading the screenshots, the diff, and the text of both pages the way a reviewer would.', 'pn-vr' ); ?></p>

            <ul class="pnvr-ai-points">
                <?php foreach( $pnvr_points as [ $pnvr_icon, $pnvr_title, $pnvr_desc ] ) : ?>
                    <li>
                        <span class="pnvr-ai-point-icon"><?php echo PNVR_Icons::svg( $pnvr_icon ); ?></span>
                        <span><strong><?php echo esc_html( $pnvr_title ); ?></strong> <?php echo esc_html( $pnvr_desc ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <dl class="pnvr-verdict-legend">
                <?php foreach( $pnvr_verdicts as [ $pnvr_mod, $pnvr_label, $pnvr_meaning ] ) : ?>
                    <div>
                        <dt><span class="pnvr-verdict pnvr-verdict--<?php echo esc_attr( $pnvr_mod ); ?>"><?php echo esc_html( $pnvr_label ); ?></span></dt>
                        <dd><?php echo esc_html( $pnvr_meaning ); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>

        <div class="pnvr-ai-visual" data-scroll-reveal data-scroll-delay="150">
            <?php get_template_part( 'template-parts/illustrations/ai-review' ); ?>
        </div>

    </div>
</section>
