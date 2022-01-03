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
spl_autoload_register( function ( $class_name ) { //phpcs:ignore PEAR.Functions.FunctionCallSignature
	if ( strpos( $class_name, __NAMESPACE__ . '\App' ) !== 0 ) {
		return; // Not in the plugin namespace, don't check.
	}
	if ( strpos( $class_name, __NAMESPACE__ . '\App\Vendor' ) === 0 ) {
		return; // 3rd party, prefixed class.
	}
	$transform  = str_replace( __NAMESPACE__ . '\\', '', $class_name );                   // Remove NAMESPACE and it's "/".
	$transform  = str_replace( '_', '-', $transform );                                    // Replace "_" with "-".
	$transform  = preg_replace( '%\\\\((?:.(?!\\\\))+$)%', '\class-$1.php', $transform ); // Set correct classname.
	$transform  = str_replace( '\\', DIRECTORY_SEPARATOR, $transform );                   // Replace NS separator with dir separator.
	$class_path = AFAS_FEED_PLUGIN_DIR . strtolower( $transform );
	if ( ! file_exists( $class_path ) ) {
		wp_die( "<h1>Can't find class</h1><pre><code>Class: {$class_name}<br/>Path:  {$class_path}</code></pre>" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	require_once $class_path;
} );//phpcs:ignore PEAR.Functions.FunctionCallSignature

/**
 * Hook everything.
 */

// Plugin activation.
register_activation_hook( __FILE__, array( '\Janw\Base_Plugin\App\Admin', 'activate' ) );

// Adds a link to the settings page on the plugin overview.
add_filter( 'plugin_action_links_' . JANW_BASE_PLUGIN_NAME, array( '\Janw\Base_Plugin\App\Admin', 'settings_link' ) );

// add the rest of the hooks & filters.
