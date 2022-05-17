<?php
/**
 * Plugin Name:       Janw Base Plugin
 * Plugin URI:        PLUGIN SITE HERE
 * Description:       PLUGIN DESCRIPTION HERE
 * Author:            janw.oostendorp
 * Author URI:        https://janw.me
 * Text Domain:       janw-plugin-base
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP:      7.3
 * Version:           0.9.5
 *
 * @package         Janw\Plugin_Base
 */

namespace Janw\Plugin_Base;

define( 'JANW_PLUGIN_BASE_VERSION', '0.9.5' );
define( 'JANW_PLUGIN_BASE_DIR', plugin_dir_path( __FILE__ ) ); // Full path with trailing slash.
define( 'JANW_PLUGIN_BASE_URL', plugin_dir_url( __FILE__ ) ); // With trailing slash.
define( 'JANW_PLUGIN_BASE_SLUG', basename( __DIR__ ) ); // janw-plugin-base.

if ( ! defined( 'ABSPATH' ) ) {
	return; // WP not loaded.
}

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
	$transform  = str_replace( __NAMESPACE__ . '\\', '', $class_name );                            // Remove NAMESPACE and it's "/".
	$transform  = str_replace( '_', '-', $transform );                                             // Replace "_" with "-".
	$transform  = (string) preg_replace( '%\\\\((?:.(?!\\\\))+$)%', '\class-$1.php', $transform ); // Set correct classname.
	$transform  = str_replace( '\\', DIRECTORY_SEPARATOR, $transform );                            // Replace NS separator with dir separator.
	$class_path = JANW_PLUGIN_BASE_DIR . strtolower( $transform );
	if ( ! file_exists( $class_path ) ) {
		wp_die( "<h1>Can't find class</h1><pre><code>Class: {$class_name}<br/>Path:  {$class_path}</code></pre>" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	require_once $class_path;
} );//phpcs:ignore PEAR.Functions.FunctionCallSignature

/**
 * Hook everything.
 */

// Plugin (de)activation & uninstall.
register_activation_hook( __FILE__, array( '\Janw\Plugin_Base\App\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( '\Janw\Plugin_Base\App\Plugin', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( '\Janw\Plugin_Base\App\Plugin', 'uninstall' ) );

// Add translation.
add_action( 'init', array( '\Janw\Plugin_Base\App\Plugin', 'load_textdomain' ), 10, 2 );


// Add the rest of the hooks & filters.
