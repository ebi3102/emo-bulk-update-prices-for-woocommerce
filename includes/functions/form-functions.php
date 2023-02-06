<?php
/**
 * @package EWPU
 * ========================
 * Forms Functions
 * ========================
 * Text Domain: emo_ewpu
 */


/**
 * Handle group price update form
 * @param boolean $is_submit
 * @return array
 */
function emo_ewpu_get_price_update_data(bool $is_submit): array
{
    global $wpdb;
    $error = false;
    if(!$is_submit){
        $error = new WP_Error( 'submitError', __( "There are an error while you update", "emo_ewpu" ) );
    }
    if ( ! isset( $_POST['emo_ewpu_nonce_field'] )
        || ! wp_verify_nonce( $_POST['emo_ewpu_nonce_field'], 'emo_ewpu_action' )
    ){
        $error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
    }
    if(@!$_POST['cat_id']){
        $error = new WP_Error( 'requirements', __( "There are some required fields that not filled", "emo_ewpu" ) );
    }
    if(@$_POST['change_rate']) {
        $error = new WP_Error( 'requirements', __( "There are some required fields that not filled", "emo_ewpu" ) );
    }

    if($error){
        return ['error'=>$error];
    }

    $cat_id = $_POST['cat_id'];
    $change_rate = $_POST['change_rate'];
    $rate_type = $_POST['emo_ewpu_rate'];
    $activeSalePrice = $_POST['sale_price'];
    $increasning_type = $_POST['emo_ewpu_increase'];

    //create csv file
    $filename = "ChangePrice_".date("Y-m-d_h-i-s").".csv";

    if (!file_exists( EWPU_CREATED_DIR )) {
        mkdir( EWPU_CREATED_DIR, 0777, true);
    }
    $filePath = EWPU_CREATED_DIR.$filename;
    $fileUrl = EWPU_CREATED_URI.$filename;
    $csvFile = fopen($filePath, 'w') or die("Unable to open file!");

    $writeCSV = array(array('parent_id', 'product_id', 'product_name', 'price_type', 'old_price', 'new_price'));

    //retrieve all related products
    $products = array();
    if($cat_id){
        $relatedProducts = $wpdb->get_results("SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = " . $cat_id);
        if(is_array(relatedProducts) && count(relatedProducts) > 0) {
            foreach ($relatedProducts as $relatedProduct) {
                array_push($products, $relatedProduct->object_id);
            }
        }else{
            return new WP_Error( 'returnedProducts', __( "The selected product category has not contain any products", "emo_ewpu" ) );
        }
    }

    foreach($products as $product){
        $_product = wc_get_product($product);
        if($_product){
            if($_product->get_type() == 'variable'){
                $variationsPrices = $_product->get_variation_prices();
                $vRegularPrices = $variationsPrices['regular_price']; //array
                foreach($vRegularPrices as $vID=>$vRegularPrice){
                    $newRegularPrice = emo_ewpu_change_price($rate_type, $increasning_type, $vRegularPrice, $change_rate);
                    $variation = wc_get_product_object( 'variation', $vID );
                    //set...
                    $variation->set_props(
                        array(
                            'regular_price' => $newRegularPrice,
                            // 'sale_price' => $sale_price,
                        )
                    );
                    $variation->save();
                    array_push($writeCSV, array($product, $vID, $variation->get_title(), 'regular', $vRegularPrice, $newRegularPrice));
                }

                if($activeSalePrice){
                    $vSalerPrices = $variationsPrices['sale_price']; //array
                    foreach($vSalerPrices as $vID=>$vSalerPrice){
                        $newSalePrice = emo_ewpu_change_price($rate_type, $increasning_type, $vSalerPrice, $change_rate);
                        $variation = wc_get_product_object( 'variation', $vID );
                        //set...
                        $variation->set_props(
                            array(
                                // 'regular_price' => $newRegularPrice,
                                'sale_price' => $newSalePrice,
                            )
                        );
                        $variation->save();
                        array_push($writeCSV, array($product, $vID, $variation->get_title(), 'sale', $vSalerPrice, $newSalePrice));
                    }

                }
            }elseif($_product->get_type() == 'simple'){
                $regularPrice = $_product->get_regular_price();
                $newRegularPrice = emo_ewpu_change_price($rate_type, $increasning_type, $regularPrice, $change_rate);
                //set...
                $productObject = wc_get_product_object( 'simple', $product );
                $productObject->set_props(
                    array(
                        'regular_price' => $newRegularPrice,
                        // 'sale_price' => $newSalePrice,
                    )
                );
                $productObject->save();
                array_push($writeCSV, array('0', $product, $_product->get_title(), 'regular', $regularPrice, $newRegularPrice));

                if($activeSalePrice){
                    $salePrice = $_product->get_sale_price();
                    $newSalePrice = emo_ewpu_change_price($rate_type, $increasning_type, $salePrice, $change_rate);
                    //set...
                    $productObject = wc_get_product_object( 'simple', $product );
                    $productObject->set_props(
                        array(
                            // 'regular_price' => $newRegularPrice,
                            'sale_price' => $newSalePrice,
                        )
                    );
                    $productObject->save();
                    array_push($writeCSV, array('0', $product, $_product->get_title(), 'sale', $salePrice, $newSalePrice));
                }
            }
        }

    }
    foreach ($writeCSV as $row) {
        fputcsv($csvFile, $row);
    }
    fclose($csvFile);

    return ['error'=>false, 'filePath'=> $fileUrl, 'fileName'=> $filename];
}

