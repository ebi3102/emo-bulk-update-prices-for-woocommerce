<?php

namespace EMO_BUPW\Templates;

use EMO_BUPW\Templates\EMO_BUPW_From_Element_Template_Interface;
use WP_Locale;

class EMO_BUPW_Date_Form_Template implements EMO_BUPW_From_Element_Template_Interface
{
    public function __construct()
    {
        add_action('emo_bupw_group_discount_form',array($this, 'template'),40);
    }

    /**
     * @inheritDoc
     */
    public function template()
    {
        $months = new WP_Locale();
        ?>
        <div>
            <h3><?php echo esc_html(__('Date and time settings', 'emo-bulk-update-prices-for-woocommerce')) ?></h3>
            <p class="description">
                <?php echo esc_html(__('Set times based on Gregorian date', 'emo-bulk-update-prices-for-woocommerce')) ?>
            </p>
            <div style="display:flex; align-items: center;">
                <h4>
                    <?php echo esc_html(__('From: ','emo-bulk-update-prices-for-woocommerce')) ?>
                </h4>
                <label for="sale_start_time_year" style="padding:10px">
                    <?php echo esc_html(__('Year: ','emo-bulk-update-prices-for-woocommerce')) ?>
                    <input type="number" style="width: 70px;"    name="sale_start_time_year" id="sale_start_time_year">
                </label>

                <label for="sale_start_time_month">
                    <?php echo esc_html(__('Month: ','emo-bulk-update-prices-for-woocommerce')) ?>
                    <select name="sale_start_time_month" id="sale_start_time_month">
                        <?php
                        for($i=1; $i<13; $i++){
                            if($i < 10){
                                $perfix = '0';
                                echo "<option value = '".esc_attr($perfix.$i)."'>".esc_html($months->get_month($i)) ."</option>";
                            }else{
                                echo "<option value = '".esc_attr($i)."'>".esc_html($months->get_month($i)) ."</option>";
                            }
                        }
                        ?>

                    </select>
                </label>

                <label for="sale_start_time_day" style="padding:10px">
                    <?php echo esc_html(__('Day: ','emo-bulk-update-prices-for-woocommerce')) ?>
                    <input type="number" style="width: 60px;" min="1" max="31" name="sale_start_time_day" id="sale_start_time_day">
                </label>
            </div>


            <div style="display:flex; align-items: center;">
                <h4>
                    <?php echo esc_html(__('To: ','emo-bulk-update-prices-for-woocommerce')) ?>
                </h4>
                <label for="sale_end_time_year" style="padding:10px">
                    <?php echo esc_html(__('Year: ','emo-bulk-update-prices-for-woocommerce')) ?>
                    <input type="number" style="width: 70px;" name="sale_end_time_year" id="sale_end_time_year">
                </label>

                <label for="sale_end_time_month">
                    <?php echo esc_html(__('Month: ','emo-bulk-update-prices-for-woocommerce')) ?>
                    <select name="sale_end_time_month" id="sale_end_time_month">
                        <?php
                        for($i=1; $i<13; $i++){
                            if($i < 10){
                                $perfix = '0';
                                echo "<option value = '".esc_attr($perfix.$i)."'>".esc_html($months->get_month($i)) ."</option>";
                            }else{
                                echo "<option value = '".esc_attr($i)."'>".esc_html($months->get_month($i)) ."</option>";
                            }
                        }
                        ?>

                    </select>
                </label>

                <label for="sale_end_time_day" style="padding:10px">
                    <?php echo esc_html(__('Day: ','emo-bulk-update-prices-for-woocommerce')) ?>
                    <input type="number" style="width: 60px;" min="1" max="31" name="sale_end_time_day" id="sale_end_time_day">
                </label>

            </div>
        </div>
    <?php }
}