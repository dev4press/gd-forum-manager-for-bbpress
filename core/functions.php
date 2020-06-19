<?php

if (!defined('ABSPATH')) {
    exit;
}

function gdfar_register_action($name, $args = array()) {
    if (current_filter() == 'gdfar_register_actions') {
        return gdfar()->actions()->register($name, $args);
    }

    _doing_it_wrong('gdfar_register_action', __("This function has to be called inside 'gdfar_register_actions' action.", "gd-forum-manager-for-bbpress"), '1.0');

    return false;
}

function gdfar_array_sanitize_text_field($array) {
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            $value = gdfar_array_sanitize_text_field($value);
        } else {
            $value = sanitize_text_field($value);
        }
    }

    return $array;
}
