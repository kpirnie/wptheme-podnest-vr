<?php
/**
 * Helper Functions
 * 
 * Global helper functions for the theme
 * 
 * @since 8.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package PodNest Visual Regressor
 * 
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

if( ! function_exists( 'pnvr_get_option' ) ) {
    /**
     * Get a theme option
     * 
     * @param string $key Option key
     * @param mixed $default Default value
     * @return mixed
     */
    function pnvr_get_option( string $key, mixed $default = null ): mixed {
        return PNVR_Main::get_option( $key, $default );
    }
}

if( ! function_exists( 'pnvr_opt' ) ) {
    /**
     * Get a Customizer text value
     * 
     * @param string $key Mod key without the prefix
     * @param string $fallback Default value
     * @return string The raw value, escape on output
     */
    function pnvr_opt( string $key, string $fallback = '' ): string {
        return (string) get_theme_mod( PNVR_Customizer::PREFIX . $key, $fallback );
    }
}

if( ! function_exists( 'pnvr_contact_url' ) ) {
    /**
     * Get the contact page URL, optionally with a plan to pre-fill the subject
     * 
     * @param string $plan The plan query value (trial, paid, source, etc.)
     * @return string The raw URL, escape on output
     */
    function pnvr_contact_url( string $plan = '' ): string {

        // the chosen page, falling back to /contact/
        $page_id = absint( get_theme_mod( PNVR_Customizer::PREFIX . 'contact_page', 0 ) );
        $url = $page_id ? get_permalink( $page_id ) : false;
        $url = $url ?: home_url( '/contact/' );

        // add the plan
        $plan = sanitize_key( $plan );
        return $plan ? add_query_arg( 'plan', $plan, $url ) : $url;
    }
}

if( ! function_exists( 'pnvr_logo_url' ) ) {
    /**
     * Get the PodNest logo URL from the CDN
     * 
     * @param string $ext Logo file extension (svg, png, ico)
     * @return string
     */
    function pnvr_logo_url( string $ext = 'svg' ): string {
        return sprintf( '%s/logos/podnest.%s', PNVR_CDN_URL, sanitize_key( $ext ) );
    }
}

if( ! function_exists( 'pnvr_framework_class' ) ) {
    /**
     * Get Tailwind CSS classes
     * 
     * Maps common class purposes to Tailwind utility class names
     * 
     * @param string $purpose Class purpose (container, row, col, btn, etc.)
     * @param array $options Additional options
     * @return string
     */
    function pnvr_framework_class( string $purpose, array $options = [] ): string {
        $class_map = [
            'container' => 'container mx-auto px-4',
            'container-fluid' => 'w-full px-4',
            'row' => 'flex flex-wrap -mx-4',
            'col' => 'w-full px-4',
            'col-12' => 'w-full px-4',
            'col-6' => 'w-full md:w-1/2 px-4',
            'col-4' => 'w-full md:w-1/3 px-4',
            'col-3' => 'w-full md:w-1/4 px-4',
            'btn' => 'px-4 py-2 rounded-pn',
            'btn-primary' => 'px-4 py-2 bg-pn-accent text-pn-on-accent rounded-pn hover:bg-pn-accent-dark',
            'btn-secondary' => 'px-4 py-2 bg-pn-card-alt text-pn-text border border-pn-border-bright rounded-pn hover:bg-pn-border',
            'card' => 'bg-pn-card border border-pn-border rounded-pn-lg shadow',
            'card-body' => 'p-6',
            'form-control' => 'w-full px-3 py-2 bg-pn-input text-pn-text border border-pn-border rounded-pn focus:outline-none focus:ring-2 focus:ring-pn-accent',
            'form-group' => 'mb-4',
            'nav' => 'flex',
            'nav-item' => '',
            'nav-link' => 'px-3 py-2',
            'alert' => 'relative p-4 rounded-pn',
            'alert-success' => 'relative p-4 bg-pn-success-dim text-pn-success border border-pn-success-soft rounded-pn',
            'alert-danger' => 'relative p-4 bg-pn-danger-dim text-pn-danger border border-pn-danger-soft rounded-pn',
            'alert-warning' => 'relative p-4 bg-pn-warning-dim text-pn-warning border border-pn-warning-soft rounded-pn',
            'hidden' => 'hidden',
            'text-center' => 'text-center',
            'text-left' => 'text-left',
            'text-right' => 'text-right',
        ];

        // Allow filtering of class map
        $class_map = apply_filters( 'pnvr_framework_class_map', $class_map );

        return $class_map[ $purpose ] ?? '';
    }
}

