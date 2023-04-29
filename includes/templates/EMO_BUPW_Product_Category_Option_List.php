<?php
namespace EMO_BUPW\Templates;
class EMO_BUPW_Product_Category_Option_list
{

	private static $optionsList;

	private static function setUp()
	{
		$product_categories = get_terms( array (
			'taxonomy' => 'product_cat',
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false
		));

		if(!empty($product_categories)){
			foreach($product_categories as $cat) {
				self::$optionsList[$cat->term_id] =$cat->name;
			}
		}else{
			self::$optionsList = false;
		}
	}

	public static function render_template()
	{
		self::setUp();
		$options_html = '';
		if(self::$optionsList){
			$options_html .= '<option value="0">'.esc_html(__('Select one category', 'emo-bulk-update-prices-for-woocommerce')).'</option>';
			foreach(self::$optionsList as $key=>$option) {
				$options_html .= '<option value="'. esc_attr($key) .'">'. $option .'</option>';
			}
		}
		return $options_html;
	}
}