<?php

namespace EmoWooPriceUpdate\Form_Handlers;


use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;
use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Csv_Handler;
use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Products_Price_List_Creator;
use EmoWooPriceUpdate\Repository\EWPU_DB_Get_All_Products_ID;

class EWPU_Form_Products_Price_List implements EWPU_Form_Submit {

	use  EWPU_Form_Handler;

	private function file_info(array $info)
	{
		$this->fileName = $info['fileName'];
		$this->filePath = $info['fileDir'].$this->fileName;
		$this->fileUrl = $info['fileUrl'].$this->fileName;
	}

	public function submit( array $args ): array {
        $error = $this->requirement_checker($args['checker_items']);
        if ($error['error']){
            return $error['error'];
        }

        $this->file_info($args['file_info']);

		$file = new EWPU_Csv_Handler($this->filePath, 'w');

        $productsObj = (new EWPU_DB_Get_All_Products_ID)->results();
        if(count($productsObj)<= 0){
            return ['error'=> EWPU_Pass_Error_Msg::error_object(
                'returnedProducts',
                __( "The selected product category has not contain any products", "emo_ewpu" )) ];
        }
		$productsCreator = new EWPU_Products_Price_List_Creator($file);
		$productsCreator->setHeader($args['csv_fields']);
        foreach($productsObj as $productObj){
            $productsID[] = $productObj->ID;
        }
		$productsCreator->init($productsID);
        $file->closeFile();

        return ['error'=>false, 'filePath'=> $this->fileUrl, 'fileName'=> $this->fileName];

	}
}