<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use WP_Error;

class Process {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function edit() {
        $actions = gdfar()->actions()->get_actions($this->data['type'], 'edit');

        if (empty($actions)) {
            return new WP_Error('no_actions_found', __("No actions found.", "gd-forum-manager-for-bbpress"));
        }

        if (($this->data['type'] == 'forum' && bbp_is_forum($this->data['id'])) || ($this->data['type'] == 'topic' && bbp_is_topic($this->data['id']))) {
            $elements = $this->_edit($actions, $this->data['type'], $this->data['id'], $this->data['field']);

            if (empty($elements)) {
                return new WP_Error('no_actions_found', __("No actions found.", "gd-forum-manager-for-bbpress"));
            } else {
                return $elements;
            }
        }

        return new WP_Error('object_not_found', __("Request object not found.", "gd-forum-manager-for-bbpress"));
    }

    private function _edit($actions, $type, $id, $field) {
        $elements = array();

        foreach ($actions as $action) {
            $value = isset($field[$action['name']]) ? $field[$action['name']] : false;

            $elements[$action['name']] = apply_filters($action['filter_process'], true, array(
                'id' => $id,
                'value' => $value
            ));
        }

        return $elements;
    }
}
