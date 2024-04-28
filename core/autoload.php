<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dev4press_plugin_gdfar_autoload( $class ) {
	$path = dirname( __FILE__ ) . '/';
	$base = 'Dev4Press\\Plugin\\GDFAR\\';

	dev4press_v48_autoload_for_plugin( $class, $base, $path );
}

spl_autoload_register( 'dev4press_plugin_gdfar_autoload' );
