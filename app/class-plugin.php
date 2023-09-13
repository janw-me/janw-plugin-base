<?php
declare(strict_types=1);

namespace Janw\Plugin_Base\App;

/**
 * Class Admin
 *
 * @package Janw\Plugin_Base\app
 */
class Plugin {

	/**
	 * Load the translations for the plugin.
	 *
	 * @return void
	 */
	public static function load_textdomain(): void {
		\load_plugin_textdomain( JANW_PLUGIN_BASE_SLUG, false, \plugin_basename( JANW_PLUGIN_BASE_DIR ) . '/languages/' );
	}

	/**
	 * Run on plugin activation.
	 *
	 * @param string|null $plugin Path to the plugin file relative to the plugins directory.
	 * @param bool        $network_wide Whether to enable the plugin for all sites in the network or just the current site.
	 *
	 * @return void
	 */
	public static function activate( string $plugin = null, bool $network_wide = false ): void {
		// update_option.
	}

	/**
	 * Run on plugin deactivation. Only disable & remove temp data.
	 *
	 * @param bool $network_deactivating Is this deactivation network wide.
	 *
	 * @return void
	 */
	public static function deactivate( bool $network_deactivating = false ): void {
		// delete_option.
	}

	/**
	 * Run when the plugin is uninstalled. Remove all traces of this plugin.
	 *
	 * @return void
	 */
	public static function uninstall(): void {
		// delete_option.
	}
}
