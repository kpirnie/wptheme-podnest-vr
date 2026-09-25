<?php
/**
 *
 * Theme Supports Class
 *
 * Handles the theme supports, menus, textdomain, and widget areas
 *
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @copyright 2025 Kevin Pirnie
 *
 * @since 1.0.1
 * @package PodNest Visual Regressor
 *
*/

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure we aren't loading in the class multiple times
if( ! class_exists( 'PNVR_Supports' ) ) {

    /**
     * PNVR_Supports
     *
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     *
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     *
    */
    class PNVR_Supports {

        /**
         * theme_setup
         *
         * Registers the theme supports, navigation menus, and textdomain
         *
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         *
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         *
         * @return void Returns nothing
         */
        public static function theme_setup( ): void {

            // Add theme support
            add_theme_support( 'title-tag' );
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'automatic-feed-links' );
            add_theme_support( 'html5', [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ] );
            add_theme_support( 'customize-selective-refresh-widgets' );
            add_theme_support( 'responsive-embeds' );
            add_theme_support( 'wp-block-styles' );
            add_theme_support( 'align-wide' );

            // Editor styles
            add_theme_support( 'editor-styles' );

            // Register navigation menus
            register_nav_menus( [
                'primary' => __( 'Primary Menu', 'pn-vr' ),
                'footer' => __( 'Footer Menu', 'pn-vr' ),
            ] );

            // Load text domain
            load_theme_textdomain( 'pn-vr', PNVR_PATH . '/languages' );

        }

        /**
         * widgets_init
         *
         * Registers the theme's widget areas
         *
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         *
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         *
         * @return void Returns nothing
         */
        public static function widgets_init( ): void {

            // primary sidebar
            register_sidebar( [
                'name' => __( 'Primary Sidebar', 'pn-vr' ),
                'id' => 'sidebar-1',
                'description' => __( 'Add widgets here to appear in your sidebar.', 'pn-vr' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            ] );

            // footer widgets
            register_sidebar( [
                'name' => __( 'Footer Widget Area', 'pn-vr' ),
                'id' => 'footer-1',
                'description' => __( 'Add widgets here to appear in your footer.', 'pn-vr' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ] );
        }

        /**
         * menu_item_classes
         * 
         * Adds the Navigation component's item class to the primary menu's items
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param array $classes The menu item's classes
         * @param WP_Post $item The menu item
         * @param stdClass $args The wp_nav_menu arguments
         * @return array Returns the classes
         */
        public static function menu_item_classes( array $classes, $item, $args ): array {

            // only the primary menu
            if( 'primary' !== ( $args -> theme_location ?? '' ) ) {
                return $classes;
            }

            // add the item class
            $classes[] = 'nav-item';

            // return the classes
            return $classes;
        }

        /**
         * menu_link_attributes
         * 
         * Adds the Navigation component's link class, and the active state, to the primary menu's links
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param array $atts The link's attributes
         * @param WP_Post $item The menu item
         * @param stdClass $args The wp_nav_menu arguments
         * @return array Returns the attributes
         */
        public static function menu_link_attributes( array $atts, $item, $args ): array {

            // only the primary menu
            if( 'primary' !== ( $args -> theme_location ?? '' ) ) {
                return $atts;
            }

            // the link class, plus the active state for the current item
            $classes = [ 'nav-link' ];
            if( ! empty( $item -> current ) ) {
                $classes[] = 'active';
            }

            // merge with anything already there
            $atts['class'] = trim( sprintf( '%s %s', $atts['class'] ?? '', implode( ' ', $classes ) ) );

            // return the attributes
            return $atts;
        }

    }

}
