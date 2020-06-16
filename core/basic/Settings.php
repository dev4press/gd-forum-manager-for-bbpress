<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\Plugins\Settings as BaseSettings;

if (!defined('ABSPATH')) {
    exit;
}

class Settings extends BaseSettings {
    public $base = 'gdfon';

    public $settings = array(
        'core' => array(
            'activated' => 0,
            'notice_gdfon_hide' => false,
            'notice_gdpol_hide' => false,
            'notice_gdtox_hide' => false,
            'notice_gdbbx_hide' => false,
            'notice_gdpos_hide' => false,
            'notice_gdqnt_hide' => false,
            'notice_gdmed_hide' => false
        ),
        'settings' => array(
            'moderators' => true,
            'forum' => true,
            'topic' => true
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