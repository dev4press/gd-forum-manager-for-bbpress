<?php

namespace Dev4Press\Plugin\GDFAR\Admin;

use Dev4Press\Plugin\GDFAR\Basic\Settings;
use Dev4Press\v56\Core\Admin\Submenu\Plugin as BasePlugin;
use Dev4Press\v56\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends BasePlugin {
	public string $plugin = 'gd-forum-manager-for-bbpress';
	public string $plugin_prefix = 'gdfar';
	public string $plugin_menu = 'forumManager';
	public string $plugin_title = 'forumManager for bbPress';

	public bool $buy_me_a_coffee = true;
	public bool $auto_mod_interface_colors = true;

	public function constructor() : void {
		$this->url  = GDFAR_URL;
		$this->path = GDFAR_PATH;
	}

	public function register_scripts_and_styles() : void {
		$folder = WordPress::i()->is_script_debug() ? 'src' : 'build';

		$this->enqueue->register( 'js', 'gdfar-admin',
			array(
				'path' => $folder . '/js/',
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => false,
				'ver'  => gdfar_settings()->file_version(),
				'src'  => 'plugin',
			) )->register( 'css', 'gdfar-admin',
			array(
				'path' => 'build/css/',
				'file' => 'admin',
				'ext'  => 'css',
				'min'  => false,
				'ver'  => gdfar_settings()->file_version(),
				'src'  => 'plugin',
			) );
	}

	public function admin_menu_items() : void {
		$this->setup_items = array(
			'install' => array(
				'title' => __( 'Install', 'gd-forum-manager-for-bbpress' ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( 'Before you continue, make sure plugin installation was successful.', 'gd-forum-manager-for-bbpress' ),
				'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\Install',
			),
			'update'  => array(
				'title' => __( 'Update', 'gd-forum-manager-for-bbpress' ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( 'Before you continue, make sure plugin was successfully updated.', 'gd-forum-manager-for-bbpress' ),
				'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\Update',
			),
		);

		$this->menu_items = array(
			'dashboard' => array(
				'title' => __( 'Getting Started', 'gd-forum-manager-for-bbpress' ),
				'icon'  => 'ui-home',
				'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\Dashboard',
			),
			'about'     => array(
				'title' => __( 'About', 'gd-forum-manager-for-bbpress' ),
				'icon'  => 'ui-info',
				'class' => '\\Dev4Press\\Plugin\\GDFAR\\Admin\\Panel\\About',
			),
		);
	}

	public function svg_icon() : string {
		return gdfon()->svg_icon;
	}

	public function run_getback() : void {
		new GetBack( $this );
	}

	public function run_postback() : void {
		new PostBack( $this );
	}

	public function message_process( $code, $msg ) {
		return $msg;
	}

	public function settings() : Settings {
		return gdfar_settings();
	}

	public function plugin() {
		return gdfar();
	}

	public function wizard() {
		return null;
	}

	public function settings_definitions() {
		return null;
	}

	protected function extra_enqueue_scripts_plugin() : void {
		$this->enqueue->js( 'gdfar-admin' );
		$this->enqueue->css( 'gdfar-admin' );
	}
}
