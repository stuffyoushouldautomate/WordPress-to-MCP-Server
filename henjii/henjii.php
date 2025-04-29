<?php
/**
 * Henjii - WordPress MCP Server
 *
 * @package           Henjii
 * @author            Jeremy Harris
 * @copyright         2025 Jeremy Harris
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Henjii - WordPress MCP Server
 * Plugin URI:        https://henjii.com
 * Description:       Turn your WordPress site into a Model Context Protocol (MCP) server that LLM applications can easily integrate with.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Jeremy Harris
 * Author URI:        https://henjii.com
 * Text Domain:       henjii
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://henjii.com/updates
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 */
define('HENJII_VERSION', '1.0.0');
define('HENJII_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HENJII_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HENJII_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_henjii() {
    require_once HENJII_PLUGIN_DIR . 'includes/class-henjii-activator.php';
    Henjii_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_henjii() {
    require_once HENJII_PLUGIN_DIR . 'includes/class-henjii-deactivator.php';
    Henjii_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_henjii');
register_deactivation_hook(__FILE__, 'deactivate_henjii');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require HENJII_PLUGIN_DIR . 'includes/class-henjii.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_henjii() {
    $plugin = new Henjii();
    $plugin->run();
}
run_henjii();
