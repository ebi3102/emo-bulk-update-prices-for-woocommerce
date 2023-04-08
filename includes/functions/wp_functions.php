<?php

/**
 * @package EMO_BUPW
 * ========================
 * Wordpress Functions
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */



/**
 *  Set New Prices to product
 *  @param $productID product id
 *  @param $productType simple | variation
 *  @param $priceType regular_price | sale_price
 *  @param $newPrice new price that should be stored
 */
if ( ! function_exists( 'emo_bupw_set_new_price' )) {
	function emo_bupw_set_new_price( $productID, $productType, $priceType, $newPrice ) {
		if ( $productType != 'variation' && $productType != 'simple' ) {
			return;
		}
		if ( $priceType != 'regular_price' && $priceType != 'sale_price' ) {
			return;
		}
		$productObject = wc_get_product_object( $productType, $productID );
		$productObject->set_props(
			array(
				$priceType => $newPrice,
			)
		);
		$productObject->save();
	}
}

/**
 * Change price calculator
 */
if ( ! function_exists( 'emo_bupw_change_price' )) {
	function emo_bupw_change_price( $rate_type = 'constant', $increasning_type = 'increase', $price, $change_value ) {
		if ( $price ) {
			if ( $increasning_type == 'increase' ) {
				if ( $rate_type == 'percent' ) {
					$newPrice = $price * ( 1 + ( $change_value / 100 ) );
				} else {
					$newPrice = $price + $change_value;
				}
			} else {
				if ( $rate_type == 'percent' ) {
					$newPrice = $price * ( 1 - ( $change_value / 100 ) );
				} else {
					$newPrice = $price - $change_value;
				}
			}
			$newPrice = round( $newPrice );
			$newPrice = ( $newPrice <= 0 ) ? null : $newPrice;
		} else {
			$newPrice = null;
		}

		return $newPrice;
	}
}