<?php

/**
 * @package EWPU
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo_ewpu
 */
?>
<h1><?php echo __( 'Group update price settings', 'emo_ewpu' ) ?></h1>
<?php
global $wpdb;

// get all product categories and render it as select input
$product_categories = get_terms( array (
    'taxonomy' => 'product_cat',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false
));
$options_html = '';
if( !empty($product_categories) ){
    $options_html .= '<option value="0">'.__('Select one category', 'emo_ewpu').'</option>';
    foreach($product_categories as $cat) {
        $options_html .= '<option value="'. $cat->term_id .'">'. $cat->name .'</option>';
    }
}

if($_POST['btnSubmit']){
    //get post fields
    $cat_id = $_POST['cat_id'];
    $rate_type = $_POST['emo_ewpu_rate'];
    $change_rate = $_POST['change_rate'];
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
        foreach($relatedProducts as $relatedProduct){
            array_push($products, $relatedProduct->object_id);
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
}

?>
<div class="wrap nosubsub">

    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    
                    <form method="post">
                        <div>
                            <h3><?php echo __('Select a product category', 'emo_ewpu') ?></h3>
                            <select name="cat_id" style="width:322px">
                                <?php echo $options_html ?>
                            </select>
                        </div>

                        <div>
                            <h3><?php echo __('Fixed rate or percentage', 'emo_ewpu') ?></h3>

                            <label for="constant">
                                <input type="radio" name="emo_ewpu_rate" id="constant" value="constant">
                                <?php echo __('Fixed rate', 'emo_ewpu') ?>
                            </label>
                            
                            <label for="percent">
                                <input type="radio" name="emo_ewpu_rate" id="percent" value="percent">
                                <?php echo __('Percentage', 'emo_ewpu') ?>
                            </label>
                        </div>
                        <div>
                            <h3><?php echo __('Change value', 'emo_ewpu') ?></h3>
                            <input type="number" name="change_rate" style="width:320px">
                            <p class="description">
                            <?php echo __('If you have selected the percentage in the previous step, enter the percentage number for the amount of changes. For example, if it is 10%, enter the number 10.', 'emo_ewpu') ?>
                            </p>
                        </div>
                        <div>
                            <h3><?php echo __('On sale products', 'emo_ewpu') ?></h3>
                            <label for="sale_price">
                                <input type="checkbox" name="sale_price" id="sale_price">
                                <?php echo __('Doing changes on the product that are on sale', 'emo_ewpu') ?>
                            </label>
                        </div>

                        <div>
                            <h3><?php echo __('Price increase or decrease', 'emo_ewpu') ?></h3>

                            <label for="increase">
                                <input type="radio" name="emo_ewpu_increase" id="increase" value="increase">
                                <?php echo __('Increase', 'emo_ewpu') ?>
                            </label>
                            
                            <label for="decrease">
                                <input type="radio" name="emo_ewpu_increase" id="decrease" value="decrease">
                                <?php echo __('Decrease', 'emo_ewpu') ?>
                            </label>
                        </div>

                        <div style="padding-top: 20px;">
                            <?php submit_button( __('Update', 'emo_ewpu'), 'primary', 'btnSubmit');  ?>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div><!-- #col-left -->
      
    </div>

   <?php
    if($_POST['btnSubmit']){
     ?>
    
    <div class="notice notice-success settings-error is-dismissible"> 
        <p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <?php echo __('Your changes have been applied successfully. Please check the ', 'emo_ewpu') ?>
        <span><a href="<?php echo $fileUrl ?>"><?php echo $filename ?></a></span>
            <?php echo __(' to check the correctness of the updated changes', 'emo_ewpu') ?>
        </span>
        </strong></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this warning', 'emo_ewpu') ?></span></button>
    </div>
    <?php } ?>
</div><!-- .wrap nosubsub -->