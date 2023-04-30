<?php

/**
 * @package EMO_BUPW
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 *
 * Available variables:
 *
 * string $options_html
 * WP_Locale $months
 * string $successMassage
 * string $errorMessage
 */

 use EMO_BUPW\EMO_BUPW_Notice_Template;
?>
<h1><?php echo esc_html(__( 'Group update price settings', 'emo-bulk-update-prices-for-woocommerce' )) ?></h1>
<div class="wrap nosubsub">

    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    
                    <form method="post">

                        <div>
                            <h3>
                                <?php echo esc_html(__('Select a product category', 'emo-bulk-update-prices-for-woocommerce')) ?>
                                <span>*</span>
                            </h3>
                            <select name="cat_id" style="width:322px" required>
                                <?php
                                $allowedHtml = array(
                                        'option' => array(
                                                'value'=>array()
                                        )
                                );
                                echo wp_kses($options_html,  $allowedHtml); ?>
                            </select>
                        </div>

                        <div>
                            <h3><?php echo esc_html(__('Fixed rate or percentage', 'emo-bulk-update-prices-for-woocommerce')) ?></h3>

                            <label for="constant">
                                <input type="radio" name="emo_ewpu_rate" id="constant" value="constant" checked="checked">
                                <?php echo esc_html(__('Fixed rate', 'emo-bulk-update-prices-for-woocommerce')) ?>
                            </label>
                            
                            <label for="percent">
                                <input type="radio" name="emo_ewpu_rate" id="percent" value="percent">
                                <?php echo esc_html(__('Percentage', 'emo-bulk-update-prices-for-woocommerce')) ?>
                            </label>
                        </div>
                        <div>
                            <h3>
                                <?php echo esc_html(__('Change value', 'emo-bulk-update-prices-for-woocommerce')) ?>
                                <span>*</span>
                            </h3>
                            <input type="number" name="change_rate" style="width:320px" min="1" required>
                            <p class="description">
                            <?php echo esc_html(__('If you have selected the percentage in the previous step, enter the percentage number for the amount of changes. For example, if it is 10%, enter the number 10.', 'emo-bulk-update-prices-for-woocommerce')) ?>
                            </p>
                        </div>
                        <div>
                            <h3><?php echo esc_html(__('On sale products', 'emo-bulk-update-prices-for-woocommerce')) ?></h3>
                            <label for="sale_price">
                                <input type="checkbox" name="sale_price" id="sale_price">
                                <?php echo esc_html(__('Doing changes on the product that are on sale', 'emo-bulk-update-prices-for-woocommerce')) ?>
                            </label>
                        </div>

                        <div>
                            <h3><?php echo esc_html(__('Price increase or decrease', 'emo-bulk-update-prices-for-woocommerce')) ?></h3>

                            <label for="increase">
                                <input type="radio" name="emo_ewpu_increase" id="increase" value="increase" checked="checked">
                                <?php echo esc_html(__('Increase', 'emo-bulk-update-prices-for-woocommerce')) ?>
                            </label>
                            
                            <label for="decrease">
                                <input type="radio" name="emo_ewpu_increase" id="decrease" value="decrease">
                                <?php echo esc_html(__('Decrease', 'emo-bulk-update-prices-for-woocommerce')) ?>
                            </label>

                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                        </div>

                        <div style="padding-top: 20px;">
                            <?php submit_button( esc_html(__('Update', 'emo-bulk-update-prices-for-woocommerce')), esc_attr('primary'), esc_attr('btnSubmit'));  ?>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div><!-- #col-left -->
      
    </div>

   <?php
   echo EMO_BUPW_Notice_Template::success ($successMassage);
   echo EMO_BUPW_Notice_Template::warning ($errorMessage);
    ?>

</div><!-- .wrap nosubsub -->