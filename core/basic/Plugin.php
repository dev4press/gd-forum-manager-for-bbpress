<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\DateTime;
use Dev4Press\Core\Plugins\Core;
use Dev4Press\Core\Shared\Enqueue;
use Dev4Press\Plugin\GDFAR\bbPress\Integration;
use Dev4Press\Plugin\GDFAR\Manager\Actions;
use Dev4Press\Plugin\GDFAR\Manager\Defaults;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends Core {
	public $plugin = 'gd-forum-manager-for-bbpress';

	private $_datetime;
	private $_bbpress = null;
	private $_actions = null;
	private $_roles = array();

	public $theme_package = 'default';

	public function __construct() {
		$this->url = GDFAR_URL;

		$this->_datetime = new DateTime();

		parent::__construct();
	}

	public function s() {
		return gdfar_settings();
	}

	public function run() {
		define( 'GDFAR_WPV', intval( $this->wp_version ) );
		define( 'GDFAR_WPV_MAJOR', substr( $this->wp_version, 0, 3 ) );

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

		if ( ! is_admin() ) {
			Enqueue::init( GDFAR_URL . 'd4plib/' );

			add_action( 'd4plib_shared_enqueue_prepare', array( $this, 'register_css_and_js' ) );
		}
	}

	public function register_css_and_js() {
		Enqueue::i()->add_css( 'gdfar-micromodal', array(
			'lib'  => false,
			'url'  => GDFAR_URL . 'css/',
			'file' => 'micromodal',
			'ver'  => gdfar_settings()->file_version(),
			'ext'  => 'css',
			'min'  => true,
			'int'  => array()
		) );

		Enqueue::i()->add_css( 'gdfar-manager', array(
			'lib'  => false,
			'url'  => GDFAR_URL . 'css/',
			'file' => 'manager',
			'ver'  => gdfar_settings()->file_version(),
			'ext'  => 'css',
			'min'  => true,
			'int'  => array( 'gdfar-micromodal' )
		) );

		Enqueue::i()->add_css( 'gdfar-manager-rtl', array(
			'lib'  => false,
			'url'  => GDFAR_URL . 'css/',
			'file' => 'manager-rtl',
			'ver'  => gdfar_settings()->file_version(),
			'ext'  => 'css',
			'min'  => true,
			'int'  => array( 'gdfar-manager' )
		) );

		Enqueue::i()->add_js( 'gdfar-micromodal', array(
			'lib'      => false,
			'url'      => GDFAR_URL . 'js/',
			'file'     => 'micromodal',
			'ver'      => gdfar_settings()->file_version(),
			'ext'      => 'js',
			'min'      => true,
			'footer'   => true,
			'localize' => true,
			'int'      => array()
		) );

		Enqueue::i()->add_js( 'gdfar-manager', array(
			'lib'      => false,
			'url'      => GDFAR_URL . 'js/',
			'file'     => 'manager',
			'ver'      => gdfar_settings()->file_version(),
			'ext'      => 'js',
			'min'      => true,
			'footer'   => true,
			'localize' => true,
			'req'      => array( 'jquery', 'jquery-form' ),
			'int'      => array( 'gdfar-micromodal' )
		) );
	}

	public function after_setup_theme() {
		do_action( 'gdfar_after_setup_theme' );
	}

	public function plugin_init() {
		do_action( 'gdfar_plugin_init' );
	}

	public function datetime() : DateTime {
		return $this->_datetime;
	}

	public function bbpress() : Integration {
		return $this->_bbpress;
	}

	public function actions() : Actions {
		return $this->_actions;
	}

	public function allowed() : bool {
		$allowed = false;

		if ( is_user_logged_in() ) {
			$allowed = d4p_is_current_user_roles( $this->_roles );
		}

		return $allowed;
	}
}
