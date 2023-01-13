<?php

/**
 * @package EWTGP
 * ========================
 * Wordpress Functions
 * ========================
 * Text Domain: emo_ewpu
 */

use JetBrains\PhpStorm\ArrayShape;

/**
 * Add new price to price history array
 * @param string $dateTime
 * @param array $priceHistory
 * @param string $newPrice
 * @return array
 */
function emo_ewpu_price_history_array(string $dateTime, array $priceHistory, string $newPrice): array
{
    global $emo_ewpu_timeSeparator;
    if(count($priceHistory)>0){
        $endElement = end($priceHistory);
        $lastPrice = end($endElement);
    }else{
        $lastPrice = '';
    }

    $dateTime_array = explode($emo_ewpu_timeSeparator, $dateTime);
    if($priceHistory[$dateTime_array[0]]){
        $priceHistory[$dateTime_array[0]] = array_merge($priceHistory[$dateTime_array[0]],[$dateTime_array[1]=>$newPrice]);
    }else{
        $priceHistory[$dateTime_array[0]] = [$dateTime_array[1]=>$newPrice];
    }
    return [$priceHistory, $lastPrice];
}


/**
 *  Store Prices as meta post in post meta table
 *  @param $productID product id
 *  @param $metaKey meta key in post_meta table
 *  @param $date date of saved price
 *  @param $newPrice new price that should be stored
 */
function emo_ewpu_store_price_history($productID, $metaKey, $dateTime, $newPrice){
    $priceHistory = get_post_meta($productID, $metaKey);
    if(count($priceHistory) > 0){
        $priceHistory_array = unserialize($priceHistory[0]);
        $newPriceHistory_array = emo_ewpu_price_history_array($dateTime, $priceHistory_array, $newPrice);
        update_post_meta(
            $productID,
            $metaKey,
            serialize($newPriceHistory_array[0])
        );
    }else{
        $priceHistory_array = array();
        $newPriceHistory_array = emo_ewpu_price_history_array($dateTime, $priceHistory_array, $newPrice);
        add_post_meta( $productID, $metaKey, serialize($newPriceHistory_array[0]), true );
    }

}


/**
 *  Store Prices as meta post in post meta table only when price has been changed
 *  @param $productID product id
 *  @param $metaKey meta key in post_meta table
 *  @param $date date of saved price
 *  @param $newPrice new price that should be stored
 */
function emo_ewpu_store_price_history_exclude($productID, $metaKey, $dateTime, $newPrice){
    $priceHistory = get_post_meta($productID, $metaKey);
    if(count($priceHistory) > 0){
        $priceHistory_array = unserialize($priceHistory[0]);
        $newPriceHistory_array = emo_ewpu_price_history_array($dateTime, $priceHistory_array, $newPrice);
        $lastPrice = $newPriceHistory_array[1];
        if($lastPrice != $newPrice){
        update_post_meta(
            $productID,
            $metaKey,
            serialize($newPriceHistory_array[0])
        );
        }
    }else{
        $priceHistory_array = array();
        $newPriceHistory_array = emo_ewpu_price_history_array($dateTime, $priceHistory_array, $newPrice);
        add_post_meta( $productID, $metaKey, serialize($newPriceHistory_array[0]), true );
    }

}

/**
 *  Set New Prices to product
 *  @param $productID product id
 *  @param $productType simple | variation
 *  @param $priceType regular_price | sale_price
 *  @param $newPrice new price that should be stored
 */
function emo_ewpu_set_new_price($productID, $productType, $priceType , $newPrice){
    if($productType!='variation' && $productType!='simple'){
        return;
    }
    if($priceType != 'regular_price' && $priceType != 'sale_price'){
        return;
    }
    $productObject = wc_get_product_object($productType,$productID);
    $productObject->set_props(
        array(
            $priceType => $newPrice,
        )
    );
    $productObject->save();
}

/**
 *  Store price history when post is created or updated
 *  @param $post_id product id
 *  @param $post

 */
function emo_ewpu_set_new_price_history($post_id, $post) {
    // Only set for post_type = product!
    if ( 'product' != $post->post_type ) {
        return;
    }
    $date = date(DATAFORMAT, time());
    $_product = wc_get_product( $post_id );
    if($_product->get_type() == "variable"){
        $variations = $_product->get_children();
        foreach ( $variations as $vID ) {
            $variation = wc_get_product_object( 'variation', $vID );
            $regularPrice_new = $variation->get_regular_price();
            $salePrice_new = $variation->get_sale_price();
            emo_ewpu_store_price_history_exclude($variation->get_id(), REGULARMETAKEY, $date, $regularPrice_new);
            emo_ewpu_store_price_history_exclude($variation->get_id(), SALEMETAKEY, $date, $salePrice_new);
        }
    }elseif($_product->get_type() == "simple"){
        $regularPrice_new = $_product->get_regular_price();
        $salePrice_new = $_product->get_sale_price();
        emo_ewpu_store_price_history_exclude($post_id, REGULARMETAKEY, $date, $regularPrice_new);
        emo_ewpu_store_price_history_exclude($post_id, SALEMETAKEY, $date, $salePrice_new);
    }
}
add_action( 'save_post', 'emo_ewpu_set_new_price_history', 10,3 );

