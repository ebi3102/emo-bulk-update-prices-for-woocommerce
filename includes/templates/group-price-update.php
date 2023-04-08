<?php

/**
 * @package EMO_BUPW
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

 use EMO_BUPW\Repository\EWPU_Request_Handler;
 use EMO_BUPW\EWPU_Notice_Template;
?>
<h1><?php echo __( 'Group update price settings', 'emo-bulk-update-prices-for-woocommerce' ) ?></h1>
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
    $options_html .= '<option value="0">'.__('Select one category', 'emo-bulk-update-prices-for-woocommerce').'</option>';
    foreach($product_categories as $cat) {
        $options_html .= '<option value="'. $cat->term_id .'">'. $cat->name .'</option>';
    }
}

if(EWPU_Request_Handler::get_POST('btnSubmit')){
    $result = emo_bupw_get_price_update_data();
}


?>
<div class="wrap nosubsub">

    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    
                    <form method="post">
                        <div>
                            <h3>
                                <?php echo __('Select a product category', 'emo-bulk-update-prices-for-woocommerce') ?>
                                <span>*</span>
                            </h3>
                            <select name="cat_id" style="width:322px" required>
                                <?php echo $options_html ?>
                            </select>
                        </div>

                        <div>
                            <h3><?php echo __('Fixed rate or percentage', 'emo-bulk-update-prices-for-woocommerce') ?></h3>

                            <label for="constant">
                                <input type="radio" name="emo_ewpu_rate" id="constant" value="constant" checked="checked">
                                <?php echo __('Fixed rate', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </label>
                            
                            <label for="percent">
                                <input type="radio" name="emo_ewpu_rate" id="percent" value="percent">
                                <?php echo __('Percentage', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </label>
                        </div>
                        <div>
                            <h3>
                                <?php echo __('Change value', 'emo-bulk-update-prices-for-woocommerce') ?>
                                <span>*</span>
                            </h3>
                            <input type="number" name="change_rate" style="width:320px" min="1" required>
                            <p class="description">
                            <?php echo __('If you have selected the percentage in the previous step, enter the percentage number for the amount of changes. For example, if it is 10%, enter the number 10.', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </p>
                        </div>
                        <div>
                            <h3><?php echo __('On sale products', 'emo-bulk-update-prices-for-woocommerce') ?></h3>
                            <label for="sale_price">
                                <input type="checkbox" name="sale_price" id="sale_price">
                                <?php echo __('Doing changes on the product that are on sale', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </label>
                        </div>

                        <div>
                            <h3><?php echo __('Price increase or decrease', 'emo-bulk-update-prices-for-woocommerce') ?></h3>

                            <label for="increase">
                                <input type="radio" name="emo_ewpu_increase" id="increase" value="increase" checked="checked">
                                <?php echo __('Increase', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </label>
                            
                            <label for="decrease">
                                <input type="radio" name="emo_ewpu_increase" id="decrease" value="decrease">
                                <?php echo __('Decrease', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </label>

                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                        </div>

                        <div style="padding-top: 20px;">
                            <?php submit_button( __('Update', 'emo-bulk-update-prices-for-woocommerce'), 'primary', 'btnSubmit');  ?>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div><!-- #col-left -->
      
    </div>

   <?php
    if(EWPU_Request_Handler::get_POST('btnSubmit') && @!$result['error']){
        $massage = __('Your changes have been applied successfully. Please check the ', 'emo-bulk-update-prices-for-woocommerce');
        $massage .= "<a href='".$result['filePath']."'>".$result['fileName']."</a>";
        $massage .= __(' to check the correctness of the updated changes', 'emo-bulk-update-prices-for-woocommerce');
        echo EWPU_Notice_Template::success ($massage);
    }
    if(EWPU_Request_Handler::get_POST('btnSubmit') && @$result['error']){
        echo EWPU_Notice_Template::warning ($result['error']->get_error_message());
    } ?>

</div><!-- .wrap nosubsub -->