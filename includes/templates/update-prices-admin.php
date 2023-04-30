<?php

/**
 * @package EMO_BUPW
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 * Available variables:
 *
 * string $downloadSuccessMassage
 * string $downloadErrorMassage
 * string $uploadSuccessMassage
 * string $uploadErrorMassage
 */

 use EMO_BUPW\EMO_BUPW_Notice_Template;
?>
<h1><?php echo __( 'Update prices by uploading CSV file', 'emo-bulk-update-prices-for-woocommerce' ) ?></h1>

<?php //____________________ Download product lists ________________________________ ?>
<div class="wrap nosubsub">
    <div id="col-container-1" class="wp-clearfix emo-flex-row">
        <div id="col-left">
            <div class="col-wrap">
                <form method="post">
                    <div class="form-wrap">
                        <div style="width:fit-content; margin: 50px auto;">
                            <h3><?php echo esc_html(__( 'Create product price list', 'emo-bulk-update-prices-for-woocommerce' )) ?></h3>
                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                            <?php submit_button(esc_html( __('Create', 'emo-bulk-update-prices-for-woocommerce')), esc_attr('primary'), esc_attr('btnSubmit'));  ?>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- #col-left -->

<?php //____________________ Upload New prices ________________________________ ?>

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <div style="width:fit-content; margin: 50px auto;">
                        <h3><?php echo esc_html(__( 'Upload products list', 'emo-bulk-update-prices-for-woocommerce' )) ?></h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div>
                                <label for="price_list"><?php echo esc_html(__( 'Upload new price list', 'emo-bulk-update-prices-for-woocommerce' )) ?></label>
                                <input id="price_list" type="file" name="price_list">
                            </div>
                            <p>
                                <description><?php echo esc_html(__( 'It should be a csv file. For getting the sample template you can download and use product list file', 'emo-bulk-update-prices-for-woocommerce' )) ?></description>
                            </p>
                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                            <div>
                                <input type="submit" name="uploadSubmit" class="button button-primary" value="<?php echo esc_attr(esc_html(__( 'Submit', 'emo-bulk-update-prices-for-woocommerce' ))) ?>">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div><!-- #col-left -->

    </div>

    <?php
    // Notice when uploading is happened
    echo EMO_BUPW_Notice_Template::success ($downloadSuccessMassage);
    echo EMO_BUPW_Notice_Template::warning ($downloadErrorMassage);

    //Notice and download link when product list is created
    echo EMO_BUPW_Notice_Template::warning ($uploadErrorMassage);
    echo EMO_BUPW_Notice_Template::success ($uploadSuccessMassage);
    ?>
</div><!-- .wrap nosubsub -->

