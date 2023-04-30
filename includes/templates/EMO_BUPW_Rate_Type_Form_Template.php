<?php

namespace EMO_BUPW\Templates;

use EMO_BUPW\Templates\EMO_BUPW_From_Element_Template_Interface;

class EMO_BUPW_Rate_Type_Form_Template implements EMO_BUPW_From_Element_Template_Interface
{
    public function __construct()
    {
        add_action('emo_bupw_group_price_form',array($this, 'template'),20);
        add_action('emo_bupw_group_discount_form',array($this, 'template'),20);
    }

    public function template()
    { ?>
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
    <?php }
}