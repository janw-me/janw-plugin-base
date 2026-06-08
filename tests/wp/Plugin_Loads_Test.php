<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\Tests;

use Janw\Plugin_Base\App\Plugin;
use WP_UnitTestCase;

/**
 * Smoke test: the plugin boots correctly inside WordPress.
 *
 * This is the template for integration tests that need a real WordPress
 * environment (the factory, the database, hooks, etc.).
 */
final class Plugin_Loads_Test extends WP_UnitTestCase {

	/**
	 * The plugin's constants and classes are available once WordPress loads it.
	 */
	public function test_plugin_is_bootstrapped(): void {
		$this->assertTrue( defined( 'JANW_PLUGIN_BASE_VERSION' ), 'Plugin constant should be defined.' );
		$this->assertTrue( class_exists( Plugin::class ), 'Plugin class should autoload.' );
	}

	/**
	 * The WordPress test factory is available for integration tests.
	 */
	public function test_factory_creates_a_post(): void {
		$post_id = self::factory()->post->create( array( 'post_type' => 'post' ) );

		$this->assertSame( 'post', get_post_type( $post_id ) );
	}
}
