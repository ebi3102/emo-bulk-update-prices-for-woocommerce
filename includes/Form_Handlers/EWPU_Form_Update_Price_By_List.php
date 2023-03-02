<?php

namespace EmoWooPriceUpdate\Form_Handlers;

use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
use EmoWooPriceUpdate\Repository\EWPU_Uploader;

class EWPU_Form_Update_Price_By_List implements EWPU_Form_Field_Setter, EWPU_Form_Submit {
    private $file;
    private $filePath;
    private $fileUrl;

	use  EWPU_Form_Handler;

	/**
	 * @inheritDoc
	 */
	function field_setter( $fields ): void {
        $this->file = EWPU_Request_Handler::get_FILE($fields['file']);
	}

    private function file_info(array $info)
    {
        $this->filePath = $info['fileDir'];
        $this->fileUrl = $info['fileUrl'];
    }

	public function submit( array $args ): array {
        $args = array(
            'checker_items' => array(
                'submit_status' => 'uploadSubmitt',
                'security' => array('emo_ewpu_nonce_field', 'emo_ewpu_action'),
                'requirements' => array('price_list')
            ),
            'fields' => array(
                'file' => 'price_list'
            ),
            'file_info'=> array(
                'fileUrl'=> EWPU_CREATED_URI,
                'fileDir'=> EWPU_CREATED_DIR,
                'extensions'=> ['csv'],
                'max-size' => 2097152
            )
        );

        $error = $this->requirement_checker($args['checker_items']);
        if ($error['error']){
            return $error['error'];
        }
        $this->field_setter($args['fields']);

        $uploadedFile = new EWPU_Uploader($this->file, $args['file_info']);
        if($uploadedFile->hasError()){
            return ['error'=> $uploadedFile->getError()];
        }

        $uploadedFile->getFileName();
        $uploadedFile->getFileUrl();
        $uploadedFile->getFilePath();
        /*
        // Read and store new prices
        if ( ($handle = new EWPU_Csv_Handler($target_file, "r")) !== false) {
            $row = 0;
            $args = ['length'=> 1000, 'separator'=> ','];
            while (($data = $handle->readFile($args)) !== false) {
                if($row != 0){
                    $productID = $data[0];
                    $regularPrice_new = (is_numeric($data[3]))? $data[3]:'';
                    $salePrice_new = (is_numeric($data[4]))? $data[4]:'';
                    $productType = ($data[5]== 'variation' || $data[5] == 'simple')?$data[5]:'';
                    $date = date(DATAFORMAT, time());
                    if($regularPrice_new !='' && $productType != ''){
                        emo_ewpu_set_new_price($productID, $productType, 'regular_price' ,$regularPrice_new);
                    }

                    if($salePrice_new !='' && $productType != ''){
                        emo_ewpu_set_new_price($productID, $productType, 'sale_price' ,$salePrice_new);
                    }
                }
                $row++;
            }
            $handle->closeFile();
            $response= __('Your prices are updated successfully.', 'emo_ewpu' );
            return ['response'=>$response];
        }else{
            $errors = new WP_Error( 'invalid', __( "The plugin is not able to open the uploaded file ", "emo_ewpu" ) );
            return ['error'=>$errors];
        }
        */

		return [];
	}
}