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