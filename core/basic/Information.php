<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-forum-manager-for-bbpress';

	public $version = '1.4';
	public $build = 40;
	public $edition = 'free';
	public $status = 'stable';
	public $updated = '2020.12.29';
	public $released = '2020.06.22';

	public $is_bbpress_plugin = true;

	public function __construct() {
		$this->plugins['bbpress'] = '2.6.2';
	}
}
