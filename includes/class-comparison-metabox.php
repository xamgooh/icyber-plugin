<?php

class Comparison_Metabox
{
    /**
     * Define metabox core
     *
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->register_save_events_meta();
        add_action('save_post_com_comporison', function () {
            $this->save_ctp_custom_metadata();
        }, 10, 3);
    }


    public static function get_lists()
    {

        $list_list = array(
            'select list' => 'null',
        );

        $args = array('post_type' => 'com_comporison_list');
        $posts = get_posts($args);

        foreach ($posts as &$value) {
            $list_list[$value->post_name] = "" . $value->ID;
        }
        return $list_list;
    }

    public static function get_metaboxes()
    {
        return array(
            array(
                'slug' => 'details',
                'label' => __('Details', "comporisons"),
                'position' => 'normal',
                'metadata' => array(
                    array(
                        'slug' => 'description',
                        'label' => __('Description', "comporisons"),
                        'type' => 'wp_editor',
                        'tab_class' => 'field_1',
                        'required' => false,
                    ),
                    array(
                        'slug' => 'amount_details',
                        'label' => 'Amount Details',
                        'type' => 'text',
                        'tab_class' => 'field_2',
                    ),
                    array(
                        'slug' => 'amount_color',
                        'label' => __('Amount Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_2',
                        'default' => '#01364A'
                    ),
                    array(
                        'slug' => 'preference_text',
                        'label' => __('Add Preference', "comporisons"),
                        'type' => 'repeater',
                        'tab_class' => 'field_3',
                        'fields' => array(
                            array(
                                'slug' => 'description',
                                'label' =>  __('Description', "comporisons"),
                                'type' => 'text',
                            ),
                        ),
                    ),
                    array(
                        'slug' => 'preference_color',
                        'label' => __('Preference Icon Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_3',
                        'default' => '#1e72bd'
                    ),
                    array(
                        'slug' => 'preference_text_color',
                        'label' => __('Preference Text Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_3',
                        'default' => '#6f6f6f'
                    ),
                    array(
                        'slug' => 'logo_brand_name',
                        'label' => __('Logo Brand Name', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_6',
                    ),
                    array(
                        'slug' => 'logo_brand_label',
                        'label' => __('Logo Brand Label', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_6',
                    ),
                    array(
                        'slug' => 'logo_brand_label_link',
                        'label' => __('Logo Brand Label Link', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_6',
                    ),
                    array(
                        'slug' => 'logo_brand_label_color',
                        'label' => __('Logo Brand Label Color', "comporisons"),
                        'type' => 'color',
                        'default' => '#fff',
                        'tab_class' => 'field_6',
                    ),
                    array(
                        'slug' => 'card_logo_bg_color',
                        'label' => __('Logo Background Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_6',
                        'default' => '#fff'
                    ),
                    array(
                        'slug' => 'rating',
                        'label' => __('Rating', "comporisons"),
                        'type' => 'number',
                        'tab_class' => 'field_6',
                        'min' => 1,
                        'max' => 5,
                        'required' => true,
                    ),
                    array(
                        'slug' => 'rating_color',
                        'label' => __('Rating Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_6',
                        'default' => '#fff'
                    ),
                    array(
                        'slug' => 'text_label_term_condition',
                        'label' => __('Label Dropdown button', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_4',
                        'default' => 'Terms and conditions',
                        'required' => true,
                    ),
                    array(
                        'slug' => 'more_info',
                        'label' => __('Add Terms and conditions', "comporisons"),
                        'type' => 'repeater',
                        'tab_class' => 'field_4',
                        'fields' => array(
                            array(
                                'slug' => 'link_title',
                                'label' =>  __('Title', "comporisons"),
                                'type' => 'text',
                            ),
                            array(
                                'slug' => 'description',
                                'label' =>  __('Description', "comporisons"),
                                'type' => 'text',
                            ),
                            array(
                                'slug' => 'icon',
                                'label' =>  __('Icon', "comporisons"),
                                'type' => 'select',
                                'options' => array(
                                    'no icon' => 'null',
                                    'yes' => 'yes',
                                    'no' => 'no',
                                    'star' => 'star',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'slug' => 'other_links',
                        'label' => __('Add other links / specific text', "comporisons"),
                        'type' => 'repeater',
                        'tab_class' => 'field_5',
                        'fields' => array(
                            array(
                                'slug' => 'other_links_title',
                                'label' =>  __('Title', "comporisons"),
                                'type' => 'text',
                            ),
                            array(
                                'slug' => 'other_link',
                                'label' =>  __('Link', "comporisons"),
                                'type' => 'text',
                            ),
                        ),
                    ),
                    array(
                        'slug' => 'select_btn_text',
                        'label' => __('Button Text', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_7',
                        'required' => true,
                        'default' => 'SELECT',
                    ),
                    array(
                        'slug' => 'select_btn_link',
                        'label' => __('Button Link', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_7',
                        'required' => true,
                    ),
                    array(
                        'slug' => 'select_btn_color',
                        'label' => __('Button Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_7',
                        'default' => '#5ad037'
                    ),
                    array(
                        'slug' => 'highlight',
                        'label' => __('Toggle Highlight', "comporisons"),
                        'type' => 'toggle',
                        'tab_class' => 'field_8',
                        'options' => array(
                            'yes' => true,
                            'no' => false,
                        ),
                        'default' => false
                    ),
                    array(
                        'slug' => 'highlight_label',
                        'label' => __('Highlight Label', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_8',
                        'default' => 'Hot now!',
                    ),
                    /* array(
                        'slug' => 'highlight_label_bg_color',
                        'label' => __('Highlight Label Background Image', "comporisons"),
                        'type' => 'file',
                        'tab_class' => 'field_8',
                    ),*/
                    /*array(
                        'slug' => 'list_number_bg_color',
                        'label' => __('List Number Background Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_9',
                        'default' => '#1e72bd'
                    ),*/
                    array(
                        'slug' => 'card_bg_color',
                        'label' => __('Background Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_9',
                        'default' => '#fff'
                    ),
                    array(
                        'slug' => 'card_redirect_bg_color',
                        'label' => __('Redirect Background Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_9',
                        'default' => '#fff'
                    ),
                    array(
                        'slug' => 'card_redirect_box_bg_color',
                        'label' => __('Redirect Box Background Color', "comporisons"),
                        'type' => 'color',
                        'tab_class' => 'field_9',
                        'default' => '#eee'
                    ),
                    /* array(
                        'slug' => 'list_col_4',
                        'label' => __('Default Column 4 content', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_10'
                    ),
                    array(
                        'slug' => 'list_col_5',
                        'label' => __('Default Column 5 content', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_10'
                    ),
                   array(
                        'slug' => 'list_col_6',
                        'label' => __('Default Column 6 content', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_10'
                    ),*/
                    array(
                        'slug' => 'litle_icon',
                        'label' => __('Additional icon url(If you need to display a different logo in the lists)', "comporisons"),
                        'type' => 'text',
                        'tab_class' => 'field_10'
                    ),
                    array(
                        'slug' => 'custom_columns_text',
                        'label' => __('Custom Columns Text', "comporisons"),
                        'type' => 'repeater',
                        'tab_class' => 'field_10',
                        'fields' => array(
                            array(
                                'slug' => 'select_list',
                                'label' =>  __('Lists select', "comporisons"),
                                'type' => 'select',
                                'options' => self::get_lists(),
                            ),
                            array(
                                'slug' => 'column_text_4',
                                'label' =>  __('Column 4 content', "comporisons"),
                                'type' => 'text',
                            ),
                            array(
                                'slug' => 'column_text_5',
                                'label' =>  __('Column 5 content', "comporisons"),
                                'type' => 'text',
                            ),
                            array(
                                'slug' => 'column_text_6',
                                'label' =>  __('Column 6 content', "comporisons"),
                                'type' => 'text',
                            ),
                        ),
                    ),
                ),
                'tabs' => array(
                    array(
                        'key' => 'field_1',
                        'label' => 'description',
                    ),
                    array(
                        'key' => 'field_2',
                        'label' => 'amount',
                    ),
                    array(
                        'key' => 'field_6',
                        'label' => 'logo/rating',
                    ),
                    array(
                        'key' => 'field_3',
                        'label' => 'bullets',
                    ),
                    array(
                        'key' => 'field_4',
                        'label' => 'extra info',
                    ),
                    array(
                        'key' => 'field_5',
                        'label' => 'other links/specific text',
                    ),
                    array(
                        'key' => 'field_7',
                        'label' => 'button highlight',
                    ),
                    array(
                        'key' => 'field_8',
                        'label' => 'highlight',
                    ),
                    array(
                        'key' => 'field_9',
                        'label' => 'other',
                    ),
                    array(
                        'key' => 'field_10',
                        'label' => 'shortcode_v2',
                    ),
                ),
            ),
        );
    }

    /**
     * Add metaboxes.
     */
    public static function co_add_event_metaboxes()
    {
        $post_type_slug = 'com_comporison';

        foreach (self::get_metaboxes() as $metabox) {

            $metabox_id = $post_type_slug . '_metabox_' . $metabox['slug'];
            $metabox_label = $metabox['label'];
            $metabox_callback = array(Comparison_Metabox::class, 'create_ctp_custom_metadata');
            $metabox_screen = $post_type_slug;
            $metabox_content = 'normal';
            $metabox_priority = 'default';
            $metabox_callback_args = array($metabox['metadata'], $post_type_slug);

            add_meta_box($metabox_id, $metabox_label, $metabox_callback, $metabox_screen, $metabox_content, $metabox_priority, $metabox_callback_args);
        }
    }

    public static function create_ctp_custom_metadata($post, $data)
    {

        global $admin_colors;
        $metabox = self::get_metaboxes();
        $metadata = $data['args'][0];
        $post_type_slug = $data['args'][1];

        $html = '<ul class="com-tab-group">';
        foreach ($metabox[0]['tabs'] as $key => $element) {
            $html .= '<li class="' . ($key === 0 ? 'active' : '') . ' ' . $element['key'] . '">
                <a href="" class="com-tab-button" data-endpoint="0" data-key="' . $element['key'] . '">' . __($element['label'], 'comporisons') . '</a>
                </li>';
        }

        $html .= '</ul>';

        foreach ($metadata as $metadatum) {

            $html .= '<div class="metadata-wrap ' . $metadatum['tab_class'] . ' ' . ($metadatum['tab_class'] === 'field_1' ? 'active' : 'hidden')  . '">';


            $metadatum_type = array_key_exists('type', $metadatum) ? $metadatum['type'] : 'text';
            $metadatum_label = array_key_exists('label', $metadatum) ? $metadatum['label'] : '';
            $metadatum_desc = array_key_exists('desc', $metadatum) ? $metadatum['desc'] : '';
            $metadatum_slug = array_key_exists('slug', $metadatum) ? $metadatum['slug'] : '';
            $metadatum_default = array_key_exists('default', $metadatum) ? $metadatum['default'] : '';
            $metadatum_options = array_key_exists('options', $metadatum) ? $metadatum['options'] : '';
            $metadatum_tab_class = array_key_exists('tab_class', $metadatum) ? $metadatum['tab_class'] : '';
            $metadatum_fields = array_key_exists('fields', $metadatum) ? $metadatum['fields'] : '';
            $metadatum_id = $post_type_slug . '_metadata_' . $metadatum_slug;
            $metadatum_value = get_post_meta($post->ID, $metadatum_id, true);
            $metadatum_value = $metadatum_value ? $metadatum_value : $metadatum_default;

            register_meta($post_type_slug, $metadatum_id, array(
                'single' => true,
                'show_in_rest' => true
            ));

            switch ($metadatum_type) {

                case 'hidden':

                    $html .= '<input type="hidden" name="' . $metadatum_id . '" id="' . $metadatum_id . '" value="' . $metadatum_value . '" class="widefat" />';

                    break;

                case 'number':
                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<input ' . (isset($metadatum['required']) ? 'required' : '') . ' type="number" min="' . $metadatum['min'] . '" max="' . $metadatum['max'] . '"  name="' . $metadatum_id . '" id="' . $metadatum_id . '" value="' . $metadatum_value . '" data-tabclass="' . $metadatum_tab_class . '" class="widefat" />';

                    break;

                case 'select':

                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<select ' . (isset($metadatum['required']) ? 'required' : '') . ' name="' . $metadatum_id . '" id="' . $metadatum_id . '" data-tabclass="' . $metadatum_tab_class . '" class="widefat">';

                    foreach ($metadatum_options as $metadatum_option_label => $metadatum_option_value) {

                        $html .= '<option' . ($metadatum_option_value == $metadatum_value ? ' selected="selected"' : '') . ' value="' . $metadatum_option_value . '">' . $metadatum_option_label . '</option>';
                    }

                    $html .= '</select>';

                    break;

                case 'textarea':

                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<textarea ' . (isset($metadatum['required']) ? 'required' : '') . ' name="' . $metadatum_id . '" id="' . $metadatum_id . '" data-tabclass="' . $metadatum_tab_class . '" class="widefat">' . $metadatum_value . '</textarea>';

                    break;


                case 'file':
                    $image_url = '';

                    if ($metadatum_value !== '') {

                        $image_url = wp_get_attachment_url($metadatum_value);
                    }

                    $html .= '
                    <style>
                    .com_upload_image {
                        max-width:100px;
                    }
                    .com_upload_image_remove {
                        display:block;
                    }
                    </style><p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<button ' . ($image_url !== '' ? 'style="display: none"' : '') . ' class="com_upload_image button button-primary button-large" name="' . $metadatum_id . '" id="' . $metadatum_id . '" data-tabclass="' . $metadatum_tab_class . '">' . __('Upload', 'comporisons') . '</button>';


                    $html .= '<input name="' . $metadatum_id . '"
						type="hidden"
						class="com_upload_image_save"
                        value="' . $metadatum_value . '"
					/>				
					<img src="' . ($image_url !== '' ? $image_url : '') . '"					
						style="width: 300px;"
						alt=""
						class="com_upload_image_show"
						' . ($image_url == '' ? 'style="display: none;"' : '') . '/>					
					<a
						href="#"
						class="com_upload_image_remove"
						' . ($image_url == '' ? 'style="display: none;"' : '') . '>Remove Image</a>';

                    break;

                case 'wp_editor':

                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';


                    ob_start();
                    // $html .= '<textarea ' . (isset($metadatum['required']) ? 'required' : '') . ' name="' . $metadatum_id . '" id="' . $metadatum_id . '" class="widefat">' . $metadatum_value . '</textarea>';
                    wp_editor($metadatum_value, $metadatum_id, array(
                        'textarea_name' => $metadatum_id,
                        'textarea_rows' => 15,
                        'media_buttons' => true,
                        'tinymce' => array(
                            /*'setup' => 'function(ed) {
                                ed.onInit.add(function(ed) {
                                    ed.execCommand("fontName", false, "Vietnam");
                                    ed.execCommand("fontSize", false, "2");
                                });
                            }',*/
                            'toolbar1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                            'toolbar2' => 'formatselect,fontsizeselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                            'toolbar3' => 'fontselect',
                        ),



                    ));
                    $html .= ob_get_clean();

                    break;
                case 'toggle':

                    $html .= '
                        <style>
                            toggle::after {
                                display:block;
                                clear:both;
                                content:"";
                            }
                            toggle input {
                                position:absolute;
                                left:-99999999px;
                            }
                            toggle svg {
                                height:20px;
                                width:20px;
                                display:block;
                            }
                            toggle label div {
                                display:block;
                                float:left;
                                padding:6px 12px;
                                border:solid 1px rgb(160,160,160);
                                fill:gray;
                                position: relative;
                            }
                            toggle label:first-child div {
                                border-radius:5px 0 0 5px;
                                left: 1px;
                            }
                            toggle label:last-child div {
                                border-radius:0 5px 5px 0;
                                right: 1px;
                            }
                            toggle input:checked ~ div {
                                color:white;
                                fill:white;
                                background-color: #135e96;
                                border-color: #135e96;
                                z-index:1;
                            }
                        </style>';

                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<toggle>';

                    foreach ($metadatum_options as $key => $metadatum_option) {
                        $html .= '<label><input data-tabclass="' . $metadatum_tab_class . '" ' . (isset($metadatum['required']) ? 'required' : '') . ' type="radio" name="' . $metadatum_id . '"' . ($metadatum_option == $metadatum_value ? ' checked="checked"' : '') . ' value="' . $metadatum_option . '" /><div>' . $key . '</div></label>';
                    }

                    $html .= '</toggle>';

                    break;

                case 'color':

                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<input type="text" name="' . $metadatum_id . '" id="' . $metadatum_id . '" value="' . $metadatum_value . '" data-tabclass="' . $metadatum_tab_class . '" class="widefat color_field" />';

                    break;

                case 'repeater':
                    $order = 1;
                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<table id="repeatable-fieldset-one" width="100%"><thead><tr>';
                    $html .= '<th>Order</th>';
                    foreach ($metadatum_fields as $field) {
                        $html .= '<th width="' . 100 / count($metadatum_fields) . '%">' . __($field['label'], 'comporisons') . '</th> ';
                    }
                    $html .= '</tr></thead><tbody>';
                    $field_name = 'repeater_' . $metadatum['slug'] . '_field';
                    $serialize_data = get_post_meta($post->ID, $metadatum_id, true);
                    $data = $repeatable_fields = [];

                    if ($serialize_data) {
                        $data = unserialize($serialize_data);
                    }

                    if (is_array($data)) {
                        foreach ($data as $key => $value) {
                            $repeatable_fields[$key] = $value;
                        }
                    } else {
                        $repeatable_fields[] = stripslashes(strip_tags($data));
                    }


                    if ($repeatable_fields) :

                        foreach ($repeatable_fields as $field) {
                            $html .= '<tr>';
                            $html .= '<td class="order text-center">' . $order++ . '</td>';
                            for ($i = 0; $i < count($field); $i++) {
                                $field_id = $field_name . '_' . $metadatum['fields'][$i]['slug'];

                                if ($field[$field_id]) {
                                    if ($field[$field_id]['type'] === 'text') {
                                        $html .= '<td><input type="text" class="widefat" name="' . $field_name . '_' . $metadatum['fields'][$i]['slug'] . '[]" value="' . $field[$field_id]['data'] . '" /></td>';
                                    } elseif ($field[$field_id]['type'] === 'select') {
                                        $html .= '<td><select name="' . $field_name . '_' . $metadatum['fields'][$i]['slug'] . '[]" class="widefat">';

                                        foreach ($metadatum['fields'][$i]['options'] as $option_label => $option_value) {

                                            $html .= '<option' . ($option_value === $field[$field_id]['data'] ? ' selected="selected"' : '') . ' value="' . $option_value . '">' . $option_label . '</option>';
                                        }

                                        $html .= '</select></td>';
                                    }
                                }
                            }
                            $html .= '<td><a class="button remove-row" href="#">Remove</a></td>
                        </tr>';
                        }
                    endif;

                    $html .= '<!-- empty hidden one for jQuery --><tr class="empty-row screen-reader-text">';
                    $html .= self::repeater_field_generate_helper($metadatum_fields, $field_name,$order, $metadatum_tab_class);
                    $html .= '<td><a class="button remove-row" href="#">Remove</a></td></tr>
                    </tbody>
                    </table>
                    <p><a class="button add-row" href="#" data-order="'. $order .'">Add another</a></p>';

                    break;

                default:

                    $html .= '<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="' . $metadatum_id . '">' . $metadatum_label . (isset($metadatum['required']) ? ' *' : '') . '</label></p>';

                    $html .= '<div class="metadata-desc">' . $metadatum_desc . '</div>';

                    $html .= '<input ' . (isset($metadatum['required']) ? 'required' : '') . ' type="' . $metadatum_type . '" name="' . $metadatum_id . '" id="' . $metadatum_id . '" value="' . $metadatum_value . '" data-tabclass="' . $metadatum_tab_class . '" class="widefat" />';

                    break;
            }

            $html .= '</div>';
        }

        echo $html . '<input type="hidden" name="custommeta_noncename" id="custommeta_noncename" value="' . wp_create_nonce(basename(__FILE__)) . '" />';
    }

    public static function repeater_field_generate_helper($metadatum_fields, $field_name, &$order, $tab_class = '')
    {
        $html = '';
        $html .= '<td class="order text-center">' . $order . '</td>';
        foreach ($metadatum_fields as $field) {

            if ($field['type'] === 'text') {
                $html .= '<td><input type="text" class="widefat" data-tabclass="' . $tab_class . '" name="' . $field_name . '_' . $field['slug'] . '[]" /></td>';
            } elseif ($field['type'] === 'select') {
                $html .= '<td><select name="' . $field_name . '_' . $field['slug'] . '[]" class="widefat" data-tabclass="' . $tab_class . '">';

                foreach ($field['options'] as $option_label => $option_value) {
                    $html .= '<option' . ($option_label == array_key_first($field['options']) ? ' selected="selected"' : '') . ' value="' . $option_value . '">' . $option_label . '</option>';
                }
                $html .= '</select></td>';
            }
        }

        return $html;
    }

    public function save_ctp_custom_metadata()
    {
        global $post;

        if (empty($_POST["custommeta_noncename"])) {
            return;
        }
        if (!wp_verify_nonce($_POST['custommeta_noncename'], basename(__FILE__))) {
            return;
        }
        if (!current_user_can('edit_post', $post->ID)) {
            return;
        }
        if ($post->post_type == 'revision') {
            return;
        }

        $post_type_slug = get_post_type($post);
        $metadata_id = '';
        $metadata_object = array();

        foreach (self::get_metaboxes() as $metabox) {

            foreach ($metabox['metadata'] as $metadatum) {
                $metadata_id = $post_type_slug . '_metadata_' . $metadatum['slug'];
                $metadata_object[$metadata_id] = isset($_POST[$metadata_id]) ? $_POST[$metadata_id] : [];

                if ($metadatum['type'] == 'repeater') {
                    $metadata_repeater_id = 'repeater_' . $metadatum['slug'] . '_field_';

                    $new = array();
                    $meta_i = 0;

                    for (; $meta_i < count($metadatum['fields']); $meta_i++) {
                        $field_slug = $metadata_repeater_id . $metadatum['fields'][$meta_i]['slug'];
                        $data = isset($_POST[$field_slug]) ? $_POST[$field_slug] : null;

                        if ($data) {
                            if (is_array($data)) {

                                for ($i = 0; $i < count(array_slice($data, 0, -1)); $i++) {
                                    $new[$i][$field_slug]['data'] =  $data[$i] != '' ? stripslashes(strip_tags($data[$i])) : '';
                                    $new[$i][$field_slug]['type'] = $metadatum['fields'][$meta_i]['type'];
                                }
                            } else {
                                $new[0][$field_slug] = stripslashes(strip_tags($data));
                            }
                        }
                    }
                    $metadata_object[$metadata_id] =  $new;
                }
            }
        }

        self::save_ctp_metadata_object($metadata_object, $post->ID);
    }

    public static function save_ctp_metadata_object($metadata_object, $post_ID)
    {
        foreach ($metadata_object as $key => $value) {
            //$value = implode(',', (array)$value);
            if (get_post_meta($post_ID, $key, FALSE)) {
                update_post_meta($post_ID, $key, is_array($value) ? serialize($value) : $value);
            } else {
                add_post_meta($post_ID, $key, is_array($value) ? serialize($value) : $value);
            }
            if (!$value) {
                delete_post_meta($post_ID, $key);
            }
        }
    }


    public static function register_save_events_meta()
    {
        /**
         * Save the metabox data
         */
        function wpt_save_events_meta($post_id, $post)
        {
            // Return if the user doesn't have edit permissions.
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

            // Verify this came from the our screen and with proper authorization,
            // because save_post can be triggered at other times.
            if (!isset($_POST['location']) || !wp_verify_nonce($_POST['com_comporison_fields'], basename(__FILE__))) {
                return $post_id;
            }

            // Now that we're authenticated, time to save the data.
            // This sanitizes the data from the field and saves it into an array $events_meta.
            $events_meta['location'] = esc_textarea($_POST['location']);

            // Cycle through the $events_meta array.
            // Note, in this example we just have one item, but this is helpful if you have multiple.
            foreach ($events_meta as $key => $value) :

                // Don't store custom data twice
                if ('revision' === $post->post_type) {
                    return;
                }

                if (get_post_meta($post_id, $key, false)) {
                    // If the custom field already has a value, update it.
                    update_post_meta($post_id, $key, $value);
                } else {
                    // If the custom field doesn't have a value, add it.
                    add_post_meta($post_id, $key, $value);
                }

                if (!$value) {
                    // Delete the meta key if there's no value
                    delete_post_meta($post_id, $key);
                }

            endforeach;
        }
        add_action('save_post_com_comporison', 'wpt_save_events_meta', 1, 2);
    }
}
