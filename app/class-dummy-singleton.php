<?php

declare( strict_types=1 );
namespace Janw\Plugin_Base\App;

/**
 * Class Dummy_Singleton
 *
 * This class is only here to test the Singleton trait.
 * PHPstan was complaining about the trait not being used.
 */
class Dummy_Singleton {
	use Singleton;
}
