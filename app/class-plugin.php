<?php

namespace Janw\Plugin_Base\App;

/**
 * Class Admin
 *
 * @package Janw\Plugin_Base\app
 */
class Plugin {

	/**
	 * Add a settings link to the the plugin on the plugin page
	 *
	 * @param string[] $actions An array of plugin action links. By default this can include 'activate', 'delete', 'network_only', ....
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 *
	 * @return string[]
	 */
	public static function settings_link( array $actions, string $plugin_file ): array {
		$this_plugin_file = JANW_PLUGIN_BASE_SLUG . DIRECTORY_SEPARATOR . JANW_PLUGIN_BASE_SLUG . '.php';
		if ( $plugin_file !== $this_plugin_file ) {
			return $actions; // wrong plugin.
		}

		$href          = admin_url( 'tools.php?page=' . JANW_PLUGIN_BASE_SLUG );
		$settings_link = '<a href="' . $href . '">' . __( 'Settings' ) . '</a>'; // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		array_unshift( $actions, $settings_link );

		return $actions;
	}

	/**
	 * Load the translations for the plugin.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( JANW_PLUGIN_BASE_SLUG, false, plugin_basename( JANW_PLUGIN_BASE_DIR ) . '/languages/' );
	}

	/**
	 * Run on plugin activation.
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 * @param bool   $network_wide Whether to enable the plugin for all sites in the network or just the current site.
	 *
	 * @return void
	 */
	public static function activate( $plugin, $network_wide = false ) {
		// update_option.
	}

	/**
	 * Run on plugin deactivation. Only disable & remove temp data.
	 *
	 * @param bool $network_deactivating Is this deactivation network wide.
	 *
	 * @return void
	 */
	public static function deactivate( $network_deactivating = false ) {
		// delete_option.
	}

	/**
	 * Run when the plugin is uninstalled. Remove all traces of this plugin.
	 *
	 * @return void
	 */
	public static function uninstall() {
		// delete_option.
	}
}

