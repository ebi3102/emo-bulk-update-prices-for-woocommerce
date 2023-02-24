<?php

namespace EmoWooPriceUpdate\Form_Handlers;

use  EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Field_Setter;
use  EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Submit;
use  EmoWooPriceUpdate\Form_Handlers\EWPU_Form_Handler;
use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;

class EWPU_Form_Group_Discount implements EWPU_Form_Field_Setter,EWPU_Form_Submit
{
    private $cat_id;
	private $rate_type;
	private $change_rate;
	private $endYear;
	private $endMonth;
	private $endDay;
	private $startYear;
	private $startMonth;
	private $startDay;


    use EWPU_Form_Handler;

    /**
	 * Set all the fields of form
	 * @param $fields
	 */
	public function field_setter($fields):void
    {
        $fields = array(
            'category'=> 'cat_id',
			'change_rate'=> 'change_rate',
			'rate_type' => 'nimo_nwab_rate',
            'start_year' => 'sale_start_time_year',
            'start_month' => 'sale_start_time_month',
            'start_day' => 'sale_start_time_day',
			'end_year' => 'sale_end_time_year',
            'end_month' => 'sale_end_time_month',
            'end_day' => 'sale_end_time_day'
        );
        $this->cat_id = EWPU_Request_Handler::get_POST($fields['category']);
        $this->rate_type = EWPU_Request_Handler::get_POST($fields['rate_type']);
        $this->change_rate = EWPU_Request_Handler::get_POST($fields['change_rate']);
        $this->endYear = EWPU_Request_Handler::get_POST($fields['end_year']);
        $this->endMonth = EWPU_Request_Handler::get_POST($fields['end_month']);
        $this->endDay = EWPU_Request_Handler::get_POST($fields['end_day']);
        $this->startYear = EWPU_Request_Handler::get_POST($fields['start_year']);
        $this->startMonth = EWPU_Request_Handler::get_POST($fields['start_month']);
        $this->startDay = EWPU_Request_Handler::get_POST($fields['start_day']);

    }

    private function file_info(array $info)
	{
        $file_info= array(
			'fileName'=> "Discount_".date("Y-m-d_h-i-s").".csv",
			'fileUrl'=> EWPU_CREATED_URI,
			'fileDir'=> EWPU_CREATED_DIR
		);
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
			    'rate_type' => 'nimo_nwab_rate',
			    'start_year' => 'sale_start_time_year',
			    'start_month' => 'sale_start_time_month',
			    'start_day' => 'sale_start_time_day',
			    'end_year' => 'sale_end_time_year',
			    'end_month' => 'sale_end_time_month',
			    'end_day' => 'sale_end_time_day'
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

		return array();
    }

}