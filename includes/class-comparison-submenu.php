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

        add_action('admin_init',  function () {

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

            /** Redirect title setting register */
            register_setting(
                'comporisons_settings', // settings group name
                'comporisons_redirect_title_label', // option name
                'sanitize_text_field' // sanitization function
            );

            /** Redirect link title setting register */
            register_setting(
                'comporisons_settings', // settings group name
                'comporisons_redirect_link_title', // option name
                'sanitize_text_field' // sanitization function
            );

            /** Redirect link message setting register */
            register_setting(
                'comporisons_settings', // settings group name
                'comporisons_redirect_link_message', // option name
                'sanitize_text_field' // sanitization function
            );

            /** Redirect Delay setting register */
            register_setting(
                'comporisons_settings', // settings group name
                'comporisons_redirect_delay', // option name
                'sanitize_text_field' // sanitization function
            );

            /** Redirect Main title setting register */
            register_setting(
                'comporisons_settings', // settings group name
                'comporisons_redirect_main_title', // option name
                'sanitize_text_field' // sanitization function
            );

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

            /** Redirect Title settigns field register */
            add_settings_field(
                'comporisons_redirect_title_label',
                'Redirect Page Title Message',
                [$this, 'comporisons_redirect_title_field_html'], // function which prints the field
                'comporisons-slug', // page slug
                'comporisons_settings_section_id', // section ID
                array(
                    'label_for' => 'comporisons_redirect_title_label',
                    'class' => 'comporisons-class', // for <tr> element
                )
            );

            /** Redirect Link Title settigns field register */
            add_settings_field(
                'comporisons_redirect_link_title',
                'Redirect Page Link Title',
                [$this, 'comporisons_redirect_link_title_field_html'], // function which prints the field
                'comporisons-slug', // page slug
                'comporisons_settings_section_id', // section ID
                array(
                    'label_for' => 'comporisons_redirect_link_title',
                    'class' => 'comporisons-class', // for <tr> element
                )
            );


            /** Redirect Link Message settigns field register */
            add_settings_field(
                'comporisons_redirect_link_message',
                'Redirect Page Link Message',
                [$this, 'comporisons_redirect_link_message_field_html'], // function which prints the field
                'comporisons-slug', // page slug
                'comporisons_settings_section_id', // section ID
                array(
                    'label_for' => 'comporisons_redirect_link_message',
                    'class' => 'comporisons-class', // for <tr> element
                )
            );

            /** Redirect Delay settigns field register */
            add_settings_field(
                'comporisons_redirect_delay',
                'Redirect Page Delay per Second',
                [$this, 'comporisons_redirect_delay_field_html'], // function which prints the field
                'comporisons-slug', // page slug
                'comporisons_settings_section_id', // section ID
                array(
                    'label_for' => 'comporisons_redirect_delay',
                    'class' => 'comporisons-class', // for <tr> element
                )
            );

            /** Redirect Delay settigns field register */
            add_settings_field(
                'comporisons_redirect_main_title',
                'Redirect Page Delay per Second',
                [$this, 'comporisons_redirect_main_title_field_html'], // function which prints the field
                'comporisons-slug', // page slug
                'comporisons_settings_section_id', // section ID
                array(
                    'label_for' => 'comporisons_redirect_main_title',
                    'class' => 'comporisons-class', // for <tr> element
                )
            );
        });
    }

    /** Redirect Delay */
    public function comporisons_redirect_main_title_field_html()
    {
        $label = get_option('comporisons_redirect_main_title');

        printf(
            '<input type="text" id="comporisons_redirect_main_title" name="comporisons_redirect_main_title" value="%s" required />',
            esc_attr($label)
        );
    }

    /** Redirect Delay */
    public function comporisons_redirect_delay_field_html()
    {
        $label = get_option('comporisons_redirect_delay');

        printf(
            '<input type="text" id="comporisons_redirect_delay" name="comporisons_redirect_delay" value="%s" required />',
            esc_attr($label)
        );
    }

    /** Redirect Link Message */
    public function comporisons_redirect_link_message_field_html()
    {
        $label = get_option('comporisons_redirect_link_message');

        printf(
            '<input type="text" id="comporisons_redirect_link_message" name="comporisons_redirect_link_message" value="%s" required />',
            esc_attr($label)
        );
    }

    /** Redirect Link Title */
    public function comporisons_redirect_link_title_field_html()
    {
        $label = get_option('comporisons_redirect_link_title');

        printf(
            '<input type="text" id="comporisons_redirect_link_title" name="comporisons_redirect_link_title" value="%s" required />',
            esc_attr($label)
        );
    }

    /** Redirect Title Field */
    public function comporisons_redirect_title_field_html()
    {
        $label = get_option('comporisons_redirect_title_label');

        printf(
            '<input type="text" id="comporisons_redirect_title_label" name="comporisons_redirect_title_label" value="%s" required />',
            esc_attr($label)
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
     * Render submenu
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
}
