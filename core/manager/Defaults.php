<?php

namespace Dev4Press\Plugin\GDFAR\Manager;

use WP_Error;

class Defaults {
	private $_defaults = array(
		'forum' => array(
			'edit' => array( 'rename', 'status', 'visibility' ),
			'bulk' => array( 'status', 'visibility' )
		),
		'topic' => array(
			'edit' => array( 'rename', 'forum', 'status', 'sticky', 'tags' ),
			'bulk' => array( 'status', 'forum', 'sticky', 'cleartags' )
		)
	);

	public function __construct() {
		foreach ( $this->_defaults as $scope => $actions ) {
			foreach ( $actions as $action => $names ) {
				foreach ( $names as $name ) {
					$key    = $scope . '-' . $action . '-' . $name;
					$method = $scope . '_' . $action . '_' . str_replace( '-', '_', $name );

					add_filter( 'gdfar-action-visible-' . $key, '__return_true' );
					add_filter( 'gdfar-action-display-' . $key, array( $this, 'display_' . $method ), 10, 2 );
					add_filter( 'gdfar-action-process-' . $key, array( $this, 'process_' . $method ), 10, 2 );
				}
			}
		}
	}

	public function modded( $type, $id ) {
		Process::instance()->modded( $type, $id );
	}

	private function _get_list_for_stickies() : array {
		return array(
			'no'     => __( "No", "gd-forum-manager-for-bbpress" ),
			'sticky' => __( "Sticky", "gd-forum-manager-for-bbpress" ),
			'super'  => __( "Super Sticky", "gd-forum-manager-for-bbpress" )
		);
	}

	private function _get_topic_sticky_status( $topic_id ) : string {
		return bbp_is_topic_super_sticky( $topic_id )
			? 'super'
			: (
			bbp_is_topic_sticky( $topic_id ) ? 'sticky' : 'no' );
	}

	private function _update_forum_status( $status, $forum_id ) {
		switch ( $status ) {
			case 'open':
				do_action( 'bbp_opened_forum', $forum_id );
				break;
			case 'closed':
				do_action( 'bbp_closed_closed', $forum_id );
				break;
		}
	}

	private function _before_update_topic_status( $status, $old_status, $topic_id ) {
		switch ( $status ) {
			case 'publish':
				if ( $old_status == 'pending' ) {
					do_action( 'bbp_approve_topic', $topic_id );
				} else if ( $old_status == 'spam' ) {
					do_action( 'bbp_unspam_topic', $topic_id );
				} else if ( $old_status == 'trash' ) {
					do_action( 'bbp_untrash_topic', $topic_id );
				} else {
					do_action( 'bbp_open_topic', $topic_id );
				}
				break;
			case 'closed':
				do_action( 'bbp_close_topic', $topic_id );
				break;
			case 'spam':
				do_action( 'bbp_spam_topic', $topic_id );
				break;
			case 'trash':
				do_action( 'bbp_trash_topic', $topic_id );
				break;
			case 'pending':
				do_action( 'bbp_unapprove_topic', $topic_id );
				break;
		}
	}

	private function _after_update_topic_status( $status, $old_status, $topic_id ) {
		switch ( $status ) {
			case 'publish':
				if ( $old_status == 'pending' ) {
					do_action( 'bbp_approved_topic', $topic_id );
				} else if ( $old_status == 'spam' ) {
					do_action( 'bbp_unspammed_topic', $topic_id );
				} else if ( $old_status == 'trash' ) {
					do_action( 'bbp_untrashed_topic', $topic_id );
				} else {
					do_action( 'bbp_opened_topic', $topic_id );
				}
				break;
			case 'closed':
				do_action( 'bbp_closed_topic', $topic_id );
				break;
			case 'spam':
				do_action( 'bbp_spammed_topic', $topic_id );
				break;
			case 'trash':
				do_action( 'bbp_trashed_topic', $topic_id );
				break;
			case 'pending':
				do_action( 'bbp_unapproved_topic', $topic_id );
				break;
		}
	}

	public function display_forum_edit_rename( $render, $args = array() ) : string {
		return '<input id="' . esc_attr( $args['element'] ) . '" type="text" name="' . esc_attr( $args['base'] ) . '[title]" value="' . esc_attr( bbp_get_forum_title( $args['id'] ) ) . '" />';
	}

