
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
                        <div>

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

                                <?php // nounce ?>
                                <?php wp_nonce_field( 'emo_bupw_action', 'emo_bupw_nonce_field' ); ?>
                            </div>
                        </div>
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


