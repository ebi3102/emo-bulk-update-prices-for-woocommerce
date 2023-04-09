<?php
/**
 * Get error message and pass it to WordPress WP_Erroe
*/
namespace EMO_BUPW\Repository;
if (!class_exists('EMO_BUPW_Form_Group_Discount')) {
	class EMO_BUPW_Pass_Error_Msg {

		private function __construct() {
		}

		/**
		 * Get error message and return as a WP_Error object
		 *
		 * @param string $error
		 * @param string $error_data
		 *
		 * @return WP_Error
		 */
		public static function error_object( string $error, string $error_data ): \WP_Error {
			return new \WP_Error( $error, $error_data );
		}

	}
}