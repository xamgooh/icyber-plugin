<?php
class ComparisonHtml
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $comparison    The string used to uniquely identify this plugin.
     */
    protected $comparison;

    /**
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $comparison_metabox
     */
    protected $comparison_metabox;

    /**
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $comparison_list_metabox
     */
    protected $comparison_list_metabox;

    /**
     * Define metabox core
     *
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->comparison = 'com_comporison';
    }

    // REMOVED: get_list_html() method - was used for card view

    // render list shortcode - V2 ONLY
    public function get_list_html_v2($wp_query, $list_id, $count = 15, $offset = 0)
    {
        $comparison_list_metabox = self::metabox_list_transform_to_array($list_id);

        if ($comparison_list_metabox) {
            $html = '';
            $html .= '<table class="comparison-bonus-list" width="100%">';
            $html .= '
        <thead><tr 
        ' . (!empty($comparison_list_metabox['table_heading_color']) ? 'style="background-color:' . $comparison_list_metabox['table_heading_color'] . '!important;"' : '') . '
        class="cbl-sort">';

            $column_num = 1;
            foreach ($comparison_list_metabox['column_more_info'] as $key => $value) {

                $html .= '<th class="cbl-col-' . $column_num . '">' . $value['column_title'];
                $html .= '
            <style>thead .cbl-col-' . $column_num . '{' .
                    (!empty($value['column_title_color']) ? 'color:' . $value['column_title_color'] . '!important;' : '') .
                    //(!empty($value['column_bg_color']) ? 'background-color:'.$value['column_bg_color'].'!important;' : '') .
                    (!empty($value['column_font_weight']) ? 'font-weight:' . $value['column_font_weight'] . '!important;' : '') .
                    (!empty($value['column_font_size']) ? 'font-size:' . $value['column_font_size'] . '!important;' : '') .
                    '@media (max-width: 768px){' .
                    (!empty($value['column_mobile_font_size']) ? 'font-size:' . $value['column_mobile_font_size'] . '!important;' : '');
                if ($value['name_on_mobile'] !== 'show') {
                    $html .= 'display:none!important;';
                }
                $html .= '}}</style></th>';

                $column_num++;
            }
            $html .= '<th class="cbl-button"></th></tr></thead><tbody class="com_comparision_table-container">';

            if ($comparison_list_metabox['top_bar_visib'] == 'show') {
                $html .= '<tr class="general-terms">';
                $html .= $this->get_html_top_bar_info($comparison_list_metabox);
                $html .= '</tr>';
            }


            $html .= $this->get_list_row_html_v2($wp_query, $list_id, $count, $offset);

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<style>';

            $html .= '.cbl-col-4 p{';
            if (!empty($comparison_list_metabox['col_4_5_title_color'])) {
                $html .= 'color:' . $comparison_list_metabox['col_4_5_title_color'] . ' !important;';
            }
            if (!empty($comparison_list_metabox['col_4_5_font_weight'])) {
                $html .= 'font-weight:' . $comparison_list_metabox['col_4_5_font_weight'] . ' !important;';
            }
            $html .= '}';
            if (!empty($comparison_list_metabox['col_4_5_font_size'])) {
                $html .= '@media (min-width: 768px){.cbl-col-4 p{font-size:' . $comparison_list_metabox['col_4_5_font_size'] . ' !important;}}';
            }
            $html .= '</style>';
            return $html;
        }
        return '<div>list does not exist</div>';
    }

    public function get_list_row_html_v2($wp_query, $list_id, $count = 15, $offset = 0)
    {
        $comparison_list_metabox = self::metabox_list_transform_to_array($list_id);
        $html = '';
        $posts_ids = [];

        foreach ($comparison_list_metabox['brand_in_list'] as $key => $value) {
            array_push($posts_ids, [ "brand_id" => $value['select_post'], "brand_other_link" => $value['brand_other_link']]);
        }

        //asort($posts_ids);

        $posts_rank = [];
        $brand_other_link = [];

        foreach ($posts_ids as $key => $value) {
            //array_push($posts_rank, $value["brand_position"]);
            array_push($brand_other_link, $value["brand_other_link"]);
        }

        $q = $wp_query; ///new WP_Query($args);

        $it = 0;
        if ($q) {
            while ($q->have_posts() && $count-- > 0) {
                $q->the_post();
                if ($offset-- > 0) {
                    $it++;
                    continue;
                }

                $html .= '<tr class="item hidden-info item-' . $it + 1 . '' . (defined('JSON_REQUEST') ? ' com-animated' : '') . '">';
                $this->comparison_metabox = $this->metabox_transform_to_array(get_the_ID());
                $is_highlight = (isset($this->comparison_metabox['highlight']) && $this->comparison_metabox['highlight']) ? true : false;
                //render Comparison card

                foreach ($comparison_list_metabox['column_more_info'] as $key => $value) {
                    if ($key == 0) {
                        $html .= '<td class="column cbl-col-1" style="background-color:' . $this->comparison_metabox['card_logo_bg_color'] . '"><span>';
                        $html .= $it + 1;
                        $html .= '</span></td>';
                    }
                    if ($key == 1) {

                        $html .= '<td class="column cbl-col-2" style="background-color:' . $this->comparison_metabox['card_logo_bg_color'] . '">';
                        if (!empty($this->comparison_metabox['litle_icon'])) {
                            $html .= '<a href="' .  get_permalink() . '" rel="nofollow" target="_blank" title="' . get_the_title() . '" class="comparison-brand-link">';
                            $html .= '<img alt="' . get_the_title() . '" class=" lazyloaded" src="' . $this->comparison_metabox['litle_icon'] . '">';
                        } else {
                            $html .= '<a href="' .  get_permalink() . '" rel="nofollow" target="_blank" title="' . get_the_title() . '" class="comparison-brand-link">';
                            $html .= (has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), [150, 'auto']) : '');
                        }

                        $html .= '</a></td>';
                    }
                    if ($key == 2) {
                        $html .= '<td class="column cbl-col-3" style="background-color:' . $this->comparison_metabox['card_logo_bg_color'] . '">';
                        $html .= $this->get_html_characteristics_block_v2($is_highlight);

                        if (!empty($this->comparison_metabox['logo_brand_label_color'])) {
                            $html .= '<style>@media (max-width: 768px){ .comparison-bonus-list .item-' . $it + 1 . ' .cbl-col-3 .comparison-brand-wrap h4{';
                            $html .= 'color:' . $this->comparison_metabox['logo_brand_label_color'] . ';';
                            $html .= '}}</style>';
                        }
                        $html .= '</td>';
                    }
                    if ($key == 3) {
                        $html .= '<td class="column cbl-col-4" rowspan="2"><p>';
                        $html .= '<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_26_255)">
                        <path d="M11.3186 12.3598C11.3186 11.3598 10.6313 9.99309 9.38531 8.51109C9.31635 8.42882 9.22999 8.36288 9.13246 8.31804C9.03492 8.27319 8.92865 8.25055 8.82131 8.25175C8.71318 8.25126 8.60623 8.27432 8.50791 8.31934C8.4096 8.36435 8.32226 8.43024 8.25198 8.51242C6.88798 10.1104 6.22131 11.5238 6.33664 12.6071C6.38203 12.9496 6.49949 13.2786 6.68128 13.5725C6.86307 13.8663 7.10506 14.1183 7.39131 14.3118C7.79624 14.6438 8.29812 14.8352 8.82131 14.8571C9.48343 14.8564 10.1182 14.593 10.5864 14.1249C11.0546 13.6567 11.3179 13.0219 11.3186 12.3598Z" fill="#F44747"/>
                        <path d="M11.9072 2.12369C11.3832 1.67835 10.8332 1.21169 10.2732 0.702353C10.0609 0.509574 9.80899 0.365535 9.53514 0.280319C9.2613 0.195102 8.97214 0.170768 8.6879 0.20902C8.41243 0.245504 8.14832 0.342033 7.91424 0.491787C7.68017 0.641542 7.48183 0.840868 7.33324 1.07569C6.53883 2.40357 5.97698 3.85735 5.6719 5.37435C5.55255 5.20152 5.44646 5.01989 5.35457 4.83102C5.26021 4.63221 5.11811 4.45985 4.94094 4.32931C4.76377 4.19877 4.55704 4.11411 4.3392 4.08289C4.12136 4.05166 3.89918 4.07484 3.69248 4.15036C3.48577 4.22588 3.30098 4.35139 3.15457 4.51569C2.03677 5.65862 1.41715 7.19774 1.43124 8.79635C1.41578 10.4293 1.94686 12.0205 2.93998 13.3169C3.9331 14.6132 5.33122 15.5402 6.9119 15.9504C7.51712 16.1026 8.13849 16.1814 8.76257 16.185C7.96506 16.1553 7.19852 15.8686 6.57724 15.3677C6.14684 15.0648 5.78495 14.6748 5.51505 14.2229C5.24515 13.7711 5.07327 13.2676 5.01057 12.745C4.85724 11.2717 5.6059 9.55635 7.2359 7.64702C7.43145 7.4178 7.67454 7.23388 7.9483 7.10805C8.22207 6.98222 8.51994 6.91749 8.82124 6.91835H8.8279C9.12832 6.9186 9.42507 6.98434 9.69748 7.11099C9.96989 7.23764 10.2114 7.42216 10.4052 7.65169C11.4306 8.86969 12.6539 10.675 12.6539 12.359C12.6524 13.3295 12.2828 14.2633 11.6198 14.9719C10.9567 15.6805 10.0495 16.1112 9.08124 16.177C10.9957 16.1153 12.8109 15.3108 14.1424 13.9339C15.4739 12.557 16.2171 10.7158 16.2146 8.80035C16.2112 5.78035 14.2166 4.08702 11.9072 2.12369Z" fill="#F44747"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_26_255">
                        <rect width="16" height="16" fill="white" transform="translate(0.821289 0.190414)"/>
                        </clipPath>
                        </defs>
                        </svg>';
                        $html .= (!empty($this->comparison_metabox['amount_details']) ?  $this->comparison_metabox['amount_details'] : '')  . '</p></td>';
                    }
                    if ($key == 4) {

                        if (array_key_exists('more_info', $this->comparison_metabox) && is_array($this->comparison_metabox['more_info'])) {
                            $html .= '<td class="column cbl-col-5" rowspan="2"><ul class="extra-info__list">';

                            foreach ($this->comparison_metabox['more_info'] as $value) {
                                if (!empty($value['link_title'])) {
                                    $html .= '<li><span  class="extra-info__title">' . $value['link_title'] . ': </span>';
                                    $html .= '<span class="extra-info__desc extra-info-list__' . $value['icon'] . '">' . $value['description'] . '</span></li>';
                                }
                            }

                            $html .= '</ul><a href="#" class="js-info-close extra__info-close" title="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" 
                            width="13" height="13"><path d="m23.341,9.48l-3.501-6c-.893-1.53-2.547-2.48-4.318-2.48h-7.071c-1.771,0-3.426.951-4.319,2.48L.631,9.48c-.906,1.554-.906,3.485,0,5.039l3.501,6c.893,1.53,2.547,2.48,4.318,2.48h7.071c1.771,0,3.426-.951,4.319-2.48l3.5-6c.906-1.554.906-3.485,0-5.039Zm-1.729,4.031l-3.499,6c-.536.918-1.529,1.488-2.592,1.488h-7.071c-1.062,0-2.056-.57-2.591-1.488l-3.5-6c-.544-.933-.544-2.091,0-3.023l3.499-6c.536-.918,1.529-1.488,2.592-1.488h7.071c1.062,0,2.056.57,2.591,1.488l3.5,6c.544.933.544,2.091,0,3.023Zm-5.905-3.805l-2.293,2.293,2.293,2.293c.391.391.391,1.023,0,1.414-.195.195-.451.293-.707.293s-.512-.098-.707-.293l-2.293-2.293-2.293,2.293c-.195.195-.451.293-.707.293s-.512-.098-.707-.293c-.391-.391-.391-1.023,0-1.414l2.293-2.293-2.293-2.293c-.391-.391-.391-1.023,0-1.414s1.023-.391,1.414,0l2.293,2.293,2.293-2.293c.391-.391,1.023-.391,1.414,0s.391,1.023,0,1.414Z" fill="#F44747" /></svg>
                            </a>';

                            $html .= '</td>';
                        }
                    }
                }

                $html .= $this->get_html_submit_block_v2(!empty($comparison_list_metabox['buttons_text']) ? $comparison_list_metabox['buttons_text']  : '');

                if (array_key_exists('preference_text', $this->comparison_metabox) && is_array($this->comparison_metabox['preference_text'])) {
                    $desc =  !empty($this->comparison_metabox['description']) ? $this->comparison_metabox['description'] : '';

                    $html .= '<td class="column cbl-list ' . (!$desc ? 'full' : '') . '">';
                    $html .= $this->get_html_characteristics_list_block();
                    //$html .= $desc ? '<div class="comparison-promote__img">' . $desc . '</div>' : '';
                    $html .= '</td>';
                }

                if ($comparison_list_metabox['other_links'] == 'show') {
                    if ($brand_other_link[$it] == 'show') {
                        $html .= '<td class="cbl-terms" rowspan="2">';
                        $html .= $this->get_html_other_links(true);
                        $html .= '</td>';
                    }
                }
                $html .= '</tr>';
                $it++;
            }
            // Restore original Post Data
            wp_reset_postdata();
        }

        return $html;
    }

    /** return highlight svg - KEPT because used by v2 */
    private function get_html_highlight_svg()
    {
        $highlight = $this->comparison_metabox['highlight_label'];
        return '<span class="comparison__highlight_svg">' . $highlight . '</span>';
    }

    /** render html stars block - KEPT because used by v2 */
    private function get_html_stars_block()
    {
        $html = '<div class="com-comparison-stars">';
        for ($i = 0; $i < 5; $i++) {
            $html .= $i < $this->comparison_metabox['rating']
                ? '<span class="com-comparison__fill-star"><svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.04894 0.92705C7.3483 0.00573921 8.6517 0.00573969 8.95106 0.92705L10.0206 4.21885C10.1545 4.63087 10.5385 4.90983 10.9717 4.90983H14.4329C15.4016 4.90983 15.8044 6.14945 15.0207 6.71885L12.2205 8.75329C11.87 9.00793 11.7234 9.4593 11.8572 9.87132L12.9268 13.1631C13.2261 14.0844 12.1717 14.8506 11.388 14.2812L8.58778 12.2467C8.2373 11.9921 7.7627 11.9921 7.41221 12.2467L4.61204 14.2812C3.82833 14.8506 2.77385 14.0844 3.0732 13.1631L4.14277 9.87132C4.27665 9.4593 4.12999 9.00793 3.7795 8.75329L0.979333 6.71885C0.195619 6.14945 0.598395 4.90983 1.56712 4.90983H5.02832C5.46154 4.90983 5.8455 4.63087 5.97937 4.21885L7.04894 0.92705Z" fill="' . $this->getMetabox('rating_color', '#F0544F') . '"/>
            </svg></span>' :
                '<span class="com-comparison__empty-star"><svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.04894 0.92705C7.3483 0.00573921 8.6517 0.00573969 8.95106 0.92705L10.0206 4.21885C10.1545 4.63087 10.5385 4.90983 10.9717 4.90983H14.4329C15.4016 4.90983 15.8044 6.14945 15.0207 6.71885L12.2205 8.75329C11.87 9.00793 11.7234 9.4593 11.8572 9.87132L12.9268 13.1631C13.2261 14.0844 12.1717 14.8506 11.388 14.2812L8.58778 12.2467C8.2373 11.9921 7.7627 11.9921 7.41221 12.2467L4.61204 14.2812C3.82833 14.8506 2.77385 14.0844 3.0732 13.1631L4.14277 9.87132C4.27665 9.4593 4.12999 9.00793 3.7795 8.75329L0.979333 6.71885C0.195619 6.14945 0.598395 4.90983 1.56712 4.90983H5.02832C5.46154 4.90983 5.8455 4.63087 5.97937 4.21885L7.04894 0.92705Z" fill="#CDCDCD"/>
            </svg>
            </span>';
        }
        return $html .= '</div>';
    }

    // REMOVED: get_html_logo_block() - was used for card view
    // REMOVED: get_html_characteristics_block() - was used for card view
    // REMOVED: get_html_promote_block() - was used for card view
    // REMOVED: get_html_price_block() - was used for card view
    // REMOVED: get_html_submit_block() - was used for card view
    // REMOVED: get_html_terms_block() - was used for card view

    /** render html characteristics list block - KEPT for v2 */
    private function get_html_characteristics_list_block()
    {
        $html = '
            <ul class="com-comparison-characteristics__list">';
        if (is_array($this->comparison_metabox['preference_text'])) {
            foreach ($this->comparison_metabox['preference_text'] as $value) {
                $html .= !empty($value['description']) ? '<li style="color:' . $this->getMetabox('preference_text_color', '#6f6f6f') . '">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.00293 5.65202C0.65918 5.65202 0.37793 5.37077 0.37793 5.02702V3.77702C0.37793 2.05202 1.77793 0.652023 3.50293 0.652023H4.75293C5.09668 0.652023 5.37793 0.933273 5.37793 1.27702C5.37793 1.62077 5.09668 1.90202 4.75293 1.90202H3.50293C2.47168 1.90202 1.62793 2.74577 1.62793 3.77702V5.02702C1.62793 5.37077 1.34668 5.65202 1.00293 5.65202ZM5.37793 15.027C5.37793 14.6833 5.09668 14.402 4.75293 14.402H3.50293C2.47168 14.402 1.62793 13.5583 1.62793 12.527V11.277C1.62793 10.9333 1.34668 10.652 1.00293 10.652C0.65918 10.652 0.37793 10.9333 0.37793 11.277V12.527C0.37793 14.252 1.77793 15.652 3.50293 15.652H4.75293C5.09668 15.652 5.37793 15.3708 5.37793 15.027ZM15.3779 12.527V11.277C15.3779 10.9333 15.0967 10.652 14.7529 10.652C14.4092 10.652 14.1279 10.9333 14.1279 11.277V12.527C14.1279 13.5583 13.2842 14.402 12.2529 14.402H11.0029C10.6592 14.402 10.3779 14.6833 10.3779 15.027C10.3779 15.3708 10.6592 15.652 11.0029 15.652H12.2529C13.9779 15.652 15.3779 14.252 15.3779 12.527ZM6.32793 11.4145L7.87793 10.377L9.45293 11.402C9.65918 11.5333 9.92168 11.527 10.1154 11.377C10.3092 11.227 10.3842 10.977 10.3092 10.7458L9.74668 9.03952L11.1029 7.93327C11.2842 7.77702 11.3529 7.52702 11.2717 7.29577C11.1904 7.06452 10.9717 6.92077 10.7342 6.92077H9.01543L8.40918 5.28952C8.32793 5.06452 8.10918 4.91452 7.87168 4.91452C7.63418 4.91452 7.41543 5.06452 7.33418 5.28952L6.72793 6.92077H5.00918C4.77168 6.92077 4.55293 7.07077 4.47168 7.29577C4.39043 7.52077 4.45293 7.77702 4.64043 7.92702L6.00293 9.03327L5.46543 10.7583C5.39043 10.9895 5.47168 11.2458 5.66543 11.3895C5.76543 11.4645 5.89043 11.502 6.00918 11.502C6.12168 11.502 6.23418 11.4708 6.32793 11.4083V11.4145ZM12.2217 5.50202L14.7467 4.23952C15.1404 4.01452 15.3779 3.60827 15.3779 3.15202C15.3779 2.69577 15.1404 2.28952 14.7154 2.05202L12.2529 0.820773C11.8592 0.595773 11.3967 0.595773 11.0029 0.820773C10.6154 1.04577 10.3779 1.45202 10.3779 1.90202V4.39577C10.3779 4.84577 10.6092 5.25202 11.0029 5.47702C11.2029 5.59577 11.4217 5.65202 11.6404 5.65202C11.8592 5.65202 12.0467 5.60202 12.2217 5.50202ZM14.1592 3.13952L11.6279 4.40202V1.90202L14.1592 3.13327V3.13952Z" 
                        fill="' . ($this->comparison_metabox['preference_color'] ?? "#234AD3") . '"/>
                        </svg>
                        ' . $value['description'] . '</li>' : '';
            }
        }
        return $html .= '</ul>';
    }

    private function get_html_characteristics_block_v2($is_highlight)
    {
        $html = "";
        $title = isset($this->comparison_metabox['logo_brand_name']) ? $this->comparison_metabox['logo_brand_name'] : '';
        $is_highlight && $html = $this->get_html_highlight_svg();
        if(!empty($title)) {
            $html .= '<div class="comparison-brand-wrap"><h4>' . $title . '</h4>';
        } else {
            $html .= '<div class="comparison-brand-wrap"><h4>' . get_the_title() . '</h4>';
        }

        $html .= $this->get_html_stars_block() . '</div>';
        return $html;
    }

    private function get_html_submit_block_v2($buttons_text)
    {
        $html = ' <td class="column cbl-button">';
        if (!empty($this->comparison_metabox['select_btn_link'])) {
            // Get the actual affiliate URL
            $affiliate_url = $this->comparison_metabox['select_btn_link'];
            
            $html .= '<a ' . (isset($this->comparison_metabox['select_btn_color']) ? ('style="background-color:' . $this->comparison_metabox['select_btn_color'] . '"') : '') .
                'href = "' . get_permalink() . '" 
            data-affiliate-url="' . esc_url($affiliate_url) . '"
            rel="nofollow" target="_blank" class="comparison-bonus-list-submit__button" 
            title="' . (!empty($buttons_text) ? $buttons_text  : get_the_title()) . '">' . (!empty($buttons_text) ? ''  : __($this->comparison_metabox['select_btn_text'], "comporisons")) . ' ' . (!empty($buttons_text) ? $buttons_text  : get_the_title()) . '</a>';
        }

        return $html . '</td>';
    }

    /** render html Other Links block - KEPT for both */
    private function get_html_other_links($list = null)
    {
        $simbol = ' | ';
        if (array_key_exists('other_links', $this->comparison_metabox) && is_array($this->comparison_metabox['other_links'])) {
            $html = '<div class="com-comparison-promote__links' . (defined('JSON_REQUEST') ? ' com-animated' : '') . '">';
            foreach ($this->comparison_metabox['other_links'] as $key => $value) {
                if ($key == array_key_last($this->comparison_metabox['other_links'])) {
                    $simbol = '';
                }

                if ($value['other_links_title']) {
                    if (!empty($value['other_link'])) {
                        $html .= '<span>
                        <a href="' . $value['other_link'] . '" rel="nofollow noopener" target="_blank" title="' . $value['other_links_title'] . '">
                        ' . $value['other_links_title'] . '</a>
                        </span>' . $simbol;
                    } else {
                        $html .= '<span title="' . $value['other_links_title'] . '">'
                            . $value['other_links_title'] .
                            '</span>' . $simbol;
                    }
                }
            }

            $html .= '</div>';

            if ($list && array_key_exists('more_info', $this->comparison_metabox) && is_array($this->comparison_metabox['more_info'])) {
                $html .= '<a class="js-info-open extra__info-open" href="#" title="Open"><svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.55924 12.2196H6.82692C5.89057 12.236 4.96108 12.0573 4.09755 11.6949C3.23403 11.3326 2.45538 10.7945 1.81116 10.1149C1.26605 9.51907 0.853021 8.81477 0.599149 8.04818C0.345278 7.28159 0.256291 6.47001 0.33802 5.66663C0.462033 4.35946 1.0108 3.12886 1.90043 2.16301C2.79005 1.19715 3.97156 0.549194 5.2643 0.318203C6.11357 0.160699 6.98726 0.194208 7.82197 0.416298C8.65667 0.638389 9.43146 1.04349 10.0901 1.60221C10.7874 2.18828 11.3475 2.92029 11.7308 3.74652C12.1142 4.57275 12.3114 5.47306 12.3086 6.38385V9.47066C12.3076 10.1994 12.0177 10.8981 11.5023 11.4134C10.9869 11.9288 10.2881 12.2187 9.55924 12.2196ZM6.32504 1.72366C5.20257 1.72269 4.12009 2.14033 3.28921 2.89493C2.45833 3.64953 1.93879 4.68684 1.83215 5.80408C1.77099 6.40004 1.83646 7.0022 2.02425 7.57111C2.21205 8.14001 2.51794 8.66283 2.92188 9.1053C3.42532 9.63036 4.03243 10.045 4.70467 10.323C5.37692 10.601 6.09962 10.7363 6.82692 10.7202H9.55924C9.89068 10.7202 10.2085 10.5885 10.4429 10.3542C10.6773 10.1199 10.8089 9.80205 10.8089 9.47066V6.38385C10.8112 5.69203 10.6618 5.00811 10.3711 4.38031C10.0803 3.7525 9.65546 3.19608 9.12635 2.75026C8.34426 2.08609 7.35115 1.72214 6.32504 1.72366ZM6.31004 3.22308C6.11118 3.22308 5.92046 3.30207 5.77985 3.44266C5.63923 3.58326 5.56023 3.77395 5.56023 3.97279C5.56023 4.17162 5.63923 4.36232 5.77985 4.50291C5.92046 4.64351 6.11118 4.7225 6.31004 4.7225C6.50891 4.7225 6.69963 4.64351 6.84024 4.50291C6.98086 4.36232 7.05986 4.17162 7.05986 3.97279C7.05986 3.77395 6.98086 3.58326 6.84024 3.44266C6.69963 3.30207 6.50891 3.22308 6.31004 3.22308ZM6.55998 9.72056C6.36112 9.72056 6.1704 9.64158 6.02978 9.50098C5.88917 9.36038 5.81017 9.16969 5.81017 8.97085V6.72172H5.56023C5.36137 6.72172 5.17065 6.64274 5.03003 6.50214C4.88941 6.36154 4.81041 6.17085 4.81041 5.97201C4.81041 5.77318 4.88941 5.58249 5.03003 5.44189C5.17065 5.30129 5.36137 5.2223 5.56023 5.2223H6.06011C6.39155 5.2223 6.70941 5.35395 6.94377 5.58828C7.17813 5.82261 7.3098 6.14043 7.3098 6.47182V8.97085C7.3098 9.16969 7.2308 9.36038 7.09018 9.50098C6.94956 9.64158 6.75885 9.72056 6.55998 9.72056Z" fill="#F44747"/>
            </svg></a>';
            }
            return $html;
        }

        return '<div class="com-comparison-promote__links"></div>';
    }

    /** render html Top Bar Info block - KEPT for v2 */
    private function get_html_top_bar_info($comparison_list_metabox)
    {
        $simbol = ' | ';
        if (array_key_exists('top_bar_info', $comparison_list_metabox) && is_array($comparison_list_metabox['top_bar_info'])) {
            $html = '<td class="cbl-top-bar' . (defined('JSON_REQUEST') ? ' com-animated' : '') . '">';
            foreach ($comparison_list_metabox['top_bar_info'] as $key => $value) {
                if ($key == array_key_last($comparison_list_metabox['top_bar_info'])) {
                    $simbol = '';
                }

                if ($value['top_bar_title']) {
                    if (!empty($value['top_bar_link'])) {
                        $html .= '<span>
                        <a href="' . $value['top_bar_link'] . '" rel="nofollow noopener" target="_blank" title="' . $value['top_bar_title'] . '" ' . (!empty($value['top_bar_link_color']) ? 'style="color:' . $value['top_bar_link_color'] . '!important;"' : '') . '>' . $value['top_bar_title'] . '</a>
                        </span>' . $simbol;
                    } else {
                        $html .= '<span title="' . $value['top_bar_title'] . '" ' . (!empty($value['top_bar_link_color']) ? 'style="color:' . $value['top_bar_link_color'] . '!important;"' : '') . '>'
                            . $value['top_bar_title'] .
                            '</span>' . $simbol;
                    }
                }
            }

            $html .= '
            <style>.cbl-top-bar, .cbl-top-bar *{' .
                (!empty($comparison_list_metabox['top_bar_title_color']) ? 'color:' . $comparison_list_metabox['top_bar_title_color'] . '!important;' : '') .
                (!empty($comparison_list_metabox['top_bar_bg_title_color']) ? 'background-color:' . $comparison_list_metabox['top_bar_bg_title_color'] . '!important;' : '') .
                (!empty($comparison_list_metabox['top_bar_font_weight']) ? 'font-weight:' . $comparison_list_metabox['top_bar_font_weight'] . '!important;' : '') .
                (!empty($comparison_list_metabox['top_bar_font_size']) ? 'font-size:' . $comparison_list_metabox['top_bar_font_size'] . '!important;' : '') .
                '@media (max-width: 768px){' .
                (!empty($comparison_list_metabox['top_bar_mobile_font_size']) ? 'font-size:' . $comparison_list_metabox['top_bar_mobile_font_size'] . '!important;' : '');
            $html .= '}}
            </style></th>';

            return $html .= '</td>';
        }

        return '<td class="com-comparison-promote__links"></td>';
    }

    /**
     *  get comparison metabox value - KEPT (helper method)
     */
    public function getMetabox($metabox_id, $default = '')
    {
        return $this->comparison_metabox[$metabox_id] ?? $default;
    }

    /**
     * transform post metabox to array - KEPT (essential helper)
     */
    public function metabox_transform_to_array($id)
    {
        $metabox_field = Comparison_Metabox::get_metaboxes();
        $metabox_array = [];

        $comparison_metabox = get_post_meta($id, '', true);

        foreach ($metabox_field[0]['metadata'] as $key => $field) {

            if (
                isset($comparison_metabox[$this->comparison . '_metadata_' . $field['slug']]) &&
                is_array($comparison_metabox[$this->comparison . '_metadata_' . $field['slug']])
            ) {

                if ($field['type'] === 'repeater') {
                    $data = unserialize(unserialize($comparison_metabox[$this->comparison . '_metadata_' . $field['slug']][0]));

                    foreach ($data as $key => $repeater_field) {
                        foreach ($field['fields'] as $field_key => $value) {
                            $slug = 'repeater_' . $field['slug'] . '_field_' . $field['fields'][$field_key]['slug'];
                            $metabox_array[$field['slug']][$key][$field['fields'][$field_key]['slug']] =  $repeater_field[$slug]['data'];
                        }
                    }
                    continue;
                }
                $metabox_array[$field['slug']] = $comparison_metabox[$this->comparison . '_metadata_' . $field['slug']][0];
            }
        }

        return $metabox_array;
    }

    public static function metabox_list_transform_to_array($id)
    {
        $metabox_field = Comparison_List_Metabox::get_list_metaboxes();
        $metabox_array = [];

        $comparison_list_metabox = get_post_meta($id, '', true);

        foreach ($metabox_field[0]['metadata'] as $key => $field) {

            if (
                isset($comparison_list_metabox['com_comporison_list_metadata_' . $field['slug']]) &&
                is_array($comparison_list_metabox['com_comporison_list_metadata_' . $field['slug']])
            ) {

                if ($field['type'] === 'repeater') {
                    $data = unserialize(unserialize($comparison_list_metabox['com_comporison_list_metadata_' . $field['slug']][0]));

                    foreach ($data as $key => $repeater_field) {
                        foreach ($field['fields'] as $field_key => $value) {
                            $slug = 'repeater_' . $field['slug'] . '_field_' . $field['fields'][$field_key]['slug'];
                            $metabox_array[$field['slug']][$key][$field['fields'][$field_key]['slug']] =  $repeater_field[$slug]['data'];
                        }
                    }
                    continue;
                }
                $metabox_array[$field['slug']] = $comparison_list_metabox['com_comporison_list_metadata_' . $field['slug']][0];
            }
        }

        return $metabox_array;
    }

    /**
     * Converting px to em - KEPT (utility method)
     */
    public function convertPxToEm($px = 16)
    {
        return $px / 16;
    }
}
