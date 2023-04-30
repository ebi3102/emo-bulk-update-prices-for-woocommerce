<?php

namespace EMO_BUPW\Templates;

use EMO_BUPW\Templates\EMO_BUPW_From_Element_Template_Interface;

class EMO_BUPW_Inc_Dec_Form_Template implements EMO_BUPW_From_Element_Template_Interface
{
    public function __construct()
    {
        add_action('emo_bupw_group_price_form',array($this, 'template'),50);
    }

    /**
     * @inheritDoc
     */
    public function template()
    { ?>
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
    <?php }
}