if( ! function_exists( 'pnvr_container' ) ) {
    /**
     * Output a container opening tag
     * 
     * @param bool $fluid Whether to use fluid container
     * @param string $extra_classes Additional classes
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_container( bool $fluid = false, string $extra_classes = '', bool $echo = true ): string {
        $class = $fluid ? pnvr_framework_class( 'container-fluid' ) : pnvr_framework_class( 'container' );
        $class .= $extra_classes ? ' ' . $extra_classes : '';
        $class = trim( $class );
        
        $html = '<div' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}

if( ! function_exists( 'pnvr_container_end' ) ) {
    /**
     * Output a container closing tag
     * 
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_container_end( bool $echo = true ): string {
        $html = '</div>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}

if( ! function_exists( 'pnvr_row' ) ) {
    /**
     * Output a row opening tag
     * 
     * @param string $extra_classes Additional classes
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_row( string $extra_classes = '', bool $echo = true ): string {
        $class = pnvr_framework_class( 'row' );
        $class .= $extra_classes ? ' ' . $extra_classes : '';
        $class = trim( $class );

        $html = '<div' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}

if( ! function_exists( 'pnvr_row_end' ) ) {
    /**
     * Output a row closing tag
     * 
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_row_end( bool $echo = true ): string {
        $html = '</div>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}

if( ! function_exists( 'pnvr_col' ) ) {
    /**
     * Output a column opening tag
     * 
     * @param int $size Column size (12, 6, 4, 3, or 0 for auto)
     * @param string $extra_classes Additional classes
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_col( int $size = 0, string $extra_classes = '', bool $echo = true ): string {
        $class_key = $size > 0 ? 'col-' . $size : 'col';
        $class = pnvr_framework_class( $class_key );
        $class .= $extra_classes ? ' ' . $extra_classes : '';
        $class = trim( $class );
        
        $html = '<div' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}

if( ! function_exists( 'pnvr_col_end' ) ) {
    /**
     * Output a column closing tag
     * 
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_col_end( bool $echo = true ): string {
        $html = '</div>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}

if( ! function_exists( 'pnvr_btn_class' ) ) {
    /**
     * Get button classes
     * 
     * @param string $type Button type (primary, secondary, etc.)
     * @param string $extra_classes Additional classes
     * @return string
     */
    function pnvr_btn_class( string $type = 'primary', string $extra_classes = '' ): string {
        $class = pnvr_framework_class( 'btn-' . $type );
        if( ! $class ) {
            $class = pnvr_framework_class( 'btn' );
        }
        $class .= $extra_classes ? ' ' . $extra_classes : '';
        return trim( $class );
    }
}

if( ! function_exists( 'pnvr_alert' ) ) {
    /**
     * Output an alert/notification
     * 
     * @param string $message Alert message
     * @param string $type Alert type (success, danger, warning)
     * @param bool $dismissible Whether alert is dismissible
     * @param bool $echo Whether to echo or return
     * @return string
     */
    function pnvr_alert( string $message, string $type = 'success', bool $dismissible = true, bool $echo = true ): string {
        $class = pnvr_framework_class( 'alert-' . $type );

        // Dismissible alerts are wired up by the Alert component
        if( $dismissible ) {
            $class .= ' alert-dismissible';
        }
        
        $html = '<div class="' . esc_attr( trim( $class ) ) . '" role="alert">';
        
        // Dismissible button
        if( $dismissible ) {
            $html .= '<button type="button" class="alert-close" aria-label="' . esc_attr__( 'Close', 'pn-vr' ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>';
        }
        
        $html .= wp_kses_post( $message );
        $html .= '</div>';
        
        if( $echo ) {
            echo $html;
        }
        
        return $html;
    }
}