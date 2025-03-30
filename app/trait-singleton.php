<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\App;

/**
 * Class Singleton
 *
 * Make a class into a singleton by adding `use Singleton;`
 *
 * phpcs:disable Squiz.Commenting.FunctionComment
 * phpcs:disable Generic.Commenting.DocComment
 *
 * @package Janw\Plugin_Base\App
 */
trait Singleton {

	/**
	 * @var static[]
	 */
	private static array $instances = array();

	final public static function instance(): static {
		$class = static::class;
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static();
		}
		return self::$instances[ $class ];
	}

	final protected function __clone() {
	}

	final public function __sleep() {
		// @phpstan-ignore-line
	}

	final public function __wakeup(): void {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Singleton constructor cannot be overridden.
	 *
	 * To allow it to be "extended" use the `init()` method.
	 */
	final private function __construct() {
		if ( \method_exists( $this, 'init' ) ) {
			$this->init();
		}
	}
}
