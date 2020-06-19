<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use WP_Error;

class Render {
    public function __construct() {

    }

    public static function instance() {
        static $instance = false;

        if ($instance === false) {
            $instance = new Render();
        }

        return $instance;
    }

    public function bulk($type, $context = array()) {
        $actions = gdfar()->actions()->get_actions($type, 'bulk');

        if (empty($actions)) {
            return new WP_Error('no_actions_found', __("No actions found.", "gd-forum-manager-for-bbpress"));
        }

        $elements = $this->_bulk($actions, $type, $context);

        if (empty($elements)) {
            return new WP_Error('no_actions_found', __("No actions found.", "gd-forum-manager-for-bbpress"));
        } else {
            $render = '<form method="post" id="gdfar-manager-form-bulk">';
            $render .= '<input type="hidden" name="gdfar[action]" value="bulk" />';
            $render .= '<input type="hidden" name="gdfar[nonce]" value="'.wp_create_nonce('gdfar-manager-bulk-'.$type).'" />';
            $render .= '<input type="hidden" name="gdfar[type]" value="'.esc_attr($type).'" />';
            $render .= join('', $elements);
            $render .= '</form>';

            return $render;
        }
    }

    private function _bulk($actions, $type, $context) {
        $elements = array();

        foreach ($actions as $action) {
            $visible = apply_filters($action['filter_visible'], true, array(
                'action' => 'bulk',
                'context' => $context
            ));

            if ($visible) {
                $element = 'action-'.$action['name'].'-'.rand(1000, 9999);

                $render = apply_filters($action['filter_display'], '', array(
                    'base' => 'gdfar[field]['.$action['name'].']',
                    'type' => $type,
                    'element' => $element,
                    'context' => $context
                ));

                $label = $action['label'];

                if (!empty($render) && !empty($label)) {
                    $classes = array(
                        'gdfar-action',
                        'gdfar-action-'.$action['name']
                    );

                    if (!empty($action['class'])) {
                        $classes[] = $action['class'];
                    }

                    $elements[] = '<dl class="'.join(' ', $classes).'"><dt>'.
                        '<div class="gdfar-label-wrapper"><label for="'.$element.'">'.$label.'</label></div>'.
                        '</dt><dd>'.
                        '<div class="gdfar-conent-wrapper">'.$render.'</div>'.
                        '</dd></dl>';
                }
            }
        }

        return $elements;
    }

    public function edit($type, $id, $context = array()) {
        $actions = gdfar()->actions()->get_actions($type, 'edit');

        if (empty($actions)) {
            return new WP_Error('no_actions_found', __("No actions found.", "gd-forum-manager-for-bbpress"));
        }

        $id = absint($id);

        if (($type == 'forum' && bbp_is_forum($id)) || ($type == 'topic' && bbp_is_topic($id))) {
            $elements = $this->_edit($actions, $type, $id, $context);

            if (empty($elements)) {
                return new WP_Error('no_actions_found', __("No actions found.", "gd-forum-manager-for-bbpress"));
            } else {
                $render = '<form method="post" id="gdfar-manager-form-edit">';
                $render .= '<input type="hidden" name="gdfar[action]" value="edit" />';
                $render .= '<input type="hidden" name="gdfar[nonce]" value="'.wp_create_nonce('gdfar-manager-edit-'.$type.'-'.$id).'" />';
                $render .= '<input type="hidden" name="gdfar[type]" value="'.esc_attr($type).'" />';
                $render .= '<input type="hidden" name="gdfar[id]" value="'.esc_attr($id).'" />';
                $render .= join('', $elements);
                $render .= '</form>';

                return $render;
            }
        }

        return new WP_Error('object_not_found', __("Request object not found.", "gd-forum-manager-for-bbpress"));
    }

    private function _edit($actions, $type, $id, $context) {
        $elements = array();

        foreach ($actions as $action) {
            $visible = apply_filters($action['filter_visible'], true, array(
                'action' => 'edit',
                'id' => $id,
                'context' => $context
            ));

            if ($visible) {
                $element = 'action-'.$action['name'].'-'.rand(1000, 9999);

                $render = apply_filters($action['filter_display'], '', array(
                    'id' => $id,
                    'base' => 'gdfar[field]['.$action['name'].']',
                    'type' => $type,
                    'element' => $element,
                    'context' => $context
                ));

                $label = $action['label'];

                if (!empty($render) && !empty($label)) {
                    $classes = array(
                        'gdfar-action',
                        'gdfar-action-'.$action['name']
                    );

                    if (!empty($action['class'])) {
                        $classes[] = $action['class'];
                    }

                    $elements[] = '<dl class="'.join(' ', $classes).'"><dt>'.
                                  '<div class="gdfar-label-wrapper"><label for="'.$element.'">'.$label.'</label></div>'.
                                  '</dt><dd>'.
                                  '<div class="gdfar-conent-wrapper">'.$render.'</div>'.
                                  '</dd></dl>';
                }
            }
        }

        return $elements;
    }

    public function select($values, $args = array(), $attr = array()) {
        $defaults = array(
            'selected' => '', 'name' => '', 'id' => '', 'class' => '',
            'style' => '', 'multi' => false, 'echo' => false, 'readonly' => false);
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $render = '';
        $attributes = array();
        $selected = is_null($selected) ? array_keys($values) : (array)$selected;
        $associative = !d4p_is_array_associative($values);
        $id = d4p_html_id_from_name($name, $id);

        if ($class != '') {
            $attributes[] = 'class="'.esc_attr($class).'"';
        }

        if ($style != '') {
            $attributes[] = 'style="'.esc_attr($style).'"';
        }

        if ($multi) {
            $attributes[] = 'multiple';
        }

        if ($readonly) {
            $attributes[] = 'readonly';
        }

        foreach ($attr as $key => $value) {
            $attributes[] = $key.'="'.esc_attr($value).'"';
        }

        $name = $multi ? $name.'[]' : $name;

        if ($id != '') {
            $attributes[] = 'id="'.esc_attr($id).'"';
        }

        if ($name != '') {
            $attributes[] = 'name="'.esc_attr($name).'"';
        }

        $render .= '<select '.join(' ', $attributes).'>';
        foreach ($values as $value => $display) {
            $real_value = $associative ? $display : $value;

            $sel = in_array($real_value, $selected) ? ' selected="selected"' : '';
            $render .= '<option value="'.esc_attr($value).'"'.$sel.'>'.esc_html($display).'</option>';
        }
        $render .= '</select>';

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }
}
