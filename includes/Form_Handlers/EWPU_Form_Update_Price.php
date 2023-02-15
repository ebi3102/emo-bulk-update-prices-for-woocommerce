<?php
namespace EmoWooPriceUpdate\Form_Handlers;
use EmoWooPriceUpdate\EWPU_Form_Error;
use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;
use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Csv_Handler;

class EWPU_Form_Update_Price {
	private $cat_id;
	private $change_rate;
	private $rate_type;
	private $activeSalePrice;
	private $change_type;
	private $fileName;
	private $filePath;
	private $fileUrl;

	/**
	 * Check the security and required items of the form
	 * @param array $checker_items {
	 *      The parameters that must be checked before handling a form
	 *      @type string $submit_status the name of submit button
	 *      @type array $security {     the fields of WordPress nonce checker
	 *          @type string nonce
	 *          @type string nonce action
	 *      @type array $requirements   the fields of form that are required
	 *
	 * @return array|void
	 */

	// move to a trait class
	protected function requirement_checker( array $checker_items)
	{
		$errors[] = EWPU_Form_Error::submit_status($checker_items['submit_status']);
		$errors[] = EWPU_Form_Error::nonce_inspection($checker_items['security'][0], ($checker_items['security'][1])? $checker_items['security'][1]: -1);
		foreach ($checker_items['requirements'] as $item){
			$errors[] = EWPU_Form_Error::requirement_inspection($item);
		}
		foreach ($errors as $error){
			if($error){
				return ['error'=>$error];
			}
		}
	}

	//create an interface

	/**
	 * Set all the fields of form
	 * @param $fields
	 */
	private function field_setter($fields)
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

	public function submit( array $args)
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

	}



}