<?php

namespace EMO_BUPW\Templates;
use EMO_BUPW\Templates\EMO_BUPW_From_Element_Template_Interface;

class EMO_BUPW_On_Sale_Activation_Form_Template implements EMO_BUPW_From_Element_Template_Interface
{
    public function __construct()
    {
        add_action('emo_bupw_group_price_form',array($this, 'template'),40);
    }

    /**
     * @inheritDoc
     */
    public function template()
    { ?>
        <div>
            <h3><?php echo esc_html(__('On sale products', 'emo-bulk-update-prices-for-woocommerce')) ?></h3>
            <label for="sale_price">
                <input type="checkbox" name="sale_price" id="sale_price">
                <?php echo esc_html(__('Doing changes on the product that are on sale', 'emo-bulk-update-prices-for-woocommerce')) ?>
            </label>
        </div>
    <?php }
}