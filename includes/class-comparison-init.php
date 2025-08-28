<?php

class Comparison_Init
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $сomparison The ID of this plugin.
     */
    private $сomparison;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $сomparison The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($сomparison, $version)
    {
        $this->сomparison = $сomparison;
        $this->version = $version;

        if ( is_admin() ) {
            $this->add_shortcode_columns();
        }
        
        // Register category form fields
        $this->comparison_category_form_fields();
    }

    public function add_shortcode_columns() {

        $list_post_type = 'com_comporison_list';
        $post_type = 'com_comporison';

        // Register the columns.
        add_filter( "manage_{$list_post_type}_posts_columns", function ( $defaults ) {
            
            $defaults['list_shortcode'] = 'List Shortcode';
    
            return $defaults;
        } );
        add_filter( "manage_{$post_type}_posts_columns", function ( $defaults ) {
            
            $defaults['post_id'] = 'Post id';
    
            return $defaults;
        } );
        
        // Handle the value for each of the new columns.
        add_action( "manage_{$list_post_type}_posts_custom_column", function ( $column_name, $post_id ) {
            $post_id = absint( $post_id );
            if ( $column_name == 'list_shortcode' ) {
                echo '<code>[Comparison_v2 list_id="' . esc_attr( $post_id ) . '"]</code>';
            }
            
   
            
        }, 10, 2 );
        add_action( "manage_{$post_type}_posts_custom_column", function ( $column_name, $post_id ) {
            $post_id = absint( $post_id );
            if ( $column_name == 'post_id' ) {
                echo '<code>' . esc_attr( $post_id ) . '</code>';
            }
            
   
            
        }, 10, 2 );

    }

    public function rest_api_end_point()
    {
        add_action('rest_api_init', function () {
            // REMOVED: getcards endpoint - no longer needed for card view
            // Only register the list cards endpoint for table/list view
            
            register_rest_route('comparison/v1', '/get_list_cards', array(
                'methods' => 'post',
                'callback' => array($this, 'postListCards'),
                'permission_callback' => function ($request) {
                    // This always returns true
                    return __return_true();
                },
            ));
        });
    }

    public function comparison_category_form_fields()
    {
        add_action('com_category_add_form_fields', function () {
            // this will add the custom meta field to the add new term page
            ?>
            <div class="form-field">
                <label for="series_image"><?php _e('Series Image:', 'journey'); ?></label>
                <input type="text" name="series_image[image]" id="series_image[image]" class="series-image">
                <input class="upload_image_button button" name="_add_series_image" id="_add_series_image" type="button"
                       value="Select/Upload Image"/>
                <script>
                    jQuery(document).ready(function () {
                        jQuery('#_add_series_image').click(function () {
                            wp.media.editor.send.attachment = function (props, attachment) {
                                jQuery('.series-image').val(attachment.url);
                            }
                            wp.media.editor.open(this);
                            return false;
                        });
                    });
                </script>
            </div>
            <?php

        });

        add_action('com_category_edit_form_fields', function ($term) {
            // put the term ID into a variable
            $t_id = $term->term_id;

            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_option("weekend-series_$t_id"); ?>

            <tr class="form-field">
                <th scope="row" valign="top"><label for="_series_image"><?php _e('Series Image', 'journey'); ?></label>
                </th>
                <td>
                    <?php
                    $seriesimage = esc_attr($term_meta['image']) ? esc_attr($term_meta['image']) : '';
                    ?>
                    <input type="text" name="series_image[image]" id="series_image[image]" class="series-image"
                           value="<?php echo $seriesimage; ?>">
                    <input class="upload_image_button button" name="_series_image" id="_series_image" type="button"
                           value="Select/Upload Image"/>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"></th>
                <td style="height: 150px;">
                    <style>
                        div.img-wrap {
                            background-size: contain;
                            max-width: 450px;
                            max-height: 150px;
                            width: 100%;
                            height: 100%;
                            overflow: hidden;
                        }

                        div.img-wrap img {
                            max-width: 450px;
                        }
                    </style>
                    <div class="img-wrap">
                        <img src="<?php echo $seriesimage; ?>" id="series-img">
                    </div>
                    <script>
                        jQuery(document).ready(function () {
                            jQuery('#_series_image').click(function () {
                                wp.media.editor.send.attachment = function (props, attachment) {
                                    jQuery('#series-img').attr("src", attachment.url)
                                    jQuery('.series-image').val(attachment.url)
                                }
                                wp.media.editor.open(this);
                                return false;
                            });
                        });
                    </script>
                </td>
            </tr>
            <?php
        }, 10, 2);

        add_action('created_com_category', 'com_category_save_term_fields');
        add_action('edited_com_category', 'com_category_save_term_fields');

        function com_category_save_term_fields($term_id)
        {
            if (isset($_POST['series_image'])) {
                $t_id = $term_id;
                $term_meta = get_option("weekend-series_$t_id");
                $cat_keys = array_keys($_POST['series_image']);
                foreach ($cat_keys as $key) {
                    if (isset($_POST['series_image'][$key])) {
                        $term_meta[$key] = $_POST['series_image'][$key];
                    }
                }
                // Save the option array.
                update_option("weekend-series_$t_id", $term_meta);
            }
        }
    }

    // REMOVED: postCards method - no longer needed for card view
    // This method was used for the [Comparison] shortcode which is being removed

    public function postListCards($request)
    {
        define( 'JSON_REQUEST', true );
        $req = $request->get_params();

        $schema = array(
            'type' => 'object',
            'properties' => array(
                'category' => array(
                    'type' => array( 'null', 'string' ),
                ),
                'offset' => array(
                    'type' => array( 'null', 'number' ),
                ),
                'list_id' => array(
                    'type' => array( 'null', 'number' ),
                ),
            ),
        );
        
        if (is_wp_error(rest_validate_value_from_schema(json_decode($req['p']), $schema))) {
            return new WP_Error('no_author', 'Invalid author', array('status' => 404));
        }

        $data = rest_sanitize_value_from_schema(json_decode($req['p']), $schema);
        
        // Parse offset - this represents how many items have already been loaded
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        
        // Default posts per page for load more
        $posts_per_page = 15;

        $args = array(
            'post_type' => 'com_comporison',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'offset' => $offset, // Use WordPress native offset parameter
        );

        if (!empty($data['category']) && $data['category'] != 'null') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'com_category',
                    'field' => 'slug',
                    'terms' => $data['category'],
                    'operator' => 'IN'
                )
            );
        }

        if (!empty($data['list_id'])) {
            $comparison_list_metabox = ComparisonHtml::metabox_list_transform_to_array($data['list_id']);
            $posts_ids = [];
            $posts_ids2 = [];
            
            foreach ($comparison_list_metabox['brand_in_list'] as $key => $value) {
                array_push($posts_ids, ["brand_id" => $value['select_post'], "brand_other_link" => $value['brand_other_link']]);
            }
            
            if ($comparison_list_metabox) {
                foreach ($posts_ids as $key => $value) {
                    array_push($posts_ids2, $value["brand_id"]);
                }
                
                // When using post__in with offset, we need to slice the array
                if ($offset > 0) {
                    // Get only the IDs we need based on offset
                    $posts_ids2 = array_slice($posts_ids2, $offset, $posts_per_page);
                    
                    // If we have specific IDs after offset, use them
                    if (!empty($posts_ids2)) {
                        $args['post__in'] = $posts_ids2;
                        $args['orderby'] = 'post__in';
                        // Reset offset since we're using specific IDs
                        $args['offset'] = 0;
                        // Set posts_per_page to the number of IDs we have
                        $args['posts_per_page'] = count($posts_ids2);
                    } else {
                        // No more posts to load
                        return wp_send_json(['code' => 'success', 'data' => '', 'status' => 200]);
                    }
                } else {
                    // For initial load or when no offset
                    $sliced_ids = array_slice($posts_ids2, 0, $posts_per_page);
                    if (!empty($sliced_ids)) {
                        $args['post__in'] = $sliced_ids;
                        $args['orderby'] = 'post__in';
                    }
                }
            }
        }

        $query = new WP_Query($args);
        
        if (empty($query) || !$query->have_posts()) {
            return wp_send_json(['code' => 'success', 'data' => '', 'status' => 200]);
        }

        $comparion_list_html = new ComparisonHtml();
        
        // Pass the actual offset to maintain correct numbering
        // The third parameter should be the count to display, fourth is the starting number for items
        $html = $comparion_list_html->get_list_row_html_v2($query, $data['list_id'], $posts_per_page, $offset);

        return wp_send_json(['code' => 'success', 'data' => $html, 'status' => 200]);
    }

    public function comparison_custom_posttype()
    {
        $comparsionPostType = new Comparison_Custom_Posttype();
        $comparsionPostType->comparison_setup_post_type(Comparison_Metabox::class);
        $comparsionPostType->register_post_type_template();
    }
    
    public function comparison_list_custom_posttype()
    {
        $comparsionPostType = new Comparison_List_Custom_Posttype();
        $comparsionPostType->comparison_list_setup_post_type(Comparison_List_Metabox::class);
        $comparsionPostType->register_post_type_template();
    }

    public function comparison_taxonomy()
    {
        /**
         * Taxonomy: Programs.
         */
        $labels = [
            'name' => __('Categories', 'comporisons'),
            'singular_name' => __('Category', 'comporisons'),
        ];

        $args = [
            'label' => __('Categories', 'comporisons'),
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'com_category', 'with_front' => true],
            'show_admin_column' => false,
            'show_in_rest' => false,
            'rest_base' => 'com_category',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit' => false,
            'show_in_graphql' => false,
        ];
        register_taxonomy('com_category', ['com_comporison'], $args);
    }
}
