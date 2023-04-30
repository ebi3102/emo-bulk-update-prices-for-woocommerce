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
                        <?php
                        /**
                         * Add element to the group update prices form
                         *
                         *
                        */
                        do_action('emo_bupw_group_price_form')
                        ?>

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