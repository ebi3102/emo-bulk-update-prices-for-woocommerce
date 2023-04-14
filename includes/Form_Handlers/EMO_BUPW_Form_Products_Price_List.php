<?php

namespace EMO_BUPW\Form_Handlers;


use EMO_BUPW\Repository\EMO_BUPW_Pass_Error_Msg;
use EMO_BUPW\Repository\File_Handlers\EMO_BUPW_Csv_Handler;
use EMO_BUPW\Repository\File_Handlers\EMO_BUPW_Products_Price_List_Creator;
use EMO_BUPW\Repository\EMO_BUPW_DB_Get_All_Products_ID;

if (!class_exists('EMO_BUPW_Form_Products_Price_List')) {
	class EMO_BUPW_Form_Products_Price_List implements EMO_BUPW_Form_Submit {

		use  EMO_BUPW_Form_Handler;

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

			$this->file_info( $args['file_info'] );

			$file = new EMO_BUPW_Csv_Handler( $this->filePath, 'w' );

			$productsObj = ( new EMO_BUPW_DB_Get_All_Products_ID )->results();
			if ( count( $productsObj ) <= 0 ) {
				return [
					'error' => EMO_BUPW_Pass_Error_Msg::error_object(
						'returnedProducts',
						__( "The selected product category has not contain any products", "emo-bulk-update-prices-for-woocommerce" ) )
				];
			}
			$productsCreator = new EMO_BUPW_Products_Price_List_Creator( $file );
			$productsCreator->setHeader( $args['csv_fields'] );
			foreach ( $productsObj as $productObj ) {
				$productsID[] = $productObj->ID;
			}
			$productsCreator->init( $productsID );
			$file->closeFile();

			return [ 'error' => false, 'filePath' => $this->fileUrl, 'fileName' => $this->fileName ];

		}
	}
}