/**
 * Set variation props before save.
 * @param $variation
 */
function emo_ewpu_set_new_variation_price($variation) {
    $date = date(DATAFORMAT, time());
    $regularPrice_new = $variation->get_regular_price();
    $salePrice_new = $variation->get_sale_price();
    emo_ewpu_store_price_history($variation->get_id(), REGULARMETAKEY, $date, $regularPrice_new);
    emo_ewpu_store_price_history($variation->get_id(), SALEMETAKEY, $date, $salePrice_new);
}
add_action('woocommerce_admin_process_variation_object', 'emo_ewpu_set_new_variation_price', 10, 1);


/**
 * @param string $resultType start | end | max | min | avg | all |default
 * @param array $priceHistory
 * @return array
 */
function emo_ewpu_filtered_price_history(string $resultType, array $priceHistory): array
{
    global $emo_ewpu_timeSeparator;
    $filteredPriceHistory = array();
    foreach($priceHistory as $dateSaved=>$row){
        switch($resultType){
            case 'end':
                $filteredPriceHistory[$dateSaved] = end($row);
                break;
            case 'start':
                $filteredPriceHistory[$dateSaved] = $row[array_key_first($row)];
                break;
            case 'max':
                $filteredPriceHistory[$dateSaved] = max($row);
                break;
            case 'min':
                $filteredPriceHistory[$dateSaved] = min($row);
                break;
            case 'avg':
                $values  = array_values($row);
                $values = array_filter($values);
                $average = array_sum($values)/count($values);
                $filteredPriceHistory[$dateSaved] = $average;
                break;
            case 'all':
                foreach($row as $timeSaved=>$price ){
                    $filteredPriceHistory[$dateSaved.$emo_ewpu_timeSeparator.$timeSaved] = $price;
                }
                break;
            case 'default':
                $filteredPriceHistory[$dateSaved] = $row;
                break;
        }
    }
    return $filteredPriceHistory;
}

/**
 * get price history meta post
 * @param WC_Product $_product object woocommerce product
 * @param string $resultType start | end | max | min | avg | all |default
 * @return array
 */
function emo_ewpu_get_price_histoy(WC_Product $_product, string $resultType='end'): array
{
    if($_product->get_type() == "variable"){
        $mainVarID = get_post_meta( $_product->get_ID(), MAINVAR, true );
        $variation = wc_get_product_object( 'variation', $mainVarID );
        if($variation->is_on_sale()){
            $priceHistory = get_post_meta($mainVarID, SALEMETAKEY);
        }else{
            $priceHistory = get_post_meta($mainVarID, REGULARMETAKEY);
        }
    }elseif($_product->get_type() == "simple"){
        if($_product->is_on_sale()){
            $priceHistory = get_post_meta($_product->get_ID(), SALEMETAKEY);
        }else{
            $priceHistory = get_post_meta($_product->get_ID(), REGULARMETAKEY);
        }

    }elseif ($_product->get_type() == "variation"){
        if($_product->is_on_sale()){
            $priceHistory = get_post_meta($_product->get_ID(), SALEMETAKEY);
        }else{
            $priceHistory = get_post_meta($_product->get_ID(), REGULARMETAKEY);
        }
    }
    $priceHistory_array =unserialize($priceHistory[0]);
    $filteredPriceHistory = emo_ewpu_filtered_price_history( $resultType, $priceHistory_array);

    return $filteredPriceHistory;
}

/**
 * get woocommerce price html
 * @param WC_Product $_product
 * @return array (price html, last update date, price trend html, price color font)
 */
function emo_ewpu_woo_get_price_info(WC_Product $_product){
    if($_product->get_type() == "variable"){
        $mainVarID = get_post_meta( $_product->get_ID(), MAINVAR, true );
        $variation = wc_get_product_object( 'variation', $mainVarID );
        $priceHtml = $variation->get_price_html();
    }elseif($_product->get_type() == "simple"){
        $priceHtml = $_product->get_price_html();
    }
    $priceHistory = emo_ewpu_get_price_histoy($_product, 'all');
    $lastUpdate = array_key_last($priceHistory);
    $lastUpdate = emo_ewpu_date_to_array($lastUpdate,'list');
    $perLastPrice =array_slice($priceHistory, count($priceHistory)-2, 1, true);
    $perLastPrice = floatval($perLastPrice[array_key_first($perLastPrice)]);
    $lastPrice = floatval($_product->get_price());
    if($lastPrice > $perLastPrice){
        $priceTrend = "&#8593;";
        $priceColor = "emo-increase";
    }elseif ($lastPrice < $perLastPrice){
        $priceTrend = "&#8595;";
        $priceColor = "emo-decrease";
    }else{
        $priceTrend = "&#8722;";
        $priceColor = "emo-constant";
    }
    return array($priceHtml, $lastUpdate[0], $priceTrend, $priceColor);
}

