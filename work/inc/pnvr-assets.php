<?php
/**
 *
 * Assets Class
 *
 * Handles loading the theme's compiled Tailwind stylesheet and scripts
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
if( ! class_exists( 'PNVR_Assets' ) ) {

    /**
     * PNVR_Assets
     *
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     *
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     *
    */
    class PNVR_Assets {

        /**
         * Stylesheet handle
         * @var string
         */
        public const STYLE_HANDLE = 'pnvr-theme';

        /**
         * Script handle
         * @var string
         */
        public const SCRIPT_HANDLE = 'pnvr-app';

        /**
         * Whether or not we are loading the unbundled source module
         * @var bool
         */
        private static bool $using_source_js = false;

        /**
         * enqueue_assets
         *
         * Enqueue the compiled theme stylesheet and script
         *
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         *
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         *
         * @return void Returns nothing
         */
        public function enqueue_assets( ): void {

            // Fire action before assets are enqueued
            do_action( 'pnvr_before_assets_enqueue' );

            // the compiled tailwind stylesheet
            if( file_exists( PNVR_PATH . '/assets/css/theme.css' ) ) {
                wp_enqueue_style(
                    self::STYLE_HANDLE,
                    PNVR_ASSETS_URL . '/css/theme.css',
                    [ ],
                    PNVR_VERSION
                );
            }

            // the bundled script, falling back to the source module when it has not been built
            self::$using_source_js = ! file_exists( PNVR_PATH . '/assets/js/theme.js' );
            wp_enqueue_script(
                self::SCRIPT_HANDLE,
                self::$using_source_js ? PNVR_URL . '/src/js/main.js' : PNVR_ASSETS_URL . '/js/theme.js',
                [ ],
                PNVR_VERSION,
                true
            );

            // Fire action after assets are enqueued
            do_action( 'pnvr_after_assets_enqueue', self::$using_source_js );
        }

        /**
         * preload_fonts
         * 
         * Outputs preload hints for the self-hosted fonts early in the head
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
        public static function preload_fonts( ): void {

            // the fonts we ship
            $fonts = [
                'sora-latin-wght-normal.woff2',
                'orbitron-latin-wght-normal.woff2',
                'jetbrains-mono-latin-wght-normal.woff2',
            ];

            // loop them and write out the hint
            foreach( $fonts as $file ) {
                printf(
                    '<link rel="preload" as="font" type="font/woff2" href="%1$s" crossorigin>%2$s',
                    esc_url( PNVR_ASSETS_URL . '/fonts/' . $file ),
                    PHP_EOL
                );
            }
        }

        /**
         * resource_hints
         * 
         * Adds a preconnect hint for the CDN the logos are served from
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param array $urls The URLs to print for the relation type
         * @param string $relation_type The relation type the URLs are printed for
         * @return array Returns the URLs
         */
        public static function resource_hints( array $urls, string $relation_type ): array {

            // only the preconnect hints
            if( 'preconnect' === $relation_type ) {
                $urls[] = [
                    'href' => PNVR_CDN_URL,
                    'crossorigin' => 'anonymous',
                ];
            }

            // return the urls
            return $urls;
        }

        /**
         * module_type
         *
         * Injects type="module" into the theme script tag when the unbundled source is loaded
         *
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         *
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         *
         * @param string $tag The full script tag
         * @param string $handle The script handle
         * @return string Returns the possibly modified script tag
         */
        public static function module_type( string $tag, string $handle ): string {

            // only our script, and only when it's the source module
            if( $handle !== self::SCRIPT_HANDLE || ! self::$using_source_js ) {
                return $tag;
            }

            // add the module type to the tag
            return str_replace( '<script ', '<script type="module" ', $tag );
        }

    }

}
