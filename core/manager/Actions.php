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
            'source' => 'bbPress',
            'prefix' => 'gdfar',
            'notice' => '',
            'description' => '',
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
            $args['filter_visible'] = $args['prefix'].'-action-visible-'.$key;
        }

        if (empty($args['filter_display'])) {
            $args['filter_display'] = $args['prefix'].'-action-display-'.$key;
        }

        if (empty($args['filter_process'])) {
            $args['filter_process'] = $args['prefix'].'-action-process-'.$key;
        }

        $this->_actions[$args['scope']][$args['action']][$name] = $args;

        return true;
    }

    public function get_actions($scope, $action) {
        return isset($this->_actions[$scope][$action]) ? $this->_actions[$scope][$action] : array();
    }

    public function count_actions($scope, $action) {
        return isset($this->_actions[$scope][$action]) ? count($this->_actions[$scope][$action]) : 0;
    }

    private function _default_actions() {
        $this->register('rename', array(
            'scope' => 'forum',
            'action' => 'edit',
            'label' => __("Title", "gd-forum-manager-for-bbpress"),
            'description' => __("Change forum title.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'forum',
            'action' => 'edit',
            'label' => __("Status", "gd-forum-manager-for-bbpress"),
            'description' => __("Change forum status.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('visibility', array(
            'scope' => 'forum',
            'action' => 'edit',
            'label' => __("Visibility", "gd-forum-manager-for-bbpress"),
            'description' => __("Change forum visibility.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'forum',
            'action' => 'bulk',
            'label' => __("Status", "gd-forum-manager-for-bbpress"),
            'description' => __("Change forum status.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('visibility', array(
            'scope' => 'forum',
            'action' => 'bulk',
            'label' => __("Visibility", "gd-forum-manager-for-bbpress"),
            'description' => __("Change forum visibility.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('rename', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Title", "gd-forum-manager-for-bbpress"),
            'description' => __("Change topic title.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('forum', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Forum", "gd-forum-manager-for-bbpress"),
            'description' => __("Change the forum for the topic.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('forum', array(
            'scope' => 'topic',
            'action' => 'bulk',
            'label' => __("Forum", "gd-forum-manager-for-bbpress"),
            'description' => __("Change the forum for the topic.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Status", "gd-forum-manager-for-bbpress"),
            'description' => __("Change topic status.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('status', array(
            'scope' => 'topic',
            'action' => 'bulk',
            'label' => __("Status", "gd-forum-manager-for-bbpress"),
            'description' => __("Change topic status.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('sticky', array(
            'scope' => 'topic',
            'action' => 'edit',
            'label' => __("Sticky", "gd-forum-manager-for-bbpress"),
            'description' => __("Change topic sticky status.", "gd-forum-manager-for-bbpress")
        ));

        $this->register('sticky', array(
            'scope' => 'topic',
            'action' => 'bulk',
            'label' => __("Sticky", "gd-forum-manager-for-bbpress"),
            'description' => __("Change topic sticky status.", "gd-forum-manager-for-bbpress")
        ));
    }
}
