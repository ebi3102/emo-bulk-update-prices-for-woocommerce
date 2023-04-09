<h1><?php echo __( 'Group discount settings', 'emo-bulk-update-prices-for-woocommerce' ) ?></h1>
<?php
use EMO_BUPW\Repository\EMO_BUPW_Request_Handler;
use EMO_BUPW\EWPU_Notice_Template;

global $wpdb;
$months = new WP_Locale();
// $product_categories = get_terms( 'product_cat');
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

if(EMO_BUPW_Request_Handler::get_POST('btnSubmit')){
    $result = emo_bupw_get_group_discount_data();
}

?>
<div class="wrap nosubsub emo-ewpu">

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
                            <input type="number" name="change_rate" style="width:320px" required>
                            <p class="description">
                            <?php echo __('If you have selected the percentage in the previous step, enter the percentage number for the amount of changes. For example, if it is 10%, enter the number 10.', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </p>
                        </div>
                        <div>
                            <h3><?php echo __('Date and time settings', 'emo-bulk-update-prices-for-woocommerce') ?></h3>
                            <p class="description">
                                <?php echo __('Set times based on Gregorian date', 'emo-bulk-update-prices-for-woocommerce') ?>
                            </p>
                            <div style="display:flex; align-items: center;">
                                <h4>
                                    <?php echo __('From: ','emo-bulk-update-prices-for-woocommerce') ?>
                                </h4>
                                <label for="sale_start_time_year" style="padding:10px">
                                    <?php echo __('Year: ','emo-bulk-update-prices-for-woocommerce') ?>
                                    <input type="number" style="width: 70px;"    name="sale_start_time_year" id="sale_start_time_year">
                                </label>

                                <label for="sale_start_time_month">
                                    <?php echo __('Month: ','emo-bulk-update-prices-for-woocommerce') ?>
                                    <select name="sale_start_time_month" id="sale_start_time_month">
                                        <?php
                                        for($i=1; $i<13; $i++){
                                            if($i < 10){
	                                            echo "<option value = '0".$i."'>".$months->get_month($i) ."</option>";
                                            }else{
	                                            echo "<option value = '".$i."'>".$months->get_month($i) ."</option>";
                                            }
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label for="sale_start_time_day" style="padding:10px">
                                    <?php echo __('Day: ','emo-bulk-update-prices-for-woocommerce') ?>
                                    <input type="number" style="width: 60px;" min="1" max="31" name="sale_start_time_day" id="sale_start_time_day">
                                </label>
                            </div>


                            <div style="display:flex; align-items: center;">
                                <h4>
                                    <?php echo __('To: ','emo-bulk-update-prices-for-woocommerce') ?>
                                </h4>
                                <label for="sale_end_time_year" style="padding:10px">
                                    <?php echo __('Year: ','emo-bulk-update-prices-for-woocommerce') ?>
                                    <input type="number" style="width: 70px;" name="sale_end_time_year" id="sale_end_time_year">
                                </label>

                                <label for="sale_end_time_month">
                                    <?php echo __('Month: ','emo-bulk-update-prices-for-woocommerce') ?>
                                    <select name="sale_end_time_month" id="sale_end_time_month">
                                        <?php
                                        for($i=1; $i<13; $i++){
	                                        if($i < 10){
		                                        echo "<option value = '0".$i."'>".$months->get_month($i) ."</option>";
	                                        }else{
		                                        echo "<option value = '".$i."'>".$months->get_month($i) ."</option>";
	                                        }
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label for="sale_end_time_day" style="padding:10px">
                                    <?php echo __('Day: ','emo-bulk-update-prices-for-woocommerce') ?>
                                    <input type="number" style="width: 60px;" min="1" max="31" name="sale_end_time_day" id="sale_end_time_day">
                                </label>

                                <?php // nounce ?>
                                <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                            </div>
                        </div>
                        <div style="padding-top: 20px;"><?php submit_button( __('Update', 'emo-bulk-update-prices-for-woocommerce'), 'primary', 'btnSubmit');  ?></div>

                    </form>
                </div>
            </div>
        </div><!-- #col-left -->

    </div>

    <?php
    if( EMO_BUPW_Request_Handler::get_POST('btnSubmit') && @!$result['error']){
	    $massage = __('Your changes have been applied successfully. Please check the ', 'emo-bulk-update-prices-for-woocommerce');
	    $massage .= "<a href='".$result['filePath']."'>".$result['fileName']."</a>";
	    $massage .= __(' to check the correctness of the updated changes', 'emo-bulk-update-prices-for-woocommerce');
	    echo EWPU_Notice_Template::success ($massage);
    }
    if( EMO_BUPW_Request_Handler::get_POST('btnSubmit') && @$result['error']){
	    echo EWPU_Notice_Template::warning ($result['error']->get_error_message());
    } ?>
</div><!-- .wrap nosubsub -->


