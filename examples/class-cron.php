<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\App;

/**
 * Example: a scheduled (cron) task.
 *
 * Copy to app/, then register at the bottom of the main plugin file:
 *
 *   add_action( 'init', array( Cron::instance(), 'schedule' ) );
 *   add_action( Cron::ACTION_HOOK, array( Cron::instance(), 'run' ) );
 */
class Cron {
	use Singleton;

	/**
	 * The action hook fired when the scheduled event runs.
	 */
	public const ACTION_HOOK = 'janwpb_cron';

	/**
	 * Schedule the recurring event once, if it is not already scheduled.
	 */
	public function schedule(): void {
		if ( \wp_next_scheduled( self::ACTION_HOOK ) ) {
			return;
		}

		// Supported recurrences are hourly, twicedaily, daily and weekly.
		\wp_schedule_event( \time(), 'hourly', self::ACTION_HOOK );
	}

	/**
	 * Run the scheduled task.
	 */
	public function run(): void {
		// Do the work here.
	}
}
