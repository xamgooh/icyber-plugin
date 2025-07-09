<?php

class Comparison
{
   protected $loader;
   protected $comparison;
   protected $comparison_shortcode;
   protected $comparison_custom_posttype;
   protected $comparison_list_custom_posttype;
   protected $version;
   protected $comparison_metabox;
   protected $comparison_list_metabox;
   protected $comparison_sub_menu;
   protected $plugin_init;
   protected $plugin_admin;
   protected $plugin_public;

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

   public static function setup_options()
   {
   	!empty(get_option('comporisons_filter_label')) ?: update_option('comporisons_filter_label',  __('Load More', 'comporisons'));
   	!empty(get_option('comporisons_sorting_label')) ?: update_option('comporisons_sorting_label', __('Sorting', 'comporisons'));
   	!empty(get_option('comporisons_redirect_delay')) ?: update_option('comporisons_redirect_delay',  3);
   	!empty(get_option('comporisons_redirect_link_message')) ?: update_option('comporisons_redirect_link_message', 'If you are not forwarded to');
   	!empty(get_option('comporisons_redirect_link_title')) ?: update_option('comporisons_redirect_link_title',  __('CLICK HERE', 'comporisons'));
   	!empty(get_option('comporisons_redirect_title_label')) ?: update_option('comporisons_redirect_title_label', 'Thank you for visiting');
   	!empty(get_option('comporisons_redirect_main_title')) ?: update_option('comporisons_redirect_main_title', 'You are now being redirected...');
   }

   public static function delete_options()
   {
   	delete_option('comporisons_filter_label');
   	delete_option('comporisons_sorting_label');
   }

   private function define_sub_menu()
   {
   	$this->comparison_sub_menu = new Comparison_Sub_Menu();
   }

   private function define_custom_posttype()
   {
   	$this->comparison_custom_posttype = new Comparison_Custom_Posttype();
   }

   private function define_list_custom_posttype()
   {
   	$this->comparison_list_custom_posttype = new Comparison_List_Custom_Posttype();
   }

   private function define_metabox()
   {
   	$this->comparison_metabox = new Comparison_Metabox();
   }

   private function define_list_metabox()
   {
   	$this->comparison_list_metabox = new Comparison_List_Metabox();
   }

   private function define_shortcode()
   {
   	$this->comparison_shortcode = new ComparisonShortcode();
   }

   private function load_dependencies()
   {
   	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-comparison-loader.php';
   	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-comparison-admin.php';
   	require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-comparison-public.php';

   	$this->loader = new Comparison_Loader();
   }

   private function define_init_hooks()
   {
   	$this->plugin_init = new Comparison_Init($this->get_comparison(), $this->get_version());

   	// Register hooks through the loader for proper timing
   	$this->loader->add_action('init', $this->plugin_init, 'comparison_custom_posttype', 5);
   	$this->loader->add_action('init', $this->plugin_init, 'comparison_list_custom_posttype', 5);
   	$this->loader->add_action('init', $this->plugin_init, 'comparison_taxonomy', 10);
   	$this->loader->add_action('init', $this->plugin_init, 'rest_api_end_point', 20);
   }

   private function define_admin_hooks()
   {
   	$this->plugin_admin = new Comparison_Admin($this->get_comparison(), $this->get_version());

   	$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles');
   	$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts');

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

   private function define_public_hooks()
   {		
   	$this->plugin_public = new Comparison_Public($this->get_comparison(), $this->get_version());

   	$this->loader->add_action('wp_enqueue_scripts', $this->plugin_public, 'enqueue_styles');
   	$this->loader->add_action('wp_enqueue_scripts', $this->plugin_public, 'enqueue_scripts');
   }

   public function get_comparison()
   {
   	return $this->comparison;
   }

   public function get_loader()
   {
   	return $this->loader;
   }

   public function get_version()
   {
   	return $this->version;
   }
}
