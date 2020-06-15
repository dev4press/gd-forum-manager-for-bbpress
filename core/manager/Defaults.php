<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use WP_Error;

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
                    add_filter('gdfar-action-process-'.$key, array($this, 'process_'.$method), 10, 2);
                }
            }
        }
    }

    private function _get_list_for_stickies() {
        return array(
            'no' => __("No", "gd-forum-manager-for-bbpress"),
            'sticky' => __("Sticky", "gd-forum-manager-for-bbpress"),
            'super' => __("Super Sticky", "gd-forum-manager-for-bbpress")
        );
    }

    private function _get_topic_sticky_status($topic_id) {
        return bbp_is_topic_super_sticky($topic_id) ? 'super' : (
               bbp_is_topic_sticky($topic_id) ? 'sticky' : 'no');
    }

    public function display_forum_edit_rename($render, $args = array()) {
        return '<input id="'.$args['element'].'" type="text" name="'.$args['base'].'[title]" value="'.esc_attr(bbp_get_forum_title($args['id'])).'" />';
    }

    public function process_forum_edit_rename($result, $args = array()) {
        $forum_id = $args['id'];
        $new_title = isset($args['value']['title']) ? sanitize_text_field($args['value']['title']) : '';
        $old_title = bbp_get_forum_title($forum_id);

        if (!empty($new_title) && $old_title != $new_title) {
            $forum_title = apply_filters('bbp_edit_forum_pre_title', $new_title, $forum_id);

            if (bbp_is_title_too_long($forum_title)) {
                return new WP_Error("title_too_long", __("The title is too long.", "gd-forum-manager-for-bbpress"));
            }

            $update = wp_update_post(array(
                'ID' => $forum_id,
                'post_title' => $forum_title
            ), true);

            if (is_wp_error($update)) {
                return $update;
            }
        }

        return $result;
    }

    public function display_topic_edit_rename($render, $args = array()) {
        return '<input id="'.$args['element'].'" type="text" name="'.$args['base'].'[title]" value="'.esc_attr(bbp_get_topic_title($args['id'])).'" />';
    }

    public function display_topic_edit_status($render, $args = array()) {
        $list = bbp_get_topic_statuses($args['id']);

        return gdfar_render()->select($list, array('selected' => bbp_get_topic_status($args['id']), 'name' => $args['base'].'[status]', 'id' => $args['element']));
    }

    public function display_topic_edit_sticky($render, $args = array()) {
        $list = $this->_get_list_for_stickies();

        return gdfar_render()->select($list, array('selected' => $this->_get_topic_sticky_status($args['id']), 'name' => $args['base'].'[sticky]', 'id' => $args['element']));
    }

    public function process_topic_edit_rename($result, $args = array()) {
        $topic_id = $args['id'];
        $new_title = isset($args['value']['title']) ? sanitize_text_field($args['value']['title']) : '';
        $old_title = bbp_get_topic_title($topic_id);

        if (!empty($new_title) && $old_title != $new_title) {
            $topic_title = apply_filters('bbp_edit_topic_pre_title', $new_title, $topic_id);

            if (bbp_is_title_too_long($topic_title)) {
                return new WP_Error("title_too_long", __("The title is too long.", "gd-forum-manager-for-bbpress"));
            }

            $update = wp_update_post(array(
                'ID' => $topic_id,
                'post_title' => $topic_title
            ), true);

            if (is_wp_error($update)) {
                return $update;
            }
        }

        return $result;
    }

    public function process_topic_edit_status($result, $args = array()) {
        $list = bbp_get_topic_statuses($args['id']);

        $topic_id = $args['id'];
        $new_status = isset($args['value']['status']) ? sanitize_text_field($args['value']['status']) : '';
        $old_status = bbp_get_topic_status($topic_id);

        if (empty($new_status) || !isset($list[$new_status])) {
            return new WP_Error("invalid_status", __("Invalid status value.", "gd-forum-manager-for-bbpress"));
        }

        if ($old_status != $new_status) {
            $update = wp_update_post(array(
                'ID' => $topic_id,
                'post_status' => $new_status
            ), true);

            if (is_wp_error($update)) {
                return $update;
            }
        }

        return $result;
    }

    public function process_topic_edit_sticky($result, $args = array()) {
        $list = $this->_get_list_for_stickies();

        $topic_id = $args['id'];
        $new_status = isset($args['value']['sticky']) ? sanitize_text_field($args['value']['sticky']) : '';
        $old_status = $this->_get_topic_sticky_status($args['id']);

        if (empty($new_status) || !isset($list[$new_status])) {
            return new WP_Error("invalid_status", __("Invalid status value.", "gd-forum-manager-for-bbpress"));
        }

        if ($old_status != $new_status) {
            bbp_unstick_topic($topic_id);

            switch ($new_status) {
                case 'sticky':
                    bbp_stick_topic($topic_id);
                    break;
                case 'super':
                    bbp_stick_topic($topic_id, true);
                    break;
            }
        }

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
