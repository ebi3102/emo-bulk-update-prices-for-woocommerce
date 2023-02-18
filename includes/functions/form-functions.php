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
 * @param boolean $is_submit
 * @return array
 */
function emo_ewpu_get_group_discount_data(bool $is_submit): array
{
    $months = new WP_Locale();
    $error = false;
    if(!$is_submit){
        $error = new WP_Error( 'submitError', __( "There are an error while you update", "emo_ewpu" ) );
    }
    if ( ! EWPU_Request_Handler::get_POST('emo_ewpu_nonce_field')
        || ! wp_verify_nonce( EWPU_Request_Handler::get_POST('emo_ewpu_nonce_field'), 'emo_ewpu_action' )
    ){
        $error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
    }

	$cat_id = EWPU_Request_Handler::get_POST('cat_id');
	$rate_type = EWPU_Request_Handler::get_POST('nimo_nwab_rate');
	$change_rate = EWPU_Request_Handler::get_POST('change_rate');
	$endYear = EWPU_Request_Handler::get_POST('sale_end_time_year');
	$endMonth = EWPU_Request_Handler::get_POST('sale_end_time_month');
	$endDay = EWPU_Request_Handler::get_POST('sale_end_time_day');
	$startYear = EWPU_Request_Handler::get_POST('sale_start_time_year');
	$startMonth = EWPU_Request_Handler::get_POST('sale_start_time_month');
	$startDay = EWPU_Request_Handler::get_POST('sale_start_time_day');

    if(!$cat_id){
        $error = new WP_Error( 'requirements', __( "There are some required fields that not filled", "emo_ewpu" ) );
    }
    if(!$change_rate) {
        $error = new WP_Error( 'requirements', __( "There are some required fields that not filled", "emo_ewpu" ) );
    }

    if($error){
        return ['error'=>$error];
    }

    $textStartDate = $startYear . '/' . $months->get_month(intval($startMonth)) . '/' .$startDay ;
    $UTMStartDate = $startYear . '-' . $startMonth . '-' .$startDay ;

    $textEndDate = $endYear . '/' . $months->get_month(intval($endMonth)) . '/' .$endDay ;
    $UTMEndDate = $endYear . '-' . $endMonth . '-' .$endDay ;

    $filename = "Discount_".date("Y-m-d_h-i-s").".csv";

    if (!file_exists( EWPU_CREATED_DIR )) {
        mkdir( EWPU_CREATED_DIR, 0777, true);
    }
    $filePath = EWPU_CREATED_DIR.$filename;
    $fileUrl = EWPU_CREATED_URI.$filename;

	$csvFile = new EWPU_Csv_Handler($filePath, 'w');
	if(!$csvFile){
		return ['error'=>new WP_Error( 'unable', __( "Unable to open file!", "emo_ewpu" ) )];
	}

    $writeCSV = array(array('parent_id', 'product_id', 'product_name', 'Regular_price', 'Sale_price', 'Start_time', 'End_time'));

    //retrieve all related products
    $cat_products = array();
    if($cat_id){
	    $relatedProductsDB = new \EmoWooPriceUpdate\Repository\EWPU_DB_Get_Related_Object($cat_id);
	    $relatedProducts = $relatedProductsDB->results();
		foreach($relatedProducts as $relatedProduct){
            array_push($cat_products, $relatedProduct->object_id);
        }
    }

    $products = $cat_products;

    foreach($products as $product){
        $_product = wc_get_product($product);
        if($_product->get_type() == 'variable'){
            $variationsPrices = $_product->get_variation_prices();
            $vRegularPrices = $variationsPrices['regular_price']; //array
            foreach($vRegularPrices as $vID=>$vRegularPrice){
                $newSalePrice = emo_ewpu_change_price($rate_type, 'decrease', $vRegularPrice, $change_rate);
                $variation = wc_get_product_object( 'variation', $vID );
                //set...
                $variation->set_props(
                    array(
                        // 'regular_price' => $newRegularPrice,
                        'sale_price' => $newSalePrice,
                        'date_on_sale_from'  => $UTMStartDate,
                        'date_on_sale_to' => $UTMEndDate
                    )
                );
                $variation->save();
                array_push($writeCSV, array($product, $vID, $variation->get_title(), $vRegularPrice, $newSalePrice, $textStartDate, $textEndDate));
            }

        }elseif($_product->get_type() == 'simple'){
            $regularPrice = $_product->get_regular_price();
            $newSalePrice = emo_ewpu_change_price($rate_type, 'decrease', $regularPrice, $change_rate);
            //set...
            $productObject = wc_get_product_object( 'simple', $product );
            $productObject->set_props(
                array(
                    // 'regular_price' => $newRegularPrice,
                    'sale_price' => $newSalePrice,
                    'date_on_sale_from'  => $UTMStartDate,
                    'date_on_sale_to' => $UTMEndDate
                )
            );
            $productObject->save();
            // $_product->set_date_on_sale_to();
            array_push($writeCSV, array('0', $product, $_product->get_title(), $regularPrice, $newSalePrice, $textStartDate, $textEndDate));
        }
    }

    foreach ($writeCSV as $row) {
		$csvFile->writeToFile(['content'=>$row]);
    }
	$csvFile->closeFile();

    return ['error'=>false, 'filePath'=> $fileUrl, 'fileName'=> $filename];
}

/**
 * Get products list and store it as a csv file
 * @param boolean $is_submit
 * @param string $fileName
 * @return array
 */
function emo_ewpu_get_product_list(bool $is_submit, string $fileName): array
{
    $error = false;
    if(!$is_submit){
        $error = new WP_Error( 'submitError', __( "There are an error while you update", "emo_ewpu" ) );
    }
    if ( ! EWPU_Request_Handler::get_POST('emo_ewpu_nonce_field')
        || ! wp_verify_nonce( EWPU_Request_Handler::get_POST('emo_ewpu_nonce_field'), 'emo_ewpu_action' )
    ){
        $error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
    }
	$productsDB = new \EmoWooPriceUpdate\Repository\EWPU_DB_Get_All_Products;
    $products = $productsDB->results();
    if(count($products)<= 0){
        $error = new WP_Error( 'noProduct', __( "Sorry, There are no products.", "emo_ewpu" ) );
    }

    if($error){
        return ['error'=>$error];
    }
    $fileUrl = EWPU_CREATED_URI . $fileName;
    $filePath = EWPU_CREATED_DIR. $fileName;

    $myFile = new EWPU_Csv_Handler($filePath, "w");
    $data = array('Product ID', 'SKU', 'Product Title', 'Regular Price', 'Sale Price', 'Type');
    $arg = array('content'=>$data);
    $myFile->writeToFile($arg);
    foreach ($products as $product) {
        $_product = wc_get_product($product->ID);
        $sku = $_product->get_sku();
        if ($_product->get_type() == "variable") {
            $variations = $_product->get_children();
            foreach ($variations as $vID) {
                $variation = wc_get_product_object('variation', $vID);
                $data = array($vID, $variation->get_sku(), $variation->get_name(), $variation->get_regular_price(), $variation->get_sale_price(), "variation");
                $myFile->writeToFile(array('content'=>$data));
            }
        } elseif ($_product->get_type() == "simple") {
            $data = array($product->ID, $sku, $product->post_title, $_product->get_regular_price(), $_product->get_sale_price(), "simple");
            $myFile->writeToFile(array('content'=>$data));
        }
    }
    $myFile->closeFile();

    return ['error'=>false, 'filePath'=> $fileUrl, 'fileName'=> $fileName];
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