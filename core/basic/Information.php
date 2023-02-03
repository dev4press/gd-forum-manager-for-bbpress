<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\v39\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-forum-manager-for-bbpress';

	public $version = '2.3';
	public $build = 100;
	public $edition = 'free';
	public $status = 'stable';
	public $updated = '2023.02.03';
	public $released = '2020.06.22';

	public $is_bbpress_plugin = true;

	public function __construct() {
		$this->plugins['bbpress'] = '2.6.2';
	}

	public static function instance() : Information {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new Information();
		}

		return $instance;
	}
}
