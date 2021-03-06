<?php
/*
	Plugin Name: Core Functionality
	Plugin URI: http://redblue.us
	Description: Functionality specific to your site which should be maintained even if you switch themes
	Version: 1.0.2
    Author: Jon Schroeder
    Author URI: http://redblue.us

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
*/

// Plugin Directory 
define( 'RBC_DIR', dirname( __FILE__ ) );

// Add a semi-admin user role called "Webmaster" with access to everything
include_once( 'lib/roles.php' );

// Dashboard customization: hide meaningless dashboard widgets, organize the menu, and hide unnecessary items from the webmaster role
include_once( 'lib/dashboard_customization.php' );

// Register taxonomies
// include_once( 'lib/taxonomies.php' );

// Custom meta (using the CMB library)
// include_once( 'lib/metabox/metabox.php' );

// General
include_once( 'lib/general.php' );

