<?php
/**
 * Footer template
 * 
 * @package PodNest Visual Regressor
 */
?>

    <footer id="colophon" class="site-footer">

        <div class="container container-2xl pnvr-footer-grid">

            <div class="pnvr-footer-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pnvr-footer-home" rel="home" aria-label="<?php esc_attr_e( 'PodNest Visual Regressor - Home', 'pn-vr' ); ?>">
                    <?php get_template_part( 'template-parts/brand/wordmark', null, [ 'tag' => 'p' ] ); ?>
                </a>
                <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description ) :
                ?>
                    <p class="pnvr-footer-tagline"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <div class="pnvr-footer-widgets">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="container container-2xl pnvr-footer-bottom">
            <p class="pnvr-footer-copy">
                <?php
                printf(
                    /* translators: 1: the current year, 2: link to kevinpirnie.com */
                    esc_html__( 'Copyright &copy; %1$s %2$s. All Rights Reserved.', 'pn-vr' ),
                    esc_html( wp_date( 'Y' ) ),
                    '<a href="https://kevinpirnie.com/" rel="noopener" target="_blank">Kevin Pirnie</a>'
                );
                ?>
                <?php the_privacy_policy_link( '<br>', '' ); ?>
            </p>
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer',
                'menu_id' => 'footer-menu',
                'container' => false,
                'fallback_cb' => false,
                'depth' => 1,
            ] );
            ?>
        </div>

    </footer>

    <button type="button" class="back-to-top" aria-label="<?php esc_attr_e( 'Scroll to top', 'pn-vr' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><polyline points="18 15 12 9 6 15"></polyline></svg>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
