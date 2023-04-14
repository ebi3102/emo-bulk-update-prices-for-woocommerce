<?php

namespace EMO_BUPW\Form_Handlers;
if (!interface_exists('EMO_BUPW_Form_Field_Setter')) {
	interface EMO_BUPW_Form_Field_Setter {
		/**
		 * Set all the fields of form
		 *
		 * @param $fields
		 *
		 * @return void
		 */
		function field_setter( $fields ): void;
	}
}