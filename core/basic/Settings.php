<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\v42\Core\Plugins\Settings as BaseSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	public $base = 'gdfar';

	public $settings = array(
		'core'     => array(
			'activated' => 0
		),
		'settings' => array(
			'moderators'               => true,
			'forum_moderators'         => false,
			'forum'                    => true,
			'topic'                    => true,
			'notices_under_fields'     => true,
			'small_screen_always_show' => false,
			'topic_edit_log'           => false
		)
	);

	protected function constructor() {
		$this->info = new Information();

		define( 'GDFAR_VERSION', $this->info->version_full() );

		add_action( 'gdfar_load_settings', array( $this, 'init' ), 2 );
	}

	protected function _name( $name ) : string {
		return 'dev4press_' . $this->info->code . '_' . $name;
	}
}