/**
 * Store data table in xlsx format
 * @param string $fileName File name
 * @param string $sheetName Sheet name
 * @param array $header Headers of data table
 * @param array $data content of data table
 * @return void
 */
function emo_ewpu_write_excel(string $fileName, string $sheetName, array $header, array $data){
    //https://stackoverflow.com/questions/37958282/how-to-generate-an-xlsx-using-php
        $fname=EWPU_CREATED_DIR.'/'.$fileName.'.xlsx';
//    $styles2 = array( ['font-size'=>6],['font-size'=>8],['font-size'=>10],['font-size'=>16] );
    $writer = new XLSXWriter();
    $writer->setAuthor('Arad Ahan');
    $writer->writeSheet($data,$sheetName, $header);  // with headers
//    $writer->writeSheetRow('MySheet2', $rowdata = array(300,234,456,789), $styles2 );
    $writer->writeToFile($fname);   // creates XLSX file (in current folder)
}


/**
 * Find the start date in a date range
 * @param int $period date range
 * @return array #[ArrayShape(['year' => "int|mixed", 'month' => "int|mixed", 'day' => "mixed"])]
 */

function emo_ewpu_start_date(int $period)
{
    $monthNumbers = array(1,2,3,4,5,6,7,8,9,10,11,12);
    $dateString = date(DATAFORMAT, time());
    $currentDate = emo_ewpu_date_to_array($dateString);

    if(($currentDate['month'] - $period) >= 0){
        $startMonth = $currentDate['month'] - $period + 1;
        $startYear = $currentDate['year'];
    }else{
        if(abs($currentDate['month'] - $period) <= 12){
            $startMonth = $monthNumbers[12-abs($currentDate['month'] - $period)];
            $startYear = $currentDate['year']-1;
        }else{
            $startYear = $currentDate['year'] - ((intdiv(abs($currentDate['month'] - $period),12))+1);
            $startMonth = $monthNumbers[12-(abs($currentDate['month'] - $period)%12)];

        }
    }
    $startDate = array(
        'year' => $startYear,
        'month' => $startMonth,
        'day' => $currentDate['day']
    );
    return $startDate;
}

/**
 * Convert date and time that as string to array
 * @param string $dateString
 * @return array
 */
function emo_ewpu_date_to_array( string $dateString, string $resultType='dict'): array
{
    global $emo_ewpu_timeSeparator;
    global $emo_ewpu_dateSeparator;
    //'DATAFORMAT', 'Y-m-d/h:i:s'
    $currentDate = explode($emo_ewpu_timeSeparator, $dateString);
    if($resultType == 'dict'){
        $currentDate = explode($emo_ewpu_dateSeparator, $currentDate[0]);
        $dateKey = ['year','month','day'];
        $currentDate = array_combine( $dateKey, $currentDate);
        array_walk($currentDate, function (&$value) {
            if (ctype_digit($value)) {
                $value = (int) $value;
            }
        });
    }elseif($resultType == 'list'){
        $currentDate = $currentDate;
    }

    return $currentDate;
}

/**
 * Get months and prices in a date range from start date
 * @param array $startDate
 * @param array $priceHistory
 * @return array[] array( get )
 */
function emo_ewpu_resulted_date_price(array $startDate, array $priceHistory): array
{
    $months = array();
    $prices = array();
    foreach ($priceHistory as $key=>$record){
        $recordDate = emo_ewpu_date_to_array($key);
        if(wp_timezone_string() == 'Asia/Tehran'){
            $dateString = $recordDate['year'].'-'.$recordDate['month'] .'-'. $recordDate['day'];
            $dateObject = new MyDateTime($dateString);
            $dateObject->setCalendar('persian');
            $month = $dateObject->format('M/d');
        }else{
            $month = $recordDate['month'] .'-'. $recordDate['day'];
        }

        if($startDate['year'] < $recordDate['year']){
            $months[] = $month;
            $prices[] = $record;
        }elseif($startDate['year'] == $recordDate['year']){
            if($startDate['month'] <= $recordDate['month']){
                $months[] = $month;
                $prices[] = $record;
            }
        }
    }
    return array($months, $prices);
}