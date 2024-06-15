<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\v49\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-forum-manager-for-bbpress';

	public $version = '2.8';
	public $build = 150;
	public $edition = 'free';
	public $status = 'stable';
	public $updated = '2024.05.15';
	public $released = '2020.06.22';

	public $is_bbpress_plugin = true;

	public $github_url = 'https://github.com/dev4press/gd-forum-manager-for-bbpress';
	public $wp_org_url = 'https://wordpress.org/plugins/gd-forum-manager-for-bbpress/';
}
