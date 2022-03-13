<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\v37\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-forum-manager-for-bbpress';

	public $version = '2.1.1';
	public $build = 75;
	public $edition = 'free';
	public $status = 'stable';
	public $updated = '2022.03.13';
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
