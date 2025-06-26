<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Сomparison
 * @subpackage Сomparison/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Сomparison
 * @subpackage Сomparison/admin
 * @author     Your Name <email@example.com>
 */
class Сomparison_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $сomparison    The ID of this plugin.
	 */
	private $сomparison;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $сomparison       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($сomparison, $version)
	{

		$this->сomparison = $сomparison;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Сomparison_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Сomparison_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->сomparison, plugin_dir_url(__FILE__) . 'css/comparison-admin.css', array(), $this->version, 'all');
		wp_enqueue_media();
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_editor();
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Сomparison_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Сomparison_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->сomparison, plugin_dir_url(__FILE__) . 'js/comparison-admin.js', array('jquery'), $this->version, false);
		wp_enqueue_script('jquery-validation', plugin_dir_url(__FILE__) . 'js/jquery-validate-min.js', array('jquery', $this->сomparison), $this->version, false);

		wp_enqueue_script('wp-color-picker');
	}
}
