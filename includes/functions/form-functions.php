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
    $filePath = EWPU_CREATED_DIR.'/'.$filename;
    $fileUrl = EWPU_CREATED_URI.'/'.$filename;
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