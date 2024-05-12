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
 * Requires PHP:      8.0
 * Version:           0.10.0
 *
 * @package         Janw\Plugin_Base
 */

namespace Janw\Plugin_Base;

define( 'JANW_PLUGIN_BASE_VERSION', '0.10.0' );
define( 'JANW_PLUGIN_BASE_DIR', plugin_dir_path( __FILE__ ) ); // Full path with trailing slash.
define( 'JANW_PLUGIN_BASE_URL', plugin_dir_url( __FILE__ ) ); // With trailing slash.
define( 'JANW_PLUGIN_BASE_SLUG', basename( __DIR__ ) ); // janw-plugin-base.

if ( ! defined( 'ABSPATH' ) ) {
	return; // WP not loaded.
}

/**
 * Autoload internal classes.
 */
require_once JANW_PLUGIN_BASE_DIR . 'app/class-plugin.php';
spl_autoload_register( array( '\Janw\Plugin_Base\App\Plugin', 'autoloader' ) );

/**
 * Hook everything.
 */

// Plugin (de)activation & uninstall.
register_activation_hook( __FILE__, array( '\Janw\Plugin_Base\App\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( '\Janw\Plugin_Base\App\Plugin', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( '\Janw\Plugin_Base\App\Plugin', 'uninstall' ) );

// Add translation.
add_action( 'init', array( '\Janw\Plugin_Base\App\Plugin', 'load_textdomain' ), 9 );


// Add the rest of the hooks & filters.
