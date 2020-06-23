<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\Plugins\Settings as BaseSettings;

if (!defined('ABSPATH')) {
    exit;
}

class Settings extends BaseSettings {
    public $base = 'gdfar';

    public $settings = array(
        'core' => array(
            'activated' => 0
        ),
        'settings' => array(
            'moderators' => true,
            'forum' => true,
            'topic' => true,
            'small_screen_always_show' => false
        )
    );

    protected function constructor() {
        $this->info = new Information();

        add_action('gdfar_load_settings', array($this, 'init'), 2);
    }

    protected function _name($name) {
        return 'dev4press_'.$this->info->code.'_'.$name;
    }
}