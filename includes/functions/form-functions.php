<?php
/**
 * @package EWPU
 * ========================
 * Forms Functions
 * ========================
 * Text Domain: emo_ewpu
 */

use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Csv_Handler;
use EmoWooPriceUpdate\EWPU_Form_Error;
use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;
use EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Update_Price;
use EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Group_Discount;
use EmoWooPriceUpdate\Repository\EWPU_DB_Get_All_Products_ID;
use EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Products_Price_List;

/**
 * Handle group price update form
 * @return array
 */

function emo_ewpu_get_price_update_data(): array
{
	$args = array(
		'checker_items' => array(
			'submit_status' => 'btnSubmit',
			'security' => array('emo_ewpu_nonce_field', 'emo_ewpu_action'),
			'requirements' => array('cat_id', 'change_rate')
		),
		'fields' => array(
			'category'=> 'cat_id',
			'change_rate'=> 'change_rate',
			'rate_type' => 'emo_ewpu_rate',
			'on_sale' => 'sale_price',
			'change_type' => 'emo_ewpu_increase'
		),
		'file_info'=> array(
			'fileName'=> "ChangePrice_".date("Y-m-d_h-i-s").".csv",
			'fileUrl'=> EWPU_CREATED_URI,
			'fileDir'=> EWPU_CREATED_DIR
		),
		'csv_fields'=> array('parent_id', 'product_id', 'product_name', 'price_type', 'old_price', 'new_price'),
	);
	$formHandler = new EWPU_Form_Update_Price();
    return $formHandler->submit($args);
}

/**
 * Handle group discount form
 * @return array
 */
function emo_ewpu_get_group_discount_data(): array
{
	$args = array(
		'checker_items' => array(
			'submit_status' => 'btnSubmit',
			'security' => array('emo_ewpu_nonce_field', 'emo_ewpu_action'),
			'requirements' => array('cat_id', 'change_rate')
		),
		'fields' => array(
			'category'=> 'cat_id',
			'change_rate'=> 'change_rate',
			'rate_type' => 'nimo_nwab_rate',
			'start_year' => 'sale_start_time_year',
			'start_month' => 'sale_start_time_month',
			'start_day' => 'sale_start_time_day',
			'end_year' => 'sale_end_time_year',
			'end_month' => 'sale_end_time_month',
			'end_day' => 'sale_end_time_day'
		),
		'file_info'=> array(
			'fileName'=> "Discount_".date("Y-m-d_h-i-s").".csv",
			'fileUrl'=> EWPU_CREATED_URI,
			'fileDir'=> EWPU_CREATED_DIR
		),
		'csv_fields'=> array('parent_id', 'product_id', 'product_name', 'Regular_price', 'Sale_price', 'Start_time', 'End_time'),
	);

	$formHandler = new EWPU_Form_Group_Discount();
	return $formHandler->submit($args);
}

/**
 * Get products list and store it as a csv file
 * @param boolean $is_submit
 * @param string $fileName
 * @return array
 */
function emo_ewpu_get_product_list(bool $is_submit, string $fileName): array
{
	$args = array(
		'checker_items' => array(
			'submit_status' => 'btnSubmit',
			'security' => array('emo_ewpu_nonce_field', 'emo_ewpu_action'),
			'requirements' => array()
		),
		'file_info'=> array(
			'fileName'=> "products.csv",
			'fileUrl'=> EWPU_CREATED_URI,
			'fileDir'=> EWPU_CREATED_DIR
		),
		'csv_fields'=> array('Product ID', 'SKU', 'Product Title', 'Regular Price', 'Sale Price', 'Type'),
	);

	$formHandler = new EWPU_Form_Products_Price_List();
	return $formHandler->submit($args);
}


/**
 * Get new prices from a csv file and update products price
 * @param bool $is_submit
 * @param bool|string $is_file
 * @param array $args
 *      @type array $extensions = ['csv']
 *      @type array $max-size = 2097152
 *
 * @return array|WP_Error[]
 */
function emo_ewpu_update_products_price_list(bool $is_submit, bool $is_file, array $args):array
{
	//requirement checks
	$error = false;
	if(!$is_submit){
		$error = new WP_Error( 'submitError', __( "There are an error while you update", "emo_ewpu" ) );
	}
	if(!$is_file){
		$error = new WP_Error( 'submitError', __( "There are no file to upload", "emo_ewpu" ) );
	}
	if ( ! EWPU_Request_Handler::get_POST('emo_ewpu_nonce_field')
	     || ! wp_verify_nonce( EWPU_Request_Handler::get_POST('emo_ewpu_nonce_field'), 'emo_ewpu_action' )
	){
		$error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
	}
	if(!EWPU_Request_Handler::get_FILE('price_list') || EWPU_Request_Handler::get_FILE('price_list')['error']){
		$error = new WP_Error( 'requirements', __( "There aren't any file to upload", "emo_ewpu" ) );
	}

	if($error){
		return ['error'=>$error];
	}
	//end requirement checks

	//Upload Handler
	$extensions= ($args['extensions'])? $args['extensions']:array("csv");
	$maxFileSize = ($args['max-size'])? $args['max-size']:2097152;

	$target_file = EWOU_UPLOAD_DIR. basename(EWPU_Request_Handler::get_FILE("price_list")["name"]);
	$file_name = EWPU_Request_Handler::get_FILE('price_list')['name'];
	$file_tmp =EWPU_Request_Handler::get_FILE('price_list')['tmp_name'];
	$file_size = EWPU_Request_Handler::get_FILE('price_list')['size'];
	$file_ext=strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


	if(in_array($file_ext,$extensions)=== false){
		$errors= new WP_Error( 'file-type', __( "The extension of uploaded file is not allowed, please choose a csv file.", "emo_ewpu" ) );
		return ['error'=>$errors];
	}
    if($file_size > $maxFileSize){
        $errors= new WP_Error( 'file-size', __( "File size is more than allowed size.", "emo_ewpu" ) );
	    return ['error'=>$errors];
    }
	move_uploaded_file($file_tmp,$target_file);
	//End Upload Handler

	// Read and store new prices
	if ( ($handle = new EWPU_Csv_Handler($target_file, "r")) !== false) {
		$row = 0;
		$args = ['length'=> 1000, 'separator'=> ','];
		while (($data = $handle->readFile($args)) !== false) {
			if($row != 0){
				$productID = $data[0];
				$regularPrice_new = (is_numeric($data[3]))? $data[3]:'';
				$salePrice_new = (is_numeric($data[4]))? $data[4]:'';
				$productType = ($data[5]== 'variation' || $data[5] == 'simple')?$data[5]:'';
				$date = date(DATAFORMAT, time());
				if($regularPrice_new !='' && $productType != ''){
					emo_ewpu_set_new_price($productID, $productType, 'regular_price' ,$regularPrice_new);
				}

				if($salePrice_new !='' && $productType != ''){
					emo_ewpu_set_new_price($productID, $productType, 'sale_price' ,$salePrice_new);
				}
			}
			$row++;
		}
		$handle->closeFile();
		$response= __('Your prices are updated successfully.', 'emo_ewpu' );
		return ['response'=>$response];
	}else{
		$errors = new WP_Error( 'invalid', __( "The plugin is not able to open the uploaded file ", "emo_ewpu" ) );
		return ['error'=>$errors];
	}

}