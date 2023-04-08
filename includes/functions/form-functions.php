<?php
/**
 * @package EMO_BUPW
 * ========================
 * Forms Functions
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */
use EMO_BUPW\Form_Handlers\EWPU_Form_Update_Price;
use EMO_BUPW\Form_Handlers\EWPU_Form_Group_Discount;
use EMO_BUPW\Form_Handlers\EWPU_Form_Products_Price_List;
use EMO_BUPW\Form_Handlers\EWPU_Form_Update_Price_By_List;

/**
 * Handle group price update form
 * @return array
 */
if ( ! function_exists( 'emo_bupw_get_price_update_data' )) {
	function emo_bupw_get_price_update_data(): array {
		$args        = array(
			'checker_items' => array(
				'submit_status' => 'btnSubmit',
				'security'      => array( 'emo_bupw_nonce_field', 'emo_bupw_action' ),
				'requirements'  => array( 'cat_id', 'change_rate' )
			),
			'fields'        => array(
				'category'    => 'cat_id',
				'change_rate' => 'change_rate',
				'rate_type'   => 'emo_ewpu_rate',
				'on_sale'     => 'sale_price',
				'change_type' => 'emo_ewpu_increase'
			),
			'file_info'     => array(
				'fileName' => "ChangePrice_" . date( "Y-m-d_h-i-s" ) . ".csv",
				'fileUrl'  => EMO_BUPW_CREATED_URI,
				'fileDir'  => EMO_BUPW_CREATED_DIR
			),
			'csv_fields'    => array(
				'parent_id',
				'product_id',
				'product_name',
				'price_type',
				'old_price',
				'new_price'
			),
		);
		$formHandler = new EWPU_Form_Update_Price();

		return $formHandler->submit( $args );
	}
}

/**
 * Handle group discount form
 * @return array
 */
if ( ! function_exists( 'emo_bupw_get_group_discount_data' )) {
	function emo_bupw_get_group_discount_data(): array {
		$args = array(
			'checker_items' => array(
				'submit_status' => 'btnSubmit',
				'security'      => array( 'emo_bupw_nonce_field', 'emo_bupw_action' ),
				'requirements'  => array( 'cat_id', 'change_rate' )
			),
			'fields'        => array(
				'category' => 'cat_id',
				'change_rate' => 'change_rate',
				'rate_type' => 'nimo_nwab_rate',
				'start_year' => 'sale_start_time_year',
				'start_month' => 'sale_start_time_month',
				'start_day' => 'sale_start_time_day',
				'end_year' => 'sale_end_time_year',
				'end_month' => 'sale_end_time_month',
				'end_day' => 'sale_end_time_day'
			),
			'file_info'     => array(
				'fileName' => "Discount_" . date( "Y-m-d_h-i-s" ) . ".csv",
				'fileUrl'  => EMO_BUPW_CREATED_URI,
				'fileDir'  => EMO_BUPW_CREATED_DIR
			),
			'csv_fields'    => array(
				'parent_id',
				'product_id',
				'product_name',
				'Regular_price',
				'Sale_price',
				'Start_time',
				'End_time'
			),
		);

		$formHandler = new EWPU_Form_Group_Discount();

		return $formHandler->submit( $args );
	}
}

/**
 * Get products list and store it as a csv file
 * @return array
 */
if ( ! function_exists( 'emo_bupw_get_product_list' )) {
	function emo_bupw_get_product_list(): array {
		$args = array(
			'checker_items' => array(
				'submit_status' => 'btnSubmit',
				'security'      => array( 'emo_bupw_nonce_field', 'emo_bupw_action' ),
				'requirements'  => array()
			),
			'file_info'     => array(
				'fileName' => "products.csv",
				'fileUrl'  => EMO_BUPW_CREATED_URI,
				'fileDir'  => EMO_BUPW_CREATED_DIR
			),
			'csv_fields'    => array( 'Product ID', 'SKU', 'Product Title', 'Regular Price', 'Sale Price', 'Type' ),
		);

		$formHandler = new EWPU_Form_Products_Price_List();

		return $formHandler->submit( $args );
	}
}

/**
 * Get new prices from a csv file and update products price
 * @return array
 */
if ( ! function_exists( 'emo_bupw_update_products_price_list' )) {
	function emo_bupw_update_products_price_list(): array {
		$args        = array(
			'checker_items' => array(
				'submit_status' => 'uploadSubmit',
				'security'      => array( 'emo_bupw_nonce_field', 'emo_bupw_action' ),
				'requirements'  => array()
			),
			'fields'        => array(
				'file' => 'price_list'
			),
			'file_info'     => array(
				'fileUrl'    => EMO_BUPW_UPLOAD_URI,
				'fileDir'    => EMO_BUPW_UPLOAD_DIR,
				'extensions' => [ 'csv' ],
				'max-size'   => 2097152
			)
		);
		$formHandler = new EWPU_Form_Update_Price_By_List();

		return $formHandler->submit( $args );
	}
}