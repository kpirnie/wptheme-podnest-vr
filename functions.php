<?php
/** 
 * PodNest Visual Regressor
 * 
 * Marketing theme for PodNest Visual Regressor, built on Tailwind CSS
 * 
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @copyright 2025 Kevin Pirnie
 * 
 * @since 1.0.1
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package PodNest Visual Regressor
 * 
*/

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// include the autoloader
include_once dirname( __FILE__ ) . '/vendor/autoload.php';

// initialize the them
PNVR_Main::init( );
