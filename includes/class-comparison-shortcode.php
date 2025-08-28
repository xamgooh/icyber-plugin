<?php

class ComparisonShortcode
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
	 * @var      object    $comparion_list_html
	 */
	protected $comparion_list_html;

	/**
	 * Define metabox core
	 *
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		$this->comparison = 'com_comporison';
		add_shortcode('Comparison_v2', array($this, 'custom_shortcode_v2'));
		$this->comparion_list_html = new ComparisonHtml();
	}

	// get comparison posts list
	public function get_comarison_posts($category, $max, $list_id = null)
	{
		$max  = ($max === '0' || (int)$max >= 1 || (int)$max >= -1) ? (int)$max : 15;

		if ($max < 1 && $max != -1) {
			return false;
		}

		$args = array(
			'post_type' => $this->comparison,
			'post_status' => 'publish',
			'posts_per_page' => $max,
			'orderby' => 'date',
		);

		if ($list_id) {
			$comparison_list_metabox = ComparisonHtml::metabox_list_transform_to_array($list_id);
			$posts_ids = [];
			$posts_ids2 = [];
			foreach ($comparison_list_metabox['brand_in_list'] as $key => $value) {
                array_push($posts_ids, [ "brand_id" => $value['select_post'], "brand_other_link" => $value['brand_other_link']]);
            }
			
			if ($comparison_list_metabox) {
				foreach ($posts_ids as $key => $value) {
					array_push($posts_ids2, $value["brand_id"]);
				}
				
				$args['post__in'] = $posts_ids2;
				$args['orderby'] = 'post__in';
			}
		}

		if (!empty($category)) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'com_category',
					'field'    => 'slug',
					'terms' => $category,
					'operator' => 'IN'
				)
			);
		}
		return new WP_Query($args);
	}

	//  V2 Shortcode
	public function custom_shortcode_v2($atts)
	{
		$html = '';
		$random_id = mt_rand(100, 500);
		// Attributes
		$attributes  = shortcode_atts(
			array(
				'max' 			=> 15,
				'id'			=> 0,
				'category' 		=> '',
				'post_status' 	=> 'publish',
				'filter'		=> false,
				'load_more'		=> false,
				'list_id'	=> null
			),
			$this->normalize_empty_atts($atts),
			'Comparison'
		);

		$category = $this->transform_string_to_array($attributes['category']);

		$filter = $attributes['filter'];
		$list_id = $attributes['list_id'];

		$html = '<section class="com-comparison-plugin">';
		if (is_array($category) && count($category) > 1 && $filter) {

			$html .= '<div class="tabell-dropdown">
					<button class="com__btn" type="button" aria-expanded="true">
					<span class="com__icon--menu" aria-hidden="true"></span>' . __(get_option('comporisons_sorting_label'), "comporisons") . '
					</button>
				</div>
				<ul class="category__navpills isotop__grid hidden">';

			foreach ($category as $key => $value) {
				$args = array(
					'taxonomy' => 'com_category',
					'orderby' => 'name',
					'slug' => mb_strtolower(trim($value)),
					'order'   => 'ASC'
				);
				$cats = get_categories($args);

				$html .= '<li role="presentation" class="isotop__grid--list ' . ($key === array_key_first($category) ? 'active' : '') . '">
					<a data-key="' . $random_id . '_' . str_replace(' ', '-', mb_strtolower(trim($value))) . '" class="isotop__grid--item" href="#" rel="nofollow  noopener">';
				if (is_array($cats)  && isset($cats[0])) {
					$cat_icon_url = get_option('weekend-series_' . $cats[0]->term_id);
					if (is_array($cat_icon_url) && array_key_exists('image', $cat_icon_url) && !empty($cat_icon_url['image'])) {
						$html .= '<img style="width: 30px; height: 30px" alt="icon" class="com_comparision_icon"' . ($cat_icon_url['image'] ? 'src="' . $cat_icon_url['image'] . '"' : '') . '>';
					} else {
						$html .= '<span style="width: 30px; height: 30px"></span>';
					}
				}
				$html .= '<span>' . $value . '</span></a></li>';
			}

			$html .= '</ul>';

			foreach ($category as $key => $value) {
				$value = str_replace(' ', '-', mb_strtolower(trim($value)));
				$wp_query = $this->get_comarison_posts([0 => $value], $attributes['max'], $list_id);

				$hidden = !($key === array_key_first($category)) ? " hidden" : "";
				$html .= '<div class="com_comarison__list--container ' . $random_id . '_' . $value . $hidden . '">';
				$html .= $this->comparion_list_html->get_list_html_v2($wp_query, $list_id);
				if ($wp_query->found_posts > $attributes['max'] && $attributes['load_more']) {
					$html .= '<button data-restUrl="' . site_url() . '" data-list="' . $value . '" data-limit="' . $attributes['max'] . '" data-id="'. $list_id .'" class="com_btn-list--loadmore">' . get_option('comporisons_filter_label') . '</button>';
				}

				$html .= '</div>';
			}
		} else {
			$wp_query = $this->get_comarison_posts($category, $attributes['max'], $list_id);
			$html .= '<div class="com_comarison__list--container ' . $random_id . '_' . $random_id . '">';
			$html .= $this->comparion_list_html->get_list_html_v2($wp_query, $list_id, $attributes['max']);
			if ($wp_query->found_posts > $attributes['max'] && $attributes['load_more']) {
				// Determine the category value for data-list
				$category_value = 'null'; // Default value when no category
				if (is_array($category) && !empty($category[0])) {
					$category_value = str_replace(' ', '-', mb_strtolower(trim($category[0])));
				} else if (!empty($category)) {
					$category_value = str_replace(' ', '-', mb_strtolower(trim($category)));
				}
				
				$html .= '<button data-restUrl="' . site_url() . '" data-list="' . $category_value . '" data-limit="' . $attributes['max'] . '" data-id="'. $list_id .'" class="com_btn-list--loadmore">' . get_option('comporisons_filter_label') . '</button>';
			}
			$html .= '</div>';
		}

		$html .= '</section><div class="com-comparison__clearfix"></div>';

		return $html;
	}

	// transform string to array
	public function transform_string_to_array($str)
	{
		return !empty($str) ? explode(',', preg_replace('/\s*,\s*/', ',', htmlspecialchars($str))) : $str;
	}

	public function normalize_empty_atts($atts)
	{
		if (is_array($atts)) {
			foreach ($atts as $attribute => $value) {
				if (is_int($attribute)) {
					$atts[strtolower($value)] = true;
					unset($atts[$attribute]);
				}
			}
		}
		return $atts;
	}
}
