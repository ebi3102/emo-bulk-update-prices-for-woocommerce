<?php
namespace EmoWooPriceUpdate\Form_Handlers;
use  EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Field_Setter;
use  EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Submit;
use EmoWooPriceUpdate\EWPU_Form_Error;
use  EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Handler;
use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;
use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Csv_Handler;
use EmoWooPriceUpdate\Repository\EWPU_DB_Get_Related_Object;

class EWPU_Form_Update_Price implements EWPU_Form_Field_Setter,EWPU_Form_Submit
{
	private $cat_id;
	private $change_rate;
	private $rate_type;
	private $activeSalePrice;
	private $change_type;
	private $fileName;
	private $filePath;
	private $fileUrl;

	use  EWPU_Form_Handler;
	//create an interface

	/**
	 * Set all the fields of form
	 * @param $fields
	 */
	public function field_setter($fields):void
	{
		$this->cat_id = EWPU_Request_Handler::get_POST($fields['category']);
		$this->change_rate = EWPU_Request_Handler::get_POST($fields['change_rate']);
		$this->rate_type = EWPU_Request_Handler::get_POST($fields['rate_type']);
		$this->activeSalePrice = EWPU_Request_Handler::get_POST($fields['on_sale']);
		$this->change_type = EWPU_Request_Handler::get_POST($fields['change_type']);
	}

	private function file_info(array $info)
	{
		$this->fileName = $info['fileName'];
		$this->filePath = $info['fileDir'].$this->fileName;
		$this->fileUrl = $info['fileUrl'].$this->fileName;
	}

	public function submit( array $args):array
	{
		$args = array(
			'checker_items' => array(
				'submit_status' => 'btnSubmit',
				'security' => array('emo_ewpu_nonce_field', 'emo_ewpu_action'),
				'requirements' => array('cat_id', 'change_rate')
			),
			'fields' => array(
				'category'=> 'cat_id',
				'change_rate'=> 'change_rate',
				'rate_type' => 'emo_ewpu_rate',
				'on_sale' => 'sale_price',
				'change_type' => 'emo_ewpu_increase'
			),
			'file_info'=> array(
				'fileName'=> "ChangePrice_".date("Y-m-d_h-i-s").".csv",
				'fileUrl'=> EWPU_CREATED_URI,
				'fileDir'=> EWPU_CREATED_DIR
			),
			'csv_fields'=> array('parent_id', 'product_id', 'product_name', 'price_type', 'old_price', 'new_price'),
		);

		$error = $this->requirement_checker($args['checker_items']);
		if ($error['error']){
			return $error['error'];
		}
		$this->field_setter($args['fields']);
		$this->file_info($args['file_info']);

		$csvFile = new EWPU_Csv_Handler($this->filePath, 'w');
		if(!$csvFile){
			return ['error'=> EWPU_Pass_Error_Msg::error_object('unable',  __( "Unable to open file!", "emo_ewpu" )) ];
		}
		$writeCSV = array($args['csv_fields']);
		if($this->cat_id){
			$relatedProductsDB = new EWPU_DB_Get_Related_Object($this->cat_id);
			$relatedProducts = $relatedProductsDB->results();
			if(is_array($relatedProducts) && count($relatedProducts) > 0) {
				foreach ($relatedProducts as $relatedProduct) {
					$products[]= $relatedProduct->object_id;
				}
			}else{
				return ['error'=> EWPU_Pass_Error_Msg::error_object(
					'returnedProducts',
					__( "The selected product category has not contain any products", "emo_ewpu" )) ];
			}
		}

		return array(1,2);

	}



}