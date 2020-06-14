<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

class Defaults {
    private $_defaults = array(
        'forum' => array(
            'edit' => array('rename')
        ),
        'topic' => array(
            'edit' => array('rename', 'status', 'sticky'),
            'bulk' => array('status', 'sticky')
        )
    );

    public function __construct() {
        foreach ($this->_defaults as $scope => $actions) {
            foreach ($actions as $action => $names) {
                foreach ($names as $name) {
                    $key = $scope.'-'.$action.'-'.$name;
                    $method = $scope.'_'.$action.'_'.str_replace('-', '_', $name);

                    add_filter('gdfar-action-visible-'.$key, '__return_true');
                    add_filter('gdfar-action-display-'.$key, array($this, 'display_'.$method), 10, 2);
                    add_filter('gdfar-action-process-'.$key, array($this, 'process_'.$method));
                }
            }
        }
    }

    public function display_forum_edit_rename($render, $args = array()) {
        return '<input id="'.$args['element'].'" type="text" name="'.$args['base'].'[title]" value="'.esc_attr(bbp_get_forum_title($args['id'])).'" />';
    }

    public function process_forum_edit_rename($result, $args = array()) {
        return $result;
    }

    public function display_topic_edit_rename($render, $args = array()) {
        return '<input id="'.$args['element'].'" type="text" name="'.$args['base'].'[title]" value="'.esc_attr(bbp_get_topic_title($args['id'])).'" />';
    }

    public function display_topic_edit_status($render, $args = array()) {
        $list = bbp_get_topic_statuses($args['id']);
d4p_print_r($args);
        return gdfar_render()->select($list, array('selected' => bbp_get_topic_status($args['id']), 'name' => $args['base'].'[status]', 'id' => $args['element']));
    }

    public function display_topic_edit_sticky($render, $args = array()) {
        $list = array(
            'no' => __("No", "gd-forum-manager-for-bbpress"),
            'sticky' => __("Sticky", "gd-forum-manager-for-bbpress"),
            'super' => __("Super Sticky", "gd-forum-manager-for-bbpress")
        );

        $selected = bbp_is_topic_super_sticky($args['id']) ? 'super' : (
                    bbp_is_topic_sticky($args['id']) ? 'sticky' : 'no');

        return gdfar_render()->select($list, array('selected' => $selected, 'name' => $args['base'].'[sticky]', 'id' => $args['element']));
    }

    public function process_topic_edit_rename($result, $args = array()) {
        return $result;
    }

    public function process_topic_edit_status($result, $args = array()) {
        return $result;
    }

    public function process_topic_edit_sticky($result, $args = array()) {
        return $result;
    }

    public function display_topic_bulk_status($render, $args = array()) {
        return $render;
    }

    public function display_topic_bulk_sticky($render, $args = array()) {
        return $render;
    }

    public function process_topic_bulk_status($result, $args = array()) {
        return $result;
    }

    public function process_topic_bulk_sticky($result, $args = array()) {
        return $result;
    }
}
