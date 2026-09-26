<?php
/**
 * Template Name: Support
 * 
 * The contact form in the main column, with where-to-get-help in the sidebar
 * 
 * @package PodNest Visual Regressor
 */

get_header(); 

// where customers get support
$pnvr_support_url = pnvr_opt( 'support_url', 'https://support.podnest.us/' );
?>

<main id="main" class="site-main">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php get_template_part( 'template-parts/page/hero', null, [
                'eyebrow' => __( 'Support', 'pn-vr' ),
                'intro' => has_excerpt( ) ? get_the_excerpt( ) : __( 'Questions about a plan, a trial account, or buying the source? Send us a note.', 'pn-vr' ),
            ] ); ?>

            <div class="container container-2xl pnvr-support">

                <div class="pnvr-support-main">
                    <?php if( trim( get_the_content( ) ) ) : ?>
                        <div class="pnvr-content entry-content">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // the form, unless the content already placed it
                    if( ! has_shortcode( get_the_content( ), 'pnvr_contact_form' ) ) {
                        echo PNVR_Contact::render_form( );
                    }
                    ?>
                </div>

                <aside class="pnvr-support-sidebar" aria-label="<?php esc_attr_e( 'Getting help', 'pn-vr' ); ?>">

                    <div class="pnvr-support-card is-primary">
                        <span class="pnvr-feature-icon"><?php echo PNVR_Icons::svg( 'users' ); ?></span>
                        <h3><?php esc_html_e( 'Already a customer?', 'pn-vr' ); ?></h3>
                        <p><?php esc_html_e( 'Trial and Paid accounts get help at the PodNest support center, where your tickets and their history live.', 'pn-vr' ); ?></p>
                        <a href="<?php echo esc_url( $pnvr_support_url ); ?>" class="pnvr-btn-primary" rel="noopener"><?php esc_html_e( 'Open a Support Ticket', 'pn-vr' ); ?></a>
                    </div>

                    <div class="pnvr-support-card">
                        <span class="pnvr-feature-icon"><?php echo PNVR_Icons::svg( 'container' ); ?></span>
                        <h3><?php esc_html_e( 'Bought the Source Code?', 'pn-vr' ); ?></h3>
                        <p><?php esc_html_e( 'Open an issue on the private GitHub repository you were given access to. Personalized support is available at an extra cost.', 'pn-vr' ); ?></p>
                    </div>

                    <div class="pnvr-support-card">
                        <span class="pnvr-feature-icon"><?php echo PNVR_Icons::svg( 'mail' ); ?></span>
                        <h3><?php esc_html_e( 'Anything else', 'pn-vr' ); ?></h3>
                        <p><?php esc_html_e( 'Plans, trials, a source code purchase, or a question we have not thought of yet: use the form.', 'pn-vr' ); ?></p>
                    </div>

                    <?php if( is_active_sidebar( 'sidebar-1' ) ) : ?>
                        <div class="pnvr-support-widgets">
                            <?php dynamic_sidebar( 'sidebar-1' ); ?>
                        </div>
                    <?php endif; ?>

                </aside>

            </div>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
