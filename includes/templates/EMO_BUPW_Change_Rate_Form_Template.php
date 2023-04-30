<?php

namespace EMO_BUPW\Templates;

use EMO_BUPW\Templates\EMO_BUPW_From_Element_Template_Interface;

class EMO_BUPW_Change_Rate_Form_Template implements EMO_BUPW_From_Element_Template_Interface
{
    public function __construct()
    {
        add_action('emo_bupw_group_price_form',array($this, 'template'),30);
        add_action('emo_bupw_group_discount_form',array($this, 'template'),30);
    }

    /**
     * @inheritDoc
     */
    public function template()
    { ?>
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
    <?php }
}