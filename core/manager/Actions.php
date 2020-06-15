<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

class Actions {
    private $_actions = array();

    public function __construct() {
        add_action('gdfar_plugin_init', array($this, 'init'));
    }

    public function init() {
        $this->_default_actions();

        do_action('gdfar_register_actions');
    }

    public function register($name, $args = array()) {
        $defaults = array(
            'scope' => '',
            'action' => '',
            'label' => '',
            'class' => '',
            'filter_visible' => '',
            'filter_display' => '',
            'filter_process' => ''
        );

        $args = wp_parse_args($args, $defaults);
        $args['name'] = $name;

        if (!in_array($args['scope'], array('topic', 'forum'), true)) {
            return false;
        }

        if (!in_array($args['action'], array('edit', 'bulk'), true)) {
            return false;
        }

        $key = $args['scope'].'-'.$args['action'].'-'.$args['name'];

        if (empty($args['filter_visible'])) {
            $args['filter_visible'] = 'gdfar-action-visible-'.$key;
        }

        if (empty($args['filter_display'])) {
            $args['filter_display'] = 'gdfar-action-display-'.$key;
        }

        if (empty($args['filter_process'])) {
            $args['filter_process'] = 'gdfar-action-process-'.$key;
        }

        $this->_actions[$args['scope']][$args['action']][$name] = $args;

        return true;
    }

    public function get_actions($scope, $action) {
        return isset($this->_actions[$scope][$action]) ? $this->_actions[$scope][$action] : array();
    }

    private function _default_actions() {
        $this->register('rename', array(
            'scope' => 'forum',
            'action' => 'edit',
            'label' => __("Title", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'forum',
            'action' => 'edit',
            'label' => __("Status", "gd-forum-manager-for-bbpress")
        ));

        $this->register('visibility', array(
            'scope' => 'forum',
            'action' => 'edit',
            'label' => __("Visibility", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'forum',
            'action' => 'bulk',
            'label' => __("Status", "gd-forum-manager-for-bbpress")
        ));

        $this->register('visibility', array(
            'scope' => 'forum',
            'action' => 'bulk',
            'label' => __("Visibility", "gd-forum-manager-for-bbpress")
        ));

        $this->register('rename', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Title", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Status", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'topic',
            'action' => 'bulk',
            'label' => __("Status", "gd-forum-manager-for-bbpress")
        ));

        $this->register('sticky', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Sticky", "gd-forum-manager-for-bbpress")
        ));

        $this->register('sticky', array(
            'scope' => 'topic',
            'action' => 'bulk',
            'label' => __("Sticky", "gd-forum-manager-for-bbpress")
        ));
    }
}
