<?php

/** 
 * 
 * Post Types Class
 * 
 * Registers the content post types (marquee, features, how it works steps, security items, pricing tiers) and their meta boxes
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

// pull our field framework
use \KP\WPFieldFramework\Loader;

// make sure we aren't loading in the class multiple times
if (! class_exists('PNVR_Post_Types')) {

    /** 
     * PNVR_Post_Types
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
     */
    class PNVR_Post_Types
    {

        /**
         * Post type slugs
         * @var string
         */
        public const MARQUEE = 'pnvr_marquee';
        public const FEATURE = 'pnvr_feature';
        public const PRICING = 'pnvr_pricing';
        public const STEP = 'pnvr_step';
        public const SECURITY = 'pnvr_security';

        /**
         * The admin menu slug the post types live under
         * @var string
         */
        public const MENU_SLUG = 'pnvr-content';

        /**
         * register
         * 
         * Registers the post types
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
        public static function register(): void
        {

            // marquee items: the title is the item
            register_post_type(self::MARQUEE, self::args(
                self::labels(__('Marquee Items', 'pn-vr'), __('Marquee Item', 'pn-vr'), __('Marquee', 'pn-vr')),
                ['title', 'page-attributes']
            ));

            // feature cards
            register_post_type(self::FEATURE, self::args(
                self::labels(__('Features', 'pn-vr'), __('Feature', 'pn-vr'), __('Features', 'pn-vr')),
                ['title', 'editor', 'excerpt', 'page-attributes']
            ));

            // how it works steps
            register_post_type(self::STEP, self::args(
                self::labels(__('How It Works Steps', 'pn-vr'), __('How It Works Step', 'pn-vr'), __('How It Works', 'pn-vr')),
                ['title', 'excerpt', 'page-attributes']
            ));

            // security cards
            register_post_type(self::SECURITY, self::args(
                self::labels(__('Security Items', 'pn-vr'), __('Security Item', 'pn-vr'), __('Security', 'pn-vr')),
                ['title', 'excerpt', 'page-attributes']
            ));

            // pricing tiers
            register_post_type(self::PRICING, self::args(
                self::labels(__('Pricing Tiers', 'pn-vr'), __('Pricing Tier', 'pn-vr'), __('Pricing', 'pn-vr')),
                ['title', 'editor', 'page-attributes']
            ));
        }

        /**
         * meta_boxes
         * 
         * Registers the feature, step, security, and pricing meta boxes with the field framework
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
        public static function meta_boxes(): void
        {

            // grab the field framework
            $fw = Loader::init('pnvr');

            // the framework's requirements were not met
            if (! $fw) {
                return;
            }

            // feature card
            $fw->addMetaBox([
                'id' => 'pnvr_feature_details',
                'title' => __('Feature Card', 'pn-vr'),
                'post_types' => [self::FEATURE],
                'context' => 'side',
                'fields' => [
                    [
                        'id' => '_pnvr_icon',
                        'type' => 'select',
                        'label' => __('Icon', 'pn-vr'),
                        'options' => PNVR_Icons::options(),
                    ],
                    [
                        'id' => '_pnvr_learn_more_url',
                        'type' => 'url',
                        'label' => __('Learn more URL', 'pn-vr'),
                        'description' => __('Optional. Adds a learn more link to the card.', 'pn-vr'),
                    ],
                ],
            ]);

            // how it works step
            $fw->addMetaBox([
                'id' => 'pnvr_step_details',
                'title' => __('How It Works Step', 'pn-vr'),
                'post_types' => [self::STEP],
                'context' => 'side',
                'fields' => [
                    [
                        'id' => '_pnvr_icon',
                        'type' => 'select',
                        'label' => __('Icon', 'pn-vr'),
                        'options' => PNVR_Icons::options(),
                    ],
                    [
                        'id' => '_pnvr_output',
                        'type' => 'text',
                        'label' => __('Output', 'pn-vr'),
                        'description' => __('Optional. The monospaced line at the bottom of the step, e.g. diff.png · 4.8% · 5/5', 'pn-vr'),
                    ],
                ],
            ]);

            // security card
            $fw->addMetaBox([
                'id' => 'pnvr_security_details',
                'title' => __('Security Card', 'pn-vr'),
                'post_types' => [self::SECURITY],
                'context' => 'side',
                'fields' => [
                    [
                        'id' => '_pnvr_icon',
                        'type' => 'select',
                        'label' => __('Icon', 'pn-vr'),
                        'options' => PNVR_Icons::options(),
                    ],
                ],
            ]);

            // pricing tier
            $fw->addMetaBox([
                'id' => 'pnvr_pricing_details',
                'title' => __('Pricing Tier', 'pn-vr'),
                'post_types' => [self::PRICING],
                'fields' => [
                    [
                        'id' => '_pnvr_tier_label',
                        'type' => 'text',
                        'label' => __('Tier label', 'pn-vr'),
                        'description' => __('Short label shown above the price, e.g. Hosted.', 'pn-vr'),
                    ],
                    [
                        'id' => '_pnvr_price',
                        'type' => 'text',
                        'label' => __('Price', 'pn-vr'),
                        'description' => __('Numbers only, no currency symbol. 0 shows as Free, blank shows as Custom.', 'pn-vr'),
                    ],
                    [
                        'id' => '_pnvr_price_unit',
                        'type' => 'text',
                        'label' => __('Price unit', 'pn-vr'),
                        'placeholder' => '/ month',
                    ],
                    [
                        'id' => '_pnvr_price_annual',
                        'type' => 'text',
                        'label' => __('Annual price', 'pn-vr'),
                        'description' => __('Optional. When any tier has one, the section shows a monthly / annual switch.', 'pn-vr'),
                    ],
                    [
                        'id' => '_pnvr_price_annual_unit',
                        'type' => 'text',
                        'label' => __('Annual price unit', 'pn-vr'),
                        'placeholder' => '/ year',
                    ],
                    [
                        'id' => '_pnvr_badge_text',
                        'type' => 'text',
                        'label' => __('Badge', 'pn-vr'),
                        'description' => __('Optional, shown on the featured tier, e.g. Most Popular.', 'pn-vr'),
                    ],
                    [
                        'id' => '_pnvr_is_featured',
                        'type' => 'checkbox',
                        'label' => __('Featured', 'pn-vr'),
                        'checkbox_label' => __('Highlight this tier', 'pn-vr'),
                    ],
                    [
                        'id' => '_pnvr_features_list',
                        'type' => 'textarea',
                        'label' => __('Features list', 'pn-vr'),
                        'description' => __('One per line. Prefix a line with x: for an item that is not included.', 'pn-vr'),
                        'rows' => 10,
                    ],
                    [
                        'id' => '_pnvr_cta_text',
                        'type' => 'text',
                        'label' => __('Button text', 'pn-vr'),
                        'placeholder' => __('Get in Touch', 'pn-vr'),
                    ],
                    [
                        'id' => '_pnvr_cta_plan',
                        'type' => 'text',
                        'label' => __('Button plan', 'pn-vr'),
                        'description' => __('The contact form ?plan= value, e.g. trial, paid, or source.', 'pn-vr'),
                    ],
                ],
            ]);
        }

        /**
         * admin_menu
         * 
         * Adds the top level menu the post types live under
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
        public static function admin_menu(): void
        {

            // the menu page, the post types fill in its submenu
            add_menu_page(
                __('VR Content', 'pn-vr'),
                __('VR Content', 'pn-vr'),
                'edit_posts',
                self::MENU_SLUG,
                '',
                'dashicons-layout',
                26
            );
        }

        /**
         * get_posts
         * 
         * Get a content post type's published posts in menu order
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param string $post_type The post type
         * @return array Returns the WP_Post objects
         */
        public static function get_posts(string $post_type): array
        {

            // return the posts
            return get_posts([
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
                'no_found_rows' => true,
                'update_post_term_cache' => false,
            ]);
        }

        /**
         * args
         * 
         * The shared post type arguments
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param array $labels The labels
         * @param array $supports The supported features
         * @return array Returns the arguments
         */
        private static function args(array $labels, array $supports): array
        {

            // return the arguments
            return [
                'labels' => $labels,
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => self::MENU_SLUG,
                'show_in_rest' => true,
                'supports' => $supports,
                'rewrite' => false,
                'has_archive' => false,
            ];
        }

        /**
         * labels
         * 
         * A minimal labels array
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param string $plural The plural name
         * @param string $singular The singular name
         * @param string $menu_name The menu name
         * @return array Returns the labels
         */
        private static function labels(string $plural, string $singular, string $menu_name): array
        {

            // return the labels
            return [
                'name' => $plural,
                'singular_name' => $singular,
                /* translators: %s: the post type's singular name */
                'add_new_item' => sprintf(__('Add %s', 'pn-vr'), $singular),
                /* translators: %s: the post type's singular name */
                'edit_item' => sprintf(__('Edit %s', 'pn-vr'), $singular),
                'menu_name' => $menu_name,
            ];
        }
    }
}
