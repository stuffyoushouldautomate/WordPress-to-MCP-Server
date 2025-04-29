<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/henjii
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Henjii
 * @subpackage Henjii/includes
 * @author     jeremy harris
 */
class Henjii_Deactivator {

    /**
     * Deactivate the plugin.
     *
     * Clean up any necessary data and settings when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Flush rewrite rules to remove our API endpoints
        flush_rewrite_rules();
        
        // We don't delete options or tables here to preserve user settings
        // If the user wants to completely remove the plugin, they should use the uninstall.php
    }
}
