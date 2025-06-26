<?php


class Comparison_List_Custom_Posttype
{
    /**
     * Define metabox core
     *
     *
     * @since    1.0.0
     */

    public function comparison_list_setup_post_type($comparison_list_metabox)
    {
        /**
         * Post Type: Comporisions.
         */
        $show_in_menu = false;
        $labels = [
            "name" => __("Brands Lists", "comporisons"),
            "singular_name" => __("Brands List", "comporisons"),
            'menu_name' => __("Brands Lists", "comporisons"),
            'all_items' => 'Lists',
        ];
        if (is_admin() && current_user_can('manage_options')) {
            $show_in_menu = true;
        }
        $args = [
            "label" => __("Comporisons", "comporisons"),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => $show_in_menu,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => true,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => ["slug" => "redirect_list", "with_front" => false],
            "query_var" => true,
            "menu_icon" => "dashicons-format-image",
            "supports" => ["title", "thumbnail"],
            "show_in_graphql" => false,
            "register_meta_box_cb" =>  array($comparison_list_metabox, 'co_add_event_metaboxes'),
        ];

        register_post_type("com_comporison_list", $args);
    }

    public function register_post_type_template()
    {
        add_filter('single_template', [$this, 'redirect_template'], 30);
    }

    public function redirect_template($single)
    {
        global $post;

        /* Checks for single template by post type */
        if ($post->post_type == 'com_comporison_list') {
            if (file_exists(plugin_dir_path(dirname(__FILE__)) . 'template/redirect.php')) {
                return plugin_dir_path(dirname(__FILE__)) . 'template/redirect.php';
            }
        }
        return $single;
    }
}
