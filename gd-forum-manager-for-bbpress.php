<?php

/*
Plugin Name: GD Forum Manager for bbPress
Plugin URI: https://plugins.dev4press.com/gd-forum-manager-for-bbpress/
Description: Expand how the moderators can manage forum content from the frontend, including forums and topics quick and bulk editing from any page showing list of topics or forums.
Version: 1.1
Author: Milan Petrovic
Author URI: https://www.dev4press.com/
Text Domain: gd-forum-manager-for-bbpress
Private: true

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: milan@gdragon.info)

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

$gdfar_dirname_basic = dirname(__FILE__).'/';
$gdfar_urlname_basic = plugins_url('/gd-forum-manager-for-bbpress/');

define('GDFAR_PATH', $gdfar_dirname_basic);
define('GDFAR_URL', $gdfar_urlname_basic);
define('GDFAR_D4PLIB', $gdfar_dirname_basic.'d4plib/');

require_once(GDFAR_D4PLIB.'core.php');

require_once(GDFAR_PATH.'core/autoload.php');
require_once(GDFAR_PATH.'core/bridge.php');
require_once(GDFAR_PATH.'core/functions.php');

gdfar_settings();
gdfar();

if (D4P_ADMIN) {
    require_once(GDFAR_PATH.'core/admin.php');

    gdfar_admin();
}

if (D4P_AJAX) {
    gdfar_ajax();
}
