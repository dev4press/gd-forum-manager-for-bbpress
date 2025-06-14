<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\v54\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public string $code = 'gd-forum-manager-for-bbpress';

	public string $version = '3.0';
	public int $build = 200;
	public string $edition = 'free';
	public string $status = 'stable';
	public string $updated = '2025.06.14';
	public string $released = '2020.06.22';

	public bool $is_bbpress_plugin = true;

	public string $github_url = 'https://github.com/dev4press/gd-forum-manager-for-bbpress';
	public string $wp_org_url = 'https://wordpress.org/plugins/gd-forum-manager-for-bbpress/';
}
