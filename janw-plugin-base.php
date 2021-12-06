<?php
/**
 * Plugin Name:       Janw Base Plugin
 * Plugin URI:        PLUGIN SITE HERE
 * Description:       PLUGIN DESCRIPTION HERE
 * Author:            janw.oostendorp
 * Author URI:        https://janw.me
 * Text Domain:       janw-base-plugin
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Version:           0.1.0
 *
 * @package         Janw\Base_Plugin
 */

namespace Janw\Base_Plugin;

define( 'JANW_BASE_PLUGIN_VERSION', '0.1.0' );
define( 'JANW_BASE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JANW_BASE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'JANW_BASE_PLUGIN_NAME', basename( __DIR__ ) . DIRECTORY_SEPARATOR . basename( __FILE__ ) );

/**
 * Autoload internal classes.
 */
spl_autoload_register( function ( $full_class_name ) { //phpcs:ignore PEAR.Functions.FunctionCallSignature
	if ( strpos( $full_class_name, __NAMESPACE__ . '\App' ) !== 0 ) {
		return; // Not in the plugin namespace, don't check.
	}
	if ( strpos( $full_class_name, __NAMESPACE__ . '\App\Vendor' ) === 0 ) {
		return; // 3rd party, prefixed class.
	}

	$stripped_class = str_replace(__NAMESPACE__, '', $full_class_name);
	$stripped_class = strtolower( str_replace( '_', '-', $stripped_class ) );
	$class_parts    = array_filter( explode( '\\', $stripped_class ) );

	$class_file    = 'class-' . array_pop( $class_parts ) . '.php';
	$class_parts[] = $class_file;

	$full_path = JANW_BASE_PLUGIN_DIR . implode( DIRECTORY_SEPARATOR, $class_parts );
	if ( ! file_exists( $full_path ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_die( new \WP_Error( 'unknown_class', "<b>Unknown class</b><br/>class: <code>{$full_class_name}</code><br/> path: <code>{$full_path}</code>" ) );
	}

	require_once $full_path;
} );//phpcs:ignore PEAR.Functions.FunctionCallSignature

/**
 * Hook everything.
 */

// Plugin activation.
register_activation_hook( __FILE__, array( '\Janw\Base_Plugin\App\Admin', 'activate' ) );

// Adds a link to the settings page on the plugin overview.
add_filter( 'plugin_action_links_' . JANW_BASE_PLUGIN_NAME, array( '\Janw\Base_Plugin\App\Admin', 'settings_link' ) );

// add the rest of the hooks & filters.
