<?php

/**
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

if (!defined('WPINC')) {
   die;
}
if (!function_exists('wp_get_current_user')) {
   include(ABSPATH . "wp-includes/pluggable.php");
}

define('COMPARISON_VERSION', '1.1.8');

require plugin_dir_path(__FILE__) . 'includes/class-comparison.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-metabox.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-list-metabox.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-custom-posttype.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-list-custom-posttype.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-shortcode.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-init.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-html.php';
require plugin_dir_path(__FILE__) . 'includes/class-comparison-submenu.php';

function activate_comparison()
{
   Comparison_Custom_Posttype::comparison_setup_post_type();
   flush_rewrite_rules();
   Comparison::setup_options();
}

function deactivate_comparison()
{
   unregister_post_type('com_comporison');
   unregister_post_type('com_comporison_list');
   flush_rewrite_rules();
}

function uninstall_comparison()
{
   Comparison::delete_options();
}

register_activation_hook(__FILE__, 'activate_comparison');
register_deactivation_hook(__FILE__, 'deactivate_comparison');
register_uninstall_hook(__FILE__, 'uninstall_comparison');

add_action('init', function() {
   $current_version = get_option('icyber_plugin_version');
   if ($current_version !== COMPARISON_VERSION) {
   	flush_rewrite_rules();
   	update_option('icyber_plugin_version', COMPARISON_VERSION);
   	return;
   }
   
   $rules = get_option('rewrite_rules');
   if (!$rules || !is_array($rules)) {
   	flush_rewrite_rules();
   	return;
   }
   
   $has_redirect_rule = false;
   foreach ($rules as $rule => $match) {
   	if (strpos($rule, 'redirect') !== false) {
   		$has_redirect_rule = true;
   		break;
   	}
   }
   
   if (!$has_redirect_rule) {
   	flush_rewrite_rules();
   }
}, 999);

$plugin = new Comparison();
