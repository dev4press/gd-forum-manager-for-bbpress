<?php

namespace Dev4Press\Plugin\GDFAR\bbPress;

if (!defined('ABSPATH')) {
    exit;
}

class Integration {
    public $theme_package = 'default';

    public function __construct() {
        if (get_option('_bbp_theme_package_id') == 'quantum') {
            $this->theme_package = 'quantum';
        }
    }
}
