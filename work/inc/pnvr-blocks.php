<?php

/** 
 * 
 * Blocks Class
 * 
 * Registers the theme's server-rendered blocks, their category, the pattern category, and the core button styles
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
if (! class_exists('PNVR_Blocks')) {

    /** 
     * PNVR_Blocks
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
     */
    class PNVR_Blocks
    {

        /**
         * The block and pattern category slug
         * @var string
         */
        public const CATEGORY = 'pnvr';

        /**
         * The editor script handle
         * @var string
         */
        public const EDITOR_HANDLE = 'pnvr-blocks-editor';

        /**
         * The blocks: name => [ title, dashicon, template part ]
         * @var array
         */
        private const BLOCKS = [
            'pnvr/marquee' => ['Marquee', 'leftright', 'template-parts/sections/marquee'],
            'pnvr/features' => ['Features Grid', 'grid-view', 'template-parts/sections/features'],
            'pnvr/how-it-works' => ['How It Works Steps', 'editor-ol', 'template-parts/sections/how-it-works'],
            'pnvr/security' => ['Security Grid', 'shield', 'template-parts/sections/security'],
            'pnvr/pricing' => ['Pricing', 'money-alt', 'template-parts/sections/pricing'],
            'pnvr/ai-judge-points' => ['AI Judge Points', 'yes-alt', 'template-parts/sections/ai-judge'],
            'pnvr/ai-review' => ['AI Review Illustration', 'format-image', 'template-parts/illustrations/ai-review'],
        ];

        /**
         * register
         * 
         * Registers the editor script, the blocks, the pattern category, and the core button styles
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

            // the editor placeholders
            wp_register_script(
                self::EDITOR_HANDLE,
                PNVR_ASSETS_URL . '/js/editor.js',
                ['wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n'],
                PNVR_VERSION,
                true
            );

            // loop the blocks and register them, rendered from their template part
            foreach (self::BLOCKS as $name => [$title, $icon, $template]) {
                register_block_type($name, [
                    'api_version' => 3,
                    'title' => $title,
                    'category' => self::CATEGORY,
                    'icon' => $icon,
                    'supports' => ['html' => false],
                    'editor_script_handles' => [self::EDITOR_HANDLE],
                    'render_callback' => static function () use ($template): string {

                        // capture the template part's output
                        ob_start();
                        get_template_part($template);
                        return (string) ob_get_clean();
                    },
                ]);
            }

            // the pattern category, the patterns themselves load from /patterns
            register_block_pattern_category(self::CATEGORY, ['label' => __('PN VR Theme', 'pn-vr')]);

            // the theme buttons for the core button block
            register_block_style('core/button', ['name' => 'pnvr-primary', 'label' => __('PN VR Primary', 'pn-vr')]);
            register_block_style('core/button', ['name' => 'pnvr-secondary', 'label' => __('PN VR Secondary', 'pn-vr')]);
        }

        /**
         * categories
         * 
         * Adds the theme's block category to the top of the inserter
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param array $categories The block categories
         * @return array Returns the block categories
         */
        public static function categories(array $categories): array
        {

            // add ours first
            array_unshift($categories, [
                'slug' => self::CATEGORY,
                'title' => __('PN VR Theme', 'pn-vr'),
                'icon' => null,
            ]);

            // return the categories
            return $categories;
        }
    }
}
