<?php

namespace EMO_BUPW\Form_Handlers;

use  EMO_BUPW\Form_Handlers\EMO_BUPW_Form_Field_Setter;
use  EMO_BUPW\Form_Handlers\EMO_BUPW_Form_Submit;
use  EMO_BUPW\Form_Handlers\EMO_BUPW_Form_Handler;
use EMO_BUPW\Repository\EMO_BUPW_DB_Get_Related_Object;
use EMO_BUPW\Repository\EMO_BUPW_Pass_Error_Msg;
use EMO_BUPW\Repository\EMO_BUPW_Request_Handler;
use EMO_BUPW\Repository\File_Handlers\EMO_BUPW_Csv_Handler;
use EMO_BUPW\Utils\EWPU_Date_Generator;

if (!class_exists('EMO_BUPW_Form_Group_Discount')) {
	class EMO_BUPW_Form_Group_Discount implements EMO_BUPW_Form_Field_Setter, EMO_BUPW_Form_Submit {
		private $cat_id;
		private $rate_type;
		private $change_rate;
		private $endYear;
		private $endMonth;
		private $endDay;
		private $startYear;
		private $startMonth;
		private $startDay;


		use EMO_BUPW_Form_Handler;

		/**
		 * Set all the fields of form
		 *
		 * @param $fields
		 */
		public function field_setter( $fields ): void {
			$this->cat_id      = EMO_BUPW_Request_Handler::get_POST( $fields['category'] );
			$this->rate_type   = EMO_BUPW_Request_Handler::get_POST( $fields['rate_type'] );
			$this->change_rate = EMO_BUPW_Request_Handler::get_POST( $fields['change_rate'] );
			$this->endYear     = EMO_BUPW_Request_Handler::get_POST( $fields['end_year'] );
			$this->endMonth    = EMO_BUPW_Request_Handler::get_POST( $fields['end_month'] );
			$this->endDay      = EMO_BUPW_Request_Handler::get_POST( $fields['end_day'] );
			$this->startYear   = EMO_BUPW_Request_Handler::get_POST( $fields['start_year'] );
			$this->startMonth  = EMO_BUPW_Request_Handler::get_POST( $fields['start_month'] );
			$this->startDay    = EMO_BUPW_Request_Handler::get_POST( $fields['start_day'] );
		}

		private function file_info( array $info ) {
			$this->fileName = $info['fileName'];
			$this->filePath = $info['fileDir'] . $this->fileName;
			$this->fileUrl  = $info['fileUrl'] . $this->fileName;
		}

		public function submit( array $args ): array {
			$error = $this->requirement_checker( $args['checker_items'] );
			if ( $error['error'] ) {
				return $error['error'];
			}
			$this->field_setter( $args['fields'] );
			$this->file_info( $args['file_info'] );

			$startDate     = new EWPU_Date_Generator( intval( $this->startYear ), $this->startMonth, intval( $this->startDay ) );
			$textStartDate = $startDate->text_Date();
			$UTMStartDate  = $startDate->utm_Date();

			$endDate     = new EWPU_Date_Generator( intval( $this->endYear ), $this->endMonth, intval( $this->endDay ) );
			$textEndDate = $endDate->text_Date();
			$UTMEndDate  = $endDate->utm_Date();

			$csvFile = new EMO_BUPW_Csv_Handler( $this->filePath, 'w' );
			if ( ! $csvFile ) {
				return [ 'error' => EMO_BUPW_Pass_Error_Msg::error_object( 'unable', __( "Unable to open file!", "emo-bulk-update-prices-for-woocommerce" ) ) ];
			}
			$writeCSV = array( $args['csv_fields'] );

			if ( $this->cat_id ) {
				$relatedProductsDB = new EMO_BUPW_DB_Get_Related_Object( $this->cat_id );
				$relatedProducts   = $relatedProductsDB->results();
				if ( is_array( $relatedProducts ) && count( $relatedProducts ) > 0 ) {
					foreach ( $relatedProducts as $relatedProduct ) {
						$products[] = $relatedProduct->object_id;
					}
				} else {
					return [
						'error' => EMO_BUPW_Pass_Error_Msg::error_object(
							'returnedProducts',
							__( "The selected product category has not contain any products", "emo-bulk-update-prices-for-woocommerce" ) )
					];
				}
			}
			foreach ( $products as $product ) {
				$_product = wc_get_product( $product );
				if ( $_product->get_type() == 'variable' ) {
					$variationsPrices = $_product->get_variation_prices();
					$vRegularPrices   = $variationsPrices['regular_price']; //array
					foreach ( $vRegularPrices as $vID => $vRegularPrice ) {
						$newSalePrice = emo_bupw_change_price( $this->rate_type, 'decrease', $vRegularPrice, $this->change_rate );
						$variation    = wc_get_product_object( 'variation', $vID );
						//set...
						$variation->set_props(
							array(
								// 'regular_price' => $newRegularPrice,
								'sale_price'        => $newSalePrice,
								'date_on_sale_from' => $UTMStartDate,
								'date_on_sale_to'   => $UTMEndDate
							)
						);
						$variation->save();
						array_push( $writeCSV, array(
							$product,
							$vID,
							$variation->get_title(),
							$vRegularPrice,
							$newSalePrice,
							$textStartDate,
							$textEndDate
						) );
					}

				} elseif ( $_product->get_type() == 'simple' ) {
					$regularPrice = $_product->get_regular_price();
					$newSalePrice = emo_bupw_change_price( $this->rate_type, 'decrease', $regularPrice, $this->change_rate );
					//set...
					$productObject = wc_get_product_object( 'simple', $product );
					$productObject->set_props(
						array(
							// 'regular_price' => $newRegularPrice,
							'sale_price'        => $newSalePrice,
							'date_on_sale_from' => $UTMStartDate,
							'date_on_sale_to'   => $UTMEndDate
						)
					);
					$productObject->save();
					// $_product->set_date_on_sale_to();
					array_push( $writeCSV, array(
						'0',
						$product,
						$_product->get_title(),
						$regularPrice,
						$newSalePrice,
						$textStartDate,
						$textEndDate
					) );
				}
			}

			foreach ( $writeCSV as $row ) {
				$csvFile->writeToFile( [ 'content' => $row ] );
			}
			$csvFile->closeFile();

			return [ 'error' => false, 'filePath' => $this->fileUrl, 'fileName' => $this->fileName ];
		}

	}
}