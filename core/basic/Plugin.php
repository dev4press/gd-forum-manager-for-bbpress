<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Plugin\GDFAR\bbPress\Integration;
use Dev4Press\Plugin\GDFAR\Manager\Actions;
use Dev4Press\Plugin\GDFAR\Manager\Defaults;
use Dev4Press\v56\Core\Plugins\Core;
use Dev4Press\v56\Core\Quick\WPR;
use Dev4Press\v56\Core\Shared\Enqueue;
use Dev4Press\v56\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends Core {
	public string $plugin = 'gd-forum-manager-for-bbpress';
	public string $theme_package = 'default';

	private bool $_active = false;
	private array $_roles = array();

	private $_bbpress = null;
	private $_actions = null;

	public function __construct() {
		$this->url  = GDFAR_URL;
		$this->path = GDFAR_PATH;

		parent::__construct();
	}

	public function s() {
		return gdfar_settings();
	}

	public function f() {
		return null;
	}

	public function b() {
		return null;
	}

	public function l() {
		return null;
	}

	public function run() {
		do_action( 'gdfar_load_settings' );

		if ( is_user_logged_in() ) {
			$this->_bbpress = new Integration();
			$this->_actions = new Actions();

			new Defaults();
		}

		$this->_roles[] = bbp_get_keymaster_role();

		if ( gdfar_settings()->get( 'moderators' ) ) {
			$this->_roles[] = bbp_get_moderator_role();
		}

		if ( get_option( '_bbp_theme_package_id' ) == 'quantum' ) {
			$this->theme_package = 'quantum';
		}

		add_action( 'init', array( $this, 'plugin_init' ), 20 );
		add_action( 'wp', array( $this, 'plugin_wp' ), 20 );

		if ( ! is_admin() ) {
			Enqueue::init();

			add_action( 'd4plib_shared_enqueue_prepare', array( $this, 'register_css_and_js' ) );
		}
	}

	public function register_css_and_js() : void {
		$folder = WordPress::i()->is_script_debug() ? 'src' : 'build';

		Enqueue::i()->add_css( 'gdfar-micromodal', array(
			'lib'  => false,
			'url'  => GDFAR_URL . 'build/css/',
			'file' => 'micromodal',
			'ver'  => gdfar_settings()->file_version(),
			'ext'  => 'css',
			'min'  => false,
		) );

		Enqueue::i()->add_css( 'gdfar-manager', array(
			'lib'  => false,
			'url'  => GDFAR_URL . 'build/css/',
			'file' => 'manager',
			'ver'  => gdfar_settings()->file_version(),
			'ext'  => 'css',
			'min'  => false,
			'int'  => array( 'gdfar-micromodal' ),
		) );

		Enqueue::i()->add_css( 'gdfar-manager-rtl', array(
			'lib'  => false,
			'url'  => GDFAR_URL . 'build/css/',
			'file' => 'manager-rtl',
			'ver'  => gdfar_settings()->file_version(),
			'ext'  => 'css',
			'min'  => false,
			'int'  => array( 'gdfar-manager' ),
		) );

		Enqueue::i()->add_js( 'gdfar-micromodal', array(
			'lib'    => false,
			'url'    => GDFAR_URL . 'build/js/',
			'file'   => 'micromodal',
			'ver'    => '0.7.0',
			'ext'    => 'js',
			'min'    => false,
			'footer' => true,
		) );

		Enqueue::i()->add_js( 'gdfar-manager', array(
			'lib'      => false,
			'url'      => GDFAR_URL . $folder . '/js/',
			'file'     => 'manager',
			'ver'      => gdfar_settings()->file_version(),
			'ext'      => 'js',
			'min'      => false,
			'footer'   => true,
			'localize' => true,
			'req'      => array( 'jquery', 'jquery-form' ),
			'int'      => array( 'gdfar-micromodal' ),
		) );
	}

	public function after_setup_theme() : void {
		do_action( 'gdfar_after_setup_theme' );
	}

	public function plugin_init() : void {
		if ( ! is_admin() && is_user_logged_in() ) {
			$this->_active = true;
		}

		define( 'GDFAR_EDITOR_ACTIVE', $this->_active );

		do_action( 'gdfar_plugin_init' );
	}

	public function plugin_wp() : void {
		do_action( 'gdfar_plugin_wp' );
	}

	public function bbpress() : Integration {
		return $this->_bbpress;
	}

	public function actions() : Actions {
		return $this->_actions;
	}

	public function is_allowed_for_forums() : bool {
		return is_user_logged_in() && WPR::is_current_user_roles( $this->_roles );
	}

	public function is_allowed_for_forum( $forum_id ) : bool {
		$allowed = $this->is_allowed_for_forums();

		if ( ! $allowed && gdfar_settings()->get( 'forum_moderators' ) ) {
			$allowed = current_user_can( 'moderate', $forum_id );
		}

		return $allowed;
	}

	public function is_allowed_for_topic( $topic_id ) : bool {
		$allowed = $this->is_allowed_for_forums();

		if ( ! $allowed && gdfar_settings()->get( 'forum_moderators' ) ) {
			$allowed = current_user_can( 'moderate', $topic_id );
		}

		return $allowed;
	}

	public function allowed() : bool {
		_deprecated_function( __METHOD__, '2.1' );

		$allowed = false;

		if ( is_user_logged_in() ) {
			$allowed = WPR::is_current_user_roles( $this->_roles );
		}

		return $allowed;
	}
}
