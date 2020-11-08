<?php

namespace Dev4Press\Plugin\GDFAR\Admin;

use Dev4Press\Core\Admin\Submenu\Plugin as BasePlugin;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin extends BasePlugin {
    public $plugin = 'gd-forum-manager-for-bbpress';
    public $plugin_prefix = 'gdfar';
    public $plugin_menu = 'GD Forum Manager';
    public $plugin_title = 'GD Forum Manager for bbPress';

    public function constructor() {
        $this->url = GDFAR_URL;
        $this->path = GDFAR_PATH;
    }

    public function after_setup_theme() {
        $this->setup_items = array(
            'install' => array(
                'title' => __("Install", "gd-forum-manager-for-bbpress"),
                'icon' => 'ui-traffic',
                'type' => 'setup',
                'info' => __("Before you continue, make sure plugin installation was successful.", "gd-forum-manager-for-bbpress"),
                'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\Install'),
            'update' => array(
                'title' => __("Update", "gd-forum-manager-for-bbpress"),
                'icon' => 'ui-traffic',
                'type' => 'setup',
                'info' => __("Before you continue, make sure plugin was successfully updated.", "gd-forum-manager-for-bbpress"),
                'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\Update')
        );

        $this->menu_items = array(
            'dashboard' => array(
                'title' => __("Getting Started", "gd-forum-manager-for-bbpress"),
                'icon' => 'ui-home',
                'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\Dashboard'),
            'about' => array(
                'title' => __("About", "gd-forum-manager-for-bbpress"),
                'icon' => 'ui-info',
                'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\About')
        );
    }

    public function svg_icon() {
        return gdfon()->svg_icon;
    }

    public function run_getback() {
        new GetBack($this);
    }

    public function run_postback() {
        new PostBack($this);
    }

    public function message_process($code, $msg) {
        return $msg;
    }

    public function settings() {
        return gdfar_settings();
    }

    public function settings_definitions() {
        return null;
    }
}
