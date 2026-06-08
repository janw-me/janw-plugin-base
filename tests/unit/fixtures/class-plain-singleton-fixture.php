<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\Tests\Fixtures;

use Janw\Plugin_Base\App\Singleton;

/**
 * Fixture for a class that uses the Singleton trait without defining init().
 *
 * Exercises the optional-init path and gives the trait an analysed user so
 * PHPStan does not report it as unused.
 */
class Plain_Singleton_Fixture {
	use Singleton;
}
