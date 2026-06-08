<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\Tests;

use Janw\Plugin_Base\Tests\Fixtures\Plain_Singleton_Fixture;
use Janw\Plugin_Base\Tests\Fixtures\Singleton_Fixture;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Singleton trait (app/trait-singleton.php).
 */
final class Singleton_Test extends TestCase {

	/**
	 * Repeated instance() calls return the one shared object.
	 */
	public function test_instance_returns_same_object(): void {
		$first  = Singleton_Fixture::instance();
		$second = Singleton_Fixture::instance();

		$this->assertInstanceOf( Singleton_Fixture::class, $first );
		$this->assertSame( $first, $second, 'instance() must return the one shared object.' );
	}

	/**
	 * The trait calls init() once, from the constructor, and never again.
	 */
	public function test_init_runs_exactly_once(): void {
		$instance = Singleton_Fixture::instance();
		$this->assertTrue( $instance->initialized, 'init() should have run during construction.' );

		Singleton_Fixture::instance();
		$this->assertSame( 1, $instance->init_count, 'init() must run only once.' );
	}

	/**
	 * The constructor cannot be called or overridden from outside.
	 */
	public function test_constructor_is_locked(): void {
		$constructor = new \ReflectionMethod( Singleton_Fixture::class, '__construct' );

		$this->assertTrue( $constructor->isPrivate(), 'Constructor must be private.' );
		$this->assertTrue( $constructor->isFinal(), 'Constructor must be final.' );
	}

	/**
	 * Unserializing a singleton is rejected.
	 */
	public function test_wakeup_throws(): void {
		$instance = Singleton_Fixture::instance();

		$this->expectException( \Exception::class );
		$instance->__wakeup();
	}

	/**
	 * A class may use the trait without defining the optional init() method.
	 */
	public function test_works_without_init_method(): void {
		$first  = Plain_Singleton_Fixture::instance();
		$second = Plain_Singleton_Fixture::instance();

		$this->assertInstanceOf( Plain_Singleton_Fixture::class, $first );
		$this->assertSame( $first, $second );
	}
}
