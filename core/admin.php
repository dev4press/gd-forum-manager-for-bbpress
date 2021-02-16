<?php

function _gdfar_display_option( $option ) {
	$title = '';
	$label = '';

	switch ( $option ) {
		case 'moderators':
			$title = __( "Toggle option for moderators access", "gd-forum-manager-for-bbpress" );
			$label = __( "Moderators can use the plugin", "gd-forum-manager-for-bbpress" );
			break;
		case 'forum':
			$title = __( "Toggle option for forums editing", "gd-forum-manager-for-bbpress" );
			$label = __( "Edit Forums", "gd-forum-manager-for-bbpress" );
			break;
		case 'topic':
			$title = __( "Toggle option for topics editing", "gd-forum-manager-for-bbpress" );
			$label = __( "Edit Topics", "gd-forum-manager-for-bbpress" );
			break;
		case 'small_screen_always_show':
			$title = __( "Toggle option for display on small screens", "gd-forum-manager-for-bbpress" );
			$label = __( "Always show on small screens", "gd-forum-manager-for-bbpress" );
			break;
		case 'notices_under_fields':
			$title = __( "Toggle option for displaying action notices", "gd-forum-manager-for-bbpress" );
			$label = __( "Show action notices where available", "gd-forum-manager-for-bbpress" );
			break;
		case 'topic_edit_log':
			$title = __( "Toggle option for keeping edit log for topics", "gd-forum-manager-for-bbpress" );
			$label = __( "Show keep the edit log for topics controls", "gd-forum-manager-for-bbpress" );
			break;
	}

	$status = gdfar_settings()->get( $option ) ? 'enabled' : 'disabled';

	$render = '<div class="d4p-dashboard-status-row d4p-dashboard-is-' . $status . '">';
	$render .= '<a title="' . esc_attr( $title ) . '" href="#" data-name="' . $option . '" data-nonce="' . wp_create_nonce( 'gdfar-toggle-option-' . $option ) . '" class="gdfar-option-toggle d4p-type-toggle d4p-type-status-' . $status . '">';
	$render .= '<i class="d4p-icon d4p-ui-toggle-on' . ( $status != 'enabled' ? ' d4p-icon-flip-horizontal' : '' ) . '"></i>';
	$render .= '</a>';
	$render .= '<span>' . esc_html( $label ) . '</span>';
	$render .= '</div>';

	return $render;
}

function _gdfar_display_actions( $scope, $action ) {
	$actions = gdfar()->actions()->get_actions( $scope, $action );

	echo '<ul class="d4p-with-bullets d4p-full-width">';
	foreach ( $actions as $action ) {
		echo '<li style="line-height: 2;"><strong>' . esc_html( $action['label'] ) . '</strong>: ' . esc_html( $action['description'] );
		echo '<span class="d4p-card-badge d4p-badge-right d4p-badge-green">' . esc_html( $action['source'] ) . '</span>';
		echo '</li>';
	}
	echo '</ul>';
}
