<h1><?php echo __( 'Group discount settings', 'emo_ewpu' ) ?></h1>
<?php
$months = new WP_Locale();
global $wpdb;
// $product_categories = get_terms( 'product_cat');
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


if(@$_POST['btnSubmit']){
    if ( ! isset( $_POST['emo_ewpu_nonce_field'] ) 
        || ! wp_verify_nonce( $_POST['emo_ewpu_nonce_field'], 'emo_ewpu_action' ) 
    ){
        echo __('Sorry, your nonce did not verify.', 'emo_ewpu');
    } else {
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
        $filePath = EWPU_CREATED_DIR.'/'.$filename;
        $fileUrl = EWPU_CREATED_URI.'/'.$filename;

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
    }
}


?>
<div class="wrap nosubsub emo-ewpu">

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
                            <h3><?php echo __('Date and time settings', 'emo_ewpu') ?></h3>
                            <p class="description">
                                <?php echo __('Set times based on Gregorian date', 'emo_ewpu') ?>
                            </p>
                            <div style="display:flex; align-items: center;">
                                <h4>
                                    <?php echo __('From: ','emo_ewpu') ?>
                                </h4>
                                <label for="sale_start_time_year" style="padding:10px">
                                    <?php echo __('Year: ','emo_ewpu') ?>
                                    <input type="number" style="width: 70px;"    name="sale_start_time_year" id="sale_start_time_year">
                                </label>

                                <label for="sale_start_time_month">
                                    <?php echo __('Month: ','emo_ewpu') ?>
                                    <select name="sale_start_time_month" id="sale_start_time_month">
                                        <?php
                                        for($i=1; $i<13; $i++){
                                            echo "<option value = '".$i."'>".$months->get_month($i) ."</option>";
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label for="sale_start_time_day" style="padding:10px">
                                    <?php echo __('Day: ','emo_ewpu') ?>
                                    <input type="number" style="width: 60px;" min="1" max="31" name="sale_start_time_day" id="sale_start_time_day">
                                </label>
                            </div>


                            <div style="display:flex; align-items: center;">
                                <h4>
                                    <?php echo __('To: ','emo_ewpu') ?>
                                </h4>
                                <label for="sale_end_time_year" style="padding:10px">
                                    <?php echo __('Year: ','emo_ewpu') ?>
                                    <input type="number" style="width: 70px;" name="sale_end_time_year" id="sale_end_time_year">
                                </label>

                                <label for="sale_end_time_month">
                                    <?php echo __('Month: ','emo_ewpu') ?>
                                    <select name="sale_end_time_month" id="sale_end_time_month">
                                        <?php
                                        for($i=1; $i<13; $i++){
                                            echo "<option value = '".$i."'>".$months->get_month($i) ."</option>";
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label for="sale_end_time_day" style="padding:10px">
                                    <?php echo __('Day: ','emo_ewpu') ?>
                                    <input type="number" style="width: 60px;" min="1" max="31" name="sale_end_time_day" id="sale_end_time_day">
                                </label>

                                <?php // nounce ?>
                                <?php wp_nonce_field( 'emo_ewpu_action', 'emo_ewpu_nonce_field' ); ?>
                            </div>
                        </div>
                        <div style="padding-top: 20px;"><?php submit_button( __('Update', 'emo_ewpu'), 'primary', 'btnSubmit');  ?></div>

                    </form>
                </div>
            </div>
        </div><!-- #col-left -->

    </div>

    <?php
    if(@$_POST['btnSubmit'] && @$fileUrl){

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


