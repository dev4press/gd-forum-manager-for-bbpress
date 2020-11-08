<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use WP_Error;

class Process {
	private $data;

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function bulk() {
		$actions = gdfar()->actions()->get_actions( $this->data['type'], 'bulk' );

		if ( empty( $actions ) ) {
			return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
		}

		$ids = $this->_validate( $this->data['type'], $this->data['id'] );

		if ( empty( $ids ) ) {
			return new WP_Error( 'invalid_objects', __( "Selected objects are not valid.", "gd-forum-manager-for-bbpress" ) );
		}

		$elements = $this->_bulk( $actions, $this->data['type'], $this->data['id'], $this->data['field'] );

		if ( empty( $elements ) ) {
			return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
		} else {
			$report = array();

			foreach ( $elements as $name => $value ) {
				if ( is_wp_error( $value ) ) {
					$report[ $name ] = $value->get_error_message();
				}
			}

			return $report;
		}
	}

	public function edit() {
		$actions = gdfar()->actions()->get_actions( $this->data['type'], 'edit' );

		if ( empty( $actions ) ) {
			return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
		}

		if ( ( $this->data['type'] == 'forum' && bbp_is_forum( $this->data['id'] ) ) || ( $this->data['type'] == 'topic' && bbp_is_topic( $this->data['id'] ) ) ) {
			$elements = $this->_edit( $actions, $this->data['type'], $this->data['id'], $this->data['field'] );

			if ( empty( $elements ) ) {
				return new WP_Error( 'no_actions_found', __( "No actions found.", "gd-forum-manager-for-bbpress" ) );
			} else {
				$report = array();

				foreach ( $elements as $name => $value ) {
					if ( is_wp_error( $value ) ) {
						$report[ $name ] = $value->get_error_message();
					}
				}

				return $report;
			}
		}

		return new WP_Error( 'object_not_found', __( "Request object not found.", "gd-forum-manager-for-bbpress" ) );
	}

	private function _bulk( $actions, $type, $id, $field ) {
		$elements = array();

		foreach ( $actions as $action ) {
			$value = isset( $field[ $action['name'] ] ) ? $field[ $action['name'] ] : false;

			$elements[ $action['name'] ] = apply_filters( $action['filter_process'], true, array(
				'id'    => $id,
				'value' => $value
			) );
		}

		return $elements;
	}

	private function _edit( $actions, $type, $id, $field ) {
		$elements = array();

		foreach ( $actions as $action ) {
			$value = isset( $field[ $action['name'] ] ) ? $field[ $action['name'] ] : false;

			$elements[ $action['name'] ] = apply_filters( $action['filter_process'], true, array(
				'id'    => $id,
				'value' => $value
			) );
		}

		return $elements;
	}

	private function _validate( $type, $ids = array() ) {
		$clean = array();

		foreach ( $ids as $id ) {
			if ( $type == 'forum' ) {
				if ( bbp_is_forum( $id ) ) {
					$clean[] = $id;
				}
			} else {
				if ( bbp_is_topic( $id ) ) {
					$clean[] = $id;
				}
			}
		}

		return $clean;
	}
}
