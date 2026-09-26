<?php
/**
 * Primary navigation
 * 
 * Wired up by the framework's Navigation component (.navbar-toggler / .navbar-collapse)
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );
?>
<button type="button" class="navbar-toggler" aria-controls="primary-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'pn-vr' ); ?>">
    <span class="navbar-toggler-icon" aria-hidden="true"></span>
</button>

<nav id="primary-navigation" class="navbar-collapse" aria-label="<?php esc_attr_e( 'Primary', 'pn-vr' ); ?>">
    <?php if ( has_nav_menu( 'primary' ) ) : ?>
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'menu_id' => 'primary-menu',
            'menu_class' => 'navbar-nav',
            'container' => false,
            'fallback_cb' => false,
        ] );
        ?>
    <?php else : ?>
        <ul id="primary-menu" class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/#features' ) ); ?>"><?php esc_html_e( 'Features', 'pn-vr' ); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/#how-it-works' ) ); ?>"><?php esc_html_e( 'How It Works', 'pn-vr' ); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/#ai-judge' ) ); ?>"><?php esc_html_e( 'AI Judge', 'pn-vr' ); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'pn-vr' ); ?></a></li>
        </ul>
    <?php endif; ?>
</nav>
