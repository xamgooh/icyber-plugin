<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Comparison
 * @subpackage Comparison/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Comparison
 * @subpackage Comparison/includes
 * @author     Your Name <email@example.com>
 */
class Comparison
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Comparison_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $comparison    The string used to uniquely identify this plugin.
	 */
	protected $comparison;

	/**
	 * The unique identifier of this plugin shortcode.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      ComparisonShortcode  $comparison_shortcode  The string used to uniquely identify this plugin.
	 */
	protected $comparison_shortcode;

	/**
	 * The unique identifier of this plugin shortcode.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Comparison_Custom_Posttype $comparison_custom_posttype;
	 */
	protected $comparison_custom_posttype;

	/**
	 * The unique identifier of this plugin shortcode.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Comparison_List_Custom_Posttype $comparison_list_custom_posttype;
	 */
	protected $comparison_list_custom_posttype;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The metabox register
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Comparison_Metabox  $comparison_Metabox
	 */
	protected $comparison_metabox;

	/**
	 * The metabox register
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Comparison_List_Metabox  $comparison_list_Metabox
	 */
	protected $comparison_list_metabox;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('COMPARISON_VERSION')) {
			$this->version = COMPARISON_VERSION;
		} else {
			$this->version = '1.1.7';
		}
		$this->comparison = 'comparison';

		$this->load_dependencies();
		$this->define_init_hooks();
		$this->define_metabox();
		$this->define_list_metabox();
		$this->define_custom_posttype();
		$this->define_list_custom_posttype();
		$this->define_sub_menu();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcode();

		$this->loader->run();
	}

	/**
	 * Setup default optionsu [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function setup_options()
	{

		// here you can save your default options
		// the first time your plugin will run

		!empty(get_option('comporisons_filter_label')) ?: update_option('comporisons_filter_label',  __('Load More', 'comporisons'));
		!empty(get_option('comporisons_sorting_label')) ?: update_option('comporisons_sorting_label', __('Sorting', 'comporisons'));
		!empty(get_option('comporisons_redirect_delay')) ?: update_option('comporisons_redirect_delay',  3);
		!empty(get_option('comporisons_redirect_link_message')) ?: update_option('comporisons_redirect_link_message', 'If you are not forwarded to');
		!empty(get_option('comporisons_redirect_link_title')) ?: update_option('comporisons_redirect_link_title',  __('CLICK HERE', 'comporisons'));
		!empty(get_option('comporisons_redirect_title_label')) ?: update_option('comporisons_redirect_title_label', 'Thank you for visiting');
		!empty(get_option('comporisons_redirect_main_title')) ?: update_option('comporisons_redirect_main_title', 'You are now being redirected...');
		//etc

	}


	/**
	 * Setup default optionsu [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function delete_options()
	{
		delete_option('comporisons_filter_label');
		delete_option('comporisons_sorting_label');
	}

	/**
	 * Register Custom Sub Menu [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_sub_menu()
	{
		$this->comparison_sub_menu = new Comparison_Sub_Menu();
	}

	/**
	 * Register Custom Posttype [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_custom_posttype()
	{
		$this->comparison_custom_posttype = new Comparison_Custom_Posttype();
	}

	
	/**
	 * Register Custom Posttype [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_list_custom_posttype()
	{
		$this->comparison_list_custom_posttype = new Comparison_List_Custom_Posttype();
	}

	/**
	 * Register Metabox [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_metabox()
	{
		$this->comparison_metabox = new Comparison_Metabox();
	}

	/**
	 * Register Metabox [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_list_metabox()
	{
		$this->comparison_list_metabox = new Comparison_List_Metabox();
	}

	/**
	 * Register Shortcode [comparison]
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	private function define_shortcode()
	{
		$this->comparison_shortcode = new ComparisonShortcode();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Comparison_Loader. Orchestrates the hooks of the plugin.
	 * - Comparison_i18n. Defines internationalization functionality.
	 * - Comparison_Admin. Defines all hooks for the admin area.
	 * - Comparison_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-comparison-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-comparison-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-comparison-public.php';

		$this->loader = new Comparison_Loader();
	}


	/**
	 * Register all of the hooks related to the init
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_init_hooks()
	{

		$plugin_init = new Comparison_Init($this->get_comparison(), $this->get_version());

		$this->loader->add_action('init', $plugin_init, 'rest_api_end_point');
		$this->loader->add_action('init', $plugin_init, 'comparison_custom_posttype');
		$this->loader->add_action('init', $plugin_init, 'comparison_list_custom_posttype');
		$this->loader->add_action('init', $plugin_init, 'comparison_taxonomy');
		$this->loader->add_action('init', $plugin_init, 'comparison_category_form_fields');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Сomparison_Admin($this->get_comparison(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');


		/*
		* The function creates a duplicate post in the form of a draft and redirects to its edit page
		*/
		add_action('admin_action_true_duplicate_post_as_draft', function () {

			global $wpdb;
			if (!(isset($_GET['post']) || isset($_POST['post'])  || (isset($_REQUEST['action']) && 'true_duplicate_post_as_draft' == $_REQUEST['action']))) {
				wp_die('Nothing for duplicate!');
			}

			$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

			$post = get_post($post_id);

			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;

			if (isset($post) && $post != null) {

				$args = array(
					'comment_status' => $post->comment_status,
					'ping_status'    => $post->ping_status,
					'post_author'    => $new_post_author,
					'post_content'   => $post->post_content,
					'post_excerpt'   => $post->post_excerpt,
					'post_name'      => $post->post_name,
					'post_parent'    => $post->post_parent,
					'post_password'  => $post->post_password,
					'post_status'    => 'draft',
					'post_title'     => $post->post_title,
					'post_type'      => $post->post_type,
					'to_ping'        => $post->to_ping,
					'menu_order'     => $post->menu_order
				);

				$new_post_id = wp_insert_post($args);

				$taxonomies = get_object_taxonomies($post->post_type);
				foreach ($taxonomies as $taxonomy) {
					$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
					wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
				}

				$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
				if (count($post_meta_infos) != 0) {
					$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
					foreach ($post_meta_infos as $meta_info) {
						$meta_key = $meta_info->meta_key;
						$meta_value = addslashes($meta_info->meta_value);
						$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
					}
					$sql_query .= implode(" UNION ALL ", $sql_query_sel);
					$wpdb->query($sql_query);
				}

				wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
				exit;
			} else {
				wp_die('Post creation error, I can not find the original post with ID=: ' . $post_id);
			}
		});

		add_filter('post_row_actions', function ($actions, $post) {
			if ($post->post_type == 'com_comporison') {
				$actions['duplicate'] = '<a href="#" title="" rel="permalink">Duplicate</a>';

				if (current_user_can('edit_posts')) {
					$actions['duplicate'] = '<a href="admin.php?action=true_duplicate_post_as_draft&post=' . $post->ID . '" title="Duplicate this post" rel="permalink">Duplicate</a>';
				}
			}
			return $actions;
		}, 10, 2);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{		
		$plugin_public = new Comparison_Public($this->get_comparison(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_comparison()
	{
		return $this->comparison;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    comparison_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
