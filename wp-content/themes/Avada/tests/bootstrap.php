<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once $_tests_dir . '/includes/functions.php';
define( 'WP_DEFAULT_THEME', 'Avada' );

function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/../functions.php';
	switch_theme( 'Avada' );
}
tests_add_filter( 'active_plugins', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';
