<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\v42\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-forum-manager-for-bbpress';

	public $version = '2.4';
	public $build = 110;
	public $edition = 'free';
	public $status = 'stable';
	public $updated = '2023.07.15';
	public $released = '2020.06.22';

	public $is_bbpress_plugin = true;
}
