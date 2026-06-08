<?php
/**
 * PHPUnit bootstrap for the fast unit suite — no WordPress, no database.
 *
 * @package Janw\Plugin_Base\Tests
 */

namespace Janw\Plugin_Base\Tests;

defined( 'JANW_PLUGIN_BASE_VERSION' ) || define( 'JANW_PLUGIN_BASE_VERSION', 'test' );
defined( 'JANW_PLUGIN_BASE_DIR' ) || define( 'JANW_PLUGIN_BASE_DIR', dirname( __DIR__ ) . '/' );
defined( 'JANW_PLUGIN_BASE_URL' ) || define( 'JANW_PLUGIN_BASE_URL', 'https://example.test/' );
defined( 'JANW_PLUGIN_BASE_SLUG' ) || define( 'JANW_PLUGIN_BASE_SLUG', 'janw-plugin-base' );

/*
 * Minimal autoloader for the plugin's app/ classes and traits. Mirrors the
 * runtime autoloader (app/class-plugin.php) but stays WordPress-free so unit
 * tests can run without bootstrapping WordPress.
 */
spl_autoload_register(
	static function ( string $class_name ): void {
		$app_namespace = 'Janw\Plugin_Base\App';
		if ( ! str_starts_with( $class_name, $app_namespace ) ) {
			return;
		}
		$relative   = strtolower( str_replace( array( $app_namespace, '\\', '_' ), array( '', '/', '-' ), $class_name ) );
		$class_file = JANW_PLUGIN_BASE_DIR . 'app' . dirname( $relative ) . '/class-' . basename( $relative ) . '.php';
		if ( is_file( $class_file ) ) {
			require_once $class_file;
			return;
		}
		$trait_file = str_replace( '/class-', '/trait-', $class_file );
		if ( is_file( $trait_file ) ) {
			require_once $trait_file;
		}
	}
);

// Load test fixtures.
require_once __DIR__ . '/unit/fixtures/class-singleton-fixture.php';
require_once __DIR__ . '/unit/fixtures/class-plain-singleton-fixture.php';
