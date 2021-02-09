<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Plugin\GDFAR\Manager\Process;

class AJAX {
	private $nonce = 'gdfar-manager-request';

	public function __construct() {
		add_action( 'wp_ajax_gdfar_toggle_option', array( $this, 'toggle_option' ) );

		add_action( 'wp_ajax_gdfar_request_edit', array( $this, 'edit_request' ) );
		add_action( 'wp_ajax_gdfar_process_edit', array( $this, 'edit_process' ) );
		add_action( 'wp_ajax_gdfar_request_bulk', array( $this, 'bulk_request' ) );
		add_action( 'wp_ajax_gdfar_process_bulk', array( $this, 'bulk_process' ) );
	}

	public static function instance() : AJAX {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new AJAX();
		}

		return $instance;
	}

	private function check_moderation() {
		$mod = gdfar()->allowed();

		if ( $mod === false ) {
			_ajax_wp_die_handler( __( "Invalid Request.", "gd-forum-manager-for-bbpress" ) );
		}
	}

	public function admin_check_nonce( $action = 'gdfar-admin-internal', $nonce = '_ajax_nonce' ) {
		$check = wp_verify_nonce( $_REQUEST[ $nonce ], $action );

		if ( $check === false ) {
			wp_die( - 1 );
		}
	}

	private function check_nonce( $is = false, $forum = false ) {
		$nonce = $this->nonce;

		if ( $is !== false ) {
			$nonce .= '-' . $is;
		}

		if ( $forum !== false ) {
			$nonce .= '-' . $forum;
		}

		$valid = wp_verify_nonce( $_REQUEST['_ajax_nonce'], $nonce );

		if ( $valid === false ) {
			$this->error( __( "Invalid Request.", "gd-forum-manager-for-bbpress" ) );
		}
	}

	public function toggle_option() {
		if ( d4p_is_current_user_admin() ) {
			$name = d4p_sanitize_key_expanded( $_POST['option'] );

			$this->admin_check_nonce( 'gdfar-toggle-option-' . $name );

			$new_value = gdfar_settings()->get( $name ) ? false : true;
			gdfar_settings()->set( $name, $new_value, 'settings', true );

			die( _gdfar_display_option( $name ) );
		}

		die( __( "Invalid Request", "gd-forum-manager-for-bbpress" ) );
	}

	private function error( $message ) {
		$html = '<div class="gdfar-dialog-error">' . $message . '</div>';

		_ajax_wp_die_handler( $html );
	}

	private function json_respond( $response, $code = 200 ) {
		status_header( $code );

		if ( ! headers_sent() ) {
			nocache_headers();
			header( 'Content-Type: application/json' );
		}

		die( json_encode( $response ) );
	}

	public function edit_request() {
		$this->check_moderation();

		$is    = isset( $_REQUEST['is'] ) ? d4p_sanitize_slug( $_REQUEST['is'] ) : '';
		$forum = isset( $_REQUEST['forum'] ) ? absint( $_REQUEST['forum'] ) : 0;
		$type  = isset( $_REQUEST['type'] ) ? d4p_sanitize_slug( $_REQUEST['type'] ) : '';
		$id    = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;

		$this->check_nonce( $is, $forum );

		if ( ! in_array( $type, array( 'forum', 'topic' ) ) || $id == 0 ) {
			$this->error( __( "Invalid Request.", "gd-forum-manager-for-bbpress" ) );
		}

		$edit = gdfar_render()->edit( $type, $id, array( 'is' => $is, 'forum' => $forum ) );

		if ( is_wp_error( $edit ) ) {
			$this->error( $edit->get_error_message() );
		}

		die( $edit );
	}

	public function edit_process() {
		$this->check_moderation();

		if ( isset( $_REQUEST['gdfar'] ) ) {
			$data = (array) $_REQUEST['gdfar'];

			$data['action'] = isset( $data['action'] ) ? d4p_sanitize_slug( $data['action'] ) : '';
			$data['type']   = isset( $data['type'] ) ? d4p_sanitize_slug( $data['type'] ) : '';
			$data['nonce']  = isset( $data['nonce'] ) ? d4p_sanitize_basic( $data['nonce'] ) : '';
			$data['id']     = isset( $data['id'] ) ? absint( $data['id'] ) : 0;
			$data['field']  = isset( $data['field'] ) ? gdfar_array_sanitize_text_field( (array) $data['field'] ) : array();

			if (
				in_array( $data['type'], array(
					'forum',
					'topic'
				) ) && $data['id'] > 0 || $data['action'] == 'edit' || ! empty( $nonce ) ) {
				if ( wp_verify_nonce( $data['nonce'], 'gdfar-manager-edit-' . $data['type'] . '-' . $data['id'] ) ) {
					$process = new Process( $data );
					$result  = $process->edit();

					if ( is_wp_error( $result ) ) {
						$this->json_respond( array(
							'status' => 'error',
							'error'  => $result->get_error_message()
						) );
					} else {
						$this->json_respond( array(
							'status'   => 'ok',
							'errors'   => count( $result ),
							'elements' => $result
						) );
					}
				}
			}
		}

		$this->json_respond( array(
			'status' => 'error',
			'error'  => __( "Invalid Request.", "gd-forum-manager-for-bbpress" )
		) );
	}

	public function bulk_request() {
		$this->check_moderation();

		$is    = isset( $_REQUEST['is'] ) ? d4p_sanitize_slug( $_REQUEST['is'] ) : '';
		$forum = isset( $_REQUEST['forum'] ) ? absint( $_REQUEST['forum'] ) : 0;
		$type  = isset( $_REQUEST['type'] ) ? d4p_sanitize_slug( $_REQUEST['type'] ) : '';

		$this->check_nonce( $is, $forum );

		if ( ! in_array( $type, array( 'forum', 'topic' ) ) ) {
			$this->error( __( "Invalid Request.", "gd-forum-manager-for-bbpress" ) );
		}

		$edit = gdfar_render()->bulk( $type, array( 'is' => $is, 'forum' => $forum ) );

		if ( is_wp_error( $edit ) ) {
			$this->error( $edit->get_error_message() );
		}

		die( $edit );
	}

	public function bulk_process() {
		$this->check_moderation();

		if ( isset( $_REQUEST['gdfar'] ) ) {
			$data = (array) $_REQUEST['gdfar'];

			$data['action'] = isset( $data['action'] ) ? d4p_sanitize_slug( $data['action'] ) : '';
			$data['type']   = isset( $data['type'] ) ? d4p_sanitize_slug( $data['type'] ) : '';
			$data['nonce']  = isset( $data['nonce'] ) ? d4p_sanitize_basic( $data['nonce'] ) : '';
			$data['field']  = isset( $data['field'] ) ? (array) $data['field'] : array();

			$data['id'] = isset( $data['id'] ) ? (array) $data['id'] : array();
			$data['id'] = array_map( 'absint', $data['id'] );
			$data['id'] = array_unique( $data['id'] );
			$data['id'] = array_filter( $data['id'] );

			if (
				in_array( $data['type'], array(
					'forum',
					'topic'
				) ) && ! empty( $data['id'] ) || $data['action'] == 'bulk' || ! empty( $nonce ) ) {
				if ( wp_verify_nonce( $data['nonce'], 'gdfar-manager-bulk-' . $data['type'] ) ) {
					$process = new Process( $data );
					$result  = $process->bulk();

					if ( is_wp_error( $result ) ) {
						$this->json_respond( array(
							'status' => 'error',
							'error'  => $result->get_error_message()
						) );
					} else {
						$this->json_respond( array(
							'status'   => 'ok',
							'errors'   => count( $result ),
							'elements' => $result
						) );
					}
				}
			}
		}

		$this->json_respond( array(
			'status' => 'error',
			'error'  => __( "Invalid Request.", "gd-forum-manager-for-bbpress" )
		) );
	}
}
