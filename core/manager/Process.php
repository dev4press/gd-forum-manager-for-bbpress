<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use WP_Error;

class Process {
	private $data;
	private $modd;

	public function __construct() {
	}

	public static function instance() : Process {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new Process();
		}

		return $instance;
	}

	public function init( $data ) {
		define( 'GDFAR_EDITOR_PROCESSING', true );

		$this->data = $data;
		$this->modd = array(
			'topic' => array(),
			'forum' => array(),
		);

		return $this;
	}

	public function modded( $type, $id ) {
		if ( $type === 'forum' || $type === 'topic' ) {
			$id = absint( $id );

			if ( $id > 0 ) {
				if ( ! in_array( $id, $this->modd[ $type ] ) ) {
					$this->modd[ $type ][] = $id;
				}
			}
		}
	}

	public function update_post( $args, $type ) {
		$revisions_removed = false;

		if ( $type == 'topic' && post_type_supports( bbp_get_topic_post_type(), 'revisions' ) ) {
			$revisions_removed = true;
			remove_post_type_support( bbp_get_topic_post_type(), 'revisions' );
		}

		$id = wp_update_post( $args );

		if ( true === $revisions_removed ) {
			add_post_type_support( bbp_get_topic_post_type(), 'revisions' );
		}

		return $id;
	}

	public function is_modded( $type, $id ) : bool {
		if ( $type === 'forum' || $type === 'topic' ) {
			$id = absint( $id );

			return in_array( $id, $this->modd[ $type ] );
		}

		return false;
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

	private function _bulk( $actions, $type, $id, $field ) : array {
		$elements = array();

		foreach ( $actions as $action ) {
			$value = $field[ $action['name'] ] ?? false;

			$elements[ $action['name'] ] = apply_filters( $action['filter_process'], true, array(
				'id'    => $id,
				'value' => $value,
			) );
		}

		if ( $type == 'topic' && $this->data['edit-log'] !== false ) {
			foreach ( $id as $topic_id ) {
				if ( $this->is_modded( 'topic', $topic_id ) ) {
					$this->_revision( $topic_id );
				}
			}
		}

		return $elements;
	}

	private function _edit( $actions, $type, $id, $field ) : array {
		$elements = array();

		foreach ( $actions as $action ) {
			$value = $field[ $action['name'] ] ?? false;

			$elements[ $action['name'] ] = apply_filters( $action['filter_process'], true, array(
				'id'    => $id,
				'value' => $value,
			) );
		}

		if ( $type == 'topic' && $this->data['edit-log'] !== false ) {
			if ( $this->is_modded( 'topic', $id ) ) {
				$this->_revision( $id );
			}
		}

		return $elements;
	}

	private function _validate( $type, $ids = array() ) : array {
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

	private function _revision( $topic_id ) {
		add_filter( 'wp_save_post_revision_check_for_changes', '__return_false' );

		$this->update_post( array( 'ID' => $topic_id ), 'topic' );

		$revision_id = wp_save_post_revision( $topic_id );

		if ( ! empty( $revision_id ) ) {
			bbp_update_topic_revision_log( array(
				'topic_id'    => $topic_id,
				'revision_id' => $revision_id,
				'author_id'   => bbp_get_current_user_id(),
				'reason'      => $this->data['edit-log']['reason'],
			) );
		}
	}
}
