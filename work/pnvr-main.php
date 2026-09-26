<?php

/** 
 * 
 * This is the primary theme class file. 
 * It is responsible for pulling together everything for us to use
 * 
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @copyright 2025 Kevin Pirnie
 * 
 * @since 1.0.1
 * @package PodNest Visual Regressor
 * 
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// make sure we aren't loading in the class multiple times
if (! class_exists('PNVR_Main')) {

    /** 
     * PNVR_Main
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
     */
    class PNVR_Main
    {

        /**
         * Initialize the theme's functionality
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
        public static function init(): void
        {

            // setup the theme's defined constants
            self::define_constants();

            // initialize the hooks we'll utilize
            self::init_hooks();
        }

        /**
         * Define theme constants
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return void Returns nothing
         */
        private static function define_constants(): void
        {

            // Theme version
            defined('PNVR_VERSION') || define('PNVR_VERSION', wp_get_theme()->get('Version') ?? '1.0.0');

            // Theme paths
            defined('PNVR_PATH') || define('PNVR_PATH', get_template_directory());
            defined('PNVR_URL') || define('PNVR_URL', get_template_directory_uri());
            defined('PNVR_WORK_PATH') || define('PNVR_WORK_PATH', PNVR_PATH . '/work');
            defined('PNVR_INC_PATH') || define('PNVR_INC_PATH', PNVR_WORK_PATH . '/inc');
            defined('PNVR_ASSETS_URL') || define('PNVR_ASSETS_URL', PNVR_URL . '/assets');

            // The PodNest CDN, where the logos live
            defined('PNVR_CDN_URL') || define('PNVR_CDN_URL', 'https://cdn.pnst.us');

            // Theme settings
            defined('PNVR_OPTION_KEY') || define('PNVR_OPTION_KEY', 'pnvr_settings');
        }

        /**
         * Initialize hooks
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return void Returns nothing
         */
        private static function init_hooks(): void
        {

            // Theme supports, menus, and textdomain
            add_action('after_setup_theme', ['PNVR_Supports', 'theme_setup'], 1);

            // Initialize theme settings
            add_action('after_setup_theme', function () {

                // fire up the settings class
                new PNVR_Settings();
            }, 10);

            // Widget areas
            add_action('widgets_init', ['PNVR_Supports', 'widgets_init']);

            // Content post types, their meta boxes, and their admin menu
            add_action('init', ['PNVR_Post_Types', 'register']);
            add_action('init', ['PNVR_Post_Types', 'meta_boxes']);
            add_action('admin_menu', ['PNVR_Post_Types', 'admin_menu']);

            // The theme's blocks, their category, and the pattern category
            add_action('init', ['PNVR_Blocks', 'register']);
            add_filter('block_categories_all', ['PNVR_Blocks', 'categories']);

            // The contact form
            PNVR_Contact::init();

            // Structured data
            PNVR_SEO::init();

            // Customizer
            add_action('customize_register', ['PNVR_Customizer', 'register']);

            // Primary menu classes for the Navigation component
            add_filter('nav_menu_css_class', ['PNVR_Supports', 'menu_item_classes'], 10, 3);
            add_filter('nav_menu_link_attributes', ['PNVR_Supports', 'menu_link_attributes'], 10, 3);

            // Enqueue assets
            add_action('wp_enqueue_scripts', function () {

                // fire up the asset class
                $assets = new PNVR_Assets();

                // properly enqueue our assets
                $assets->enqueue_assets();

                // clean up
                unset($assets);
            }, 10);

            // Preload the self-hosted fonts
            add_action('wp_head', ['PNVR_Assets', 'preload_fonts'], 1);

            // Preconnect to the CDN
            add_filter('wp_resource_hints', ['PNVR_Assets', 'resource_hints'], 10, 2);

            // Load the source module when the bundle has not been built
            add_filter('script_loader_tag', ['PNVR_Assets', 'module_type'], 10, 2);

            // Admin assets
            add_action('admin_enqueue_scripts', function () {}, 10);

            // initialization
            add_action('init', function () {
                return false;
            });
        }

        /**
         * Get a theme option
         * 
         * Pulls a single field from the theme settings option array
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param string $key Option key
         * @param mixed $default Default value
         * @return mixed Returns the value of the option requested
         */
        public static function get_option(string $key, mixed $default = null): mixed
        {

            // grab our field framework instance, initializing it if needed
            $fw = \KP\WPFieldFramework\Loader::init('pnvr');

            // pull the settings array from the field framework's storage
            $options = $fw ? $fw->getStorage()->getOption(PNVR_OPTION_KEY, []) : get_option(PNVR_OPTION_KEY, []);

            // return the requested field, or the default
            return (is_array($options) && array_key_exists($key, $options)) ? $options[$key] : $default;
        }
    }
}