	public function display_forum_edit_status( $render, $args = array() ) : string {
		$list = bbp_get_forum_statuses( $args['id'] );

		return gdfar_render()->select( $list, array(
			'selected' => bbp_get_forum_status( $args['id'] ),
			'name'     => $args['base'] . '[status]',
			'id'       => $args['element']
		) );
	}

	public function display_forum_edit_visibility( $render, $args = array() ) : string {
		$list = bbp_get_forum_visibilities( $args['id'] );

		return gdfar_render()->select( $list, array(
			'selected' => bbp_get_forum_visibility( $args['id'] ),
			'name'     => $args['base'] . '[visibility]',
			'id'       => $args['element']
		) );
	}

	public function process_forum_edit_rename( $result, $args = array() ) {
		$forum_id  = $args['id'];
		$new_title = isset( $args['value']['title'] ) ? sanitize_text_field( $args['value']['title'] ) : '';
		$old_title = bbp_get_forum_title( $forum_id );

		if ( ! empty( $new_title ) && $old_title != $new_title ) {
			$forum_title = apply_filters( 'bbp_edit_forum_pre_title', $new_title, $forum_id );

			if ( bbp_is_title_too_long( $forum_title ) ) {
				return new WP_Error( "title_too_long", __( "The title is too long.", "gd-forum-manager-for-bbpress" ) );
			}

			$update = wp_update_post( array(
				'ID'         => $forum_id,
				'post_title' => $forum_title
			), true );

			if ( is_wp_error( $update ) ) {
				return $update;
			}

			$this->modded( 'forum', $forum_id );
		}

		return $result;
	}

	public function process_forum_edit_status( $result, $args = array() ) {
		$list = bbp_get_forum_statuses( $args['id'] );

		$forum_id   = $args['id'];
		$new_status = isset( $args['value']['status'] ) ? sanitize_text_field( $args['value']['status'] ) : '';
		$old_status = bbp_get_forum_status( $forum_id );

		if ( empty( $new_status ) || ! isset( $list[ $new_status ] ) ) {
			return new WP_Error( "invalid_status", __( "Invalid status value.", "gd-forum-manager-for-bbpress" ) );
		}

		if ( $old_status != $new_status ) {
			if ( $new_status == 'closed' ) {
				bbp_close_forum( $forum_id );
			} else {
				bbp_open_forum( $forum_id );
			}

			$this->_update_forum_status( $new_status, $forum_id );

			$this->modded( 'forum', $forum_id );
		}

		return $result;
	}

	public function process_forum_edit_visibility( $result, $args = array() ) {
		$list = bbp_get_forum_visibilities( $args['id'] );

		$forum_id   = $args['id'];
		$new_status = isset( $args['value']['visibility'] ) ? sanitize_text_field( $args['value']['visibility'] ) : '';
		$old_status = bbp_get_forum_visibility( $forum_id );

		if ( empty( $new_status ) || ! isset( $list[ $new_status ] ) ) {
			return new WP_Error( "invalid_status", __( "Invalid visibility value.", "gd-forum-manager-for-bbpress" ) );
		}

		if ( $old_status != $new_status ) {
			$update = wp_update_post( array(
				'ID'          => $forum_id,
				'post_status' => $new_status
			), true );

			if ( is_wp_error( $update ) ) {
				return $update;
			}

			$this->modded( 'forum', $forum_id );
		}

		return $result;
	}

	public function display_forum_bulk_status( $render, $args = array() ) {
		$list = array_merge( array( '' => __( "Don't change", "gd-forum-manager-for-bbpress" ) ), bbp_get_forum_statuses() );

		return gdfar_render()->select( $list, array(
			'selected' => '',
			'name'     => $args['base'] . '[status]',
			'id'       => $args['element']
		) );
	}

	public function display_forum_bulk_visibility( $render, $args = array() ) {
		$list = array_merge( array( '' => __( "Don't change", "gd-forum-manager-for-bbpress" ) ), bbp_get_forum_visibilities() );

		return gdfar_render()->select( $list, array(
			'selected' => '',
			'name'     => $args['base'] . '[visibility]',
			'id'       => $args['element']
		) );
	}

