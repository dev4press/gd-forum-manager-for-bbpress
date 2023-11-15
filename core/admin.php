<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function _gdfar_display_option( $option ) : string {
	$title = '';
	$label = '';
	$info  = '';

	switch ( $option ) {
		case 'moderators':
			$title = __( 'Toggle option for moderators access', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Global Moderators can use the plugin', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'All users with moderator or keymaster role will be able to use the plugin.', 'gd-forum-manager-for-bbpress' );
			break;
		case 'forum_moderators':
			$title = __( 'Toggle option for forum moderators access', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Forum Moderators can use the plugin', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'All users with the moderation capability for individual forums will be able to use the plugin inside .', 'gd-forum-manager-for-bbpress' );
			break;
		case 'forum':
			$title = __( 'Toggle option for forums editing', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Edit Forums', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'If enabled, plugin will add edit controls to every forums list.', 'gd-forum-manager-for-bbpress' );
			break;
		case 'topic':
			$title = __( 'Toggle option for topics editing', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Edit Topics', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'If enabled, plugin will add edit controls to every topics list. Some edit options may be unavailable in Topic Views.', 'gd-forum-manager-for-bbpress' );
			break;
		case 'small_screen_always_show':
			$title = __( 'Toggle option for display on small screens', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Always show on small screens', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'If enabled, controls will be available on small screens or mobile devices.', 'gd-forum-manager-for-bbpress' );
			break;
		case 'notices_under_fields':
			$title = __( 'Toggle option for displaying action notices', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Show action notices where available', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'If enabled, information for edit fields will be visible. If you want to have more compact layout of editor popup, it is better to disable this option.', 'gd-forum-manager-for-bbpress' );
			break;
		case 'topic_edit_log':
			$title = __( 'Toggle option for keeping edit log for topics', 'gd-forum-manager-for-bbpress' );
			$label = __( 'Show \'Keep The Edit Log\' for topics controls', 'gd-forum-manager-for-bbpress' );
			$info  = __( 'If enabled, you will be able to add the edit log message with each edit.', 'gd-forum-manager-for-bbpress' );
			break;
	}

	$status = gdfar_settings()->get( $option ) ? 'enabled' : 'disabled';

	$render = '<div class="d4p-dashboard-status-row d4p-dashboard-is-' . $status . '">';
	$render .= '<button title="' . esc_attr( $title ) . '" href="#" data-name="' . esc_attr( $option ) . '" data-nonce="' . wp_create_nonce( 'gdfar-toggle-option-' . $option ) . '" class="gdfar-option-toggle d4p-type-toggle d4p-type-status-' . esc_attr( $status ) . '">';
	$render .= '<i class="d4p-icon d4p-ui-toggle-on' . ( $status != 'enabled' ? ' d4p-icon-flip-horizontal' : '' ) . '"></i>';
	$render .= '</button>';
	$render .= '<div>';
	$render .= esc_html( $label );

	if ( ! empty( $info ) ) {
		$render .= '<em>' . esc_html( $info ) . '</em>';
	}

	$render .= '</div>';
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
