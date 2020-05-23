<?php

use Dev4Press\Plugin\GDFAR\Basic\DB;
use Dev4Press\Plugin\GDFAR\Basic\Plugin;
use Dev4Press\Plugin\GDFAR\Admin\Plugin as AdminPlugin;
use Dev4Press\Plugin\GDFAR\Basic\Settings;

if (!defined('ABSPATH')) {
    exit;
}

/** @return \Dev4Press\Plugin\GDFAR\Basic\Plugin */
function gdfar() {
    return Plugin::instance();
}

/** @return \Dev4Press\Plugin\GDFAR\Basic\Settings */
function gdfar_settings() {
    return Settings::instance();
}

/** @return \Dev4Press\Plugin\GDFAR\Basic\DB */
function gdfar_db() {
    return DB::instance();
}

/** @return \Dev4Press\Plugin\GDFAR\Admin\Plugin */
function gdfar_admin() {
    return AdminPlugin::instance();
}
