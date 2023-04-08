<?php
namespace EMO_BUPW\Form_Handlers;
use  EMO_BUPW\Form_Handlers\EMO_BUPW_Form_Field_Setter;
use  EMO_BUPW\Form_Handlers\EMO_BUPW_Form_Submit;
use  EMO_BUPW\Form_Handlers\EMO_BUPW_Form_Handler;
use EMO_BUPW\Repository\EWPU_Request_Handler;
use EMO_BUPW\Repository\EWPU_Pass_Error_Msg;
use EMO_BUPW\Repository\File_Handlers\EWPU_Csv_Handler;
use EMO_BUPW\Repository\EWPU_DB_Get_Related_Object;

if (!class_exists('EMO_BUPW_Form_Update_Price')) {
	class EMO_BUPW_Form_Update_Price implements EMO_BUPW_Form_Field_Setter, EMO_BUPW_Form_Submit {
		private $cat_id;
		private $change_rate;
		private $rate_type;
		private $activeSalePrice;
		private $change_type;
		private $fileName;
		private $filePath;
		private $fileUrl;

		use  EMO_BUPW_Form_Handler;

		/**
		 * Set all the fields of form
		 *
		 * @param $fields
		 */
		public function field_setter( $fields ): void {
			$this->cat_id          = EWPU_Request_Handler::get_POST( $fields['category'] );
			$this->change_rate     = EWPU_Request_Handler::get_POST( $fields['change_rate'] );
			$this->rate_type       = EWPU_Request_Handler::get_POST( $fields['rate_type'] );
			$this->activeSalePrice = EWPU_Request_Handler::get_POST( $fields['on_sale'] );
			$this->change_type     = EWPU_Request_Handler::get_POST( $fields['change_type'] );
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

			$csvFile = new EWPU_Csv_Handler( $this->filePath, 'w' );
			if ( ! $csvFile ) {
				return [ 'error' => EWPU_Pass_Error_Msg::error_object( 'unable', __( "Unable to open file!", "emo-bulk-update-prices-for-woocommerce" ) ) ];
			}
			$writeCSV = array( $args['csv_fields'] );
			if ( $this->cat_id ) {
				$relatedProductsDB = new EWPU_DB_Get_Related_Object( $this->cat_id );
				$relatedProducts   = $relatedProductsDB->results();
				if ( is_array( $relatedProducts ) && count( $relatedProducts ) > 0 ) {
					foreach ( $relatedProducts as $relatedProduct ) {
						$products[] = $relatedProduct->object_id;
					}
				} else {
					return [
						'error' => EWPU_Pass_Error_Msg::error_object(
							'returnedProducts',
							__( "The selected product category has not contain any products", "emo-bulk-update-prices-for-woocommerce" ) )
					];
				}
			}
			foreach ( $products as $product ) {
				$_product = wc_get_product( $product );
				if ( $_product ) {
					if ( $_product->get_type() == 'variable' ) {
						$variationsPrices = $_product->get_variation_prices();
						$vRegularPrices   = $variationsPrices['regular_price']; //array
						foreach ( $vRegularPrices as $vID => $vRegularPrice ) {
							$newRegularPrice = emo_bupw_change_price( $this->rate_type, $this->change_type, $vRegularPrice, $this->change_rate );
							$variation       = wc_get_product_object( 'variation', $vID );
							//set...
							$variation->set_props(
								array(
									'regular_price' => $newRegularPrice,
									// 'sale_price' => $sale_price,
								)
							);
							$variation->save();
							array_push( $writeCSV, array(
								$product,
								$vID,
								$variation->get_title(),
								'regular',
								$vRegularPrice,
								$newRegularPrice
							) );
						}

						if ( $this->activeSalePrice ) {
							$vSalerPrices = $variationsPrices['sale_price']; //array
							foreach ( $vSalerPrices as $vID => $vSalerPrice ) {
								$newSalePrice = emo_bupw_change_price( $this->rate_type, $this->change_type, $vSalerPrice, $this->change_rate );
								$variation    = wc_get_product_object( 'variation', $vID );
								//set...
								$variation->set_props(
									array(
										// 'regular_price' => $newRegularPrice,
										'sale_price' => $newSalePrice,
									)
								);
								$variation->save();
								array_push( $writeCSV, array(
									$product,
									$vID,
									$variation->get_title(),
									'sale',
									$vSalerPrice,
									$newSalePrice
								) );
							}

						}
					} elseif ( $_product->get_type() == 'simple' ) {
						$regularPrice    = $_product->get_regular_price();
						$newRegularPrice = emo_bupw_change_price( $this->rate_type, $this->change_type, $regularPrice, $this->change_rate );
						//set...
						$productObject = wc_get_product_object( 'simple', $product );
						$productObject->set_props(
							array(
								'regular_price' => $newRegularPrice,
								// 'sale_price' => $newSalePrice,
							)
						);
						$productObject->save();
						array_push( $writeCSV, array(
							'0',
							$product,
							$_product->get_title(),
							'regular',
							$regularPrice,
							$newRegularPrice
						) );

						if ( $this->activeSalePrice ) {
							$salePrice    = $_product->get_sale_price();
							$newSalePrice = emo_bupw_change_price( $this->rate_type, $this->change_type, $salePrice, $this->change_rate );
							//set...
							$productObject = wc_get_product_object( 'simple', $product );
							$productObject->set_props(
								array(
									// 'regular_price' => $newRegularPrice,
									'sale_price' => $newSalePrice,
								)
							);
							$productObject->save();
							array_push( $writeCSV, array(
								'0',
								$product,
								$_product->get_title(),
								'sale',
								$salePrice,
								$newSalePrice
							) );
						}
					}
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