<?php
/**
 * Plugin Name:       Janw Base Plugin
 * Plugin URI:        PLUGIN SITE HERE
 * Description:       PLUGIN DESCRIPTION HERE
 * Author:            janw.oostendorp
 * Author URI:        https://janw.me
 * Text Domain:       janw-plugin-base
 * Domain Path:       /languages
 * Requires at least: 6.6
 * Requires PHP:      8.2
 * Version:           0.10.0
 * License:           GPLv2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package           Janw\Plugin_Base
 */

namespace Janw\Plugin_Base;

use Janw\Plugin_Base\App\Plugin;

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
spl_autoload_register( array( Plugin::class, 'autoloader' ) );

/**
 * Hook everything.
 */

// Plugin (de)activation & uninstall.
register_activation_hook( __FILE__, array( Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( Plugin::class, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( Plugin::class, 'uninstall' ) );

// Add translation.
add_action( 'init', array( Plugin::class, 'load_textdomain' ), 9 );


// Add the rest of the hooks & filters.
