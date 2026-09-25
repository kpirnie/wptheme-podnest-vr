<?php
/** 
 * 
 * Icons Class
 * 
 * The theme's inline SVG icon set, used by the feature cards and the static sections
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
if( ! class_exists( 'PNVR_Icons' ) ) {

    /** 
     * PNVR_Icons
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
    */
    class PNVR_Icons {

        /**
         * The icons: key => [ label, svg inner markup ]
         * @var array
         */
        private const ICONS = [
            'compare' => [ 'Compare', '<rect x="3" y="4" width="8" height="16" rx="1.5"/><rect x="13" y="4" width="8" height="16" rx="1.5"/><path d="M12 2v20"/>' ],
            'pixels' => [ 'Pixel diff', '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/>' ],
            'ai' => [ 'AI', '<path d="M12 3l1.8 4.7 4.7 1.8-4.7 1.8L12 16l-1.8-4.7-4.7-1.8 4.7-1.8z"/><path d="M19 15l.8 2.2 2.2.8-2.2.8L19 21l-.8-2.2-2.2-.8 2.2-.8z"/>' ],
            'content' => [ 'Content check', '<path d="M4 6h16M4 12h10M4 18h7"/><path d="M15 17l2 2 4-4"/>' ],
            'camera' => [ 'Capture', '<path d="M4 8h3l2-3h6l2 3h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="4"/>' ],
            'spider' => [ 'Spider', '<circle cx="12" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="M12 7v4M12 11l-5.5 6.5M12 11l5.5 6.5"/>' ],
            'report' => [ 'Report', '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>' ],
            'mail' => [ 'Email', '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>' ],
            'shield' => [ 'Shield', '<path d="M12 3l8 3v6c0 4.5-3.4 8.3-8 9-4.6-.7-8-4.5-8-9V6z"/><path d="M9 12l2 2 4-4"/>' ],
            'users' => [ 'Users', '<circle cx="9" cy="8" r="3"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><circle cx="17" cy="9" r="2.5"/><path d="M16 14.2c2.8.4 5 2.8 5 5.8"/>' ],
            'dashboard' => [ 'Dashboard', '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>' ],
            'lock' => [ 'Lock', '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>' ],
            'key' => [ 'Key', '<circle cx="8" cy="15" r="4"/><path d="M10.8 12.2L20 3M16 7l3 3M14 9l2 2"/>' ],
            'clock' => [ 'Clock', '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>' ],
            'link' => [ 'Signed link', '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>' ],
            'list' => [ 'Audit list', '<path d="M9 6h11M9 12h11M9 18h11"/><circle cx="4.5" cy="6" r="1"/><circle cx="4.5" cy="12" r="1"/><circle cx="4.5" cy="18" r="1"/>' ],
            'container' => [ 'Container', '<path d="M12 3l8 4.5v9L12 21l-8-4.5v-9z"/><path d="M12 12l8-4.5M12 12v9M12 12L4 7.5"/>' ],
            'globe' => [ 'Network', '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>' ],
            'refresh' => [ 'Live refresh', '<path d="M20 11a8 8 0 1 0-2.3 5.7"/><path d="M20 4v7h-7"/>' ],
            'check' => [ 'Pass', '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>' ],
            'hand' => [ 'Manual pass', '<path d="M8 13V5.5a1.5 1.5 0 0 1 3 0V12"/><path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0V12"/><path d="M14 10.5a1.5 1.5 0 0 1 3 0V12"/><path d="M17 11.5a1.5 1.5 0 0 1 3 0V16a5 5 0 0 1-5 5h-2.5a5 5 0 0 1-4-2L5 15.5a1.5 1.5 0 0 1 2.3-2L8 14"/>' ],
        ];

        /**
         * options
         * 
         * The icons as a key => label list, for select fields
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return array Returns the icon options
         */
        public static function options( ): array {

            // hold the return, with a none option first
            $ret = [ '' => __( 'None', 'pn-vr' ) ];

            // loop the icons
            foreach( self::ICONS as $key => [ $label ] ) {
                $ret[ $key ] = $label;
            }

            // return them
            return $ret;
        }

        /**
         * svg
         * 
         * Get an icon's full SVG markup
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param string $key The icon key
         * @param string $class Extra class(es) for the svg
         * @return string Returns the svg, or an empty string for an unknown key
         */
        public static function svg( string $key, string $class = '' ): string {

            // unknown icon
            if( ! isset( self::ICONS[ $key ] ) ) {
                return '';
            }

            // return the markup, the paths are static and trusted
            return sprintf(
                '<svg class="%1$s" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
                esc_attr( trim( sprintf( 'pnvr-icon %s', $class ) ) ),
                self::ICONS[ $key ][1]
            );
        }

    }

}
