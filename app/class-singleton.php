<?php

namespace Janw\Base_Plugin\App;

/**
 * Class Singleton
 *
 * Make a class into a singleton by adding `use Singleton;`
 *
 * phpcs:disable Squiz.Commenting.FunctionComment
 * phpcs:disable Generic.Commenting.DocComment
 *
 * @package Baked_Blocks\App
 */
trait Singleton {

	/**
	 * @var self
	 */
	private static $inst;

	/**
	 * @return self
	 */
	public static function instance() {
		if ( ! static::$inst ) {
			static::$inst = new self();
		}

		return static::$inst;
	}

	protected function __clone() {
	}

	public function __sleep() {
	}

	protected function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * @return self
	 */
	private function __construct() {
		// Possible extend.
		return $this;
	}
}
