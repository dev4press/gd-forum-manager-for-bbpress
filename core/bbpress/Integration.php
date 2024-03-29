<?php

namespace Dev4Press\Plugin\GDFAR\bbPress;

use Dev4Press\v45\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Integration {
	public $_always_on = false;
	public $_queued = false;
	public $_key = 1;

	public function __construct() {
		add_action( 'gdfar_plugin_init', array( $this, 'init' ), 20 );
		add_action( 'gdfar_plugin_wp', array( $this, 'wp' ), 20 );
	}

	public function init() {
		if ( gdfar_settings()->get( 'small_screen_always_show' ) ) {
			$this->_always_on = true;
		}
	}

	public function wp() {
		if ( gdfar_settings()->get( 'forum' ) && gdfar()->is_allowed_for_forums() ) {
			add_action( 'bbp_theme_before_forum_title', array( $this, 'forum_controls' ), 1 );
			add_action( 'bbp_template_after_forums_loop', array( $this, 'forum_bulk' ) );
		}

		if ( gdfar_settings()->get( 'topic' ) && gdfar()->is_allowed_for_forum( bbp_get_forum_id() ) ) {
			add_action( 'bbp_theme_before_topic_title', array( $this, 'topic_controls' ), 1 );
			add_action( 'bbp_template_after_topics_loop', array( $this, 'topic_bulk' ) );
			add_filter( 'bbp_topic_admin_links', array( $this, 'topic_admin_links' ), 10, 2 );
		}
	}

	public function enqueue() {
		$this->_queued = true;

		if ( is_rtl() ) {
			wp_enqueue_style( 'gdfar-manager-rtl' );
		} else {
			wp_enqueue_style( 'gdfar-manager' );
		}

		wp_enqueue_script( 'gdfar-manager' );

		$id = bbp_is_single_forum()
			? bbp_get_forum_id()
			: (
			bbp_is_single_topic() ? bbp_get_topic_forum_id() : 0
			);
		$is = bbp_is_single_forum()
			? 'forum'
			: (
			bbp_is_single_topic()
				? 'topic'
				: (
			bbp_is_single_view()
				? 'view'
				: (
			bbp_is_topic_archive()
				? 'topic-archive'
				: (
			bbp_is_forum_archive() ? 'forum-archive' : ''
			) ) ) );

		wp_localize_script( 'gdfar-manager', 'gdfar_manager_data', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'gdfar-manager-request-' . $is . '-' . $id ),
			'bbpress' => array(
				'is'       => $is,
				'forum_id' => $id,
			),
			'message' => array(
				'please_wait' => esc_html__( 'Please Wait...', 'gd-forum-manager-for-bbpress' ),
			),
			'titles'  => array(
				'edit' => array(
					'forum' => esc_html_x( 'Edit Forum', 'Edit Modal Dialog, Title', 'gd-forum-manager-for-bbpress' ),
					'topic' => esc_html_x( 'Edit Topic', 'Edit Modal Dialog, Title', 'gd-forum-manager-for-bbpress' ),
				),
				'bulk' => array(
					'forum' => esc_html_x( 'Edit selected Forums', 'Edit Modal Dialog, Title', 'gd-forum-manager-for-bbpress' ),
					'topic' => esc_html_x( 'Edit selected Topics', 'Edit Modal Dialog, Title', 'gd-forum-manager-for-bbpress' ),
				),
			),
		) );

		do_action( 'gdfar_plugin_enqueue_scripts' );
	}

	public function forum_controls() {
		$forum_id = absint( bbp_get_forum_id() );

		$classes = array( 'gdfar-ctrl-wrapper', 'gdfar-ctrl-forum' );

		if ( $this->_always_on ) {
			$classes[] = 'is-always-on';
		}

		$_link_edit = apply_filters( 'gdfar_control_forum_edit', esc_html__( 'edit', 'gd-forum-manager-for-bbpress' ) );
		$_link_aria = apply_filters( 'gdfar_control_forum_edit_aria_label', sprintf( esc_html__( 'Open popup to edit \'%s\' forum', 'gd-forum-manager-for-bbpress' ), bbp_get_forum_title() ) );
		$_bulk_aria = apply_filters( 'gdfar_control_forum_edit_bulk_aria_label', sprintf( esc_html__( 'Enable bulk edit for \'%s\' forum', 'gd-forum-manager-for-bbpress' ), bbp_get_forum_title() ) );

		echo '<div class="' . Sanitize::html_classes( $classes ) . '" data-key="' . $this->_key . '" data-type="forum" data-id="' . $forum_id . '">';
		echo '<input aria-label="' . $_bulk_aria . '" type="checkbox" class="gdfar-ctrl-checkbox" />';
		echo '<a aria-label="' . $_link_aria . '" href="#" class="gdfar-ctrl-edit">' . $_link_edit . '</a>';
		echo '</div>';

		if ( ! $this->_queued ) {
			$this->enqueue();

			add_action( 'wp_footer', array( $this, 'modals' ) );
		}
	}

	public function topic_controls() {
		$topic_id = absint( bbp_get_topic_id() );

		if ( gdfar()->is_allowed_for_topic( $topic_id ) ) {
			$classes = array( 'gdfar-ctrl-wrapper', 'gdfar-ctrl-topic' );

			if ( $this->_always_on ) {
				$classes[] = 'is-always-on';
			}

			$_link_edit = apply_filters( 'gdfar_control_topic_edit', esc_html__( 'edit', 'gd-forum-manager-for-bbpress' ) );
			$_link_aria = apply_filters( 'gdfar_control_topic_edit_aria_label', sprintf( esc_html__( 'Open popup to edit \'%s\' topic', 'gd-forum-manager-for-bbpress' ), bbp_get_topic_title() ) );
			$_bulk_aria = apply_filters( 'gdfar_control_topic_edit_bulk_aria_label', sprintf( esc_html__( 'Enable bulk edit for \'%s\' topic', 'gd-forum-manager-for-bbpress' ), bbp_get_topic_title() ) );

			echo '<div class="' . Sanitize::html_classes( $classes ) . '" data-key="' . $this->_key . '" data-type="topic" data-id="' . $topic_id . '">';
			echo '<input aria-label="' . $_bulk_aria . '" type="checkbox" class="gdfar-ctrl-checkbox" />';
			echo '<a aria-label="' . $_link_aria . '" href="#" class="gdfar-ctrl-edit">' . $_link_edit . '</a>';
			echo '</div>';

			if ( ! $this->_queued ) {
				$this->enqueue();

				add_action( 'wp_footer', array( $this, 'modals' ) );
			}
		}
	}

	public function topic_admin_links( $links, $topic_id ) : array {
		if ( gdfar()->is_allowed_for_topic( $topic_id ) ) {
			$_edit = apply_filters( 'gdfar_control_topic_quick_edit', esc_html__( 'Quick Edit', 'gd-forum-manager-for-bbpress' ) );

			$links = array( 'quick-edit' => '<a class="bbp-topic-quick-edit-link" href="#" data-id="' . $topic_id . '">' . $_edit . '</a>' ) + $links;

			if ( ! $this->_queued ) {
				$this->enqueue();

				add_action( 'wp_footer', array( $this, 'modal_quick' ) );
			}
		}

		return $links;
	}

	public function modals() {
		require_once( GDFAR_PATH . 'forms/manager/dialog-edit.php' );
		require_once( GDFAR_PATH . 'forms/manager/dialog-bulk.php' );
	}

	public function modal_quick() {
		require_once( GDFAR_PATH . 'forms/manager/dialog-edit.php' );
	}

	public function forum_bulk() {
		echo '<div class="gdfar-bulk-control gdfar-bulk-forum-' . $this->_key . '" aria-hidden="true" data-type="forum" data-key="' . $this->_key . '">';
		echo '<div class="__status">' . esc_html__( 'Selected Forums', 'gd-forum-manager-for-bbpress' ) . ': <span class="__selected">0</span>/<span class="__total">0</span></div>';
		echo '<div class="__select"><a class="__all" href="#all">' . esc_html__( 'select all', 'gd-forum-manager-for-bbpress' ) . '</a> &middot; <a class="__none" href="#none">' . esc_html__( 'select none', 'gd-forum-manager-for-bbpress' ) . '</a></div>';
		echo '<div class="__editor"><button class="gdfar-ctrl-bulk">' . esc_html__( 'Edit Selected Forums', 'gd-forum-manager-for-bbpress' ) . '</button></div>';
		echo '</div>';

		$this->_key ++;
	}

	public function topic_bulk() {
		echo '<div class="gdfar-bulk-control gdfar-bulk-topic-' . $this->_key . '" aria-hidden="true" data-type="topic" data-key="' . $this->_key . '">';
		echo '<div class="__status">' . esc_html__( 'Selected Topics', 'gd-forum-manager-for-bbpress' ) . ': <span class="__selected">0</span>/<span class="__total">0</span></div>';
		echo '<div class="__select"><a class="__all" href="#all">' . esc_html__( 'select all', 'gd-forum-manager-for-bbpress' ) . '</a> &middot; <a class="__none" href="#none">' . esc_html__( 'select none', 'gd-forum-manager-for-bbpress' ) . '</a></div>';
		echo '<div class="__editor"><button class="gdfar-ctrl-bulk">' . esc_html__( 'Edit Selected Topics', 'gd-forum-manager-for-bbpress' ) . '</button></div>';
		echo '</div>';

		$this->_key ++;
	}
}
