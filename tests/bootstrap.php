<?php
/**
 * PHPUnit bootstrap for the WordPress integration suite.
 *
 * Run `composer test:install` once to install the WordPress test library,
 * then `composer test:wp`.
 *
 * @package Janw\Plugin_Base\Tests
 */

namespace Janw\Plugin_Base\Tests;

$janwpb_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $janwpb_tests_dir ) {
	$janwpb_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

// Forward the PHPUnit Polyfills location to the WordPress test bootstrap if set.
$janwpb_polyfills_path = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( $janwpb_polyfills_path !== false ) {
	// Constant name is dictated by the WordPress test suite, so it cannot be prefixed.
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $janwpb_polyfills_path ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
}

if ( ! file_exists( "{$janwpb_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$janwpb_tests_dir}/includes/functions.php — run: composer test:install" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter().
require_once "{$janwpb_tests_dir}/includes/functions.php";

/**
 * Manually load this plugin into the test WordPress instance.
 */
function janwpb_manually_load_plugin(): void {
	$plugin_dir = dirname( __DIR__ );
	require_once $plugin_dir . '/' . basename( $plugin_dir ) . '.php';
}
tests_add_filter( 'muplugins_loaded', __NAMESPACE__ . '\\janwpb_manually_load_plugin' );

// Start the WordPress testing environment.
require "{$janwpb_tests_dir}/includes/bootstrap.php";
