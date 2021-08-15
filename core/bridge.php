<?php

use Dev4Press\Plugin\GDFAR\Admin\Plugin as AdminPlugin;
use Dev4Press\Plugin\GDFAR\Basic\AJAX;
use Dev4Press\Plugin\GDFAR\Basic\Plugin;
use Dev4Press\Plugin\GDFAR\Basic\Settings;
use Dev4Press\Plugin\GDFAR\Manager\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdfar() : Plugin{
	return Plugin::instance();
}

function gdfar_settings() : Settings {
	return Settings::instance();
}

function gdfar_admin() : AdminPlugin {
	return AdminPlugin::instance();
}

function gdfar_ajax() : AJAX {
	return AJAX::instance();
}

function gdfar_render() : Render {
	return Render::instance();
}
