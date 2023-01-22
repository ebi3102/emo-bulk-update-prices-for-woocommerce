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
    $is_submit = true;
    $result = emo_ewpu_get_price_update_data($is_submit);
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
                                <?php echo __('Select a product category', 'emo_ewpu') ?>
                                <span>*</span>
                            </h3>
                            <select name="cat_id" style="width:322px" required>
                                <?php echo $options_html ?>
                            </select>
                        </div>

                        <div>
                            <h3><?php echo __('Fixed rate or percentage', 'emo_ewpu') ?></h3>

                            <label for="constant">
                                <input type="radio" name="emo_ewpu_rate" id="constant" value="constant" checked="checked">
                                <?php echo __('Fixed rate', 'emo_ewpu') ?>
                            </label>
                            
                            <label for="percent">
                                <input type="radio" name="emo_ewpu_rate" id="percent" value="percent">
                                <?php echo __('Percentage', 'emo_ewpu') ?>
                            </label>
                        </div>
                        <div>
                            <h3>
                                <?php echo __('Change value', 'emo_ewpu') ?>
                                <span>*</span>
                            </h3>
                            <input type="number" name="change_rate" style="width:320px" min="1" required>
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
                                <input type="radio" name="emo_ewpu_increase" id="increase" value="increase" checked="checked">
                                <?php echo __('Increase', 'emo_ewpu') ?>
                            </label>
                            
                            <label for="decrease">
                                <input type="radio" name="emo_ewpu_increase" id="decrease" value="decrease">
                                <?php echo __('Decrease', 'emo_ewpu') ?>
                            </label>

                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_ewpu_action', 'emo_ewpu_nonce_field' ); ?>
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
    if(@$_POST['btnSubmit'] && @!$result['error']){
     ?>
    <div class="notice notice-success settings-error is-dismissible"> 
        <p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <?php echo __('Your changes have been applied successfully. Please check the ', 'emo_ewpu') ?>
        <span><a href="<?php echo $result['filePath'] ?>"><?php echo $result['fileName'] ?></a></span>
            <?php echo __(' to check the correctness of the updated changes', 'emo_ewpu') ?>
        </span>
        </strong></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this warning', 'emo_ewpu') ?></span></button>
    </div>
    <?php } ?>
    <?php
    if(@$_POST['btnSubmit'] && @$result['error']){ ?>
        <div class="notice notice-warning settings-error is-dismissible">
            <p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">
        <?php echo $result['error']->get_error_message(); ?>

        </span>
                </strong></p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this warning', 'emo_ewpu') ?></span></button>
        </div>

    <?php } ?>
</div><!-- .wrap nosubsub -->