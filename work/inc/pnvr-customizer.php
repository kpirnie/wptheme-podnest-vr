<?php
/** 
 * 
 * Customizer Class
 * 
 * Registers the theme's Customizer sections, settings, and controls
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
if( ! class_exists( 'PNVR_Customizer' ) ) {

    /** 
     * PNVR_Customizer
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
    */
    class PNVR_Customizer {

        /**
         * Theme mod prefix
         * @var string
         */
        public const PREFIX = 'pnvr_';

        /**
         * register
         * 
         * Adds the theme's sections, settings, and controls to the Customizer
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @return void Returns nothing
         */
        public static function register( WP_Customize_Manager $c ): void {

            // the sections
            self::hero_section( $c );
            self::contact_section( $c );
            self::seo_section( $c );
        }

        /**
         * hero_section
         * 
         * Registers the hero copy, calls to action, and comparison slider images
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @return void Returns nothing
         */
        private static function hero_section( WP_Customize_Manager $c ): void {

            // the section
            $c -> add_section( 'pnvr_hero', [
                'title' => __( 'Hero Section', 'pn-vr' ),
                'priority' => 30,
            ] );

            // copy
            self::add_text( $c, 'hero_badge_text', __( 'Badge text', 'pn-vr' ), 'pnvr_hero', 'Pixel Diff &middot; Content Checks &middot; AI Verdicts' );
            self::add_text( $c, 'hero_title_line1', __( 'Title line 1', 'pn-vr' ), 'pnvr_hero', 'Catch visual regressions' );
            self::add_text( $c, 'hero_title_line2', __( 'Title line 2 (gradient)', 'pn-vr' ), 'pnvr_hero', 'before your users do.' );
            self::add_textarea( $c, 'hero_description', __( 'Description', 'pn-vr' ), 'pnvr_hero', 'Screenshot two URLs, compare them pixel for pixel, check the content you care about is on both, and let an AI judge whether what changed is a real regression or just a rotating advert.' );

            // calls to action
            self::add_text( $c, 'hero_cta_primary', __( 'Primary CTA text', 'pn-vr' ), 'pnvr_hero', 'Start a Free Trial' );
            self::add_text( $c, 'hero_cta_primary_plan', __( 'Primary CTA plan (the contact form ?plan= value)', 'pn-vr' ), 'pnvr_hero', 'trial' );
            self::add_text( $c, 'hero_cta_secondary', __( 'Secondary CTA text', 'pn-vr' ), 'pnvr_hero', 'See How It Works' );
            self::add_url( $c, 'hero_cta_secondary_url', __( 'Secondary CTA URL', 'pn-vr' ), 'pnvr_hero', '#how-it-works' );

            // comparison slider
            self::add_image( $c, 'hero_image_before', __( 'Slider: baseline image (left)', 'pn-vr' ), 'pnvr_hero' );
            self::add_image( $c, 'hero_image_after', __( 'Slider: candidate image (right)', 'pn-vr' ), 'pnvr_hero' );
            self::add_text( $c, 'hero_label_before', __( 'Slider: baseline label', 'pn-vr' ), 'pnvr_hero', 'Baseline' );
            self::add_text( $c, 'hero_label_after', __( 'Slider: candidate label', 'pn-vr' ), 'pnvr_hero', 'Candidate' );
        }

        /**
         * contact_section
         * 
         * Registers the contact page every call to action points at, the support center, and the reCAPTCHA keys
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @return void Returns nothing
         */
        private static function contact_section( WP_Customize_Manager $c ): void {

            // the section
            $c -> add_section( 'pnvr_contact', [
                'title' => __( 'Contact', 'pn-vr' ),
                'priority' => 48,
            ] );

            // the contact page
            $c -> add_setting( self::PREFIX . 'contact_page', [
                'default' => 0,
                'sanitize_callback' => 'absint',
            ] );
            $c -> add_control( self::PREFIX . 'contact_page', [
                'label' => __( 'Contact page', 'pn-vr' ),
                'description' => __( 'Every call to action links here, with a ?plan= value to pre-fill the subject.', 'pn-vr' ),
                'section' => 'pnvr_contact',
                'type' => 'dropdown-pages',
            ] );

            // where customers get support
            self::add_url( $c, 'support_url', __( 'Support center URL', 'pn-vr' ), 'pnvr_contact', 'https://support.podnest.us/' );

            // the optional reCAPTCHA v3 keys
            self::add_text( $c, 'recaptcha_site_key', __( 'reCAPTCHA v3 site key', 'pn-vr' ), 'pnvr_contact' );
            self::add_text( $c, 'recaptcha_secret_key', __( 'reCAPTCHA v3 secret key', 'pn-vr' ), 'pnvr_contact' );
        }

        /**
         * seo_section
         * 
         * Registers the pages the structured data points at
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @return void Returns nothing
         */
        private static function seo_section( WP_Customize_Manager $c ): void {

            // the section
            $c -> add_section( 'pnvr_seo', [
                'title' => __( 'Structured Data', 'pn-vr' ),
                'priority' => 49,
            ] );

            // the license page
            $c -> add_setting( self::PREFIX . 'license_page', [
                'default' => 0,
                'sanitize_callback' => 'absint',
            ] );
            $c -> add_control( self::PREFIX . 'license_page', [
                'label' => __( 'License page', 'pn-vr' ),
                'description' => __( 'The Source Code license. The front page schema links to it as the software license.', 'pn-vr' ),
                'section' => 'pnvr_seo',
                'type' => 'dropdown-pages',
            ] );
        }

        /**
         * add_text
         * 
         * Adds a text setting and control pair
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @param string $key The mod key without the prefix
         * @param string $label The control label
         * @param string $section The section ID
         * @param string $default The default value
         * @return void Returns nothing
         */
        private static function add_text( WP_Customize_Manager $c, string $key, string $label, string $section, string $default = '' ): void {

            // the setting
            $c -> add_setting( self::PREFIX . $key, [
                'default' => $default,
                'sanitize_callback' => 'sanitize_text_field',
            ] );

            // the control
            $c -> add_control( self::PREFIX . $key, [
                'label' => $label,
                'section' => $section,
                'type' => 'text',
            ] );
        }

        /**
         * add_textarea
         * 
         * Adds a textarea setting and control pair
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @param string $key The mod key without the prefix
         * @param string $label The control label
         * @param string $section The section ID
         * @param string $default The default value
         * @return void Returns nothing
         */
        private static function add_textarea( WP_Customize_Manager $c, string $key, string $label, string $section, string $default = '' ): void {

            // the setting
            $c -> add_setting( self::PREFIX . $key, [
                'default' => $default,
                'sanitize_callback' => 'sanitize_textarea_field',
            ] );

            // the control
            $c -> add_control( self::PREFIX . $key, [
                'label' => $label,
                'section' => $section,
                'type' => 'textarea',
            ] );
        }

        /**
         * add_url
         * 
         * Adds a URL setting and control pair
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @param string $key The mod key without the prefix
         * @param string $label The control label
         * @param string $section The section ID
         * @param string $default The default value
         * @return void Returns nothing
         */
        private static function add_url( WP_Customize_Manager $c, string $key, string $label, string $section, string $default = '' ): void {

            // the setting
            $c -> add_setting( self::PREFIX . $key, [
                'default' => $default,
                'sanitize_callback' => 'esc_url_raw',
            ] );

            // the control
            $c -> add_control( self::PREFIX . $key, [
                'label' => $label,
                'section' => $section,
                'type' => 'url',
            ] );
        }

        /**
         * add_image
         * 
         * Adds an image setting, stored as an attachment ID, and its media control
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param WP_Customize_Manager $c The Customizer manager
         * @param string $key The mod key without the prefix
         * @param string $label The control label
         * @param string $section The section ID
         * @return void Returns nothing
         */
        private static function add_image( WP_Customize_Manager $c, string $key, string $label, string $section ): void {

            // the setting
            $c -> add_setting( self::PREFIX . $key, [
                'default' => 0,
                'sanitize_callback' => 'absint',
            ] );

            // the control
            $c -> add_control( new WP_Customize_Media_Control( $c, self::PREFIX . $key, [
                'label' => $label,
                'section' => $section,
                'mime_type' => 'image',
            ] ) );
        }

    }

}
