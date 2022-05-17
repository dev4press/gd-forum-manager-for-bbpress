<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Plugin\GDFAR\Manager\Process;
use Dev4Press\v38\Core\Quick\Sanitize;
use Dev4Press\v38\Core\Quick\WPR;

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

	public function check_edit_moderation( $type, $id ) {
		$mod = false;

		if ( $type == 'forum' ) {
			$mod = gdfar()->is_allowed_for_forums();
		} else if ( $type == 'topic' ) {
			$mod = gdfar()->is_allowed_for_topic( $id );
		}

		if ( $mod === false ) {
			$this->error( __( "Invalid Request.", "gd-forum-manager-for-bbpress" ) );
		}
	}

	public function check_bulk_moderation( $type, $id ) {
		$mod = false;

		if ( $type == 'forum' ) {
			$mod = gdfar()->is_allowed_for_forums();
		} else if ( $type == 'topic' ) {
			foreach ( $id as $topic_id ) {
				$mod = gdfar()->is_allowed_for_topic( $topic_id );

				if ( $mod === false ) {
					break;
				}
			}
		}

		if ( $mod === false ) {
			$this->error( __( "Invalid Request.", "gd-forum-manager-for-bbpress" ) );
		}
	}

	public function admin_check_nonce( $action = 'gdfar-admin-internal', $nonce = '_ajax_nonce' ) {
		$check = wp_verify_nonce( $_REQUEST[ $nonce ], $action );

		if ( $check === false ) {
			wp_die( - 1 );
		}
	}

	public function toggle_option() {
		if ( WPR::is_current_user_admin() ) {
			$name = Sanitize::key_expanded( $_POST['option'] );

			$this->admin_check_nonce( 'gdfar-toggle-option-' . $name );

			$new_value = ! gdfar_settings()->get( $name );
			gdfar_settings()->set( $name, $new_value, 'settings', true );

			die( _gdfar_display_option( $name ) );
		}

		die( __( "Invalid Request", "gd-forum-manager-for-bbpress" ) );
	}

	public function edit_request() {
		$is    = isset( $_REQUEST['is'] ) ? Sanitize::slug( $_REQUEST['is'] ) : '';
		$forum = isset( $_REQUEST['forum'] ) ? absint( $_REQUEST['forum'] ) : 0;
		$type  = isset( $_REQUEST['type'] ) ? Sanitize::slug( $_REQUEST['type'] ) : '';
		$id    = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;

		$this->check_nonce( $is, $forum );
		$this->check_edit_moderation( $type, $id );

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
		if ( isset( $_REQUEST['gdfar'] ) ) {
			$data = (array) $_REQUEST['gdfar'];

			$log = false;

			if ( isset( $data['edit-log'] ) ) {
				if ( isset( $data['edit-log']['keep'] ) ) {
					$log = array(
						'keep'   => true,
						'reason' => isset( $data['edit-log']['reason'] ) ? Sanitize::basic( $data['edit-log']['reason'] ) : ''
					);
				}
			}

			$data['action']   = isset( $data['action'] ) ? Sanitize::slug( $data['action'] ) : '';
			$data['type']     = isset( $data['type'] ) ? Sanitize::slug( $data['type'] ) : '';
			$data['nonce']    = isset( $data['nonce'] ) ? Sanitize::basic( $data['nonce'] ) : '';
			$data['id']       = isset( $data['id'] ) ? absint( $data['id'] ) : 0;
			$data['field']    = isset( $data['field'] ) ? gdfar_array_sanitize_text_field( (array) $data['field'] ) : array();
			$data['edit-log'] = $log;

			if (
				in_array( $data['type'], array(
					'forum',
					'topic'
				) ) && $data['id'] > 0 || $data['action'] == 'edit' || ! empty( $nonce ) ) {
				if ( wp_verify_nonce( $data['nonce'], 'gdfar-manager-edit-' . $data['type'] . '-' . $data['id'] ) ) {
					$this->check_edit_moderation( $data['type'], $data['id'] );

					do_action( 'gdfar_ajax_edit_process_start', $data );

					$result = Process::instance()->init( $data )->edit();

					do_action( 'gdfar_ajax_edit_process_end', $data, $result );

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
		$is    = isset( $_REQUEST['is'] ) ? Sanitize::slug( $_REQUEST['is'] ) : '';
		$forum = isset( $_REQUEST['forum'] ) ? absint( $_REQUEST['forum'] ) : 0;
		$type  = isset( $_REQUEST['type'] ) ? Sanitize::slug( $_REQUEST['type'] ) : '';
		$id    = isset( $_REQUEST['id'] ) ? Sanitize::ids_list( $_REQUEST['id'] ) : array();

		$this->check_nonce( $is, $forum );
		$this->check_bulk_moderation( $type, $id );

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
		if ( isset( $_REQUEST['gdfar'] ) ) {
			$data = (array) $_REQUEST['gdfar'];

			$log = false;

			if ( isset( $data['edit-log'] ) ) {
				if ( isset( $data['edit-log']['keep'] ) ) {
					$log = array(
						'keep'   => true,
						'reason' => isset( $data['edit-log']['reason'] ) ? Sanitize::basic( $data['edit-log']['reason'] ) : ''
					);
				}
			}

			$data['action']   = isset( $data['action'] ) ? Sanitize::slug( $data['action'] ) : '';
			$data['type']     = isset( $data['type'] ) ? Sanitize::slug( $data['type'] ) : '';
			$data['nonce']    = isset( $data['nonce'] ) ? Sanitize::basic( $data['nonce'] ) : '';
			$data['field']    = isset( $data['field'] ) ? (array) $data['field'] : array();
			$data['id']       = isset( $data['id'] ) ? Sanitize::ids_list( $data['id'] ) : array();
			$data['edit-log'] = $log;

			if (
				in_array( $data['type'], array(
					'forum',
					'topic'
				) ) && ! empty( $data['id'] ) || $data['action'] == 'bulk' || ! empty( $nonce ) ) {
				if ( wp_verify_nonce( $data['nonce'], 'gdfar-manager-bulk-' . $data['type'] ) ) {
					$this->check_bulk_moderation( $data['type'], $data['id'] );

					do_action( 'gdfar_ajax_bulk_process_start', $data );

					$result = Process::instance()->init( $data )->bulk();

					do_action( 'gdfar_ajax_bulk_process_end', $data, $result );

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
