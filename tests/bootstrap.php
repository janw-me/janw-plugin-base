<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Janw\Plugin_Base\Tests
 */

namespace Janw\Plugin_Base\Tests;

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file.
$_phpunit_polyfills_path = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( false !== $_phpunit_polyfills_path ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $_phpunit_polyfills_path );
}

if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	$dir = dirname( dirname( __FILE__ ) );
	$file_name = basename( dirname( dirname( __FILE__ ) ) );
	require_once "$dir/$file_name.php";
}

tests_add_filter( 'muplugins_loaded', '\\'.__NAMESPACE__.'\\_manually_load_plugin' );

// Start up the WP testing environment.
require_once "{$_tests_dir}/includes/bootstrap.php";

/**
 * Autoload internal classes.
 */
spl_autoload_register( function ( $class_name ) { //phpcs:ignore PEAR.Functions.FunctionCallSignature
	if ( strpos( $class_name, __NAMESPACE__ ) !== 0 ) {
		return; // Not in the tests namespace, don't check.
	}
	$transform  = str_replace( __NAMESPACE__ . '\\', '', $class_name );                            // Remove NAMESPACE and it's "/".
	$transform  = str_replace( '_', '-', $transform );                                             // Replace "_" with "-".
	$transform  = (string) preg_replace( '%\\\\((?:.(?!\\\\))+$)%', '\class-$1.php', $transform ); // Set correct classname.
	$transform  = str_replace( '\\', DIRECTORY_SEPARATOR, $transform );                            // Replace NS separator with dir separator.
	$class_path = __DIR__ . '/' . strtolower( $transform );
	if ( ! file_exists( $class_path ) ) {
		wp_die( "<h1>Can't find class</h1><pre><code>Class: {$class_name}<br/>Path: {$class_path}</code></pre>" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	require_once $class_path;
} );//phpcs:ignore PEAR.Functions.FunctionCallSignature
