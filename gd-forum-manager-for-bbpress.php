<?php

/*
Plugin Name:       GD Forum Manager for bbPress
Plugin URI:        https://plugins.dev4press.com/gd-forum-manager-for-bbpress/
Description:       Expand how the moderators can manage forum content from the frontend, including forums and topics quick and bulk editing from any page showing list of topics or forums.
Author:            Milan Petrovic
Author URI:        https://www.dev4press.com/
Text Domain:       gd-forum-manager-for-bbpress
Version:           2.1.1
Requires at least: 5.3
Tested up to:      5.9
Requires PHP:      7.2
License:           GPLv3 or later
License URI:       http://www.gnu.org/licenses/gpl-3.0.html

== Copyright ==
Copyright 2008 - 2022 Milan Petrovic (email: support@dev4press.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

use Dev4Press\v37\WordPress;

$gdfar_dirname_basic = dirname( __FILE__ ) . '/';
$gdfar_urlname_basic = plugins_url( '/', __FILE__ );

define( 'GDFAR_PATH', $gdfar_dirname_basic );
define( 'GDFAR_URL', $gdfar_urlname_basic );
define( 'GDFAR_D4PLIB', $gdfar_dirname_basic . 'd4plib/' );

require_once( GDFAR_D4PLIB . 'core.php' );

require_once( GDFAR_PATH . 'core/autoload.php' );
require_once( GDFAR_PATH . 'core/bridge.php' );
require_once( GDFAR_PATH . 'core/functions.php' );

gdfar_settings();
gdfar();

if ( WordPress::instance()->is_admin() ) {
	require_once( GDFAR_PATH . 'core/admin.php' );

	gdfar_admin();
}

if ( WordPress::instance()->is_ajax() ) {
	gdfar_ajax();
}
