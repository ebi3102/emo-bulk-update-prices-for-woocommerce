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
        <?php
        /**
         * Add content before of form
         *
         */
        do_action('emo_bupw_before_group_price_form');
        ?>
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <form method="post">
                        <?php
                        /**
                         * Add element to the group update prices form
                         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Product_Option_List_Form_Template, 'template'),10);
                         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Rate_Type_Form_Template, 'template'),20);
                         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Change_Rate_Form_Template, 'template'),30);
                         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_On_Sale_Activation_Form_Template, 'template'),40);
                         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Inc_Dec_Form_Template, 'template'),50);
                         *
                        */
                        do_action('emo_bupw_group_price_form');
                        ?>

                        <?php // nounce ?>
                        <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                        <div style="padding-top: 20px;">
                            <?php submit_button( esc_html(__('Update', 'emo-bulk-update-prices-for-woocommerce')), esc_attr('primary'), esc_attr('btnSubmit'));  ?>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div><!-- #col-left -->
        <div id="col-right"></div>

    </div>
    <?php
    /**
     * Add content after of form
     *
     */
    do_action('emo_bupw_after_group_price_form');
    ?>

   <?php
   echo EMO_BUPW_Notice_Template::success ($successMassage);
   echo EMO_BUPW_Notice_Template::warning ($errorMessage);
    ?>

</div><!-- .wrap nosubsub -->