	public function process_forum_bulk_status( $result, $args = array() ) {
		$list = bbp_get_forum_statuses();

		$new_status = isset( $args['value']['status'] ) ? sanitize_text_field( $args['value']['status'] ) : '';

		if ( ! empty( $new_status ) ) {
			if ( ! isset( $list[ $new_status ] ) ) {
				return new WP_Error( "invalid_status", __( "Invalid status value.", "gd-forum-manager-for-bbpress" ) );
			}

			foreach ( $args['id'] as $forum_id ) {
				$old_status = bbp_get_forum_status( $forum_id );

				if ( $old_status != $new_status ) {
					if ( $new_status == 'closed' ) {
						bbp_close_forum( $forum_id );
					} else {
						bbp_open_forum( $forum_id );
					}

					$this->_update_forum_status( $new_status, $forum_id );

					$this->modded( 'forum', $forum_id );
				}
			}
		}

		return $result;
	}

	public function process_forum_bulk_visibility( $result, $args = array() ) {
		$list = bbp_get_forum_visibilities();

		$new_status = isset( $args['value']['visibility'] ) ? sanitize_text_field( $args['value']['visibility'] ) : '';

		if ( ! empty( $new_status ) ) {
			if ( ! isset( $list[ $new_status ] ) ) {
				return new WP_Error( "invalid_status", __( "Invalid visibility value.", "gd-forum-manager-for-bbpress" ) );
			}

			foreach ( $args['id'] as $forum_id ) {
				$old_status = bbp_get_forum_visibility( $forum_id );

				if ( $old_status != $new_status ) {
					$update = wp_update_post( array(
						'ID'          => $forum_id,
						'post_status' => $new_status
					), true );

					if ( is_wp_error( $update ) ) {
						return $update;
					}

					$this->modded( 'forum', $forum_id );
				}
			}
		}

		return $result;
	}

	public function display_topic_edit_rename( $render, $args = array() ) {
		return '<input id="' . esc_attr( $args['element'] ) . '" type="text" name="' . esc_attr( $args['base'] ) . '[title]" value="' . esc_attr( bbp_get_topic_title( $args['id'] ) ) . '" />';
	}

	public function display_topic_edit_tags( $render, $args = array() ) {
		return '<input id="' . esc_attr( $args['element'] ) . '" type="text" name="' . esc_attr( $args['base'] ) . '[topic-tags]" value="' . esc_attr( bbp_get_topic_tag_names( $args['id'] ) ) . '" />';
	}

	public function display_topic_edit_forum( $render, $args = array() ) {
		return bbp_get_dropdown( array(
			'selected'     => bbp_get_topic_forum_id( $args['id'] ),
			'select_class' => 'bbp_dropdown gdfar-full-width',
			'show_none'    => false,
			'select_id'    => $args['base'] . '[forum]'
		) );
	}

	public function display_topic_edit_status( $render, $args = array() ) {
		$list = bbp_get_topic_statuses( $args['id'] );

		return gdfar_render()->select( $list, array(
			'selected' => bbp_get_topic_status( $args['id'] ),
			'name'     => $args['base'] . '[status]',
			'id'       => $args['element']
		) );
	}

	public function display_topic_edit_sticky( $render, $args = array() ) {
		$list = $this->_get_list_for_stickies();

		return gdfar_render()->select( $list, array(
			'selected' => $this->_get_topic_sticky_status( $args['id'] ),
			'name'     => $args['base'] . '[sticky]',
			'id'       => $args['element']
		) );
	}

	public function process_topic_edit_rename( $result, $args = array() ) {
		$topic_id  = $args['id'];
		$new_title = isset( $args['value']['title'] ) ? sanitize_text_field( $args['value']['title'] ) : '';
		$old_title = bbp_get_topic_title( $topic_id );

		if ( ! empty( $new_title ) && $old_title != $new_title ) {
			$topic_title = apply_filters( 'bbp_edit_topic_pre_title', $new_title, $topic_id );

			if ( bbp_is_title_too_long( $topic_title ) ) {
				return new WP_Error( "title_too_long", __( "The title is too long.", "gd-forum-manager-for-bbpress" ) );
			}

			$update = wp_update_post( array(
				'ID'         => $topic_id,
				'post_title' => $topic_title
			), true );

			if ( is_wp_error( $update ) ) {
				return $update;
			}

			$this->modded( 'topic', $topic_id );
		}

		return $result;
	}

