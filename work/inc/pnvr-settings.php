<?php
/**
 * Theme Settings Class
 * 
 * Handles the theme settings
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

// pull our field framework
use \KP\WPFieldFramework\Loader;

// make sure we aren't loading in the class multiple times
if( ! class_exists( 'PNVR_Settings' ) ) {

    /**
     * Class PNVR_Settings
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
     */
    class PNVR_Settings {

        /**
         * The field framework
         * @var bool
         */
        private ?\KP\WPFieldFramework\Framework $fw = null;

        /**
         * Class constructor.
         * 
         * Setup the object
         * 
         * @internal
         */
        public function __construct( ) {

            // load up our framework
            $this -> fw = Loader::init( 'pnvr' );

            // the framework's requirements were not met, so there is nothing to build on
            if( ! $this -> fw ) {
                return;
            }

            // add in the theme settings once every post type has been registered
            add_action( 'init', function( ) {

                // add in the theme settings
                $this -> add_theme_settings( );

            }, 999 );
        }

        /**
         * add_theme_settings
         * 
         * Creates the theme settings
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return void Returns nothing
         */
        private function add_theme_settings( ): void {

            // setup the key
            $key = PNVR_OPTION_KEY;

            // create the official options page
            $this -> fw -> addOptionsPage([
                'option_key' => $key,
                'page_title'  => __( 'PodNest Visual Regressor Theme Settings', 'pn-vr' ),
                'menu_title'  => __( 'PN-VR Settings', 'pn-vr'),
                'capability'  => 'list_users',
                'menu_slug'   => 'pnvr-settings',
                'icon_url'    => 'dashicons-vault',
                'position'    => 2,
                'tabs'       => [
                    'security' => $this -> add_security_settings(),
                    'images' => $this -> add_image_settings(),
                    'content' => $this -> add_content_settings(),
                    'performance' => $this -> add_performance_settings(),
                    'smtp' => $this -> add_smtp_settings(),
                    'cookienotice' => $this -> add_cookie_notice_settings(),
                ],
                'save_button' => __( 'Save Theme Settings', 'pn-vr'),
                'footer_text' => __( '<p class="alignright">Copyright &copy; ' . date('Y') . ' <a href="https://kevinpirnie.com" target="_blank">Kevin Pirnie</a></p>', 'pn-vr'),
                'show_export_import' => true,
            ]);

        }

        /**
         * add_smtp_settings
         * 
         * Creates SMTP configuration options
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the sections fields
         */
        private function add_smtp_settings( ): array {

            // return the settings array
            return [
                'title' => __('SMTP', 'pn-vr'),

            ];
        }

        /**
         * add_cookie_notice_settings
         * 
         * Creates a configurable cookie notice
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the sections fields
         */
        private function add_cookie_notice_settings( ): array {

            // return the settings array
            return [
                'title' => __('Cookie Notice', 'pn-vr'),

            ];
        }

        /**
         * add_security_settings
         * 
         * Creates the theme security settings
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the sections fields
         */
        private function add_security_settings(): array {

            // return the settings array
            return [
                'title' => __('Security', 'pn-vr'),
                'sections' => [
                    'security' => [
                        'fields' => [
                            [
                                'id'    => 'pnvr_wp_rest',
                                'type'  => 'radio',
                                'inline' => true,
                                'label' => __( 'REST API?', 'pn-vr' ),
                                'description' => __( 'Should we disable the REST API?', 'pn-vr' ),
                                'options' => [
                                    2 => __('Get Rid of It!', 'pn-vr' ),
                                    1 => __('Only on the Front-End', 'pn-vr' ),
                                    0 => __('No Way!', 'pn-vr' ),
                                ],
                                'default' => 1,
                            ],
                            [
                                'id'    => 'pnvr_app_password',
                                'type'  => 'switch',
                                'label' => __( 'Aplication Passwords?', 'pn-vr' ),
                                'description' => __( 'Should we disable Application Passwords?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => true,
                            ],
                            [
                                'id'    => 'pnvr_remove_feeds',
                                'type'  => 'switch',
                                'label' => __( 'Remove Feeds?', 'pn-vr' ),
                                'description' => __( 'Should we remove all RSS Feeds?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id'    => 'pnvr_remove_rpc',
                                'type'  => 'switch',
                                'label' => __( 'Remove RPC?', 'pn-vr' ),
                                'sublabel' => __( 'Should we remove the XML RPC?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => true,
                            ],
                            [
                                'id'    => 'pnvr_remove_identifiers',
                                'type'  => 'switch',
                                'label' => __( 'Remove Identifiers?', 'pn-vr' ),
                                'description' => __( 'Should we remove the WordPress Identifiers?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id'    => 'pnvr_remove_adminbar',
                                'type'  => 'switch',
                                'label' => __( 'Remove Admin-Bar?', 'pn-vr' ),
                                'description' => __( 'Should we remove the admin bar on the front-end?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => true,
                            ],
                            [
                                'id'    => 'pnvr_remove_commenting',
                                'type'  => 'radio',
                                'inline' => true,
                                'label' => __( 'Remove Commenting?', 'pn-vr' ),
                                'description' => __( 'Should we remove commenting?', 'pn-vr' ),
                                'options' => [
                                    2 => __('Get Rid of It!', 'pn-vr' ),
                                    1 => __('Only for the Selected Post Types!', 'pn-vr' ),
                                    0 => __('No Way!', 'pn-vr' ),
                                ],
                                'default' => 0,
                            ],
                            // needs to be conditional
                            [
                                'id' => 'pnvr_remove_commenting_posttypes',
                                'type' => 'multiselect',
                                'label' => __('Post Types', 'pn-vr'),
                                'description' => __('This will remove the commenting capabilities from the selected post types.', 'pn-vr'),
                                'options' => $this -> get_all_post_types(),
                                'size' => 100,
                                'conditional' => [
                                    'field' => 'pnvr_remove_commenting',
                                    'value' => '1',
                                    'condition' => '==',
                                ],
                            ],
                            
                        ],
                    ],
                ],
            ];
        }

        /**
         * add_image_settings
         * 
         * Create the imagery settings tab
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the sections fields
         */
        private function add_image_settings(): array {

            // return the settings array
            return [
                'title' => __('Imagery', 'pn-vr'),
                'sections' => [
                    'imagery' => [
                        'fields' => [
                            [
                                'id'    => 'pnvr_allow_svg',
                                'type'  => 'switch',
                                'label' => __( 'Allow SVGs?', 'pn-vr' ),
                                'description' => __( 'Should we allow SVG images to be uploaded to the media library?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => true,
                            ],
                            [
                                'id'    => 'pnvr_allow_webm',
                                'type'  => 'switch',
                                'label' => __( 'Allow WEBPs?', 'pn-vr' ),
                                'description' => __( 'Should we allow WEBP imagess to be uploaded to the media library?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => true,
                            ],
                            [
                                'id'    => 'pnvr_allow_convert',
                                'type'  => 'switch',
                                'label' => __( 'Convert Images?', 'pn-vr' ),
                                'description' => __( 'Should we attempt to convert images uploaded to webp format?', 'pn-vr' ),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => true,
                            ],
                            [
                                'id'    => 'pnvr_extra_image_sizes',
                                'type'  => 'repeater',
                                'label' => __( 'Extra Image Sizes', 'pn-vr' ),
                                'sublabel' => __( 'Existing Sizes:<br />' . $this -> get_current_image_sizes(), 'pn-vr'),
                                'description' => __( 'This will add additional image sizes. WordPress will automagically create them on upload.<br /><strong>NOTE: </strong>If you already have images uploaded, you will need to regenerate your thumbnails.', 'pn-vr' ),
                                'fields' => [
                                    [
                                        'id' => 'pnvr_img_size_name',
                                        'type' => 'text',
                                        'label' => __('Size Name', 'pn-vr'),
                                    ],
                                    [
                                        'id' => 'pnvr_img_dimensions',
                                        'type' => 'group',
                                        'fields' => [
                                            [
                                                'id' => 'pnvr_img_width',
                                                'type' => 'text',
                                                'label' => __('Image Width', 'pn-vr'),
                                                'sublabel' => __('in pixels', 'pn-vr'),
                                            ],
                                            [
                                                'id' => 'pnvr_img_height',
                                                'type' => 'text',
                                                'label' => __('Image Height', 'pn-vr'),
                                                'sublabel' => __('in pixels', 'pn-vr'),
                                            ],
                                            [
                                                'id' => 'pnvr_img_crop_vert',
                                                'type' => 'radio',
                                                'label' => __('Crop Vertical', 'pn-vr'),
                                                'options' => [
                                                    0 => __('None', 'pn-vr'),
                                                    1 => __('Top', 'pn-vr'),
                                                    2 => __('Center', 'pn-vr'),
                                                    3 => __('Bottom', 'pn-vr'),                                                    
                                                ],
                                                'default' => 0,
                                            ],
                                            [
                                                'id' => 'pnvr_img_crop_horz',
                                                'type' => 'radio',
                                                'label' => __('Crop Horizontal', 'pn-vr'),
                                                'options' => [
                                                    0 => __('None', 'pn-vr'),
                                                    1 => __('Left', 'pn-vr'),
                                                    2 => __('Center', 'pn-vr'),
                                                    3 => __('Right', 'pn-vr'),                                                    
                                                ],
                                                'default' => 0,
                                            ],
                                        ]
                                    ],
                                ],
                            ],
                            
                        ],
                    ],
                ],
            ];
        }

        /**
         * add_content_settings
         * 
         * Create the content settings tab
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the sections fields
         */
        private function add_content_settings(): array {

            // return the array of the fields we need
            return [
                'title' => __('Content', 'pn-vr'),
                'sections' => [
                    'content' => [
                        'fields' => [
                            [
                                'id' => 'pnvr_search_type',
                                'type' => 'radio',
                                'label' => __('Search Type', 'pn-vr'),
                                'inline' => true,
                                'options' => [
                                    0 => __('Loose (default)', 'pn-vr'),
                                    1 => __('Exact', 'pn-vr'),
                                    2 => __('Sentence', 'pn-vr'),
                                ],
                                'default' => 0,
                            ],
                            [
                                'id' => 'pnvr_revisions',
                                'label' => __('Revisions', 'pn-vr'),
                                'description' => __('How many revisions shoud be kept?', 'pn-vr'),
                                'type' => 'number',
                                'default' => 3,
                            ],
                            [
                                'id' => 'pnvr_allow_inline_css',
                                'type' => 'switch',
                                'label' => __('Allow Inline CSS?', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_inline_css_posttypes',
                                'type' => 'multiselect',
                                'label' => __('Post Types', 'pn-vr'),
                                'description' => __('This will add a textarea to the post types you select, that will allow you to input inlined CSS.', 'pn-vr'),
                                'options' => $this -> get_all_post_types(),
                                'size' => 100,
                                'conditional' => [
                                    'field' => 'pnvr_allow_inline_css',
                                    'value' => true,
                                    'condition' => '==',
                                ],
                            ],
                            [
                                'id' => 'pnvr_allow_inline_js',
                                'type' => 'switch',
                                'label' => __('Allow Inline Javascript?', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_inline_js_posttypes',
                                'type' => 'multiselect',
                                'label' => __('Post Types', 'pn-vr'),
                                'description' => __('This will add a textarea to the post types you select, that will allow you to input inlined javascript.', 'pn-vr'),
                                'options' => $this -> get_all_post_types(),
                                'size' => 100,
                                'conditional' => [
                                    'field' => 'pnvr_allow_inline_js',
                                    'value' => true,
                                    'condition' => '==',
                                ],
                            ],
                        ],
                    ]
                ]
            ];
        }

        /**
         * add_performance_settings
         * 
         * Create the performance settings tab
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the sections fields
         */
        private function add_performance_settings(): array {

            // return the array of the fields
            return [
                'title' => __('Performance', 'pn-vr'),
                'sections' => [
                    'performance' => [
                        'fields' => [
                            [
                                'id' => 'pnvr_perf_remove_qs',
                                'type' => 'switch',
                                'label' => __('Remove Querystrings?', 'pn-vr'),
                                'description' => __('This will attempt to remove querystrings from all static resources.', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_perf_force_js_footer',
                                'type' => 'switch',
                                'label' => __('JS to Footer?', 'pn-vr'),
                                'description' => __('This will attempt to force all javascript resrouces to load in the site footer.', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_perf_defer_js',
                                'type' => 'switch',
                                'label' => __('Defer JS?', 'pn-vr'),
                                'description' => __('This will attempt to mark all javascript resources as defered.', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_perf_apply_pre',
                                'type' => 'switch',
                                'label' => __('Apply the Pre\'s?', 'pn-vr'),
                                'sublabel' => __('Pre-Load, Pre-Render, and Pre-Fetch', 'pn-vr'),
                                'description' => __('This will attempt inject pre-load, pre-render, and pre-fetch meta-tags for all external resources.', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_perf_apply_ip',
                                'type' => 'switch',
                                'label' => __('Apply instant.page?', 'pn-vr'),
                                'description' => __('This will attempt to inject the instant.page library. See here for more info: <a href="https://instant.page/" target="_blank">https://instant.page/</a>', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_perf_slow_hb',
                                'type' => 'switch',
                                'label' => __('Slow Down Heartbeat?', 'pn-vr'),
                                'description' => __('This will attempt to slow down WordPress\'s heartbeat. This will change it from the default 15 seconds to 300 seconds.', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                            [
                                'id' => 'pnvr_perf_rmove_emojis',
                                'type' => 'switch',
                                'label' => __('Remove Emojis?', 'pn-vr'),
                                'description' => __('This will attempt to remove all WordPress default emoji scripts and stylesheets.', 'pn-vr'),
                                'on_label'  => __('Yes', 'pn-vr'),
                                'off_label' => __('No', 'pn-vr'),
                                'default' => false,
                            ],
                        ],
                    ]
                ],
            ];
        }

        /**
         * get_all_post_types
         * 
         * Get all of the sites post types
         * built-in and custom
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * 
         * @return array Returns the post types
         */
        private function get_all_post_types(): array {

            // hold the return
            $ret = [];
            
            // retrieve them
            $post_types = get_post_types( [], 'objects' );

            // looop over all of them
            foreach( $post_types as $pt ) {
                $labels = get_post_type_labels($pt );
                $ret[esc_attr( $pt->name )] =  esc_html__( $labels->name, 'pn-vr' );
            }

            // return the array
            return $ret;
        }


        /** 
         * get_current_image_sizes
         * 
         * Gets a list of all registered Wordpress image sizes
         * 
         * @since 7.3
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Framework
         * 
         * @return string Returns a formattted string of all currently registered image sizes
         * 
        */
        private function get_current_image_sizes( ) : ?string {

            // hold our return
            $_ret = '';

            // get the wp global for additional image sizes
            global $_wp_additional_image_sizes;

            // let's setup our processing variables
            $sizes = array( );
            $rSizes = array( );

            // get the existing image sizes
            $_get_image_sizes = get_intermediate_image_sizes( );

            // loop over them so we can setup out display
            foreach ( $_get_image_sizes as $s ) {

                // fire up
                $sizes[$s] = array( 0, 0 );
                
                // let's check for the standard sizes
                if ( in_array( $s, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                    
                    $sizes[$s][0] = get_option( $s . '_size_w' );
                    $sizes[$s][1] = get_option( $s . '_size_h' );
                } else {

                    if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[$s] ) )
                        $sizes[$s] = array( $_wp_additional_image_sizes[$s]['width'], $_wp_additional_image_sizes[$s]['height'], );
                }
            }

            // make sure there actually are sizes
            if( $sizes ) {

                // setup the output string
                foreach ( $sizes as $size => $atts ) {
                    $_ret .= '&nbsp;<strong>' . $size . ':</strong> ' . '(' . implode( 'x', $atts ) . ')<br />';
                }
                
            }
            
            // return the sizes string
            return $_ret;

        }

    }

}