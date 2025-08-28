<?php

/**
 * Sub menu class
 *
 * @author Mostafa <mostafa.soufi@hotmail.com>
 */
class Comparison_Sub_Menu
{
    /**
     * Autoload method
     * @return void
     */
    public function __construct()
    {
        add_action('admin_menu', array(&$this, 'register_sub_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_comparison_save_redirect_settings', array($this, 'ajax_save_redirect_settings'));
    }

    /**
     * Register all settings
     */
    public function register_settings()
    {
        add_settings_section(
            'comporisons_settings_section_id', // section ID
            '', // title (if needed)
            '', // callback function (if needed)
            'comporisons-slug' // page slug
        );

        /** Sort button label */
        register_setting(
            'comporisons_settings', // settings group name
            'comporisons_sorting_label', // option name
            'sanitize_text_field' // sanitization function
        );

        /** Filter button label */
        register_setting(
            'comporisons_settings', // settings group name
            'comporisons_filter_label', // option name
            'sanitize_text_field' // sanitization function
        );

        /** NEW Popup Redirect Settings */
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_title');
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_safe_text');
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_loading_text');
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_fallback_text');
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_button_text');
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_delay');
        register_setting('comporisons_redirect_settings', 'comporisons_redirect_terms');

        /** sorting label settigns field register */
        add_settings_field(
            'comporisons_sorting_label',
            'Sort Button Label',
            [$this, 'comporisons_text_field_html'], // function which prints the field
            'comporisons-slug', // page slug
            'comporisons_settings_section_id', // section ID
            array(
                'label_for' => 'comporisons_sorting_label',
                'class' => 'comporisons-class', // for <tr> element
            )
        );

        /** filter label settigns field register */
        add_settings_field(
            'comporisons_filter_label',
            'Filter Button Label',
            [$this, 'comporisons_filter_text_field_html'], // function which prints the field
            'comporisons-slug', // page slug
            'comporisons_settings_section_id', // section ID
            array(
                'label_for' => 'comporisons_filter_label',
                'class' => 'comporisons-class', // for <tr> element
            )
        );
    }

    /** filter button label */
    public function comporisons_filter_text_field_html()
    {
        $label = get_option('comporisons_filter_label');
        printf(
            '<input type="text" id="comporisons_filter_label" name="comporisons_filter_label" value="%s" required />',
            esc_attr($label)
        );
    }

    /** sorting button label */
    public function comporisons_text_field_html()
    {
        $label = get_option('comporisons_sorting_label');
        printf(
            '<input type="text" id="comporisons_sorting_label" name="comporisons_sorting_label" value="%s" required />',
            esc_attr($label)
        );
    }

    /**
     * Register submenu
     * @return void
     */
    public function register_sub_menu()
    {
        add_submenu_page(
            'edit.php?post_type=com_comporison',
            __('Settings', 'comporisons'),
            __('Settings', 'comporisons'),
            'manage_options',
            'com_comporison-settings',
            array(&$this, 'submenu_page_callback')
        );

        // NEW Redirect Settings Page
        add_submenu_page(
            'edit.php?post_type=com_comporison',
            __('Redirect Settings', 'comporisons'),
            __('Redirect Settings', 'comporisons'),
            'manage_options',
            'com_comporison-redirect-settings',
            array(&$this, 'render_redirect_settings_page')
        );

        add_submenu_page(
            'edit.php?post_type=com_comporison_list',
            __('Settings', 'comporisons'),
            __('Settings', 'comporisons'),
            'manage_options',
            'com_comporison-list-settings',
            array(&$this, 'submenu_list_page_callback')
        );
    }

    /**
     * Render submenu
     * @return void
     */
    public function submenu_page_callback()
    {
        echo '<div class="wrap">
        <h2>' . __('Compareit Settings Page', 'comporisons') . '</h2>
        <form method="post" action="options.php">';

        settings_fields('comporisons_settings'); // settings group name
        do_settings_sections('comporisons-slug'); // just a page slug
        submit_button();

        echo '</form>';
        echo '<h3>Shortcode Name</h3><code id="comporisons_shortcode">[Comparison]</code>';
        echo '<h3>Default withdrawal of the last cards. The maximum number is 15 pieces.</h3><code id="comporisons_shortcode">[Comparison]</code>';
        echo '<h3>Attribute: \'max = number\' - changes the number of displayed cards.</h3><code id="comporisons_shortcode">[Comparison max = 20], [Comparison max = 5]</code>';
        echo '<h3>Attribute \'load_more\' - Adds a button that loads all cards that match the conditions. This attribute is best combined with a category attribute.</h3><code id="comporisons_shortcode">[Comparison load_more]</code></p>';
        echo '<h3>Attribute \'category = "Category Name"\' - Filters the output by the category or categories that were submitted.</h3><code id="comporisons_shortcode">[Comparison category = "Category Name"], [Comparison category = "Category Name, Other Name"]</code></p>';
        echo '<h3>Attribute \'filter\' - Adds a panel for filtering by categories. This attribute is best used with the category attribute.</h3><code id="comporisons_shortcode">[Comparison filter]</code></p>';
        echo '<h3>You can also combine:</h3><p><code id="comporisons_shortcode">[Comparison category="Category1, Сategory2" max=10 filter load_more]</code></p>';
        echo '</div>';
    }

    /**
     * Render submenu list page
     * @return void
     */
    public function submenu_list_page_callback()
    {
        echo '<div class="wrap">
        <h2>' . __('Brand Lists Settings Page', 'comporisons') . '</h2>';

        echo '<h3>Shortcode Name</h3><code id="comporisons_shortcode">[Comparison_v2 list_id="*"]</code>';
        echo '<h3>Attribute: \'max = number\' - changes the number of displayed cards.</h3><code id="comporisons_shortcode">[Comparison_v2 max=20 list_id="*"], [Comparison_v2 max=5 list_id="*"]</code>';
        echo '<h3>Attribute \'load_more\' - Adds a button that loads all cards that match the conditions. This attribute is best combined with a category attribute.</h3><code id="comporisons_shortcode">[Comparison list_id="*" max=5 list_id="*" load_more]</code></p>';
        echo '</div>';
    }

    /**
     * Render NEW redirect settings page
     * @return void
     */
    public function render_redirect_settings_page()
    {
        // Get current settings with defaults
        $title = get_option('comporisons_redirect_title', 'Thank you for visiting {BRAND_NAME}!');
        $safe_text = get_option('comporisons_redirect_safe_text', 'You are now being redirected to {BRAND_NAME}');
        $loading_text = get_option('comporisons_redirect_loading_text', 'PLEASE WAIT');
        $fallback_text = get_option('comporisons_redirect_fallback_text', 'If you are not forwarded to');
        $button_text = get_option('comporisons_redirect_button_text', 'CLICK HERE');
        $redirect_delay = get_option('comporisons_redirect_delay', '3000');
        
        // Terms stored as JSON
        $terms_json = get_option('comporisons_redirect_terms', '["18+ only", "Play responsibly", "Terms apply"]');
        $terms = json_decode($terms_json, true);
        if (!is_array($terms)) {
            $terms = array('18+ only', 'Play responsibly', 'Terms apply');
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Redirect Settings', 'comporisons'); ?></h1>
            
            <p><?php _e('Configure the redirect popup that appears when users click on brand links.', 'comporisons'); ?></p>
            <p><strong><?php _e('Note:', 'comporisons'); ?></strong> <?php _e('Use {BRAND_NAME} as a placeholder where you want the brand name to appear.', 'comporisons'); ?></p>
            
            <form id="redirect-settings-form" method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="redirect_title"><?php _e('Popup Title', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="redirect_title" 
                                   name="redirect_title" 
                                   class="large-text" 
                                   value="<?php echo esc_attr($title); ?>">
                            <p class="description"><?php _e('Main heading in the redirect popup. Use {BRAND_NAME} to insert brand name.', 'comporisons'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="safe_text"><?php _e('Status Message', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="safe_text" 
                                   name="safe_text" 
                                   class="large-text" 
                                   value="<?php echo esc_attr($safe_text); ?>">
                            <p class="description"><?php _e('Message shown below the title. Use {BRAND_NAME} to insert brand name.', 'comporisons'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="loading_text"><?php _e('Loading Text', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="loading_text" 
                                   name="loading_text" 
                                   class="regular-text" 
                                   value="<?php echo esc_attr($loading_text); ?>">
                            <p class="description"><?php _e('Text shown below the loading bar', 'comporisons'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="fallback_text"><?php _e('Fallback Text', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="fallback_text" 
                                   name="fallback_text" 
                                   class="large-text" 
                                   value="<?php echo esc_attr($fallback_text); ?>">
                            <p class="description"><?php _e('Text shown before the fallback button', 'comporisons'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="button_text"><?php _e('Button Text', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="button_text" 
                                   name="button_text" 
                                   class="regular-text" 
                                   value="<?php echo esc_attr($button_text); ?>">
                            <p class="description"><?php _e('Text for the green fallback button', 'comporisons'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="redirect_delay"><?php _e('Redirect Delay', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="redirect_delay" 
                                   name="redirect_delay" 
                                   class="small-text" 
                                   value="<?php echo esc_attr($redirect_delay); ?>" 
                                   min="1000" 
                                   max="10000" 
                                   step="100">
                            <span><?php _e('milliseconds', 'comporisons'); ?></span>
                            <p class="description"><?php _e('Time before automatic redirect (1000 = 1 second, 3000 = 3 seconds)', 'comporisons'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label><?php _e('Terms & Conditions', 'comporisons'); ?></label>
                        </th>
                        <td>
                            <div id="terms-list">
                                <?php foreach ($terms as $term) : ?>
                                <div class="term-item">
                                    <input type="text" name="terms[]" class="regular-text" value="<?php echo esc_attr($term); ?>">
                                    <button type="button" class="button remove-term"><?php _e('Remove', 'comporisons'); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="button" id="add-term"><?php _e('+ Add Term', 'comporisons'); ?></button>
                            <p class="description"><?php _e('Terms displayed at the bottom of the redirect popup', 'comporisons'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary"><?php _e('Save Settings', 'comporisons'); ?></button>
                    <span class="spinner" style="float: none;"></span>
                </p>
                
                <?php wp_nonce_field('comparison_redirect_settings', 'redirect_nonce'); ?>
            </form>
        </div>
        
        <style>
            .term-item {
                margin-bottom: 10px;
            }
            .term-item input {
                margin-right: 10px;
            }
            #add-term {
                margin-top: 10px;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Add term
            $('#add-term').on('click', function() {
                var html = '<div class="term-item">' +
                    '<input type="text" name="terms[]" class="regular-text" value="">' +
                    '<button type="button" class="button remove-term"><?php _e('Remove', 'comporisons'); ?></button>' +
                    '</div>';
                $('#terms-list').append(html);
            });
            
            // Remove term
            $(document).on('click', '.remove-term', function() {
                $(this).closest('.term-item').remove();
            });
            
            // Save settings via AJAX
            $('#redirect-settings-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var $spinner = $form.find('.spinner');
                var $button = $form.find('.button-primary');
                
                $spinner.addClass('is-active');
                $button.prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: $form.serialize() + '&action=comparison_save_redirect_settings',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            var notice = $('<div class="notice notice-success is-dismissible"><p><?php _e('Settings saved successfully.', 'comporisons'); ?></p></div>');
                            $('.wrap h1').after(notice);
                            
                            // Auto-dismiss after 3 seconds
                            setTimeout(function() {
                                notice.fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 3000);
                        } else {
                            // Show error
                            var notice = $('<div class="notice notice-error is-dismissible"><p><?php _e('Error saving settings.', 'comporisons'); ?></p></div>');
                            $('.wrap h1').after(notice);
                        }
                    },
                    complete: function() {
                        $spinner.removeClass('is-active');
                        $button.prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX handler for saving redirect settings
     */
    public function ajax_save_redirect_settings() {
        // Check nonce
        if (!isset($_POST['redirect_nonce']) || !wp_verify_nonce($_POST['redirect_nonce'], 'comparison_redirect_settings')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        // Save settings
        update_option('comporisons_redirect_title', sanitize_text_field($_POST['redirect_title']));
        update_option('comporisons_redirect_safe_text', sanitize_text_field($_POST['safe_text']));
        update_option('comporisons_redirect_loading_text', sanitize_text_field($_POST['loading_text']));
        update_option('comporisons_redirect_fallback_text', sanitize_text_field($_POST['fallback_text']));
        update_option('comporisons_redirect_button_text', sanitize_text_field($_POST['button_text']));
        update_option('comporisons_redirect_delay', intval($_POST['redirect_delay']));
        
        // Save terms as JSON
        $terms = array();
        if (isset($_POST['terms']) && is_array($_POST['terms'])) {
            foreach ($_POST['terms'] as $term) {
                $term = sanitize_text_field($term);
                if (!empty($term)) {
                    $terms[] = $term;
                }
            }
        }
        update_option('comporisons_redirect_terms', json_encode($terms));
        
        wp_send_json_success();
    }
}
