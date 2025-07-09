<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Icyber Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Icyber Plugin
 * Plugin URI:        -
 * Description:       -
 * Version:           1.1.8
 * Author:            Max L.
 * Author URI:        -
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       comporisons
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php");
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 */
define('COMPARISON_VERSION', '1.1.8');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-comparison.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-metabox.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-list-metabox.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-custom-posttype.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-list-custom-posttype.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-shortcode.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-init.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-html.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-submenu.php';

/**
 * The code that runs during plugin activation.
 */
function activate_comparison()
{
    // Just setup options and flush rewrite rules
    // The post types will be registered when the plugin runs normally
    Comparison::setup_options();
    flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_comparison()
{
    flush_rewrite_rules();
}

/**
 * The code that runs during plugin uninstall.
 */
function uninstall_comparison()
{
    Comparison::delete_options();
}

register_activation_hook(__FILE__, 'activate_comparison');
register_deactivation_hook(__FILE__, 'deactivate_comparison');
register_uninstall_hook(__FILE__, 'uninstall_comparison');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$plugin = new Comparison();
