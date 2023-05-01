<?php

namespace Janw\Plugin_Base\App;

/**
 * Class Cron
 *
 * @package Janw\Plugin_Base\App
 */
class Cron {
	use Singleton;

	const ACTION_HOOK = 'cron_janw_plugin_base';

	/**
	 * Schedule the general cronjob.
	 * The general cron should run _always_ and always check if it should run.
	 * In the mapper the actual run time should be registered.
	 *
	 * @return void
	 */
	public function schedule_cron() {
		if ( wp_next_scheduled( self::ACTION_HOOK ) ) {
			return; // Cron is scheduled.
		}

		/**
		 * Schedule the cron as fast as possible
		 */
		wp_schedule_single_event( time(), self::ACTION_HOOK );

		/**
		 * Schedule the cron in 5 minutes.
		 */
		wp_schedule_single_event( time() + ( MINUTE_IN_SECONDS * 13 ), self::ACTION_HOOK );

		/**
		 * Normal recurring scheduling.
		 * The default supported recurrences are 'hourly', 'twicedaily', 'daily', and 'weekly'.
		 */
		wp_schedule_event( time(), 'hourly', self::ACTION_HOOK );
	}

	/**
	 * Execute the cronjob.
	 *
	 * @return void
	 */
	public function run_cron() {
		// Execute the cronjob.
	}
}
