<?php
/**
 * Plugin Name:       forumManager for bbPress
 * Plugin URI:        https://www.dev4press.com/plugins/gd-forum-manager-for-bbpress/
 * Description:       Expand how the moderators can manage forum and topics content from the frontend, from any page showing the list of topics or forums.
 * Author:            Milan Petrovic
 * Author URI:        https://www.dev4press.com/
 * Text Domain:       gd-forum-manager-for-bbpress
 * Version:           3.1
 * Requires at least: 6.4
 * Tested up to:      7.1
 * Requires PHP:      8.0
 * Requires Plugins:  bbpress
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 *
 * == Copyright ==
 * Copyright 2008 - 2026 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>
 */

use Dev4Press\v56\WordPress;

define( 'GDFAR_FILE', __FILE__ );
define( 'GDFAR_PATH', __DIR__ . '/' );
define( 'GDFAR_URL', plugins_url( '/', __FILE__ ) );

require_once GDFAR_PATH . 'vendor/autoload.php';

require_once GDFAR_PATH . 'vendor/dev4press/library/core.php';

require_once GDFAR_PATH . 'core/autoload.php';
require_once GDFAR_PATH . 'core/bridge.php';
require_once GDFAR_PATH . 'core/functions.php';

gdfar_settings();
gdfar();

if ( WordPress::instance()->is_admin() ) {
	require_once GDFAR_PATH . 'core/admin.php';

	gdfar_admin();
}

if ( WordPress::instance()->is_ajax() ) {
	gdfar_ajax();
}
