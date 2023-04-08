<?php

namespace EMO_BUPW\Form_Handlers;

interface EWPU_Form_Field_Setter {
	/**
	 * Set all the fields of form
	 * @param $fields
	 * @return void
	 */
	function field_setter($fields):void;

}