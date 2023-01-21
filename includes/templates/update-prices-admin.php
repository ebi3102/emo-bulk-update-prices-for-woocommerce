<?php

/**
 * @package EWPU
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo_ewpu
 */
?>
<h1><?php echo __( 'Update prices by uploading excel file', 'emo_ewpu' ) ?></h1>
<?php

//Download Current prices
/* Extract all Poducts site */
global $wpdb;

if(@$_POST['btnSubmit']) {
    if (!file_exists(EWPU_CREATED_DIR)) {
        mkdir(EWPU_CREATED_DIR, 0777, true);
    }
    if (!file_exists(EWOU_UPLOAD_DIR)) {
        mkdir(EWOU_UPLOAD_DIR, 0777, true);
    }
    $fileLocation = EWPU_CREATED_URI . "/products.csv";
    $fileLocationDirectory = EWPU_CREATED_DIR . "/products.csv";

    $products = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title, post_modified, post_date FROM $wpdb->posts WHERE post_type ='product' AND post_status = 'publish' ORDER BY post_modified DESC; "));
    $myfile = fopen($fileLocationDirectory, "w");

    $data = array('Product ID', 'SKU', 'Product Title', 'Regular Price', 'Sale Price', 'Type');
    fputcsv($myfile, $data);
    foreach ($products as $product) {
        $_product = wc_get_product($product->ID);
        $sku = $_product->get_sku();
        if ($_product->get_type() == "variable") {
            $variations = $_product->get_children();
            foreach ($variations as $vID) {
                $variation = wc_get_product_object('variation', $vID);
                $data = array($vID, $variation->get_sku(), $variation->get_name(), $variation->get_regular_price(), $variation->get_sale_price(), "variation");
                fputcsv($myfile, $data);
            }
        } elseif ($_product->get_type() == "simple") {
            $data = array($product->ID, $sku, $product->post_title, $_product->get_regular_price(), $_product->get_sale_price(), "simple");
            fputcsv($myfile, $data);
        }
    }
    fclose($myfile);
}
/* End of Extract all Poducts site */

?>
<?php //____________________ Download product lists ________________________________ ?>
<div class="wrap nosubsub">
    <div id="col-container-1" class="wp-clearfix emo-flex-row">
        <div id="col-left">
            <div class="col-wrap">
                <form method="post">
                    <div class="form-wrap">
                        <div style="width:fit-content; margin: 50px auto;">
                            <h3><?php echo __( 'Create product price list', 'emo_ewpu' ) ?></h3>
                            <?php submit_button( __('Create', 'emo_ewpu'), 'primary', 'btnSubmit');  ?>
                            <?php /*
                            <button class="button button-primary"><a style="color:#fff; text-decoration: none;" href="<?php echo $fileLocation ?>"><?php echo __( 'Create', 'emo_ewpu' ) ?></a></button>
                            */ ?>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- #col-left -->

<?php

//Upload CSV file
if(isset($_FILES['price_list'])){
    $errors= array();
    $target_file = EWOU_UPLOAD_DIR.'/' . basename($_FILES["price_list"]["name"]);
    $file_name = $_FILES['price_list']['name'];
    $file_tmp =$_FILES['price_list']['tmp_name'];
    $file_type=$_FILES['price_list']['type'];
    $file_ext=strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $extensions= array("csv");

    if(in_array($file_ext,$extensions)=== false){
        $errors[]= __('extension not allowed, please choose a csv file.', 'emo_ewpu' );
    }
//    if($file_size > 2097152){
//        $errors[]='File size must be excately 2 MB';
//    }
    if(empty($errors)==true){
        move_uploaded_file($file_tmp,$target_file);
        $response= __('Success', 'emo_ewpu' );
    }else{
        $response= $errors;
    }

    // Read and store new prices
    if(empty($errors)==true){

        if (($handle = fopen($target_file, "r")) !== FALSE) {
            $row = 0;
             while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                 if($row != 0){
                     $productID = $data[0];
                     $regularPrice_new = (is_numeric($data[3]))? $data[3]:'';
                     $salePrice_new = (is_numeric($data[4]))? $data[4]:'';
                     $productType = ($data[5]== 'variation' || $data[5] == 'simple')?$data[5]:'';
                     $date = date(DATAFORMAT, time());
//                     echo $data[0].'__________'. $regularPrice_new .'___________'. $productType . '<br>';
                     if($regularPrice_new !='' && $productType != ''){
                         emo_ewpu_set_new_price($productID, $productType, 'regular_price' ,$regularPrice_new);
//                         emo_ewpu_store_price_history($productID, REGULARMETAKEY, $date, $regularPrice_new);
                     }

                     if($salePrice_new !='' && $productType != ''){
                         emo_ewpu_set_new_price($productID, $productType, 'sale_price' ,$salePrice_new);
//                         emo_ewpu_store_price_history($productID, SALEMETAKEY, $date, $salePrice_new);
                     }
                 }
                 $row++;
             }
             fclose($handle);
        }
    }
}
?>
<?php //____________________ Upload New prices ________________________________ ?>

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <div style="width:fit-content; margin: 50px auto;">
                        <h3><?php echo __( 'Upload products list', 'emo_ewpu' ) ?></h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div>
                                <label for="price_list"><?php echo __( 'Upload new price list', 'emo_ewpu' ) ?></label>
                                <input id="price_list" type="file" name="price_list">
                            </div>
                            <p>
                                <description><?php echo __( 'It should be a csv file.<br>For getting the sample template you can download and use product list file', 'emo_ewpu' ) ?></description>
                            </p>
                            <div>
                                <input type="submit" name="submit" class="button button-primary" value="<?php echo __( 'Submit', 'emo_ewpu' ) ?>">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div><!-- #col-left -->

    <?php
    if($_POST['submit'] && $_FILES["price_list"]["name"]){
        ?>
            <div class="notice notice-success settings-error is-dismissible">
                <p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
            <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
            <?php echo $response ?>
            </span>
                    </strong></p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this warning', 'emo_ewpu') ?></span></button>
            </div>
    <?php } ?>

    </div>

    <?php
    if(@$_POST['btnSubmit']){
        ?>
        <div class="notice notice-success settings-error is-dismissible">
            <p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <?php echo __('You can download the list of price products from ', 'emo_ewpu') ?>
        <span><a href="<?php echo $fileLocation ?>"><?php echo "products.csv" ?></a></span>
            <?php // echo __(' to check the correctness of the updated changes', 'emo_ewpu') ?>
        </span>
                </strong></p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this warning', 'emo_ewpu') ?></span></button>
        </div>
    <?php } ?>
</div><!-- .wrap nosubsub -->

