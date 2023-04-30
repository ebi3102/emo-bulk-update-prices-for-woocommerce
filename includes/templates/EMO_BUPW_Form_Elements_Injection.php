<?php

namespace EMO_BUPW\Templates;

use EMO_BUPW\Templates\EMO_BUPW_Product_Option_List_Form_Template;
use EMO_BUPW\Templates\EMO_BUPW_Rate_Type_Form_Template;
use EMO_BUPW\Templates\EMO_BUPW_Change_Rate_Form_Template;
use EMO_BUPW\Templates\EMO_BUPW_On_Sale_Activation_Form_Template;

class EMO_BUPW_Form_Elements_Injection
{
    public function __construct()
    {
        $this->setUp();
    }

    public function setUp()
    {
        /**
         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Product_Option_List_Form_Template, 'template'),10);
         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Rate_Type_Form_Template, 'template'),20);
         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_Change_Rate_Form_Template, 'template'),30);
         * add_action('emo_bupw_group_price_form',array(EMO_BUPW_On_Sale_Activation_Form_Template, 'template'),40);
        */

        /**
         * add_action('emo_bupw_group_discount_form',array(EMO_BUPW_Product_Option_List_Form_Template, 'template'),10);
         * add_action('emo_bupw_group_discount_form',array(EMO_BUPW_Rate_Type_Form_Template, 'template'),20);
         * add_action('emo_bupw_group_discount_form',array(EMO_BUPW_Change_Rate_Form_Template, 'template'),30);
         */

        new EMO_BUPW_Product_Option_List_Form_Template();
        new EMO_BUPW_Rate_Type_Form_Template();
        new EMO_BUPW_Change_Rate_Form_Template();
        new EMO_BUPW_On_Sale_Activation_Form_Template();
    }

}