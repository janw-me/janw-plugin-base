<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\Tests\Fixtures;

use Janw\Plugin_Base\App\Singleton;

/**
 * Fixture class that exercises the Singleton trait under test.
 */
class Singleton_Fixture {
	use Singleton;

	/**
	 * Whether init() has run.
	 *
	 * @var bool
	 */
	public bool $initialized = false;

	/**
	 * How many times init() has run (should never exceed 1).
	 *
	 * @var int
	 */
	public int $init_count = 0;

	/**
	 * Called once by the Singleton trait's constructor.
	 */
	protected function init(): void {
		$this->initialized = true;
		++$this->init_count;
	}
}
