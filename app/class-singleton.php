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
	 * @var self
	 */
	private static $inst;

	/**
	 * @return self
	 */
	final public static function instance(): self {
		if ( ! static::$inst ) { // @phpstan-ignore-line
			static::$inst = new static(); // @phpstan-ignore-line
		}

		return static::$inst; // @phpstan-ignore-line
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
	 * @return self
	 */
	final private function __construct() {
	}
}
