<?php

namespace Dev4Press\Plugin\GDFAR\Traits;

if (!defined('ABSPATH')) {
    exit;
}

trait Panel {
    /** @param $admin \Dev4Press\Plugin\GDFAR\Admin\Plugin */
    protected function local_enqueue_scripts($admin) {
        $admin->js('admin');
    }
}
