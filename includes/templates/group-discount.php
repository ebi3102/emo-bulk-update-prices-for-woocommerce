
<?php
/**
 * Group discount template.
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
<h1><?php echo __( 'Group discount settings', 'emo-bulk-update-prices-for-woocommerce' ) ?></h1>
<?php


?>
<div class="wrap nosubsub emo-ewpu">

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
                        do_action('emo_bupw_group_discount_form')
                        ?>
                        <?php // nounce ?>
                        <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>

                        <div style="padding-top: 20px;"><?php submit_button( esc_html(__('Update', 'emo-bulk-update-prices-for-woocommerce')), esc_attr('primary'), esc_attr('btnSubmit'));  ?></div>

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