	public function process_topic_edit_tags( $result, $args = array() ) {
		$topic_id = $args['id'];
		$terms    = isset( $args['value']['topic-tags'] ) ? sanitize_text_field( $args['value']['topic-tags'] ) : '';
		$current  = bbp_get_topic_tag_names( $topic_id );

		if ( ! taxonomy_exists( bbp_get_topic_tag_tax_id() ) ) {
			return new WP_Error( 'invalid_taxonomy', __( "Topic Tags taxonomy not found.", "gd-forum-manager-for-bbpress" ) );
		}

		if ( strstr( $terms, ',' ) ) {
			$terms = explode( ',', $terms );
		}

		$terms = array( bbp_get_topic_tag_tax_id() => $terms );

		$update = wp_update_post( array(
			'ID'        => $topic_id,
			'tax_input' => $terms
		), true );

		if ( is_wp_error( $update ) ) {
			return $update;
		}

		wp_cache_flush();

		$updated = bbp_get_topic_tag_names( $topic_id );

		if ( $updated != $current ) {
			$this->modded( 'topic', $topic_id );
		}

		return $result;
	}

	public function process_topic_edit_forum( $result, $args = array() ) {
		$topic_id  = $args['id'];
		$new_forum = isset( $args['value']['forum'] ) ? absint( $args['value']['forum'] ) : 0;
		$old_forum = bbp_get_topic_forum_id( $topic_id );

		if ( $new_forum > 0 ) {
			if ( ! bbp_is_forum( $new_forum ) ) {
				return new WP_Error( "invalid_forum", __( "Invalid forum ID.", "gd-forum-manager-for-bbpress" ) );
			}

			if ( $old_forum != $new_forum ) {
				bbp_move_topic_handler( $topic_id, $old_forum, $new_forum );

				$this->modded( 'topic', $topic_id );
			}
		}

		return $result;
	}

	public function process_topic_edit_status( $result, $args = array() ) {
		$list = bbp_get_topic_statuses( $args['id'] );

		$topic_id   = $args['id'];
		$new_status = isset( $args['value']['status'] ) ? sanitize_text_field( $args['value']['status'] ) : '';
		$old_status = bbp_get_topic_status( $topic_id );

		if ( empty( $new_status ) || ! isset( $list[ $new_status ] ) ) {
			return new WP_Error( "invalid_status", __( "Invalid status value.", "gd-forum-manager-for-bbpress" ) );
		}

		if ( $old_status != $new_status ) {
			$this->_before_update_topic_status( $new_status, $old_status, $topic_id );

			$update = wp_update_post( array(
				'ID'          => $topic_id,
				'post_status' => $new_status
			), true );

			$this->_after_update_topic_status( $new_status, $old_status, $topic_id );

			if ( is_wp_error( $update ) ) {
				return $update;
			}

			$this->modded( 'topic', $topic_id );
		}

		return $result;
	}

	public function process_topic_edit_sticky( $result, $args = array() ) {
		$list = $this->_get_list_for_stickies();

		$topic_id   = $args['id'];
		$new_status = isset( $args['value']['sticky'] ) ? sanitize_text_field( $args['value']['sticky'] ) : '';
		$old_status = $this->_get_topic_sticky_status( $topic_id );

		if ( empty( $new_status ) || ! isset( $list[ $new_status ] ) ) {
			return new WP_Error( "invalid_sticky", __( "Invalid sticky value.", "gd-forum-manager-for-bbpress" ) );
		}

		if ( $old_status != $new_status ) {
			bbp_unstick_topic( $topic_id );

			switch ( $new_status ) {
				case 'sticky':
					bbp_stick_topic( $topic_id );
					break;
				case 'super':
					bbp_stick_topic( $topic_id, true );
					break;
			}

			$this->modded( 'topic', $topic_id );
		}

		return $result;
	}

	public function display_topic_bulk_forum( $render, $args = array() ) {
		return bbp_get_dropdown( array(
			'selected'     => 0,
			'select_class' => 'bbp_dropdown gdfar-full-width',
			'show_none'    => __( "Don't change", "gd-forum-manager-for-bbpress" ),
			'select_id'    => $args['base'] . '[forum]'
		) );
	}

	public function display_topic_bulk_status( $render, $args = array() ) {
		$list = array_merge( array( '' => __( "Don't change", "gd-forum-manager-for-bbpress" ) ), bbp_get_topic_statuses() );

		return gdfar_render()->select( $list, array(
			'selected' => '',
			'name'     => $args['base'] . '[status]',
			'id'       => $args['element']
		) );
	}

