<?php

function _gdfar_display_option($option) {
    $title = '';
    $label = '';

    switch ($option) {
        case 'moderators':
            $title = __("Toggle option for moderators access", "gd-forum-manager-for-bbpress");
            $label = __("Moderators can use the plugin", "gd-forum-manager-for-bbpress");
            break;
        case 'forum':
            $title = __("Toggle option for forums editing", "gd-forum-manager-for-bbpress");
            $label = __("Edit Forums", "gd-forum-manager-for-bbpress");
            break;
        case 'topic':
            $title = __("Toggle option for topics editing", "gd-forum-manager-for-bbpress");
            $label = __("Edit Topics", "gd-forum-manager-for-bbpress");
            break;
    }

    $status = gdfar_settings()->get($option) ? 'enabled' : 'disabled';

    $render = '<div class="d4p-dashboard-status-row d4p-dashboard-is-'.$status.'">';
    $render .= '<a title="'.$title.'" href="#" data-name="'.$option.'" data-nonce="'.wp_create_nonce('gdfar-toggle-option-'.$option).'" class="gdfar-option-toggle d4p-type-toggle d4p-type-status-'.$status.'">';
    $render .= '<i class="d4p-icon d4p-ui-toggle-on'.($status != 'enabled' ? ' d4p-flip-horizontal' : '').'"></i>';
    $render .= '</a>';
    $render .= '<span>'.$label.'</span>';
    $render .= '</div>';

    return $render;
}

function _gdfar_display_actions($scope, $action) {
    $actions = gdfar()->actions()->get_actions($scope, $action);

    echo '<ul class="d4p-with-bullets">';
    foreach ($actions as $action) {
        echo '<li style="line-height: 1.6;"><strong>'.$action['label'].'</strong>: '.$action['description'];
        echo '<span class="d4p-card-badge d4p-badge-right d4p-badge-green">'.$action['source'].'</span>';
        echo '</li>';
    }
    echo '</ul>';
}
