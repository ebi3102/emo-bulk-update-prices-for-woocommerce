<?php

/**
 * @package EMO_BUPW
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

 use EMO_BUPW\Repository\EMO_BUPW_Request_Handler;
 use EMO_BUPW\EMO_BUPW_Notice_Template;
?>
<h1><?php echo __( 'Update prices by uploading CSV file', 'emo-bulk-update-prices-for-woocommerce' ) ?></h1>
<?php

//Download Current prices
/* Extract all Poducts site */

if(EMO_BUPW_Request_Handler::get_POST('btnSubmit')){
    $result = emo_bupw_get_product_list();
}

?>
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

<?php

if( EMO_BUPW_Request_Handler::get_POST('uploadSubmit') && EMO_BUPW_Request_Handler::get_FILE('price_list')){
    $result = emo_bupw_update_products_price_list();
}

?>
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
    if( EMO_BUPW_Request_Handler::get_POST('uploadSubmit') && EMO_BUPW_Request_Handler::get_FILE('price_list')){
        if(@$result['response']){
	        echo EMO_BUPW_Notice_Template::success ($result['response']);
        }
	    if(@$result['error']){
		    echo EMO_BUPW_Notice_Template::warning ($result['error']->get_error_message());
        }
    }

    //Notice and download link when product list is created
    if(EMO_BUPW_Request_Handler::get_POST('btnSubmit')){
        if(@$result['error']){
            echo EMO_BUPW_Notice_Template::warning ($result['error']->get_error_message());
        }elseif(@$result['filePath']){
            $massage = __('You can download the list of price products from ', 'emo-bulk-update-prices-for-woocommerce');
            $massage .= "<a href='".esc_url($result['filePath'])."'>".esc_html($result['fileName'])."</a>";
            echo EMO_BUPW_Notice_Template::success ($massage);
        }
    }
    ?>
</div><!-- .wrap nosubsub -->