	public function display_topic_bulk_cleartags( $render, $args = array() ) {
		$list = array(
			''      => __( "Don't change", "gd-forum-manager-for-bbpress" ),
			'clear' => __( "Remove all topic tags", "gd-forum-manager-for-bbpress" )
		);

		return gdfar_render()->select( $list, array(
			'selected' => '',
			'name'     => $args['base'] . '[clear-tags]',
			'id'       => $args['element']
		) );
	}

	public function display_topic_bulk_sticky( $render, $args = array() ) {
		$list = array_merge( array( '' => __( "Don't change", "gd-forum-manager-for-bbpress" ) ), $this->_get_list_for_stickies() );

		return gdfar_render()->select( $list, array(
			'selected' => '',
			'name'     => $args['base'] . '[sticky]',
			'id'       => $args['element']
		) );
	}

	public function process_topic_bulk_forum( $result, $args = array() ) {
		$new_forum = isset( $args['value']['forum'] ) ? absint( $args['value']['forum'] ) : 0;

		if ( $new_forum > 0 ) {
			if ( ! bbp_is_forum( $new_forum ) ) {
				return new WP_Error( "invalid_forum", __( "Invalid forum ID.", "gd-forum-manager-for-bbpress" ) );
			}

			foreach ( $args['id'] as $topic_id ) {
				$old_forum = bbp_get_topic_forum_id( $topic_id );

				if ( $old_forum != $new_forum ) {
					bbp_move_topic_handler( $topic_id, $old_forum, $new_forum );

					$this->modded( 'topic', $topic_id );
				}
			}
		}

		return $result;
	}

	public function process_topic_bulk_cleartags( $result, $args = array() ) {
		$clear = isset( $args['value']['clear-tags'] ) && $args['value']['clear-tags'] === 'clear';

		if ( $clear ) {
			if ( ! taxonomy_exists( bbp_get_topic_tag_tax_id() ) ) {
				return new WP_Error( 'invalid_taxonomy', __( "Topic Tags taxonomy not found.", "gd-forum-manager-for-bbpress" ) );
			}

			foreach ( $args['id'] as $topic_id ) {
				$current = bbp_get_topic_tag_names( $topic_id );

				if ( ! empty( $current ) ) {
					$update = wp_set_object_terms( $topic_id, array(), bbp_get_topic_tag_tax_id() );

					if ( is_wp_error( $update ) ) {
						return $update;
					}

					$this->modded( 'topic', $topic_id );
				}
			}
		}

		return $result;
	}

	public function process_topic_bulk_status( $result, $args = array() ) {
		$list = bbp_get_topic_statuses();

		$new_status = isset( $args['value']['status'] ) ? sanitize_text_field( $args['value']['status'] ) : '';

		if ( ! empty( $new_status ) ) {
			if ( ! isset( $list[ $new_status ] ) ) {
				return new WP_Error( "invalid_status", __( "Invalid status value.", "gd-forum-manager-for-bbpress" ) );
			}

			foreach ( $args['id'] as $topic_id ) {
				$old_status = bbp_get_topic_status( $topic_id );

				if ( $old_status != $new_status ) {
					$this->_before_update_topic_status( $new_status, $old_status, $topic_id );

					$update = wp_update_post( array(
						'ID'          => $topic_id,
						'post_status' => $new_status
					), true );

					$this->_after_update_topic_status( $new_status, $old_status, $topic_id );

					if ( is_wp_error( $update ) ) {
						return $update;
					}

					$this->modded( 'topic', $topic_id );
				}
			}
		}

		return $result;
	}

	public function process_topic_bulk_sticky( $result, $args = array() ) {
		$list = $this->_get_list_for_stickies();

		$new_status = isset( $args['value']['sticky'] ) ? sanitize_text_field( $args['value']['sticky'] ) : '';

		if ( ! empty( $new_status ) ) {
			if ( ! isset( $list[ $new_status ] ) ) {
				return new WP_Error( "invalid_sticky", __( "Invalid sticky value.", "gd-forum-manager-for-bbpress" ) );
			}

			foreach ( $args['id'] as $topic_id ) {
				$old_status = $this->_get_topic_sticky_status( $topic_id );

				if ( $old_status != $new_status ) {
					bbp_unstick_topic( $topic_id );

					switch ( $new_status ) {
						case 'sticky':
							bbp_stick_topic( $topic_id );
							break;
						case 'super':
							bbp_stick_topic( $topic_id, true );
							break;
					}

					$this->modded( 'topic', $topic_id );
				}
			}
		}

		return $result;
	}
}
