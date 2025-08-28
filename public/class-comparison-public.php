<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Comparison
 * @subpackage Comparison/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Comparison
 * @subpackage Comparison/public
 * @author     Your Name <email@example.com>
 */
class Comparison_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $comparison    The ID of this plugin.
     */
    private $comparison;
    
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
     * @param      string    $comparison       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($comparison, $version)
    {
        $this->comparison = $comparison;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Comparison_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Comparisone_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->comparison, plugin_dir_url(__FILE__) . 'css/comparison-public.css', array(), $this->version, 'all');
        wp_enqueue_style('comparison-redirect', plugin_dir_url(__FILE__) . 'css/comparison-redirect.css', array(), $this->version, 'all');
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Comparison_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Comparison_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->comparison, plugin_dir_url(__FILE__) . 'js/comparison-public.js', array('jquery'), $this->version, false);
        
        // Get redirect settings from database
        $redirect_delay = get_option('comporisons_redirect_delay', '3000');
        $redirect_title = get_option('comporisons_redirect_title', 'Thank you for visiting {BRAND_NAME}!');
        $safe_text = get_option('comporisons_redirect_safe_text', 'You are now being redirected to {BRAND_NAME}');
        $loading_text = get_option('comporisons_redirect_loading_text', 'PLEASE WAIT');
        $fallback_text = get_option('comporisons_redirect_fallback_text', 'If you are not forwarded to');
        $button_text = get_option('comporisons_redirect_button_text', 'CLICK HERE');
        
        // Get terms as array
        $terms_json = get_option('comporisons_redirect_terms', '["18+ only", "Play responsibly", "Terms apply"]');
        $terms = json_decode($terms_json, true);
        if (!is_array($terms)) {
            $terms = array('18+ only', 'Play responsibly', 'Terms apply');
        }
        
        // Pass redirect settings to JavaScript
        wp_localize_script($this->comparison, 'comparisonRedirectSettings', array(
            'redirectDelay' => intval($redirect_delay),
            'redirectTitle' => $redirect_title,
            'safeText' => $safe_text,
            'loadingText' => $loading_text,
            'fallbackText' => $fallback_text,
            'buttonText' => $button_text,
            'terms' => $terms
        ));
    }
}
