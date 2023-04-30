<?php

namespace EMO_BUPW\Templates;

use EMO_BUPW\Templates\EMO_BUPW_Product_Category_Option_list;
use EMO_BUPW\Templates\EMO_BUPW_From_Element_Template_Interface;

class EMO_BUPW_Product_Option_List_Form_Template implements EMO_BUPW_From_Element_Template_Interface
{
    public function __construct()
    {
        add_action('emo_bupw_group_price_form',array($this, 'template'),10);
    }

    public function template()
    {
        $options_html = EMO_BUPW_Product_Category_Option_list::render_template();?>
        <div>
            <h3>
                <?php echo esc_html(__('Select a product category', 'emo-bulk-update-prices-for-woocommerce')) ?>
                <span>*</span>
            </h3>
            <select name="cat_id" style="width:322px" required>
                <?php
                $allowedHtml = array(
                    'option' => array(
                        'value'=>array()
                    )
                );
                echo wp_kses($options_html,  $allowedHtml); ?>
            </select>
        </div>
        <?php
    }

}