/**
 * Handle group discount form
 * @param boolean $is_submit
 * @return array
 */
function emo_ewpu_get_group_discount_data(bool $is_submit): array
{
    global $wpdb;
    $months = new WP_Locale();
    $error = false;
    if(!$is_submit){
        $error = new WP_Error( 'submitError', __( "There are an error while you update", "emo_ewpu" ) );
    }
    if ( ! isset( $_POST['emo_ewpu_nonce_field'] )
        || ! wp_verify_nonce( $_POST['emo_ewpu_nonce_field'], 'emo_ewpu_action' )
    ){
        $error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
    }
    if(@!$_POST['cat_id']){
        $error = new WP_Error( 'requirements', __( "There are some required fields that not filled", "emo_ewpu" ) );
    }
    if(@$_POST['change_rate']) {
        $error = new WP_Error( 'requirements', __( "There are some required fields that not filled", "emo_ewpu" ) );
    }

    if($error){
        return ['error'=>$error];
    }

    $cat_id = $_POST['cat_id'];
    $rate_type = $_POST['nimo_nwab_rate'];
    $change_rate = $_POST['change_rate'];
    $endYear = $_POST['sale_end_time_year'];
    $endMonth = $_POST['sale_end_time_month'];
    $endDay = $_POST['sale_end_time_day'];
    $startYear = $_POST['sale_start_time_year'];
    $startMonth = $_POST['sale_start_time_month'];
    $startDay = $_POST['sale_start_time_day'];

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

    $csvFile = fopen($filePath, 'w') or die("Unable to open file!");

    $writeCSV = array(array('parent_id', 'product_id', 'product_name', 'Regular_price', 'Sale_price', 'Start_time', 'End_time'));

    //retrieve all related products
    $cat_products = array();
    if($cat_id){
        $relatedProducts = $wpdb->get_results("SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = " . $cat_id);
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
        fputcsv($csvFile, $row);
    }
    fclose($csvFile);

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
    global $wpdb;
    $error = false;
    if(!$is_submit){
        $error = new WP_Error( 'submitError', __( "There are an error while you update", "emo_ewpu" ) );
    }
    if ( ! isset( $_POST['emo_ewpu_nonce_field'] )
        || ! wp_verify_nonce( $_POST['emo_ewpu_nonce_field'], 'emo_ewpu_action' )
    ){
        $error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
    }

    $products = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts WHERE post_type ='product' AND post_status = 'publish' ORDER BY post_modified DESC; "));
    if(count($products)<= 0){
        $error = new WP_Error( 'noProduct', __( "Sorry, There are no products.", "emo_ewpu" ) );
    }

    if($error){
        return ['error'=>$error];
    }
    $fileUrl = EWPU_CREATED_URI . $fileName;

    $myFile = new EMO_EWPU_CsvHandler($fileName, "w");
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
	if ( ! isset( $_POST['emo_ewpu_nonce_field'] )
	     || ! wp_verify_nonce( $_POST['emo_ewpu_nonce_field'], 'emo_ewpu_action' )
	){
		$error = new WP_Error( 'nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ) );
	}
	if(@!$_FILES['price_list']){
		$error = new WP_Error( 'requirements', __( "There aren't any file to upload", "emo_ewpu" ) );
	}

	if($error){
		return ['error'=>$error];
	}

	$extensions= ($args['extensions'])? $args['extensions']:array("csv");
	$maxFileSize = ($args['max-size'])? $args['max-size']:2097152;

	$target_file = EWOU_UPLOAD_DIR. basename($_FILES["price_list"]["name"]);
	$file_name = $_FILES['price_list']['name'];
	$file_tmp =$_FILES['price_list']['tmp_name'];
	$file_size = $_FILES['price_list']['size'];
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
	if (($handle = fopen($target_file, "r")) !== FALSE) {
		$row = 0;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
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
		fclose($handle);
		$response= __('Your prices are updated successfully.', 'emo_ewpu' );
		return ['response'=>$response];
	}else{
		$errors = new WP_Error( 'invalid', __( "The plugin is not able to open the uploaded file ", "emo_ewpu" ) );
		return ['error'=>$errors];
	}

}