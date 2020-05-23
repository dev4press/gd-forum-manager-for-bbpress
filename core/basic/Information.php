<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\Plugins\Information as BaseInformation;

if (!defined('ABSPATH')) {
    exit;
}

class Information extends BaseInformation {
    public $code = 'gd-forum-manager-for-bbpress';

    public $version = '1.0';
    public $build = 1;
    public $edition = 'pro';
    public $status = 'stable';
    public $updated = '2020.05.29';
    public $released = '2020.04.29';

    public $is_bbpress_plugin = true;

    public function __construct() {
        $this->plugins['bbpress'] = '2.5.12';
    }
}
