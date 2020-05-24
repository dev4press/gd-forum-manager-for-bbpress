<?php

namespace Dev4Press\Plugin\GDFAR\bbPress;

if (!defined('ABSPATH')) {
    exit;
}

class Integration {
    public $_queued = false;

    public function __construct() {
        if (is_user_logged_in()) {
            add_action('bbp_init', array($this, 'init'));
        }
    }

    public function can_moderate() {
        return current_user_can('moderate');
    }

    public function init() {
        if ($this->can_moderate()) {
            add_action('bbp_theme_before_topic_title', array($this, 'controls'), 1);
        }
    }

    public function enqueue() {
        $this->_queued = true;

        wp_enqueue_style('gdfar-manager');
        wp_enqueue_script('gdfar-manager');

        wp_localize_script('gdfar-manager', 'gdfar_manager_data', array(

        ));

        do_action('gdfar_plugin_enqueue_scripts');
    }

    public function controls() {
        $topic_id = absint(bbp_get_topic_id());

        echo '<div class="gdfar-ctrl-wrapper" data-topic="'.$topic_id.'">';
            echo '<input type="checkbox" class="gdfar-ctrl-checkbox" />';
            echo '<a href="#" class="gdfar-ctrl-edit">'.__("edit").'</a>';
        echo '</div>';

        if (!$this->_queued) {
            $this->enqueue();

            add_action('wp_footer', array($this, 'modal'));
        }
    }

    public function modal() {
        require_once(GDFAR_PATH.'forms/manager/modal-edit.php');
        require_once(GDFAR_PATH.'forms/manager/modal-bulk.php');
    }
}
