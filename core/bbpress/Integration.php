<?php

namespace Dev4Press\Plugin\GDFAR\bbPress;

use Dev4Press\Plugin\GDFAR\Manager\Defaults;

if (!defined('ABSPATH')) {
    exit;
}

class Integration {
    public $_actions = false;
    public $_queued = false;

    public function __construct() {
        $this->_actions = new Defaults();

        if (is_user_logged_in()) {
            add_action('bbp_init', array($this, 'init'));
        }
    }

    public function can_moderate() {
        return current_user_can('moderate');
    }

    public function init() {
        if ($this->can_moderate()) {
            add_action('bbp_theme_before_forum_title', array($this, 'forum_controls'), 1);
            add_action('bbp_theme_before_topic_title', array($this, 'topic_controls'), 1);
            add_action('bbp_template_after_forums_loop', array($this, 'bulk'));
            add_action('bbp_template_after_topics_loop', array($this, 'bulk'));
        }
    }

    public function enqueue() {
        $this->_queued = true;

        wp_enqueue_style('gdfar-manager');
        wp_enqueue_script('gdfar-manager');

        $id = bbp_is_single_forum() ? bbp_get_forum_id() : 0;
        $is = bbp_is_single_forum() ? 'forum' : (
              bbp_is_single_topic() ? 'topic' : (
              bbp_is_single_view() ? 'view' : (
              bbp_is_topic_archive() ? 'topic-archive' : (
              bbp_is_forum_archive() ? 'forum-archive' : ''
        ))));

        wp_localize_script('gdfar-manager', 'gdfar_manager_data', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gdfar-manager-request-'.$is.'-'.$id),
            'bbpress' => array(
                'is' => $is,
                'forum_id' => $id
            ),
            'message' => array(
                'please_wait' => __("Please Wait...", "gd-forum-manager-for-bbpress")
            ),
            'titles' => array(
                'edit' => array(
                    'forum' => _x("Edit Forum", "Edit Modal Dialog, Title", "gd-forum-manager-for-bbpress"),
                    'topic' => _x("Edit Topic", "Edit Modal Dialog, Title", "gd-forum-manager-for-bbpress")
                ),
                'bulk' => array(
                    'forum' => _x("Edit selected Forums", "Edit Modal Dialog, Title", "gd-forum-manager-for-bbpress"),
                    'topic' => _x("Edit selected Topics", "Edit Modal Dialog, Title", "gd-forum-manager-for-bbpress")
                )
            )
        ));

        do_action('gdfar_plugin_enqueue_scripts');
    }

    public function forum_controls() {
        $forum_id = absint(bbp_get_forum_id());

        echo '<div class="gdfar-ctrl-wrapper gdfar-ctrl-forum" data-type="forum" data-id="'.$forum_id.'">';
        echo '<input type="checkbox" class="gdfar-ctrl-checkbox" />';
        echo '<a href="#" class="gdfar-ctrl-edit">'.__("edit", "gd-forum-manager-for-bbpress").'</a>';
        echo '</div>';

        if (!$this->_queued) {
            $this->enqueue();

            add_action('wp_footer', array($this, 'modals'));
        }
    }

    public function topic_controls() {
        $topic_id = absint(bbp_get_topic_id());

        echo '<div class="gdfar-ctrl-wrapper gdfar-ctrl-topic" data-type="topic" data-id="'.$topic_id.'">';
        echo '<input type="checkbox" class="gdfar-ctrl-checkbox" />';
        echo '<a href="#" class="gdfar-ctrl-edit">'.__("edit", "gd-forum-manager-for-bbpress").'</a>';
        echo '</div>';

        if (!$this->_queued) {
            $this->enqueue();

            add_action('wp_footer', array($this, 'modals'));
        }
    }

    public function modals() {
        require_once(GDFAR_PATH.'forms/manager/dialog-edit.php');
        require_once(GDFAR_PATH.'forms/manager/dialog-bulk.php');
    }

    public function bulk() {
        include(GDFAR_PATH.'forms/manager/control-bulk.php');
    }
}
