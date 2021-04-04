<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-forum-manager-for-bbpress';

	public $version = '2.0.1';
	public $build = 51;
	public $edition = 'free';
	public $status = 'stable';
	public $updated = '2021.04.14';
	public $released = '2020.06.22';

	public $is_bbpress_plugin = true;

	public function __construct() {
		$this->plugins['bbpress'] = '2.6.2';
	}
}
